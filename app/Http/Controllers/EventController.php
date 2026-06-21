<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CompetitionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Hitung status event berdasarkan waktu sekarang
     */
    private function resolveEventStatus($startDateTime, $endDateTime)
    {
        $now = Carbon::now();

        $startDateTime = Carbon::parse($startDateTime);
        $endDateTime = Carbon::parse($endDateTime);

        if ($now->lt($startDateTime)) {
            return 'upcoming';
        }

        if ($now->gt($endDateTime)) {
            return 'finished';
        }

        return 'ongoing';
    }

    /**
     * Sinkronkan status semua event aktif ke database.
     *
     * Catatan:
     * Karena model memakai SoftDeletes, query Event::where()
     * otomatis hanya mengubah event yang belum soft delete.
     */
    private function syncAllEventStatuses()
    {
        $now = Carbon::now();

        Event::where('start_datetime', '>', $now)
            ->update([
                'status' => 'upcoming',
            ]);

        Event::where('start_datetime', '<=', $now)
            ->where('end_datetime', '>=', $now)
            ->update([
                'status' => 'ongoing',
            ]);

        Event::where('end_datetime', '<', $now)
            ->update([
                'status' => 'finished',
            ]);
    }

    /**
     * Halaman publik event
     */
    public function publicIndex()
    {
        $this->syncAllEventStatuses();

        $featured = Event::where('category', 'kompetisi')
            ->where('status', 'upcoming')
            ->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc')
            ->with('competitionCategories')
            ->first();

        $events = Event::orderBy('start_datetime', 'asc')
            ->with('competitionCategories')
            ->get();

        return view('pages.events', compact('featured', 'events'));
    }

    /**
     * List event admin
     */
    public function index(Request $request)
    {
        $this->syncAllEventStatuses();

        $query = Event::query()->with('competitionCategories');

        /* =====================
         | SEARCH
         ===================== */
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        /* =====================
         | FILTER STATUS
         ===================== */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /* =====================
         | SORT
         ===================== */
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('start_datetime', 'asc');
                break;

            case 'nearest':
                $query->orderBy('start_datetime', 'desc');
                break;

            default:
                $query->latest('start_datetime');
                break;
        }

        /* =====================
         | PAGINATION
         ===================== */
        $events = $query
            ->paginate(10)
            ->withQueryString();

        $competitionCategories = CompetitionCategory::orderBy('name')->get();

        return view('admin.events.index', compact(
            'events',
            'competitionCategories'
        ));
    }

    /**
     * Store event
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:kompetisi,gathering,lainnya',
            'custom_category' => 'nullable|required_if:category,lainnya|max:100',
            'description' => 'required|string',

            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',

            'location' => 'required|string|max:255',

            'ticket_price' => 'nullable|required_if:category,kompetisi|integer|min:0',
            'max_participants' => 'nullable|required_if:category,kompetisi|integer|min:1',
            'total_prize' => 'nullable|required_if:category,kompetisi|integer|min:0',

            'competition_categories' => 'nullable|required_if:category,kompetisi|array',
            'competition_categories.*' => 'exists:competition_categories,id',

            'cover_image' => 'nullable|image|max:2048',
        ]);

        $startDateTime = Carbon::parse($request->start_date . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($request->end_date . ' ' . $request->end_time);

        if ($endDateTime->lt($startDateTime)) {
            return back()
                ->withErrors([
                    'end_time' => 'Tanggal dan jam akhir tidak boleh lebih awal dari tanggal dan jam mulai.',
                ])
                ->withInput();
        }

        DB::transaction(function () use ($request, $startDateTime, $endDateTime) {
            $coverPath = null;

            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')
                    ->store('events/covers', 'public');
            }

            $event = Event::create([
                'title' => $request->title,
                'category' => $request->category,
                'custom_category' => $request->category === 'lainnya'
                    ? $request->custom_category
                    : null,
                'description' => $request->description,

                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,

                'location' => $request->location,
                'cover_image' => $coverPath,

                'ticket_price' => $request->category === 'kompetisi'
                    ? $request->ticket_price
                    : null,
                'max_participants' => $request->category === 'kompetisi'
                    ? $request->max_participants
                    : null,
                'total_prize' => $request->category === 'kompetisi'
                    ? $request->total_prize
                    : null,

                'status' => $this->resolveEventStatus($startDateTime, $endDateTime),
            ]);

            if ($request->category === 'kompetisi') {
                $event->competitionCategories()
                    ->sync($request->competition_categories ?? []);
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Event berhasil ditambahkan');
    }

    /**
     * Update event
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:kompetisi,gathering,lainnya',
            'custom_category' => 'nullable|required_if:category,lainnya|max:100',
            'description' => 'required|string',

            'start_date' => 'required|date',
            'start_time' => 'required',
            'end_date' => 'required|date',
            'end_time' => 'required',

            'location' => 'required|string|max:255',

            'ticket_price' => 'nullable|integer|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'total_prize' => 'nullable|integer|min:0',

            'competition_categories' => 'nullable|array',
            'competition_categories.*' => 'exists:competition_categories,id',

            'cover_image' => 'nullable|image|max:2048',
        ]);

        $startDateTime = Carbon::parse($request->start_date . ' ' . $request->start_time);
        $endDateTime = Carbon::parse($request->end_date . ' ' . $request->end_time);

        if ($endDateTime->lt($startDateTime)) {
            return back()
                ->withErrors([
                    'end_time' => 'Tanggal dan jam akhir tidak boleh lebih awal dari tanggal dan jam mulai.',
                ])
                ->withInput();
        }

        DB::transaction(function () use ($request, $event, $startDateTime, $endDateTime) {
            $coverPath = $event->cover_image;

            if ($request->hasFile('cover_image')) {
                if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                    Storage::disk('public')->delete($event->cover_image);
                }

                $coverPath = $request->file('cover_image')
                    ->store('events/covers', 'public');
            }

            $event->update([
                'title' => $request->title,
                'category' => $request->category,
                'custom_category' => $request->category === 'lainnya'
                    ? $request->custom_category
                    : null,
                'description' => $request->description,

                'start_datetime' => $startDateTime,
                'end_datetime' => $endDateTime,

                'location' => $request->location,
                'cover_image' => $coverPath,

                'ticket_price' => $request->category === 'kompetisi'
                    ? $request->ticket_price
                    : null,
                'max_participants' => $request->category === 'kompetisi'
                    ? $request->max_participants
                    : null,
                'total_prize' => $request->category === 'kompetisi'
                    ? $request->total_prize
                    : null,

                'status' => $this->resolveEventStatus($startDateTime, $endDateTime),
            ]);

            if ($request->category === 'kompetisi') {
                $event->competitionCategories()
                    ->sync($request->competition_categories ?? []);
            } else {
                $event->competitionCategories()->detach();
            }
        });

        return redirect()
            ->back()
            ->with('success', 'Event berhasil diupdate');
    }

    /**
     * Soft delete event
     *
     * Cover image tidak dihapus supaya datanya masih aman
     * jika nanti ingin direstore dari database.
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()
            ->back()
            ->with('success', 'Event berhasil dihapus');
    }
}