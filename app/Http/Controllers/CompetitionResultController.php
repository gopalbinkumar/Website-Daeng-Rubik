<?php

namespace App\Http\Controllers;

use App\Models\CompetitionCategory;
use App\Models\CompetitionResult;
use App\Models\Event;
use Illuminate\Http\Request;

class CompetitionResultController extends Controller
{
    /**
     * Daftar hasil kompetisi (admin).
     */
    public function index(Request $request)
    {
        $query = CompetitionResult::with(['event', 'category'])
            ->orderBy('event_id')
            ->orderBy('competition_category_id')
            ->orderBy('rank');

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('competition_category_id')) {
            $query->where('competition_category_id', $request->competition_category_id);
        }

        $results = $query->paginate(20)->withQueryString();

        $events = Event::competition()
            ->orderByDesc('start_datetime')
            ->get();

        $competitionCategories = CompetitionCategory::orderBy('name')->get();

        return view('admin.events.results-index', compact(
            'results',
            'events',
            'competitionCategories'
        ));
    }

    /**
     * Form input hasil kompetisi (admin).
     */
    public function create()
    {
        $events = Event::competition()
            ->orderByDesc('start_datetime')
            ->get();

        $competitionCategories = CompetitionCategory::orderBy('name')->get();

        return view('admin.events.results-create', compact(
            'events',
            'competitionCategories'
        ));
    }

    /**
     * Simpan hasil kompetisi (admin).
     */
    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'competition_category_id' => 'required|exists:competition_categories,id',
            'participant_name' => 'required|string|max:255',
            'rank' => 'required|integer|min:1',

            'attempt1' => 'nullable|numeric|min:0',
            'attempt2' => 'nullable|numeric|min:0',
            'attempt3' => 'nullable|numeric|min:0',
            'attempt4' => 'nullable|numeric|min:0',
            'attempt5' => 'nullable|numeric|min:0',

            'best' => 'nullable|numeric|min:0',
            'average' => 'nullable|numeric|min:0',
        ]);

        // pastikan event adalah kompetisi
        $event = Event::competition()->findOrFail($request->event_id);

        // hitung best & average jika belum diisi
        $attempts = collect([
            $request->attempt1,
            $request->attempt2,
            $request->attempt3,
            $request->attempt4,
            $request->attempt5,
        ])->filter(fn ($v) => $v !== null && $v !== '');

        $best = $request->best;
        $average = $request->average;

        if ($attempts->isNotEmpty()) {
            if ($best === null || $best === '') {
                $best = $attempts->min();
            }

            if ($average === null || $average === '') {
                $average = round($attempts->avg(), 2);
            }
        }

        CompetitionResult::create([
            'event_id' => $event->id,
            'competition_category_id' => $request->competition_category_id,
            'participant_name' => $request->participant_name,
            'rank' => $request->rank,
            'attempt1' => $request->attempt1,
            'attempt2' => $request->attempt2,
            'attempt3' => $request->attempt3,
            'attempt4' => $request->attempt4,
            'attempt5' => $request->attempt5,
            'best' => $best,
            'average' => $average,
        ]);

        return redirect()
            ->route('admin.events.competition.index')
            ->with('success', 'Hasil kompetisi berhasil disimpan.');
    }
}