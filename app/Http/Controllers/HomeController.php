<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Event;
use App\Models\LearningMaterial;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TransactionItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * =========================
     * HOME USER
     * =========================
     */
    public function home()
    {
        // =============================
// 🔥 PRODUK TERLARIS USER (4)
// =============================

        $limit = 4;
        $now = Carbon::now();

        // 📅 Bulan ini
        $startThisMonth = $now->copy()->startOfMonth();
        $endThisMonth = $now->copy()->endOfMonth();

        // 📅 Bulan sebelumnya
        $startLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endLastMonth = $now->copy()->subMonth()->endOfMonth();

        // 1️⃣ Terlaris bulan ini
        $topThisMonth = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold')
        )
            ->whereHas('transaction', function ($q) use ($startThisMonth, $endThisMonth) {
                $q->where('status', 'paid')
                    ->whereBetween('created_at', [$startThisMonth, $endThisMonth]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->pluck('product_id')
            ->toArray();

        // 2️⃣ Jika kurang, ambil bulan lalu
        if (count($topThisMonth) < $limit) {

            $topLastMonth = TransactionItem::select(
                'product_id',
                DB::raw('SUM(quantity) as total_sold')
            )
                ->whereHas('transaction', function ($q) use ($startLastMonth, $endLastMonth) {
                    $q->where('status', 'paid')
                        ->whereBetween('created_at', [$startLastMonth, $endLastMonth]);
                })
                ->groupBy('product_id')
                ->orderByDesc('total_sold')
                ->pluck('product_id')
                ->toArray();

            $topThisMonth = array_unique(array_merge($topThisMonth, $topLastMonth));
        }

        // 3️⃣ Ambil produk berdasarkan ID terlaris
        $featuredProducts = Product::with('primaryImage')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        // 4️⃣ Jika masih kurang → tambahkan produk terbaru
        if ($featuredProducts->count() < $limit) {

            $excludeIds = $featuredProducts->pluck('id')->toArray();

            $latestFill = Product::with('primaryImage')
                ->whereNotIn('id', $excludeIds)
                ->where('is_active', true)
                ->latest()
                ->take($limit - $featuredProducts->count())
                ->get();

            $featuredProducts = $featuredProducts->merge($latestFill);
        }

        // 🔥 1️⃣ Cari kompetisi upcoming terdekat
        $featuredEvent = Event::where('category', 'kompetisi')
            ->where('status', 'upcoming')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->with('competitionCategories')
            ->first();

        // 2️⃣ Kalau tidak ada, ambil upcoming terdekat apapun
        if (!$featuredEvent) {
            $featuredEvent = Event::where('status', 'upcoming')
                ->where('start_datetime', '>=', now())
                ->orderBy('start_datetime', 'asc')
                ->with('competitionCategories')
                ->first();
        }

        return view('pages.home', compact('featuredProducts', 'featuredEvent'));
    }

    /**
     * =========================
     * DASHBOARD ADMIN
     * =========================
     */
    public function dashboardadmin()
    {
        // 🔢 STAT
        $totalProducts = Product::count();
        $totalEvents = Event::count();
        $totalMaterials = LearningMaterial::count();
        $totalUsers = User::where('role', 'user')->count();

        // 📈 PRODUK TERLARIS BULAN INI (Top 3)
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $topProducts = TransactionItem::select(
            'product_id',
            DB::raw('SUM(quantity) as total_sold')
        )
            ->whereHas('transaction', function ($q) use ($startOfMonth, $endOfMonth) {
                $q->where('status', 'paid')
                    ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(3)
            ->with('product.primaryImage')
            ->get();

        // 📅 EVENT TERDEKAT (max 3)
        $nearestEvents = Event::where('status', 'upcoming')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->take(3)
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalEvents',
            'totalMaterials',
            'totalUsers',
            'topProducts',
            'nearestEvents'
        ));
    }
}
