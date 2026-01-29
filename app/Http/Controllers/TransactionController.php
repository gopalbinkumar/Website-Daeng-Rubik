<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'period' => ['nullable', 'in:daily,monthly,yearly'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date'],
            'status' => ['nullable', 'in:pending,paid,failed,cancelled'],
            'source' => ['nullable', 'in:website,shopee,tokopedia,tiktok_shop'],
        ]);

        $query = Transaction::query();

        if (!empty($validated['period'])) {
            $query->byPeriod($validated['period']);
        }

        if (!empty($validated['from']) || !empty($validated['to'])) {
            $query->dateRange($validated['from'] ?? null, $validated['to'] ?? null);
        }

        if (!empty($validated['status'])) {
            $query->where('status', $validated['status']);
        }

        if (!empty($validated['source'])) {
            $query->where('source', $validated['source']);
        }

        $transactions = $query->with('items')->orderByDesc('created_at')->paginate(20);

        $summary = [
            'total_revenue' => (clone $query)->where('status', 'paid')->sum('total_amount'),
            'transaction_count' => (clone $query)->count(),
            'average_transaction' => (clone $query)->where('status', 'paid')->avg('total_amount'),
        ];

        return response()->json([
            'data' => $transactions,
            'summary' => $summary,
        ]);
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'source' => ['required', 'in:website,shopee,tokopedia,tiktok_shop'],
            'buyer_name' => ['required', 'string', 'max:255'],
            'transaction_date' => ['required', 'date'],
            'quantity' => ['required', 'integer', 'min:1'],
            'subtotal_amount' => ['required', 'integer', 'min:0'],
            'shipping_cost' => ['nullable', 'integer', 'min:0'],
            'payment_method' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'in:pending,paid,failed,cancelled'],
            'note' => ['nullable', 'string'],
        ]);

        $shipping = $validated['shipping_cost'] ?? 0;
        $total = $validated['subtotal_amount'] + $shipping;

        $transaction = Transaction::create([
            'code' => 'TRX-MANUAL-' . now()->format('Ymd') . '-' . strtoupper(uniqid()),
            'user_id' => null,
            'cart_id' => null,
            'source' => $validated['source'],
            'status' => $validated['status'] ?? 'paid',
            'receiver_name' => $validated['buyer_name'],
            'receiver_phone' => '-',
            'receiver_address' => $validated['note'] ?? 'Transaksi manual dari marketplace',
            'receiver_postal_code' => '-',
            'shipping_zone' => 'makassar',
            'shipping_city' => null,
            'shipping_province' => null,
            'subtotal_amount' => $validated['subtotal_amount'],
            'shipping_cost' => $shipping,
            'total_amount' => $total,
            'payment_method' => $validated['payment_method'] ?? 'bank_transfer',
            'payment_bank_name' => 'BCA',
            'payment_account_name' => 'Daeng Rubik',
            'payment_account_number' => '1234567890',
            'paid_at' => $validated['status'] === 'paid' ? $validated['transaction_date'] : null,
        ]);

        $transaction->items()->create([
            'product_id' => null,
            'product_name' => $validated['product_name'],
            'unit_price' => (int) floor($validated['subtotal_amount'] / $validated['quantity']),
            'quantity' => $validated['quantity'],
            'line_total' => $validated['subtotal_amount'],
        ]);

        return response()->json($transaction->load('items'), 201);
    }

    public function exportPdf(Request $request)
    {
        // Placeholder - implement with a PDF library (dompdf/snappy) later.
        return response()->json([
            'message' => 'Export PDF belum diimplementasikan. Gunakan query yang sama dengan endpoint index() untuk mengambil data, lalu generate PDF menggunakan library.',
        ]);
    }
}

