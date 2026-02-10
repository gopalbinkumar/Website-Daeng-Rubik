<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CompetitionCategory;
use App\Models\CompetitionResult;
use App\Models\CompetitionRound;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use App\Models\User;

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

        return EventRegistration::with('user')
            ->where('event_id', $eventId)
            ->where('status', 'accepted')
            ->whereHas(
                'user',
                fn($u) =>
                $u->where('name', 'like', "%{$q}%")
            )
            ->orderBy('created_at')
            ->limit(10)
            ->get()
            ->map(fn($r) => [
                'user_id' => $r->user->id,
                'name' => $r->user->name,
            ]);
    }

    public function create(Request $request)
    {
        $eventId = $request->query('event_id');

        $event = Event::where('id', $eventId)
            ->where('category', 'kompetisi')
            ->firstOrFail();

        $categories = $event->competitionCategories()->orderBy('name')->get();

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

        $user = User::findOrFail($validated['user_id']);

        CompetitionResult::updateOrCreate(
            [
                'event_id' => $validated['event_id'],
                'competition_category_id' => $validated['competition_category_id'],
                'round_id' => $round->id,
                'user_id' => $user->id,
            ],
            [
                'participant_name' => $user->name,
                'attempt1' => $validated['attempt1'],
                'attempt2' => $validated['attempt2'],
                'attempt3' => $validated['attempt3'],
                'attempt4' => $validated['attempt4'],
                'attempt5' => $validated['attempt5'],
                'best' => $validated['best'],
                'average' => $validated['average'],
            ]
        );

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
            ->orderBy('best')
            ->get();

        return response()->json(
            $results->map(fn($r) => [
                'name' => $r->user->name,
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

}
