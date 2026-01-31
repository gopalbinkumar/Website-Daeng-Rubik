<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

        // cek apakah produk sudah ada di cart
        $item = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $product->id)
            ->first();

        if ($item) {
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

        // ambil cart aktif user / session
        $cart = Cart::where('status', 'active')
            ->when(
                Auth::check(),
                fn($q) => $q->where('user_id', Auth::id()),
                fn($q) => $q->where('session_token', session('cart_token'))
            )
            ->first();

        if (!$cart || $item->cart_id !== $cart->id) {
            abort(403);
        }

        $stock = $item->product->stock;

        if ($request->action === 'dec' && $item->quantity > 1) {
            $item->quantity--;
        }

        if ($request->action === 'inc' && $item->quantity < $stock) {
            $item->quantity++;
        }

        $item->save();

        return redirect()->back();
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
