<?php

namespace App\Http\Controllers;

use App\Services\WeightedScoringService;

class WeightedScoringController extends Controller
{
    protected $service;

    public function __construct(WeightedScoringService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan halaman ranking weighted scoring
     */
    public function index()
    {
        $ranking = $this->service->calculate();

        return view('admin.weighted-scoring.index', compact('ranking'));
    }

    /**
     * Optional: endpoint JSON (untuk AJAX)
     */
    public function api()
    {
        return response()->json(
            $this->service->calculate()
        );
    }
}
