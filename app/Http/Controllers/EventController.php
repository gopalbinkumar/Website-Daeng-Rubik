<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\CompetitionCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * List event
     */
    public function index(Request $request)
    {
        $query = Event::query();

        /* =====================
         |  SEARCH
         ===================== */
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        /* =====================
         |  FILTER STATUS
         ===================== */
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /* =====================
         |  SORT
         ===================== */
        switch ($request->sort) {
            case 'oldest':
                $query->orderBy('start_datetime', 'asc');
                break;

            case 'nearest':
                $query->orderBy('start_datetime', 'desc');
                break;

            default:
                // Terbaru
                $query->latest('start_datetime');
                break;
        }

        /* =====================
         |  PAGINATION
         ===================== */
        $events = $query
            ->paginate(10)
            ->withQueryString();

        /* =====================
         |  DATA TAMBAHAN
         ===================== */
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

            'status' => 'required|in:upcoming,ongoing,finished',

            'competition_categories' => 'nullable|required_if:category,kompetisi|array',
            'competition_categories.*' => 'exists:competition_categories,id',

            'cover_image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request) {

            // Upload cover image
            $coverPath = null;
            if ($request->hasFile('cover_image')) {
                $coverPath = $request->file('cover_image')
                    ->store('events/covers', 'public');
            }

            // Create event
            $event = Event::create([
                'title' => $request->title,
                'category' => $request->category,
                'custom_category' => $request->custom_category,
                'description' => $request->description,

                'start_datetime' => $request->start_date . ' ' . $request->start_time,
                'end_datetime' => $request->end_date . ' ' . $request->end_time,

                'location' => $request->location,
                'cover_image' => $coverPath,

                'ticket_price' => $request->category === 'kompetisi' ? $request->ticket_price : null,
                'max_participants' => $request->category === 'kompetisi' ? $request->max_participants : null,
                'total_prize' => $request->category === 'kompetisi' ? $request->total_prize : null,

                'status' => $request->status,
            ]);

            // Sync kategori lomba (kompetisi saja)
            if ($request->category === 'kompetisi') {
                $event->competitionCategories()
                    ->sync($request->competition_categories);
            }
        });

        return redirect()->back()->with('success', 'Event berhasil ditambahkan');
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

            'status' => 'required|in:upcoming,ongoing,finished',

            'competition_categories' => 'nullable|array',
            'competition_categories.*' => 'exists:competition_categories,id',

            'cover_image' => 'nullable|image|max:2048',
        ]);

        DB::transaction(function () use ($request, $event) {

            // 🔥 HANDLE COVER IMAGE
            if ($request->hasFile('cover_image')) {

                // hapus cover lama (jika ada)
                if ($event->cover_image && Storage::disk('public')->exists($event->cover_image)) {
                    Storage::disk('public')->delete($event->cover_image);
                }

                // upload cover baru
                $event->cover_image = $request->file('cover_image')
                    ->store('events/covers', 'public');
            }

            // UPDATE DATA UTAMA
            $event->update([
                'title' => $request->title,
                'category' => $request->category,
                'custom_category' => $request->category === 'lainnya'
                    ? $request->custom_category
                    : null,
                'description' => $request->description,
                'start_datetime' => $request->start_date . ' ' . $request->start_time,
                'end_datetime' => $request->end_date . ' ' . $request->end_time,
                'location' => $request->location,
                'ticket_price' => $request->category === 'kompetisi'
                    ? $request->ticket_price
                    : null,
                'max_participants' => $request->category === 'kompetisi'
                    ? $request->max_participants
                    : null,
                'total_prize' => $request->category === 'kompetisi'
                    ? $request->total_prize
                    : null,
                'status' => $request->status,
            ]);

            // SYNC KATEGORI LOMBA
            if ($request->category === 'kompetisi') {
                $event->competitionCategories()
                    ->sync($request->competition_categories ?? []);
            } else {
                $event->competitionCategories()->detach();
            }
        });

        return back()->with('success', 'Event berhasil diupdate');
    }


    /**
     * Delete event
     */
    public function destroy(Event $event)
    {
        if ($event->cover_image) {
            Storage::disk('public')->delete($event->cover_image);
        }

        $event->delete();

        return redirect()->back()->with('success', 'Event berhasil dihapus');
    }
}
