@php
    use Carbon\Carbon;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection;

    /* =========================
     | DUMMY EVENT
     ========================= */
    $events = collect([
        (object) [
            'id' => 1,
            'title' => 'Daeng Rubik Open 2025',
            'start_datetime' => Carbon::parse('2025-06-15'),
        ],
        (object) [
            'id' => 2,
            'title' => 'Sulsel Speedcubing Championship',
            'start_datetime' => Carbon::parse('2025-07-03'),
        ],
    ]);

    /* =========================
     | DUMMY KATEGORI RUBIK
     ========================= */
    $competitionCategories = collect([
        (object) ['id' => 1, 'name' => '3x3x3 Cube'],
        (object) ['id' => 2, 'name' => '2x2x2 Cube'],
        (object) ['id' => 3, 'name' => 'Pyraminx'],
    ]);

    /* =========================
     | RAW HASIL KOMPETISI
     ========================= */
    $rawResults = collect([
        (object) [
            'event_id' => 1,
            'category_id' => 1,
            'rank' => 1,
            'participant_name' => 'Andi Pratama',
            'best' => 7.89,
            'average' => 8.05,
        ],
        (object) [
            'event_id' => 1,
            'category_id' => 1,
            'rank' => 2,
            'participant_name' => 'Muh. Rizal',
            'best' => 8.33,
            'average' => 8.47,
        ],
        (object) [
            'event_id' => 2,
            'category_id' => 2,
            'rank' => 1,
            'participant_name' => 'Siti Aisyah',
            'best' => 3.05,
            'average' => 3.13,
        ],
        (object) [
            'event_id' => 2,
            'category_id' => 3,
            'rank' => 1,
            'participant_name' => 'Fajar Nugraha',
            'best' => 2.36,
            'average' => 2.40,
        ],
    ]);

    /* =========================
     | FILTER (EVENT & KATEGORI)
     ========================= */
    $filtered = $rawResults->filter(function ($row) {
        if (request('event_id') && $row->event_id != request('event_id')) {
            return false;
        }
        if (request('competition_category_id') && $row->category_id != request('competition_category_id')) {
            return false;
        }
        return true;
    })->map(function ($row) use ($events, $competitionCategories) {
        $row->event = $events->firstWhere('id', $row->event_id);
        $row->category = $competitionCategories->firstWhere('id', $row->category_id);
        return $row;
    })->values();

    /* =========================
     | PAGINATION DUMMY
     ========================= */
    $perPage = 10;
    $currentPage = LengthAwarePaginator::resolveCurrentPage();
    $items = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

    $results = new LengthAwarePaginator(
        $items,
        $filtered->count(),
        $perPage,
        $currentPage,
        ['path' => request()->url(), 'query' => request()->query()]
    );
@endphp

@extends('admin.layouts.app')

@section('title', 'Hasil Kompetisi')
@section('page-title', 'Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/events-results-index.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h2 class="page-title">Hasil Event Kompetisi Rubik</h2>
        <a href="{{ route('admin.events.competition.create') }}" class="btn btn-primary">
            + Input Hasil Baru
        </a>
    </div>

    <div class="table-wrapper">
        <form method="GET" action="{{ route('admin.events.competition.index') }}">
            <div class="table-toolbar">
                <select name="event_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Event</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->title }} ({{ $event->start_datetime->format('d M Y') }})
                        </option>
                    @endforeach
                </select>

                <select name="competition_category_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach ($competitionCategories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ request('competition_category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Kategori</th>
                    <th>Peringkat</th>
                    <th>Nama Peserta</th>
                    <th class="text-end">Best</th>
                    <th class="text-end">Average</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($results as $row)
                    <tr>
                        <td>
                            <strong>{{ $row->event->title }}</strong><br>
                            <small style="color:var(--admin-text-muted);">
                                {{ $row->event->start_datetime->format('d M Y') }}
                            </small>
                        </td>
                        <td>{{ $row->category->name ?? '-' }}</td>
                        <td>#{{ $row->rank }}</td>
                        <td>{{ $row->participant_name }}</td>
                        <td class="text-end"><strong>{{ $row->best }}</strong></td>
                        <td class="text-end"><strong>{{ $row->average }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:var(--admin-text-muted);">
                            Belum ada data hasil kompetisi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan
                {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }}
                dari {{ $results->total() }} hasil
            </div>

            <div class="pagination-controls">
                {{ $results->links() }}
            </div>
        </div>
    </div>
@endsection