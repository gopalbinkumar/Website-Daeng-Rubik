<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // ambil cart user
        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->first();

        if (!$cart) {
            return redirect()->route('cart');
        }

        // ambil item ID dari query ?items=6,7
        $itemIds = collect(explode(',', $request->query('items')))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->toArray();

        if (empty($itemIds)) {
            return redirect()
                ->route('cart')
                ->with('error', 'Pilih minimal 1 produk untuk checkout');
        }

        // 🔥 INI VARIABEL YANG HILANG SEBELUMNYA
        $checkoutItems = $cart->items
            ->whereIn('id', $itemIds)
            ->values();

        // subtotal
        $subtotal = $checkoutItems->sum(function ($item) {
            return $item->unit_price * $item->quantity;
        });

        // default ongkir (Makassar)
        $ongkir = 15000;
        $total  = $subtotal + $ongkir;

        return view('pages.checkout', compact(
            'user',
            'cart',
            'checkoutItems',
            'subtotal',
            'ongkir',
            'total'
        ));
    }
}
