@extends('layouts.app')

@section('title', 'Event Saya — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/my-events.css') }}">

@endpush

@section('content')
    @php
        use Illuminate\Support\Collection;
        use Carbon\Carbon;

        // Dummy object mirip model Event
        $events = collect([
            (object) [
                'id' => 1,
                'title' => 'Daeng Rubik Open 2025',
                'category' => 'kompetisi',
                'location' => 'Makassar',
                'start_datetime' => Carbon::parse('2025-06-15 09:00'),
                'pivot' => (object) [
                    'status' => 'terdaftar',
                ],
            ],
            (object) [
                'id' => 2,
                'title' => 'Workshop Beginner Rubik',
                'category' => 'non-kompetisi',
                'location' => 'Online (Zoom)',
                'start_datetime' => Carbon::parse('2025-05-10 19:30'),
                'pivot' => (object) [
                    'status' => 'selesai',
                ],
            ],
            (object) [
                'id' => 3,
                'title' => 'Sulsel Speedcubing Championship',
                'category' => 'kompetisi',
                'location' => 'Gowa',
                'start_datetime' => Carbon::parse('2025-07-03 08:00'),
                'pivot' => (object) [
                    'status' => 'terdaftar',
                ],
            ],
        ]);
    @endphp


    {{-- HEADER --}}
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">
                Beranda &gt; Event &gt; Event Saya
            </div>
            <h1 class="page-title">Event Saya</h1>
            <p class="muted" style="margin:8px 0 0;max-width:720px;line-height:1.7">
                Daftar event yang telah kamu ikuti atau sedang kamu ikuti.
            </p>
        </div>
    </section>

    {{-- LIST / EMPTY STATE --}}
    <section class="section" style="padding-top:22px;">
        <div class="container">

            @if ($events->isEmpty())
                {{-- EMPTY STATE --}}
                <div class="card card-pad" style="text-align:center;padding:32px 20px;">
                    <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                        {{-- Ilustrasi sederhana --}}
                        <div
                            style="
                            width:120px;height:120px;border-radius:32px;
                            background:
                                radial-gradient(80px 80px at 30% 30%, rgba(25,118,210,.20), transparent 60%),
                                radial-gradient(80px 80px at 70% 70%, rgba(253,216,53,.26), transparent 60%),
                                linear-gradient(135deg, rgba(229,57,53,.18), rgba(255,255,255,.10));
                            display:flex;align-items:center;justify-content:center;
                            border:1px solid rgba(17,24,39,.08);
                        ">
                            <div class="cube" style="width:60%;aspect-ratio:1/1;border-radius:18px;border-width:5px;">
                            </div>
                        </div>

                        <div>
                            <h2 style="font-size:18px;font-weight:800;letter-spacing:-.02em;margin-bottom:4px;">
                                Kamu belum terdaftar di event mana pun
                            </h2>
                            <p class="muted" style="margin:0;max-width:420px;line-height:1.7;">
                                Yuk ikuti kompetisi, workshop, atau meetup rubik pertama kamu di Daeng Rubik.
                            </p>
                        </div>

                        <a href="{{ route('events') }}" class="btn btn-primary">
                            Cari Event
                        </a>
                    </div>
                </div>
            @else
                {{-- TOOLBAR RINGKAS --}}
                <div class="sortbar" style="margin-bottom:18px;">
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                        <span class="badge">🎟️ Event Saya</span>
                        <span class="muted" style="font-weight:700;">
                            {{ $events->count() }} event terdaftar
                        </span>
                    </div>
                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                        <select class="select" aria-label="Filter status">
                            <option>Semua status</option>
                            <option>Terdaftar</option>
                            <option>Selesai</option>
                        </select>
                    </div>
                </div>

                {{-- TABEL EVENT SAYA --}}
                <div class="card card-pad" style="padding:18px 18px 10px;">
                    <div class="table-responsive">
                        <table class="table" style="margin-bottom:8px;min-width:760px;">
                            <thead>
                                <tr style="background:rgba(17,24,39,.03);">
                                    <th style="border-bottom-color:rgba(17,24,39,.06);">Nama Event</th>
                                    <th style="border-bottom-color:rgba(17,24,39,.06);">Tanggal</th>
                                    <th style="border-bottom-color:rgba(17,24,39,.06);">Lokasi</th>
                                    <th style="border-bottom-color:rgba(17,24,39,.06);">Jenis</th>
                                    <th style="border-bottom-color:rgba(17,24,39,.06);">Status</th>
                                    <th style="border-bottom-color:rgba(17,24,39,.06);width:160px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    @php
                                        $isCompetition = $event->category === 'kompetisi';
                                        $jenisLabel = $isCompetition ? 'Kompetisi' : 'Non-kompetisi';
                                        $statusLabel = $event->pivot->status ?? 'terdaftar'; // contoh
                                        $statusClass = match ($statusLabel) {
                                            'selesai' => 'badge-success',
                                            default => 'badge-warning',
                                        };
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $event->title }}</strong>
                                        </td>
                                        <td>
                                            {{ $event->start_datetime->format('d M Y') }}<br>
                                            <small class="muted">
                                                {{ $event->start_datetime->format('H:i') }} WIB
                                            </small>
                                        </td>
                                        <td>{{ $event->location }}</td>
                                        <td>
                                            <span class="badge {{ $isCompetition ? 'badge-danger' : 'badge-secondary' }}">
                                                {{ $jenisLabel }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $statusClass }}">
                                                {{ ucfirst($statusLabel) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                                @if ($isCompetition)
                                                    <a href=""
                                                        class="btn btn-primary btn-sm" style="flex:1;min-width:130px;">
                                                        Detail Kompetisi
                                                    </a>
                                                @endif
                                                {{-- Contoh: lihat tiket / detail umum --}}
                                                <a href="{{ route('events') }}" class="btn btn-outline-secondary btn-sm"
                                                    style="flex:1;min-width:110px;">
                                                    Detail Event
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <p class="muted" style="font-size:12px;margin-top:6px;">
                        Status “Terdaftar” artinya kamu sedang terdaftar di event. “Selesai” artinya event sudah berakhir.
                    </p>
                </div>
            @endif
        </div>
    </section>
@endsection
