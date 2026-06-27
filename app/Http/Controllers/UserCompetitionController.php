<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\CompetitionRound;
use App\Models\CompetitionResult;
use Illuminate\Support\Facades\Auth;

class UserCompetitionController extends Controller
{
    public function index()
    {
        $events = Auth::user()
            ->events()
            ->where('category', 'kompetisi')
            ->whereIn('event_registrations.status', ['pending', 'accepted'])
            ->orderBy('start_datetime', 'desc')
            ->get();

        return view('pages.my-events', compact('events'));
    }

    public function show($id, $slug)
    {
        $event = Event::where('id', $id)
            ->where('slug', $slug)
            ->firstOrFail();

        if ($event->category !== 'kompetisi') {
            abort(404);
        }

        $competitionCategories = $event->competitionCategories()
            ->orderBy('competition_categories.sort_order')
            ->orderBy('competition_categories.id')
            ->get();

        $rounds = CompetitionRound::where('event_id', $event->id)
            ->when(request('category'), function ($q) {
                $q->where('competition_category_id', request('category'));
            })
            ->orderBy('round_number')
            ->get();

        $selectedRound = null;

        if (request('round')) {
            $selectedRound = CompetitionRound::where('event_id', $event->id)
                ->where('round_number', request('round'))
                ->when(request('category'), function ($q) {
                    $q->where('competition_category_id', request('category'));
                })
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL HASIL KOMPETISI
        |--------------------------------------------------------------------------
        | Rank TIDAK dihitung ulang di sini.
        | Rank diambil langsung dari kolom competition_results.rank.
        | Jika rank NULL, akan tetap NULL dan di Blade tampil sebagai "-".
        */

        $allResults = CompetitionResult::with(['user', 'category', 'round'])
            ->where('competition_results.event_id', $event->id)
            ->when(request('category'), function ($q) {
                $q->where('competition_results.competition_category_id', request('category'));
            })
            ->when($selectedRound, function ($q) use ($selectedRound) {
                $q->where('competition_results.round_id', $selectedRound->id);
            })
            ->join('competition_rounds', 'competition_results.round_id', '=', 'competition_rounds.id')
            ->select('competition_results.*')
            ->orderBy('competition_results.competition_category_id')
            ->orderBy('competition_rounds.round_number')
            ->orderByRaw('competition_results.rank IS NULL, competition_results.rank ASC')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | MODE GROUPING
        |--------------------------------------------------------------------------
        */

        $groupedResults = collect();

        /*
        |--------------------------------------------------------------------------
        | MODE 1 — TANPA FILTER
        |--------------------------------------------------------------------------
        */
        if (!request('category') && !request('round')) {
            $groupedResults = $allResults
                ->groupBy('competition_category_id')
                ->map(function ($categoryGroup) {
                    return $categoryGroup
                        ->groupBy(fn ($row) => optional($row->round)->round_number)
                        ->sortKeys();
                })
                ->sortKeys();
        }

        /*
        |--------------------------------------------------------------------------
        | MODE 2 — FILTER KATEGORI SAJA
        |--------------------------------------------------------------------------
        */
        elseif (request('category') && !request('round')) {
            $groupedResults = $allResults
                ->groupBy(fn ($row) => optional($row->round)->round_number)
                ->sortKeys();

            $groupedResults = collect([
                request('category') => $groupedResults,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | MODE 3 — FILTER ROUND SAJA
        |--------------------------------------------------------------------------
        */
        elseif (!request('category') && request('round')) {
            $groupedResults = $allResults
                ->groupBy('competition_category_id')
                ->map(function ($categoryGroup) {
                    return $categoryGroup
                        ->groupBy(fn ($row) => optional($row->round)->round_number)
                        ->sortKeys();
                })
                ->sortKeys();
        }

        /*
        |--------------------------------------------------------------------------
        | MODE 4 — FILTER KATEGORI + ROUND
        |--------------------------------------------------------------------------
        */
        elseif (request('category') && request('round')) {
            $groupedResults = collect([
                request('category') => collect([
                    request('round') => $allResults,
                ]),
            ]);
        }

        $results = $allResults;

        return view('pages.competition-detail', compact(
            'event',
            'competitionCategories',
            'rounds',
            'results',
            'groupedResults'
        ));
    }
}