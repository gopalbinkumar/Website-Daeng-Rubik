<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesReportController extends Controller
{
    public function index(Request $request)
    {
        /*
        |------------------------------------------------------------------
        | BASE QUERY (SEMUA FILTER DI SINI)
        |------------------------------------------------------------------
        */
        $baseQuery = Transaction::query();

        // FILTER: STATUS
        if ($request->filled('status')) {
            $baseQuery->where('status', $request->status);
        }

        // FILTER: PRODUK
        if ($request->filled('product')) {
            $baseQuery->whereHas('items', function ($q) use ($request) {
                $q->where('product_name', $request->product);
            });
        }

        // FILTER: DATE RANGE (PRIORITAS)
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $baseQuery->whereBetween('created_at', [
                $request->start_date . ' 00:00:00',
                $request->end_date . ' 23:59:59',
            ]);
        }
        // FILTER: PERIOD
        elseif ($request->filled('period')) {
            if ($request->period === 'monthly') {
                $baseQuery->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
            } elseif ($request->period === 'yearly') {
                $baseQuery->whereYear('created_at', now()->year);
            }
            // daily = semua data
        }

        /*
        |------------------------------------------------------------------
        | TABLE DATA (PAKAI PAGINATION)
        |------------------------------------------------------------------
        */
        $transactions = (clone $baseQuery)
            ->with('items')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |------------------------------------------------------------------
        | SUMMARY (TANPA PAGINATION)
        |------------------------------------------------------------------
        */
        $summaryQuery = (clone $baseQuery)->where('status', 'paid');

        $totalRevenue = $summaryQuery->sum('total_amount');
        $totalTransaction = $summaryQuery->count();
        $avgTransaction = $totalTransaction
            ? $totalRevenue / $totalTransaction
            : 0;

        /*
        |------------------------------------------------------------------
        | DROPDOWN PRODUK
        |------------------------------------------------------------------
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

    public function monthlyRevenueChart()
{
    $start = now()->subMonths(11)->startOfMonth();
    $end   = now()->endOfMonth();

    $data = Transaction::where('status', 'paid')
        ->whereBetween('created_at', [$start, $end])
        ->select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('SUM(total_amount) as total')
        )
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();

    return response()->json($data);
}
    
}
