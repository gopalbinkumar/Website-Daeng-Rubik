<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Event;
use App\Models\LearningMaterial;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * =========================
     * HOME USER
     * =========================
     */
    public function home()
    {
        $featuredProducts = Product::with('primaryImage')
            ->where('is_active', true)
            ->latest()
            ->take(4)
            ->get();

        $featuredEvent = Event::where('category', 'kompetisi')
            ->where('status', 'upcoming')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->with('competitionCategories')
            ->first();

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
        $totalProducts   = Product::count();
        $totalEvents     = Event::count();
        $totalMaterials  = LearningMaterial::count();
        $totalAdmins     = User::where('role', 'admin')->count();

        // 🆕 PRODUK TERBARU (limit 3)
        $latestProducts = Product::latest()
            ->take(3)
            ->get();

        // 🕒 AKTIVITAS TERKINI
        $activities = collect([
            [
                'icon' => 'fa-box',
                'text' => 'Produk baru ditambahkan',
                'time' => Product::latest()->first()?->created_at,
            ],
            [
                'icon' => 'fa-calendar',
                'text' => 'Event baru dibuat',
                'time' => Event::latest()->first()?->created_at,
            ],
            [
                'icon' => 'fa-book',
                'text' => 'Materi baru diupload',
                'time' => LearningMaterial::latest()->first()?->created_at,
            ],
        ])->filter(fn ($a) => $a['time']);

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalEvents',
            'totalMaterials',
            'totalAdmins',
            'latestProducts',
            'activities'
        ));
    }
}
