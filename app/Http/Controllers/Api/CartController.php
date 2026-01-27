<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    protected function resolveCart(Request $request): Cart
    {
        $user = $request->user();
        if ($user) {
            return Cart::firstOrCreate(
                ['user_id' => $user->id, 'status' => 'active'],
                ['session_token' => null]
            );
        }

        $sessionToken = $request->header('X-Session-Token') ?? $request->cookie('cart_session');
        if (!$sessionToken) {
            $sessionToken = Str::uuid()->toString();
        }

        $cart = Cart::firstOrCreate(
            ['session_token' => $sessionToken, 'status' => 'active'],
            ['user_id' => null]
        );

        // Attach new session token in response header (handled later)
        $request->attributes->set('cart_session_token', $sessionToken);

        return $cart;
    }

    public function get(Request $request)
    {
        $cart = $this->resolveCart($request)->load('items.product');

        $data = [
            'id' => $cart->id,
            'items' => $cart->items->map(function (CartItem $item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? $item->product_id,
                    'price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->lineTotal(),
                ];
            }),
            'subtotal' => $cart->subtotal(),
        ];

        $response = response()->json($data);

        if ($token = $request->attributes->get('cart_session_token')) {
            $response->cookie('cart_session', $token, 60 * 24 * 7); // 7 hari
        }

        return $response;
    }

    public function add(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);

        $cart = $this->resolveCart($request);
        $product = Product::findOrFail($validated['product_id']);
        $qty = $validated['quantity'] ?? 1;

        $item = CartItem::firstOrNew([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ]);

        if ($item->exists) {
            $item->quantity += $qty;
        } else {
            $item->unit_price = $product->price;
            $item->quantity = $qty;
        }

        $item->save();

        return $this->get($request);
    }

    public function updateQuantity(Request $request, CartItem $item)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item->update(['quantity' => $validated['quantity']]);

        return $this->get($request);
    }

    public function remove(Request $request, CartItem $item)
    {
        $item->delete();
        return $this->get($request);
    }

    public function clear(Request $request)
    {
        $cart = $this->resolveCart($request);
        $cart->items()->delete();

        return $this->get($request);
    }
}

