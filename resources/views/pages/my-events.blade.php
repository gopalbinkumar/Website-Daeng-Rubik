@extends('layouts.app')

@section('title', 'Event Saya — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/my-events.css') }}">
@endpush

@section('content')

    <div class="my-events-page">
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
                           

                            <div>
                                <h2 style="font-size:18px;font-weight:600;letter-spacing:-.02em;margin-bottom:4px;">
                                    Kamu belum terdaftar di kompetisi mana pun
                                </h2>
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
                                        <th style="border-bottom-color:rgba(17,24,39,.06);">Nama Kompetisi</th>
                                        <th style="border-bottom-color:rgba(17,24,39,.06);">Tanggal</th>
                                        <th style="border-bottom-color:rgba(17,24,39,.06);">Lokasi</th>
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
                                                <span class="badge {{ $statusClass }}">
                                                    {{ ucfirst($statusLabel) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                                                    <a href="{{ route('events.competition.show', [$event->id, $event->slug]) }}"
                                                        class="btn btn-primary btn-sm" style="flex:1;min-width:130px;">
                                                        Hasil Kompetisi
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
