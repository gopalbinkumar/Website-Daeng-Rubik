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
        // pastikan user sudah login
        $cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        // 🔥 Sinkronisasi stok
        if ($cart) {
            foreach ($cart->items as $item) {

                $currentStock = $item->product->stock;

                if ($currentStock == 0) {
                    $item->delete();
                    continue;
                }

                if ($item->quantity > $currentStock) {
                    $item->quantity = $currentStock;
                    $item->save();
                }
            }
        }

        return view('pages.cart', compact('cart'));
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
        if ($userId) {

            // Cari cart user yang aktif
            $cart = Cart::where('user_id', $userId)
                ->where('status', 'active')
                ->first();

            if (!$cart) {
                $cart = Cart::create([
                    'user_id' => $userId,
                    'session_token' => null,
                    'status' => 'active',
                ]);
            } else {
                // Pastikan cart login tidak punya session_token
                $cart->update([
                    'session_token' => null
                ]);
            }

        } else {

            // Guest
            $cart = Cart::firstOrCreate(
                [
                    'session_token' => $sessionToken,
                    'status' => 'active',
                ],
                [
                    'user_id' => null
                ]
            );
        }

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
            'action' => 'nullable|in:inc,dec',
            'quantity' => 'nullable|integer',
        ]);

        $cart = Cart::where('status', 'active')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart || (int) $item->cart_id !== (int) $cart->id) {
            abort(403);
        }

        $product = $item->product;

        if (!$product) {
            $item->delete();
            return redirect()->back();
        }

        $stock = (int) $product->stock;

        // Jika stok sudah habis, hapus item dari keranjang tanpa alert
        if ($stock <= 0) {
            $item->delete();
            return redirect()->back();
        }

        /**
         * Jika user mengetik qty langsung
         */
        if ($request->filled('quantity')) {
            $quantity = (int) $request->quantity;
        }

        /**
         * Jika user klik tombol + / -
         */ elseif ($request->action === 'inc') {
            $quantity = (int) $item->quantity + 1;
        } elseif ($request->action === 'dec') {
            $quantity = (int) $item->quantity - 1;
        } else {
            $quantity = (int) $item->quantity;
        }

        // Kunci qty: minimal 1, maksimal stok
        $quantity = max(1, min($quantity, $stock));

        $item->update([
            'quantity' => $quantity,
        ]);

        // Tidak pakai with('success') / with('error') agar tidak muncul alert
        return redirect()->back();
    }



    public function remove(CartItem $item)
    {
        $cart = Cart::where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (!$cart || (int) $item->cart_id !== (int) $cart->id) {
            abort(403);
        }

        $item->delete();

        return redirect()->back()->with(
            'success',
            'Produk dihapus dari keranjang'
        );
    }

}
