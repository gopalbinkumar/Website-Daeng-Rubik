<?php

namespace App\Services;

use App\Models\CubeCategory;
use App\Models\TransactionItem;
use App\Models\CartItem;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\DB;

class WeightedScoringService
{
    private $wSales = 0.5;
    private $wEvent = 0.2;
    private $wCart = 0.3;

    public function calculate()
    {
        // ambil kategori utama (3x3, 4x4, dll)
        $categories = CubeCategory::all();

        /*
        =========================
        1️⃣ PENJUALAN PER KATEGORI
        =========================
        */
        $salesData = TransactionItem::join('products', 'products.id', '=', 'transaction_items.product_id')
            ->select(
                'products.cube_category_id',
                DB::raw('SUM(transaction_items.quantity) as total')
            )
            ->whereHas('transaction', function ($q) {
                $q->whereMonth('created_at', now()->month)
                    ->where('status', 'paid');
            })
            ->groupBy('products.cube_category_id')
            ->pluck('total', 'products.cube_category_id');

        /*
        =========================
        2️⃣ KERANJANG PER KATEGORI
        =========================
        */
        $cartData = CartItem::join('products', 'products.id', '=', 'cart_items.product_id')
            ->select(
                'products.cube_category_id',
                DB::raw('COUNT(cart_items.id) as total')
            )
            ->groupBy('products.cube_category_id')
            ->pluck('total', 'products.cube_category_id');

        /*
        =========================
        3️⃣ EVENT PER MAIN CATEGORY
        =========================
        */
        $latestFinishedCompetitionId = DB::table('events')
            ->where('category', 'kompetisi')
            ->where('status', 'finished')
            ->orderByDesc('end_datetime')
            ->value('id');


        $eventData = DB::table('event_registration_categories')
            ->join(
                'event_registrations',
                'event_registrations.id',
                '=',
                'event_registration_categories.event_registration_id'
            )
            ->join(
                'competition_categories',
                'competition_categories.id',
                '=',
                'event_registration_categories.competition_category_id'
            )
            ->select(
                'competition_categories.main_category',
                DB::raw('COUNT(event_registration_categories.id) as total')
            )
            ->where('event_registrations.event_id', $latestFinishedCompetitionId)
            ->groupBy('competition_categories.main_category')
            ->pluck('total', 'competition_categories.main_category');



        $maxSales = $salesData->max();
        $maxCart = $cartData->max();
        $maxEvent = $eventData->max();

        $results = [];

        foreach ($categories as $category) {

            $sales = $salesData->has($category->id)
                ? $salesData->get($category->id)
                : 0;

            $cart = $cartData->has($category->id)
                ? $cartData->get($category->id)
                : 0;

            $event = $eventData->has($category->name)
                ? $eventData->get($category->name)
                : 0;

            $normSales = ($maxSales > 0)
                ? ($sales / $maxSales) * 10
                : 0;

            $normCart = ($maxCart > 0)
                ? ($cart / $maxCart) * 10
                : 0;

            $normEvent = ($maxEvent > 0)
                ? ($event / $maxEvent) * 10
                : 0;

            $score =
                ($this->wSales * $normSales) +
                ($this->wEvent * $normEvent) +
                ($this->wCart * $normCart);

            $results[] = [
                'product' => $category->name,
                'score' => round($score, 2)
            ];
        }


        return collect($results)
            ->sortByDesc('score')
            ->values()
            ->take(5)
        ;
    }
}
