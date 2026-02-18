<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class CartController extends Controller
{

    public function index()
    {
        $cart = Cart::with('items.product')
            ->where('status', 'active')
            ->when(Auth::check(), function ($q) {
                $q->where('user_id', Auth::id());
            }, function ($q) {
                $q->where('session_token', session('cart_token'));
            })
            ->first();

        // 🔥 SINKRONISASI STOK DENGAN CART
        if ($cart) {
            foreach ($cart->items as $item) {

                $currentStock = $item->product->stock;

                // Jika stok 0 → hapus item dari cart
                if ($currentStock == 0) {
                    $item->delete();
                    continue;
                }

                // Jika qty lebih besar dari stok → sesuaikan
                if ($item->quantity > $currentStock) {
                    $item->quantity = $currentStock;
                    $item->save();
                }
            }
        }

        return view('pages.cart', [
            'cart' => $cart
        ]);
    }


    public function add(Request $request)
    {
        // validasi
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();

        // session token untuk guest
        $sessionToken = session()->get('cart_token');
        if (!$sessionToken) {
            $sessionToken = Str::uuid()->toString();
            session()->put('cart_token', $sessionToken);
        }

        /**
         * Ambil / buat cart aktif
         * - login  → user_id
         * - guest  → session_token
         */
        $cart = Cart::firstOrCreate(
            [
                'user_id' => $userId,
                'status' => 'active',
            ],
            [
                'session_token' => $sessionToken,
            ]
        );

        $product = Product::findOrFail($request->product_id);

        // ❌ Jika stok habis, jangan boleh masuk cart
        if ($product->stock <= 0) {
            return redirect()->back()->with(
                'error',
                'Stok produk sudah habis'
            );
        }


        // cek apakah produk sudah ada di cart
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
            // Jangan boleh melebihi stok
            if ($item->quantity >= $product->stock) {
                return redirect()->back()->with(
                    'error',
                    'Jumlah melebihi stok tersedia'
                );
            }

            $item->quantity += 1;
            $item->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'unit_price' => $product->price,
            ]);
        }

        // ⬅️ PENTING: redirect, BUKAN JSON
        return redirect()->back()->with(
            'success',
            'Produk berhasil ditambahkan ke keranjang'
        );
    }


    public function updateQuantity(Request $request, CartItem $item)
    {
        $request->validate([
            'action' => 'required|in:inc,dec',
        ]);

        $cart = Cart::where('status', 'active')
            ->when(
                Auth::check(),
                fn($q) => $q->where('user_id', Auth::id()),
                fn($q) => $q->where('session_token', session('cart_token'))
            )
            ->first();

        if (!$cart || $item->cart_id !== $cart->id) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        return DB::transaction(function () use ($request, $item) {

            // 🔒 Lock product row
            $product = $item->product()->lockForUpdate()->first();
            $stock = $product->stock;

            // 🔥 INC
            if ($request->action === 'inc') {

                if ($item->quantity >= $stock) {
                    return response()->json([
                        'message' => 'Stok tidak mencukupi untuk ' . $product->name
                    ], 422);
                }

                $item->increment('quantity');
            }

            // 🔥 DEC
            if ($request->action === 'dec') {

                if ($item->quantity <= 1) {
                    return response()->json([
                        'message' => 'Minimal pembelian adalah 1'
                    ], 422);
                }

                $item->decrement('quantity');
            }

            $item->refresh(); // ambil quantity terbaru

            return response()->json([
                'success' => true,
                'quantity' => $item->quantity,
                'stock' => $stock,
                'unit_price' => $item->unit_price,
                'subtotal' => $item->quantity * $item->unit_price
            ]);
        });
    }



    public function remove(CartItem $item)
    {
        // keamanan: pastikan item milik cart user / session ini
        $cart = Cart::where('status', 'active')
            ->when(Auth::check(), function ($q) {
                $q->where('user_id', Auth::id());
            }, function ($q) {
                $q->where('session_token', session('cart_token'));
            })
            ->first();

        if (!$cart || $item->cart_id !== $cart->id) {
            abort(403);
        }

        $item->delete();

        return redirect()->back()->with(
            'success',
            'Produk dihapus dari keranjang'
        );
    }

}
