<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\EventRegistration;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EventParticipantsExport implements WithMultipleSheets
{
    protected $eventId;
    protected $event;

    public function __construct($eventId)
    {
        $this->eventId = $eventId;

        $this->event = Event::competition()
            ->where('id', $eventId)
            ->firstOrFail();
    }

    public function sheets(): array
    {
        $sheets = [];

        // Sheet pertama: semua peserta
        $sheets[] = new EventParticipantSheet(
            $this->eventId,
            $this->event->title,
            'Semua Peserta'
        );

        // Ambil semua kategori yang digunakan dalam event
        $categories = $this->event
            ->competitionCategories()
            ->orderBy('name')
            ->get();

        foreach ($categories as $category) {
            $sheets[] = new EventParticipantSheet(
                $this->eventId,
                $this->event->title,
                $category->name,
                $category->id
            );
        }

        return $sheets;
    }
}