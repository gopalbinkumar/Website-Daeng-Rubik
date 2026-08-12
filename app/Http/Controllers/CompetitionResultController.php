<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CompetitionCategory;
use App\Models\CompetitionResult;
use App\Models\CompetitionRound;
use Illuminate\Http\Request;
use App\Models\EventRegistration;

class CompetitionResultController extends Controller
{
    public function index()
    {
        $results = Event::where('category', 'kompetisi')
            ->orderBy('start_datetime', 'desc')
            ->paginate(10);

        return view('admin.events.results-index', compact('results'));
    }

    public function acceptedParticipants(Request $request, $eventId)
    {
        $q = $request->query('q');
        $categoryId = $request->query('competition_category_id');

        return EventRegistration::query()
            ->where('event_id', $eventId)
            ->where('status', 'accepted')
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->whereHas('competitionCategories', function ($cat) use ($categoryId) {
                    $cat->where('competition_categories.id', $categoryId);
                });
            })
            ->when($q, function ($query) use ($q) {
                $query->where('participant_name', 'like', "%{$q}%");
            })
            ->orderBy('participant_name')
            ->limit(50)
            ->get()
            ->map(fn($r) => [
                'user_id' => $r->user_id,
                'name' => $r->participant_name,
            ]);
    }

    public function create(Request $request)
    {
        $eventId = $request->query('event_id');

        $event = Event::where('id', $eventId)
            ->where('category', 'kompetisi')
            ->firstOrFail();

        $categories = $event->competitionCategories()
            ->orderBy('competition_categories.sort_order')
            ->orderBy('competition_categories.id')
            ->get();

        $rounds = CompetitionRound::where('event_id', $event->id)
            ->orderBy('round_number')
            ->get();

        $results = CompetitionResult::with(['category', 'round'])
            ->where('event_id', $event->id)
            ->get();

        return view('admin.events.results-create', [
            'event' => $event,
            'categories' => $categories,
            'rounds' => $rounds,
            'results' => $results,

            // 🔥 STATE YANG DIPERTAHANKAN
            'selectedCategory' => $request->query('competition_category_id'),
            'selectedRound' => $request->query('round_number'),
            'selectedUserId' => $request->query('user_id'),
        ]);
    }


    /**
     * 🔍 CHECK EXISTING RESULT (AJAX)
     */
    public function check(Request $request, Event $event)
    {
        $request->validate([
            'competition_category_id' => 'required|integer',
            'round_number' => 'required|integer',
            'user_id' => 'required|integer',
        ]);

        $round = CompetitionRound::where('event_id', $event->id)
            ->where('competition_category_id', $request->competition_category_id)
            ->where('round_number', $request->round_number)
            ->first();

        if (!$round) {
            return response()->json(['exists' => false]);
        }

        $result = CompetitionResult::where('event_id', $event->id)
            ->where('competition_category_id', $request->competition_category_id)
            ->where('round_id', $round->id)
            ->where('user_id', $request->user_id)
            ->first();

        if (!$result) {
            return response()->json(['exists' => false]);
        }

        return response()->json([
            'exists' => true,
            'data' => [
                'attempt1' => $result->attempt1,
                'attempt2' => $result->attempt2,
                'attempt3' => $result->attempt3,
                'attempt4' => $result->attempt4,
                'attempt5' => $result->attempt5,
                'best' => $result->best,
                'average' => $result->average,
            ]
        ]);
    }

    /**
     * 💾 STORE = CREATE / UPDATE (UPSERT)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'competition_category_id' => 'required|exists:competition_categories,id',
            'round_number' => 'required|integer|min:1|max:10',
            'user_id' => 'required|exists:users,id',

            'attempt1' => 'nullable|string',
            'attempt2' => 'nullable|string',
            'attempt3' => 'nullable|string',
            'attempt4' => 'nullable|string',
            'attempt5' => 'nullable|string',
            'best' => 'nullable|string',
            'average' => 'nullable|string',
        ]);

        $round = CompetitionRound::firstOrCreate([
            'event_id' => $validated['event_id'],
            'competition_category_id' => $validated['competition_category_id'],
            'round_number' => $validated['round_number'],
        ]);

        $registration = EventRegistration::where('event_id', $validated['event_id'])
            ->where('user_id', $validated['user_id'])
            ->where('status', 'accepted')
            ->whereHas('competitionCategories', function ($query) use ($validated) {
                $query->where('competition_categories.id', $validated['competition_category_id']);
            })
            ->firstOrFail();

        CompetitionResult::updateOrCreate(
            [
                'event_id' => $validated['event_id'],
                'competition_category_id' => $validated['competition_category_id'],
                'round_id' => $round->id,
                'user_id' => $registration->user_id,
            ],
            [
                'participant_name' => $registration->participant_name,
                'attempt1' => $validated['attempt1'],
                'attempt2' => $validated['attempt2'],
                'attempt3' => $validated['attempt3'],
                'attempt4' => $validated['attempt4'],
                'attempt5' => $validated['attempt5'],
                'best' => $validated['best'],
                'average' => $validated['average'],
            ]
        );

        $this->recalculateRanks(
            $validated['event_id'],
            $validated['competition_category_id'],
            $round->id
        );

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Hasil kompetisi berhasil disimpan',
            ]);
        }

        return redirect()
            ->route('admin.events.competition.create', [
                'event_id' => $validated['event_id'],
                'competition_category_id' => $validated['competition_category_id'],
                'round_number' => $validated['round_number'],
                'user_id' => $validated['user_id'],
            ])
            ->with('success', 'Hasil kompetisi berhasil disimpan');

    }

    public function resultsByCategoryRound(Request $request, Event $event)
    {
        $request->validate([
            'competition_category_id' => 'required|integer',
            'round_number' => 'required|integer',
        ]);

        $round = CompetitionRound::where('event_id', $event->id)
            ->where('competition_category_id', $request->competition_category_id)
            ->where('round_number', $request->round_number)
            ->first();

        if (!$round) {
            return response()->json([]);
        }

        $results = CompetitionResult::with('user')
            ->where('event_id', $event->id)
            ->where('competition_category_id', $request->competition_category_id)
            ->where('round_id', $round->id)
            ->orderBy('rank')
            ->get();

        return response()->json(
            $results->map(fn($r) => [
                'rank' => $r->rank,
                'name' => $r->participant_name,
                'attempt1' => $r->attempt1,
                'attempt2' => $r->attempt2,
                'attempt3' => $r->attempt3,
                'attempt4' => $r->attempt4,
                'attempt5' => $r->attempt5,
                'best' => $r->best,
                'average' => $r->average,
            ])
        );
    }

    private function recalculateRanks($eventId, $categoryId, $roundId): void
    {
        $results = CompetitionResult::where('event_id', $eventId)
            ->where('competition_category_id', $categoryId)
            ->where('round_id', $roundId)
            ->get()
            ->sort(function ($a, $b) {
                $avgA = $this->timeToCentiseconds($a->average);
                $avgB = $this->timeToCentiseconds($b->average);

                if ($avgA !== $avgB) {
                    return $avgA <=> $avgB;
                }

                $bestA = $this->timeToCentiseconds($a->best);
                $bestB = $this->timeToCentiseconds($b->best);

                return $bestA <=> $bestB;
            })
            ->values();

        foreach ($results as $index => $result) {
            $result->update([
                'rank' => $index + 1,
            ]);
        }
    }

    private function timeToCentiseconds($value): int
    {
        if (!$value) {
            return PHP_INT_MAX;
        }

        $value = strtoupper(trim($value));

        if ($value === 'DNF' || $value === 'DNS') {
            return PHP_INT_MAX;
        }

        if (str_contains($value, ':')) {
            [$minutes, $rest] = explode(':', $value);
            [$seconds, $centiseconds] = explode('.', $rest);

            return (((int) $minutes * 60) + (int) $seconds) * 100 + (int) $centiseconds;
        }

        if (str_contains($value, '.')) {
            [$seconds, $centiseconds] = explode('.', $value);

            return ((int) $seconds * 100) + (int) $centiseconds;
        }

        return PHP_INT_MAX;
    }

}
