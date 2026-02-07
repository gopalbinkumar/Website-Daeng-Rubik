<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventRegistration;

class EventRegistrationController extends Controller
{
    public function create($slug)
    {
        $event = Event::competition()
            ->where('slug', $slug)
            ->with(['competitionCategories' => fn($q) => $q->active()])
            ->firstOrFail();

        $user = Auth::user();

        $alreadyRegistered = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->exists();

        return view('pages.event-register', [
            'event' => $event,
            'user' => $user,
            'alreadyRegistered' => $alreadyRegistered,
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',

            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:competition_categories,id',

            'participant_name' => 'required|string|max:255',
            'participant_email' => 'required|email|max:255',
            'participant_whatsapp' => 'required|string|max:25',
        ]);

        // simpan / ambil registrasi
        $registration = EventRegistration::firstOrCreate(
            [
                'user_id' => Auth::id(),
                'event_id' => $request->event_id,
            ],
            [
                'participant_name' => $request->participant_name,
                'participant_email' => $request->participant_email,
                'participant_whatsapp' => $request->participant_whatsapp,
                'status' => 'pending',
            ]
        );

        // 🔥 SIMPAN KATEGORI LOMBA PER PESERTA
        $registration->competitionCategories()->sync($request->categories);

        return response()->json(['success' => true]);
    }
}
