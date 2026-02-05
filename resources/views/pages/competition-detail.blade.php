@extends('layouts.app')
@php
    use Carbon\Carbon;

    /* =========================
     | DUMMY EVENT
     ========================= */
    $event = (object) [
        'id' => request()->route('event'),
        'title' => 'Daeng Rubik Open 2025',
        'location' => 'Makassar',
        'start_datetime' => Carbon::parse('2025-06-15 09:00'),
        'end_datetime' => Carbon::parse('2025-06-16 17:00'),
    ];

    /* =========================
     | DUMMY KATEGORI KOMPETISI
     ========================= */
    $competitionCategories = collect([
        (object) ['id' => 1, 'name' => '3x3x3 Cube'],
        (object) ['id' => 2, 'name' => '2x2x2 Cube'],
        (object) ['id' => 3, 'name' => 'Pyraminx'],
    ]);

    /* =========================
     | DUMMY HASIL KOMPETISI
     ========================= */
    $resultsRaw = collect([
        (object) [
            'rank' => 1,
            'participant_name' => 'Andi Pratama',
            'category_id' => 1,
            'attempt1' => 8.21,
            'attempt2' => 7.98,
            'attempt3' => 8.05,
            'attempt4' => 7.89,
            'attempt5' => 8.11,
        ],
        (object) [
            'rank' => 2,
            'participant_name' => 'Muh. Rizal',
            'category_id' => 1,
            'attempt1' => 8.55,
            'attempt2' => 8.40,
            'attempt3' => null, // DNF
            'attempt4' => 8.33,
            'attempt5' => 8.47,
        ],
        (object) [
            'rank' => 1,
            'participant_name' => 'Siti Aisyah',
            'category_id' => 2,
            'attempt1' => 3.12,
            'attempt2' => 3.05,
            'attempt3' => 3.22,
            'attempt4' => 3.10,
            'attempt5' => 3.18,
        ],
        (object) [
            'rank' => 1,
            'participant_name' => 'Fajar Nugraha',
            'category_id' => 3,
            'attempt1' => 2.45,
            'attempt2' => 2.38,
            'attempt3' => 2.41,
            'attempt4' => 2.36,
            'attempt5' => 2.40,
        ],
    ]);

    /* =========================
     | PROSES + FILTER KATEGORI
     ========================= */
    $results = $resultsRaw
        ->filter(function ($row) {
            return !request('category') || $row->category_id == request('category');
        })
        ->map(function ($row) use ($competitionCategories) {
            $attempts = collect([
                $row->attempt1,
                $row->attempt2,
                $row->attempt3,
                $row->attempt4,
                $row->attempt5,
            ])->filter();

            $row->best = $attempts->min();
            $row->average = $attempts->count() >= 3
                ? round($attempts->avg(), 2)
                : null;

            $row->category = $competitionCategories->firstWhere('id', $row->category_id);

            return $row;
        })
        ->values();
@endphp

@section('title', $event->title . ' — Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/competition-detail.css') }}">
@endpush

