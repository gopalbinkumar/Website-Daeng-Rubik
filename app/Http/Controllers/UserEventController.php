<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CompetitionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserEventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman "Event Saya"
     */
    public function index()
    {
        $user = Auth::user();

        $events = $user->events()
            ->with('pivot')
            ->orderByDesc('start_datetime')
            ->get();

        return view('pages.my-events', compact('events'));
    }

    /**
     * Detail hasil kompetisi (read-only)
     */
    public function showCompetition(Request $request, Event $event)
    {
        $user = Auth::user();

        // hanya untuk event kompetisi
        if (! $event->isCompetition()) {
            abort(404);
        }

        // user harus terdaftar pada event ini
        $isRegistered = $event->registrations()
            ->where('user_id', $user->id)
            ->exists();

        if (! $isRegistered) {
            abort(403, 'Kamu tidak terdaftar di event ini.');
        }

        $competitionCategories = $event->competitionCategories()
            ->orderBy('name')
            ->get();

        $resultsQuery = $event->competitionResults()
            ->with('category')
            ->orderBy('rank');

        if ($request->filled('category')) {
            $resultsQuery->where('competition_category_id', $request->category);
        }

        $results = $resultsQuery->get();

        return view('pages.competition-detail', compact(
            'event',
            'competitionCategories',
            'results'
        ));
    }
}