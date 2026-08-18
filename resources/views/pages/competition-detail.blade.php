@extends('layouts.app')

@section('title', $event->title . ' — Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ assetVersion('assets/css/events.css') }}">
    <link rel="stylesheet" href="{{ assetVersion('assets/css/competition-detail.css') }}">
@endpush

@section('content')
    <div class="competition-detail-page">

        {{-- HEADER EVENT --}}
        <section class="page-head">
            <div class="container">
                <div class="breadcrumb">
                    Beranda &gt; Event &gt; Hasil Kompetisi
                </div>

                <h1 class="page-title" style="margin-bottom:4px;">
                    {{ $event->title }}
                </h1>

                <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:6px;">
                    <p class="muted" style="margin:0;">
                        <i class="fa-regular fa-calendar"></i>

                        @if ($event->start_datetime->isSameDay($event->end_datetime))
                            {{ $event->start_datetime->format('d M Y') }}
                        @else
                            {{ $event->start_datetime->format('d M Y') }} –
                            {{ $event->end_datetime->format('d M Y') }}
                        @endif

                        <br>

                        <i class="fa-solid fa-location-dot"></i>
                        {{ $event->location }}
                    </p>

                </div>
            </div>
        </section>

        <section style="border-bottom:1px solid rgba(17,24,39,.06);"></section>

        <section class="section">
            <div class="container">
                @include('pages.partials.competition-results')
            </div>
        </section>
    </div>
@endsection
