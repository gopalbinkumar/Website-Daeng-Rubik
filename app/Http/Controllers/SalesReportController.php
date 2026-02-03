<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::query();

        /*
        |--------------------------------------------------------------------------
        | FILTER: STATUS
        |--------------------------------------------------------------------------
        */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER: PRODUK (PAKAI TRANSACTION ITEMS - BENAR)
        |--------------------------------------------------------------------------
        */
        if ($request->filled('product')) {
            $query->whereHas('items', function ($q) use ($request) {
                $q->where('product_name', $request->product);
            });
        }

        /*
        |--------------------------------------------------------------------------
        | FILTER: DATE RANGE (PRIORITAS)
        |--------------------------------------------------------------------------
        */
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }
        /*
        |--------------------------------------------------------------------------
        | FILTER: PERIOD (JIKA TIDAK ADA DATE RANGE)
        |--------------------------------------------------------------------------
        */
        elseif ($request->filled('period')) {
            if ($request->period === 'monthly') {
                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);
            }

            if ($request->period === 'yearly') {
                $query->whereYear('created_at', now()->year);
            }
            // daily = semua data
        }

        /*
        |--------------------------------------------------------------------------
        | DATA TABLE + EAGER LOAD YANG BENAR
        |--------------------------------------------------------------------------
        */
        $transactions = $query
            ->with('items') // 🔥 INI KUNCI
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | SUMMARY (HANYA STATUS PAID, FILTER SAMA)
        |--------------------------------------------------------------------------
        */
        $summaryQuery = clone $query;
        $summaryQuery->where('status', 'paid');

        $totalRevenue = $summaryQuery->sum('total_amount');
        $totalTransaction = $summaryQuery->count();
        $avgTransaction = $totalTransaction
            ? $totalRevenue / $totalTransaction
            : 0;

        /*
        |--------------------------------------------------------------------------
        | LIST PRODUK UNTUK DROPDOWN (REAL DARI TRANSAKSI)
        |--------------------------------------------------------------------------
        */
        $products = TransactionItem::select('product_name')
            ->distinct()
            ->orderBy('product_name')
            ->pluck('product_name');

        return view('admin.reports.sales', compact(
            'transactions',
            'totalRevenue',
            'totalTransaction',
            'avgTransaction',
            'products'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VERIFIKASI PEMBAYARAN
    |--------------------------------------------------------------------------
    */
    public function verify(Transaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return back();
        }

        $transaction->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return back()->with('success', 'Pembayaran berhasil diverifikasi');
    }
}
