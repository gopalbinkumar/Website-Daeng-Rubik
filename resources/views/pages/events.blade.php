@extends('layouts.app')

@section('title', 'Event Rubik — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
@endpush

@section('content')
    @php
        $events = [
            [
                'title' => 'Kompetisi Rubik Nasional',
                'date' => '15 Feb 2026 • 08:00 WIB',
                'location' => 'Jakarta Convention Center',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Featured'],
                'desc' => 'Kompetisi rubik tingkat nasional dengan berbagai kategori lomba dan hadiah menarik.',
            ],
            [
                'title' => 'Workshop Basic 3x3',
                'date' => '20 Feb 2026 • 13:00 WIB',
                'location' => 'Bandung',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'desc' => 'Belajar dasar rubik 3x3 dari nol, cocok untuk pemula.',
            ],
            [
                'title' => 'Speedcubing Meetup',
                'date' => '25 Feb 2026 • 16:00 WIB',
                'location' => 'Surabaya',
                'status' => 'Upcoming',
                'badge' => ['muted', 'Meetup'],
                'desc' => 'Meetup komunitas, sharing teknik, dan mini challenge.',
            ],
            [
                'title' => 'Rubik Fun Competition',
                'date' => '1 Mar 2026 • 09:00 WIB',
                'location' => 'Yogyakarta',
                'status' => 'Upcoming',
                'badge' => ['warn', 'Competition'],
                'desc' => 'Kompetisi santai untuk semua level, seru dan ramah pemula.',
            ],
            [
                'title' => 'Advanced F2L Workshop',
                'date' => '5 Mar 2026 • 14:00 WIB',
                'location' => 'Jakarta',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'desc' => 'Workshop khusus teknik F2L untuk level intermediate ke advanced.',
            ],
            [
                'title' => 'Regional Championship',
                'date' => '12 Mar 2026 • 07:00 WIB',
                'location' => 'Bali',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Championship'],
                'desc' => 'Kejuaraan regional dengan sistem ranking resmi.',
            ],
        ];
    @endphp

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Event</div>
            <h1 class="page-title">Event Rubik</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Daftar event rubik: kompetisi, workshop, meetup. Informasi tanggal, lokasi, dan pendaftaran (UI dulu).
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container" data-tabs>
            <div class="sortbar">
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <span class="badge">📌 Daftar event</span>
                    <span class="muted" style="font-weight:700;">{{ count($events) }} event tersedia</span>
                </div>
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <select class="select" aria-label="Urutkan event">
                        <option>Terbaru</option>
                        <option>Terdekat</option>
                        <option>Popular</option>
                    </select>
                </div>
            </div>

            <div class="tabs" aria-label="Filter status event">
                <button class="tab active" type="button" data-tab="all">Semua</button>
                <button class="tab" type="button" data-tab="upcoming">Upcoming</button>
                <button class="tab" type="button" data-tab="ongoing">Berlangsung</button>
                <button class="tab" type="button" data-tab="done">Selesai</button>
                <button class="tab" type="button" data-tab="mine">Daftar Saya</button>
            </div>

            <div style="height:16px"></div>

            <div data-panel="all">
                @php($featured = $events[0])
                <div class="card featured">
                    <div class="featured-media" aria-hidden="true"></div>
                    <div class="featured-body">
                        <span class="badge {{ $featured['badge'][0] }}">{{ $featured['badge'][1] }}</span>
                        <h3 style="margin:10px 0 6px;font-size:20px;letter-spacing:-.02em">{{ $featured['title'] }}</h3>
                        <p class="muted" style="margin:0;line-height:1.7">{{ $featured['desc'] }}</p>
                        <div class="kv">
                            <div><span class="k" aria-hidden="true">📅</span><span>{{ $featured['date'] }}</span></div>
                            <div><span class="k" aria-hidden="true">📍</span><span>{{ $featured['location'] }}</span></div>
                            <div><span class="k" aria-hidden="true">🧾</span><span>Pendaftaran online (UI)</span></div>
                        </div>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            <button class="btn btn-primary" type="button">Daftar sekarang</button>
                            <button class="btn btn-secondary" type="button">Lihat detail</button>
                        </div>
                    </div>
                </div>

                <div style="height:16px"></div>

                <div class="grid-3">
                    @foreach(array_slice($events, 1) as $ev)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio: 16/10;">
                                <span class="badge {{ $ev['badge'][0] }}">{{ $ev['badge'][1] }}</span>
                                <div style="width:82%;max-width:260px;">
                                    <div class="cube" style="border-radius:18px;border-width:6px"></div>
                                </div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $ev['title'] }}</p>
                                <p class="muted" style="margin:0 0 8px;line-height:1.6">
                                    📅 {{ $ev['date'] }}<br>
                                    📍 {{ $ev['location'] }}
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <button class="btn btn-primary" type="button" style="flex:1">Daftar</button>
                                    <button class="btn btn-secondary" type="button" style="flex:1">Detail</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div data-panel="upcoming" style="display:none">
                <div class="card card-pad">
                    <b>Upcoming</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Untuk UI, status filter ini masih dummy (belum ada backend).</p>
                </div>
            </div>
            <div data-panel="ongoing" style="display:none">
                <div class="card card-pad">
                    <b>Berlangsung</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Nanti saat backend siap, event akan otomatis terfilter berdasarkan tanggal.</p>
                </div>
            </div>
            <div data-panel="done" style="display:none">
                <div class="card card-pad">
                    <b>Selesai</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Riwayat event (UI placeholder).</p>
                </div>
            </div>
            <div data-panel="mine" style="display:none">
                <div class="card card-pad">
                    <b>Daftar Saya</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Menampilkan event yang kamu daftar (UI placeholder).</p>
                </div>
            </div>
        </div>
    </section>
@endsection

