<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    protected function calculateShipping(string $zone): int
    {
        return match ($zone) {
            'makassar' => 15000,
            'sulsel' => 25000,
            'luar_provinsi' => 40000,
            default => 15000,
        };
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'shipping_zone' => ['required', 'in:makassar,sulsel,luar_provinsi'],
            'cart_id' => ['required', 'exists:carts,id'],
        ]);

        /** @var Cart $cart */
        $cart = Cart::with('items')->findOrFail($validated['cart_id']);
        $subtotal = $cart->subtotal();
        $shipping = $this->calculateShipping($validated['shipping_zone']);

        return response()->json([
            'subtotal' => $subtotal,
            'shipping_cost' => $shipping,
            'total' => $subtotal + $shipping,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cart_id' => ['required', 'exists:carts,id'],
            'receiver_name' => ['required', 'string', 'max:255'],
            'receiver_phone' => ['required', 'string', 'max:50'],
            'receiver_address' => ['required', 'string'],
            'receiver_postal_code' => ['required', 'string', 'max:20'],
            'shipping_zone' => ['required', 'in:makassar,sulsel,luar_provinsi'],
            'shipping_city' => ['nullable', 'string', 'max:255'],
            'shipping_province' => ['nullable', 'string', 'max:255'],
            'payment_method' => ['nullable', 'string', 'max:50'],
        ]);

        /** @var Cart $cart */
        $cart = Cart::with('items.product')->findOrFail($validated['cart_id']);

        if ($cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        $subtotal = $cart->subtotal();
        $shipping = $this->calculateShipping($validated['shipping_zone']);
        $total = $subtotal + $shipping;

        $transaction = DB::transaction(function () use ($request, $cart, $validated, $subtotal, $shipping, $total) {
            $code = 'TRX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

            $transaction = Transaction::create([
                'code' => $code,
                'user_id' => optional($request->user())->id,
                'cart_id' => $cart->id,
                'source' => 'website',
                'status' => 'pending',
                'receiver_name' => $validated['receiver_name'],
                'receiver_phone' => $validated['receiver_phone'],
                'receiver_address' => $validated['receiver_address'],
                'receiver_postal_code' => $validated['receiver_postal_code'],
                'shipping_zone' => $validated['shipping_zone'],
                'shipping_city' => $validated['shipping_city'] ?? null,
                'shipping_province' => $validated['shipping_province'] ?? null,
                'subtotal_amount' => $subtotal,
                'shipping_cost' => $shipping,
                'total_amount' => $total,
                'payment_method' => $validated['payment_method'] ?? 'bank_transfer',
                'payment_bank_name' => 'BCA',
                'payment_account_name' => 'Daeng Rubik',
                'payment_account_number' => '1234567890',
            ]);

            foreach ($cart->items as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'Produk Rubik',
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->lineTotal(),
                ]);
            }

            $cart->update(['status' => 'converted']);

            return $transaction->load('items');
        });

        return response()->json($transaction, 201);
    }
}