@section('content')

    {{-- HEADER EVENT --}}
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">
                Beranda &gt; Event &gt; Event Saya &gt; Detail Kompetisi
            </div>

            <h1 class="page-title" style="margin-bottom:4px;">
                {{ $event->title }}
            </h1>

            <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:6px;">
                <p class="muted" style="margin:0;">
                    📅 {{ $event->start_datetime->format('d M Y') }} –
                    {{ $event->end_datetime->format('d M Y') }}
                    • {{ $event->start_datetime->format('H:i') }} WIB
                    <br>
                    📍 {{ $event->location }}
                </p>

                <span class="badge" style="
                    background:rgba(229,57,53,.08);
                    color:var(--red);
                    font-size:12px;
                    font-weight:750;
                    border-radius:999px;
                    padding:6px 12px;
                ">
                    🏆 Kompetisi Rubik
                </span>
            </div>
        </div>
    </section>

    {{-- DIVIDER TIPIS --}}
    <section style="border-bottom:1px solid rgba(17,24,39,.06);margin-bottom:10px;"></section>

    {{-- TABEL HASIL KOMPETISI --}}
    <section class="section" style="padding-top:18px;">
        <div class="container">

            <div class="card card-pad" style="padding:20px;">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">
                    <div>
                        <h2 style="font-size:18px;font-weight:800;letter-spacing:-.02em;margin-bottom:4px;">
                            Hasil Kompetisi
                        </h2>
                        <p class="muted" style="margin:0;">
                            Hasil per kategori rubik, terinspirasi dari WCA Live.
                        </p>
                    </div>

                    {{-- Filter kategori rubik --}}
                    <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                        <label for="category" class="muted" style="font-size:13px;margin:0;">
                            Kategori Rubik
                        </label>
                        <select name="category" id="category" class="select" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            @foreach ($competitionCategories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div style="height:14px;"></div>

                {{-- TABLE RESPONSIVE --}}
                <div class="table-responsive" style="overflow-x:auto;">
                    <table class="table table-sm" style="min-width:980px;">
                        <thead>
                            <tr style="background:rgba(17,24,39,.04);">
                                <th style="border-bottom-color:rgba(17,24,39,.08);">Peringkat</th>
                                <th style="border-bottom-color:rgba(17,24,39,.08);">Nama Peserta</th>
                                <th style="border-bottom-color:rgba(17,24,39,.08);">Kategori</th>
                                <th class="text-end" style="border-bottom-color:rgba(17,24,39,.08);">Attempt 1</th>
                                <th class="text-end" style="border-bottom-color:rgba(17,24,39,.08);">Attempt 2</th>
                                <th class="text-end" style="border-bottom-color:rgba(17,24,39,.08);">Attempt 3</th>
                                <th class="text-end" style="border-bottom-color:rgba(17,24,39,.08);">Attempt 4</th>
                                <th class="text-end" style="border-bottom-color:rgba(17,24,39,.08);">Attempt 5</th>
                                <th class="text-end" style="border-bottom-color:rgba(17,24,39,.08);">Best</th>
                                <th class="text-end" style="border-bottom-color:rgba(17,24,39,.08);">Average</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($results as $row)
                                <tr class="table-row-hover">
                                    <td style="font-weight:700;">
                                        #{{ $row->rank }}
                                    </td>
                                    <td>
                                        {{ $row->participant_name }}
                                    </td>
                                    <td>
                                        {{ $row->category->name ?? '-' }}
                                    </td>
                                    <td class="text-end">{{ $row->attempt1 ?? 'DNF' }}</td>
                                    <td class="text-end">{{ $row->attempt2 ?? 'DNF' }}</td>
                                    <td class="text-end">{{ $row->attempt3 ?? 'DNF' }}</td>
                                    <td class="text-end">{{ $row->attempt4 ?? 'DNF' }}</td>
                                    <td class="text-end">{{ $row->attempt5 ?? 'DNF' }}</td>
                                    <td class="text-end" style="font-weight:800;">
                                        {{ $row->best ?? '-' }}
                                    </td>
                                    <td class="text-end" style="font-weight:800;">
                                        {{ $row->average ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" style="text-align:center;" class="muted">
                                        Belum ada data hasil untuk kategori ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- INFORMASI TAMBAHAN --}}
                <p class="muted" style="margin-top:14px;font-size:12px;line-height:1.7;">
                    Data hasil kompetisi diinput oleh admin. Format waktu dan perhitungan mengikuti standar kompetisi rubik
                    (mirip WCA): nilai <b>Best</b> adalah waktu tercepat peserta, sedangkan <b>Average</b> dihitung dari
                    rata-rata tertentu sesuai ketentuan kategori.
                </p>
            </div>
        </div>
    </section>
@endsection