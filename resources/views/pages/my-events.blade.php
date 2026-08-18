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

                @if ($hasEvents)
                    {{-- TOOLBAR --}}
                    <div class="sortbar" style="margin-bottom:18px;">
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                            <form method="GET">
                                <select name="status" class="select" aria-label="Filter status"
                                    onchange="this.form.submit()">
                                    <option value="" {{ empty($status) ? 'selected' : '' }}>
                                        Semua
                                    </option>

                                    <option value="accepted" {{ $status === 'accepted' ? 'selected' : '' }}>
                                        Diterima
                                    </option>

                                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>
                                        Menunggu
                                    </option>

                                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>
                                        Ditolak
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                @endif


                @if ($events->isEmpty())

                    {{-- EMPTY STATE --}}
                    <div class="card card-pad" style="text-align:center;padding:32px 20px;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">

                            @if ($hasEvents && !empty($status))
                                @php
                                    $emptyStatusText = match ($status) {
                                        'accepted' => 'Diterima',
                                        'pending' => 'Menunggu',
                                        'rejected' => 'Ditolak',
                                        default => 'status tersebut',
                                    };
                                @endphp

                                <div>
                                    <h2 style="font-size:18px;font-weight:600;letter-spacing:-.02em;margin-bottom:4px;">
                                        Tidak ada kompetisi
                                    </h2>

                                    <p class="muted" style="margin:0;">
                                        Coba pilih status lainnya.
                                    </p>
                                </div>

                                <a href="{{ url()->current() }}" class="btn btn-primary">
                                    Tampilkan Semua Event
                                </a>
                            @else
                                <div>
                                    <h2 style="font-size:18px;font-weight:600;letter-spacing:-.02em;margin-bottom:4px;">
                                        Kamu belum terdaftar di kompetisi mana pun
                                    </h2>
                                </div>

                                <a href="{{ route('events') }}" class="btn btn-primary">
                                    Cari Event
                                </a>
                            @endif

                        </div>
                    </div>
                @else
                    {{-- TABEL EVENT SAYA --}}
                    <div class="card card-pad my-events-table-wrap" style="padding:18px 18px 10px;">
                        <div class="table-responsive">
                            <table class="table my-events-table" style="margin-bottom:8px;min-width:680px;">
                                <thead>
                                    <tr style="background:rgba(17,24,39,.03);">
                                        <th style="border-bottom-color:rgba(17,24,39,.06);">
                                            Nama Kompetisi
                                        </th>

                                        <th style="border-bottom-color:rgba(17,24,39,.06);">
                                            Tanggal
                                        </th>

                                        <th style="border-bottom-color:rgba(17,24,39,.06);">
                                            Lokasi
                                        </th>

                                        <th style="border-bottom-color:rgba(17,24,39,.06);"></th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach ($events as $event)
                                        @php
                                            $statusLabel = $event->pivot->status ?? 'pending';

                                            $statusText = match ($statusLabel) {
                                                'accepted' => 'Diterima',
                                                'pending' => 'Menunggu',
                                                'rejected' => 'Ditolak',
                                                default => ucfirst($statusLabel),
                                            };

                                            $statusClass = match ($statusLabel) {
                                                'accepted' => 'badge-success',
                                                'pending' => 'badge-warning',
                                                'rejected' => 'badge-danger',
                                                default => 'badge-secondary',
                                            };

                                            $eventUrl = route('events.register', [$event->slug]);
                                        @endphp

                                        <tr class="my-events-clickable-row" data-url="{{ $eventUrl }}" tabindex="0"
                                            role="link" aria-label="Lihat hasil kompetisi {{ $event->title }}">

                                            <td data-label="Nama Kompetisi">
                                                <a href="{{ $eventUrl }}" class="my-events-title-link">
                                                    <strong>{{ $event->title }}</strong>
                                                </a>
                                            </td>

                                            <td data-label="Tanggal">
                                                {{ $event->start_datetime->format('d M Y') }}
                                            </td>

                                            <td data-label="Lokasi">
                                                {{ $event->location }}
                                            </td>

                                            <td data-label="Status">
                                                <span class="badge {{ $statusClass }}">
                                                    {{ $statusText }}
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
