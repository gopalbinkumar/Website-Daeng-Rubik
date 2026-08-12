<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Exports\EventParticipantsExport;
use Maatwebsite\Excel\Facades\Excel;

class EventRegistrationController extends Controller
{
    public function adminIndex(Request $request)
    {
        $search = trim((string) $request->search);
        $status = $request->status;
        $sort = $request->sort;

        // Semua event kompetisi untuk kebutuhan selectedEvent / referensi
        $competitionEvents = Event::competition()
            ->orderBy('start_datetime', 'asc')
            ->get();

        /**
         * JIKA EVENT DIPILIH
         * → TAMPILKAN PESERTA EVENT TERSEBUT
         */
        if ($request->filled('event_id')) {
            $selectedEvent = Event::competition()
                ->where('id', $request->event_id)
                ->firstOrFail();

            $participantsQuery = EventRegistration::with([
                'event.competitionCategories',
                'competitionCategories',
            ])
                ->where('event_id', $selectedEvent->id);

            // Search peserta
            if ($search !== '') {
                $participantsQuery->where(function ($query) use ($search) {
                    $query->where('participant_name', 'like', "%{$search}%")
                        ->orWhere('participant_email', 'like', "%{$search}%")
                        ->orWhere('participant_whatsapp', 'like', "%{$search}%")
                        ->orWhereHas('competitionCategories', function ($catQuery) use ($search) {
                            $catQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('code', 'like', "%{$search}%");
                        });
                });
            }

            // Filter status pendaftaran peserta
            if (in_array($status, ['pending', 'accepted', 'rejected'], true)) {
                $participantsQuery->where('status', $status);
            }

            // Sort peserta
            match ($sort) {
                'oldest' => $participantsQuery->orderBy('created_at', 'asc'),
                'name_asc' => $participantsQuery->orderBy('participant_name', 'asc'),
                'name_desc' => $participantsQuery->orderBy('participant_name', 'desc'),

                // Default:
                // pending terlama → accepted terbaru → rejected terlama
                default => $participantsQuery
                    ->orderByRaw("
                    CASE
                        WHEN status = 'pending' THEN 1
                        WHEN status = 'accepted' THEN 2
                        WHEN status = 'rejected' THEN 3
                        ELSE 4
                    END
                ")
                    ->orderByRaw("
                    CASE
                        WHEN status = 'pending' THEN created_at
                    END ASC
                ")
                    ->orderByRaw("
                    CASE
                        WHEN status = 'accepted' THEN created_at
                    END DESC
                ")
                    ->orderByRaw("
                    CASE
                        WHEN status = 'rejected' THEN created_at
                    END ASC
                "),
            };

            $participants = $participantsQuery
                ->paginate(10)
                ->withQueryString();

            return view('admin.events.participant-list', [
                'competitionEvents' => $competitionEvents,
                'selectedEvent' => $selectedEvent,
                'participants' => $participants,
                'summaryMode' => false,
            ]);
        }

        /**
         * JIKA BELUM MEMILIH EVENT
         * → TAMPILKAN RINGKASAN EVENT
         */
        $eventSummariesQuery = Event::competition()
            ->withCount('registrations');

        // Search kompetisi
        if ($search !== '') {
            $eventSummariesQuery->where(function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter status event berdasarkan tanggal
        if (in_array($status, ['upcoming', 'ongoing', 'finished'], true)) {
            $now = now();

            if ($status === 'upcoming') {
                $eventSummariesQuery
                    ->where('start_datetime', '>', $now);
            }

            if ($status === 'ongoing') {
                $eventSummariesQuery
                    ->where('start_datetime', '<=', $now)
                    ->where('end_datetime', '>=', $now);
            }

            if ($status === 'finished') {
                $eventSummariesQuery
                    ->where('end_datetime', '<', $now);
            }
        }

        // Sort kompetisi
        match ($sort) {
            'nearest' => $eventSummariesQuery
                ->orderBy('start_datetime', 'asc'),

            'oldest' => $eventSummariesQuery
                ->orderBy('created_at', 'asc'),

            // Default:
            // ongoing → upcoming terdekat → finished terbaru
            default => $eventSummariesQuery
                ->orderByRaw("
                CASE
                    WHEN start_datetime <= NOW()
                         AND end_datetime >= NOW() THEN 1
                    WHEN start_datetime > NOW() THEN 2
                    WHEN end_datetime < NOW() THEN 3
                    ELSE 4
                END
            ")
                ->orderByRaw("
                CASE
                    WHEN start_datetime > NOW()
                    THEN start_datetime
                END ASC
            ")
                ->orderByRaw("
                CASE
                    WHEN end_datetime < NOW()
                    THEN end_datetime
                END DESC
            "),
        };

        $eventSummaries = $eventSummariesQuery
            ->paginate(10)
            ->withQueryString();

        return view('admin.events.participant-list', [
            'competitionEvents' => $competitionEvents,
            'eventSummaries' => $eventSummaries,
            'summaryMode' => true,
        ]);
    }

    public function export($eventId)
    {
        $event = Event::competition()
            ->where('id', $eventId)
            ->firstOrFail();

        return Excel::download(
            new EventParticipantsExport($event->id),
            'peserta-' . $event->slug . '.xlsx'
        );
    }


    public function accept(EventRegistration $registration)
    {
        $registration->update([
            'status' => 'accepted',
        ]);

        return back()->with('success', 'Pendaftaran diterima');
    }

    public function reject(EventRegistration $registration)
    {
        $registration->update([
            'status' => 'rejected',
        ]);

        return back()->with('success', 'Pendaftaran ditolak');
    }



    public function create($slug)
    {
        $event = Event::competition()
            ->where('slug', $slug)
            ->with([
                'competitionCategories' => fn($q) => $q->active()
            ])
            ->firstOrFail();

        $user = Auth::user();

        $registration = null;

        if ($user) {
            $registration = EventRegistration::where('event_id', $event->id)
                ->where('user_id', $user->id)
                ->latest()
                ->first();
        }

        $showRegistrationForm = $user &&
            (
                !$registration ||
                in_array($registration->status, ['pending', 'rejected'])
            );

        return view('pages.event-register', [
            'event' => $event,
            'user' => $user,
            'registration' => $registration,
            'showRegistrationForm' => $showRegistrationForm,
        ]);
    }




    public function store(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',

            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:competition_categories,id',

            'participant_name' => 'required|string|max:255',
            'participant_birthdate' => 'required|date',
            'participant_email' => 'required|email|max:255',
            'participant_whatsapp' => 'required|string|max:25',

        ], [
            'categories.required' => 'Silakan pilih minimal satu kategori lomba.',
            'categories.min' => 'Anda harus memilih minimal satu kategori lomba.',

            'participant_email.required' => 'Email wajib diisi.',
            'participant_email.email' => 'Format email tidak valid.',

            'participant_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
        ]);

        $registration = EventRegistration::where('user_id', Auth::id())
            ->where('event_id', $request->event_id)
            ->first();

        if ($registration && $registration->status === 'accepted') {
            return redirect()
                ->route('user.competitions')
                ->with('error', 'Pendaftaran Anda sudah diterima dan tidak dapat didaftarkan ulang.');
        }

        if ($registration) {
            $registration->update([
                'participant_name' => $request->participant_name,
                'participant_birthdate' => $request->participant_birthdate,
                'participant_email' => $request->participant_email,
                'participant_whatsapp' => $request->participant_whatsapp,
                'status' => 'pending',
            ]);
        } else {
            $registration = EventRegistration::create([
                'user_id' => Auth::id(),
                'event_id' => $request->event_id,
                'participant_name' => $request->participant_name,
                'participant_birthdate' => $request->participant_birthdate,
                'participant_email' => $request->participant_email,
                'participant_whatsapp' => $request->participant_whatsapp,
                'status' => 'pending',
            ]);
        }

        $registration->competitionCategories()->sync($request->categories);

        return redirect()
            ->route('user.competitions')
            ->with('success', 'Pendaftaran ulang berhasil dikirim.');
    }

    public function update(Request $request, EventRegistration $registration)
    {
        $request->validate([
            'categories' => 'nullable|array',
            'categories.*' => 'exists:competition_categories,id',
        ]);

        $registration->competitionCategories()
            ->sync($request->categories ?? []);

        return back()->with('success', 'Data peserta berhasil diperbarui');
    }

}
