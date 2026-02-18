<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Cart;
use App\Services\TelegramService;


class TransactionController extends Controller
{
    public function index(Request $request)
    {

        $query = Transaction::with('items')
            ->where('user_id', Auth::id()) // 🔒 FILTER USER LOGIN
            ->latest();

        // 🔍 SEARCH kode transaksi ATAU nama produk
        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use ($search) {

                // Cari berdasarkan kode transaksi
                $q->where('code', 'like', '%' . $search . '%')

                    // ATAU berdasarkan nama produk di transaction_items
                    ->orWhereHas('items', function ($itemQuery) use ($search) {
                        $itemQuery->where('product_name', 'like', '%' . $search . '%');
                    });

            });
        }

        // filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(12);

        return view('pages.transactions', compact('transactions'));
    }


    public function store(Request $request)
    {
        // =========================
        // VALIDASI DASAR
        // =========================
        $request->validate([
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:50',
            'receiver_address' => 'required|string',
            'receiver_postal_code' => 'required|string|max:20',

            'shipping_zone' => 'required|in:makassar,sulsel,luar_provinsi',

            'items' => 'required|string',
            'payment_proof' => 'nullable|image|max:5120',
        ]);

        $user = Auth::user();

        // =========================
        // NORMALISASI SHIPPING DATA
        // =========================
        $shippingZone = $request->shipping_zone;
        $shippingCity = null;
        $shippingProvince = null;

        if ($shippingZone === 'makassar') {
            // Otomatis
            $shippingCity = 'Makassar';
            $shippingProvince = 'Sulawesi Selatan';
        } elseif ($shippingZone === 'sulsel') {
            // Kota input, provinsi tetap
            $request->validate([
                'shipping_city' => 'required|string|max:255',
            ]);

            $shippingCity = $request->shipping_city;
            $shippingProvince = 'Sulawesi Selatan';
        } elseif ($shippingZone === 'luar_provinsi') {
            // Kota & provinsi input
            $request->validate([
                'shipping_city' => 'required|string|max:255',
                'shipping_province' => 'required|string|max:255',
            ]);

            $shippingCity = $request->shipping_city;
            $shippingProvince = $request->shipping_province;
        }


        // =========================
        // AMBIL CART USER
        // =========================
        $cart = Cart::with(['items.product'])
            ->where('user_id', $user->id)
            ->firstOrFail();

        $itemIds = explode(',', $request->items);
        $checkoutItems = $cart->items->whereIn('id', $itemIds);

        if ($checkoutItems->isEmpty()) {
            return back()->withErrors('Item checkout tidak valid');
        }

        // =========================
        // HITUNG SUBTOTAL
        // =========================
        $subtotal = 0;
        foreach ($checkoutItems as $item) {
            $subtotal += $item->unit_price * $item->quantity;
        }

        // =========================
        // ONGKIR SESUAI ZONE
        // =========================
        $shippingCost = match ($shippingZone) {
            'makassar' => 15000,
            'sulsel' => 25000,
            'luar_provinsi' => 40000,
            default => 15000,
        };

        $total = $subtotal + $shippingCost;

        DB::beginTransaction();

        try {
            // =========================
            // UPLOAD BUKTI TRANSFER
            // =========================
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request
                    ->file('payment_proof')
                    ->store('payment_proofs', 'public');
            }

            // =========================
            // SIMPAN TRANSACTION
            // =========================
            $transaction = Transaction::create([
                'code' => 'TRX-' . strtoupper(Str::random(10)),
                'user_id' => $user->id,
                'cart_id' => $cart->id,
                'source' => 'website',
                'status' => 'pending',

                'receiver_name' => $request->receiver_name,
                'receiver_phone' => $request->receiver_phone,
                'receiver_address' => $request->receiver_address,
                'receiver_postal_code' => $request->receiver_postal_code,

                // 🔑 FIX PENTING
                'shipping_zone' => $shippingZone,
                'shipping_city' => $shippingCity,
                'shipping_province' => $shippingProvince,

                'subtotal_amount' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total_amount' => $total,

                'payment_method' => 'bank_transfer',
                'payment_bank_name' => 'BCA',
                'payment_account_name' => 'Daeng Rubik',
                'payment_account_number' => '1234567890',
                'payment_proof_path' => $proofPath,
            ]);

            // =========================
            // SIMPAN TRANSACTION ITEMS
            // =========================
            foreach ($checkoutItems as $item) {
                TransactionItem::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'unit_price' => $item->unit_price,
                    'quantity' => $item->quantity,
                    'line_total' => $item->unit_price * $item->quantity,
                ]);
            }

            // =========================
            // HAPUS ITEM DARI CART
            // =========================
            foreach ($checkoutItems as $item) {
                $item->delete();
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memproses checkout'
            ], 500);
        }

        // 🔔 TELEGRAM DI LUAR TRANSAKSI DB (TIDAK BOLEH GAGALKAN CHECKOUT)
        try {
            $transaction->load(['user', 'items']);
            TelegramService::sendOrder($transaction);
        } catch (\Throwable $e) {
            report($e);
        }

        // ✅ RESPONSE KHUSUS UNTUK FETCH / AJAX
        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat'
        ]);

    }


}
