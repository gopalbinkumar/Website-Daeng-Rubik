<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\CompetitionCategory;
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
        $event = Event::where('slug', $slug)->firstOrFail();

        if ($event->category !== 'kompetisi') {
            abort(404);
        }

        $competitionCategories = $event->competitionCategories;

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
        | AMBIL DATA DASAR
        |--------------------------------------------------------------------------
        */

        $allResults = CompetitionResult::with(['user', 'category', 'round'])
            ->where('event_id', $event->id)
            ->when(request('category'), function ($q) {
                $q->where('competition_category_id', request('category'));
            })
            ->when($selectedRound, function ($q) use ($selectedRound) {
                $q->where('round_id', $selectedRound->id);
            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | SORT + RANK PER ROUND (MODE NORMAL DASAR)
        |--------------------------------------------------------------------------
        */

        $allResults = $allResults
            ->groupBy(function ($row) {
                return $row->competition_category_id . '-' . $row->round->round_number;
            })
            ->map(function ($group) {

                // Convert numeric
                $group = $group->map(function ($row) {
                    $row->avg_numeric = is_numeric($row->average)
                        ? (float) $row->average
                        : null;

                    $row->best_numeric = is_numeric($row->best)
                        ? (float) $row->best
                        : null;

                    return $row;
                });

                // Sort avg → best
                $group = $group->sort(function ($a, $b) {

                    if (is_null($a->avg_numeric) && is_null($b->avg_numeric))
                        return 0;
                    if (is_null($a->avg_numeric))
                        return 1;
                    if (is_null($b->avg_numeric))
                        return -1;

                    if ($a->avg_numeric !== $b->avg_numeric) {
                        return $a->avg_numeric <=> $b->avg_numeric;
                    }

                    return $a->best_numeric <=> $b->best_numeric;
                })->values();

                // Generate rank per round
                foreach ($group as $index => $row) {
                    $row->rank = $index + 1;
                }

                return $group;
            })
            ->flatten(1)
            ->values();

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
                ->groupBy(fn($row) => $row->round->round_number)
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
        ->groupBy(fn($row) => $row->round->round_number)
        ->sortKeys();

    // Bungkus supaya tetap konsisten
    $groupedResults = collect([
        request('category') => $groupedResults
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
                ->groupBy(fn($row) => $row->round->round_number)
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
            request('round') => $allResults
        ])
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
