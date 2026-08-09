@extends('layouts.app')

@section('title', 'Event Saya — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ assetVersion('assets/css/events.css') }}">
    <link rel="stylesheet" href="{{ assetVersion('assets/css/my-events.css') }}">
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
                    <div class="card card-pad my-events-table-wrap" style="padding:18px 18px 10px;">
                        <div class="table-responsive">
                            <table class="table my-events-table" style="margin-bottom:8px;min-width:680px;">
                                <thead>
                                    <tr style="background:rgba(17,24,39,.03);">
                                        <th style="border-bottom-color:rgba(17,24,39,.06);">Nama Kompetisi</th>
                                        <th style="border-bottom-color:rgba(17,24,39,.06);">Tanggal</th>
                                        <th style="border-bottom-color:rgba(17,24,39,.06);">Lokasi</th>
                                        <th style="border-bottom-color:rgba(17,24,39,.06);"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($events as $event)
                                        @php
                                            $statusLabel = $event->pivot->status ?? 'terdaftar';

                                            $statusClass = match ($statusLabel) {
                                                'selesai' => 'badge-success',
                                                default => 'badge-warning',
                                            };

                                            $eventUrl = route('events.competition.show', [$event->id, $event->slug]);
                                        @endphp

                                        <tr class="my-events-clickable-row" data-url="{{ $eventUrl }}" tabindex="0"
                                            role="link" aria-label="Lihat hasil kompetisi {{ $event->title }}">

                                            <td data-label="Nama Kompetisi">
                                                <a href="{{ $eventUrl }}" class="my-events-title-link">
                                                    <strong>{{ $event->title }}</strong>
                                                </a>
                                            </td>

                                            <td data-label="Tanggal">
                                                {{ $event->start_datetime->format('d M Y') }}<br>
                                                {{-- <small class="muted">
                                                    {{ $event->start_datetime->format('H:i') }} WIB
                                                </small> --}}
                                            </td>

                                            <td data-label="Lokasi">
                                                {{ $event->location }}
                                            </td>

                                            <td data-label=" ">
                                                <span class="badge {{ $statusClass }}">
                                                    {{ ucfirst($statusLabel) }}
                                                </span>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.my-events-clickable-row').forEach(function(row) {
                row.addEventListener('click', function(e) {
                    if (e.target.closest('a')) return;

                    const url = row.dataset.url;
                    if (url) {
                        window.location.href = url;
                    }
                });

                row.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();

                        const url = row.dataset.url;
                        if (url) {
                            window.location.href = url;
                        }
                    }
                });
            });
        });
    </script>

@endsection
