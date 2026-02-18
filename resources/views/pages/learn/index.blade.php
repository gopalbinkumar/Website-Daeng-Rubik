@extends('layouts.app')

@section('title', 'Belajar Rubik — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/learn.css') }}">
@endpush

@section('content')
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Belajar</div>
            <h1 class="page-title">Belajar Rubik</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Materi pembelajaran dari tingkat dasar hingga lanjutan. Pilih level dan mulai latihan (UI dulu).
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container" data-tabs>
            <div class="card card-pad"
                style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge"><i class="fa-solid fa-video"></i>Video Learning</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        Video Pembelajaran Rubik
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Tutorial rubik dari basic hingga advanced dalam bentuk video.
                    </p>
                </div>
                @auth
                    <a href="{{ route('learn.video') }}" class="btn btn-primary">
                        <i class="fa-solid fa-video"></i> Mulai Nonton
                    </a>
                @else
                    <button type="button" class="btn btn-primary" onclick="showLoginAlert()">
                        <i class="fa-solid fa-video"></i> Mulai Nonton
                    </button>
                @endauth

            </div>
            <div style="height:14px"></div>
            <div class="card card-pad"
                style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge"><i class="fa-solid fa-file-lines"></i>Module Learning</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        File Modul Rumus Rubik
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Rumus dan rangkuman rubik yang bisa dibaca dan diunduh.
                    </p>
                </div>
                @auth
                    <a href="{{ route('learn.module') }}" class="btn btn-primary">
                        <i class="fa-solid fa-file-lines"></i> Lihat Modul
                    </a>
                @else
                    <button type="button" class="btn btn-primary" onclick="showLoginAlert()">
                        <i class="fa-solid fa-file-lines"></i> Lihat Modul
                    </button>
                @endauth

            </div>

            <div style="height:14px"></div>

            <div class="card card-pad">
                <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                    Watchlist / Bookmark Anda
                </h2>
                <p class="muted" style="margin:8px 0 0;line-height:1.7">
                    Materi yang kamu simpan untuk ditonton atau dibaca nanti.
                </p>

                <div class="divider"></div>

                @forelse($bookmarks as $item)
                    <div class="range" style="margin-top:8px">
                        <span>
                            @if ($item->type === 'video')
                                <i class="fa-solid fa-video"></i>
                                {{ $item->title }}
                            @else
                                <i class="fa-solid fa-file-lines"></i>
                                {{ $item->title }}
                            @endif
                        </span>

                        <span class="muted">
                            {{ ucfirst($item->type) }} • {{ ucfirst($item->level) }}
                        </span>
                    </div>

                @empty

                    <div class="range">
                        <span class="muted">Belum ada materi tersimpan</span>
                        <span></span>
                    </div>
                @endforelse

                <div class="divider"></div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="muted">
                        {{ $bookmarks->count() }} item tersimpan
                    </span>

                    <a href="{{ route('learn.bookmark') }}" class="btn btn-secondary btn-sm">
                        Kelola Bookmark
                    </a>
                </div>
            </div>


    </section>
@endsection
