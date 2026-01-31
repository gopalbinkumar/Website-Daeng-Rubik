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
                    <span class="badge"><i class="fa-solid fa-video"></i> Video Learning</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        Video Pembelajaran Rubik
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Tutorial rubik dari basic hingga advanced dalam bentuk video.
                    </p>
                </div>
                <a href="{{ route('learn.video') }}" class="btn btn-primary">
                    <i class="fa-solid fa-video"></i> Mulai Nonton
                </a>
            </div>
            <div style="height:14px"></div>
            <div class="card card-pad"
                style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge"><i class="fa-solid fa-file-lines"></i>  Module Learning</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        File Modul Rumus Rubik
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Rumus dan rangkuman rubik yang bisa dibaca dan diunduh.
                    </p>
                </div>
                <a href="{{ route('learn.module') }}" class="btn btn-primary">
                    <i class="fa-solid fa-file-lines"></i> Lihat Modul
                </a>
            </div>

            <div style="height:14px"></div>

            <div class="card card-pad">
                <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">Watchlist / Bookmark Anda</h2>
                <p class="muted" style="margin:8px 0 0;line-height:1.7">
                    Materi yang kamu simpan untuk ditonton atau dibaca nanti.
                    (Sementara ini masih tampilan UI).
                </p>

                <div class="divider"></div>

                <!-- ITEM 1 -->
                <div class="range">
                    <span>🎥 Pengenalan Rubik 3x3</span>
                    <span class="muted">Video • Basic</span>
                </div>

                <!-- ITEM 2 -->
                <div class="range" style="margin-top:8px">
                    <span>📄 Notasi Rubik (PDF)</span>
                    <span class="muted">PDF • Basic</span>
                </div>

                <!-- ITEM 3 -->
                <div class="range" style="margin-top:8px">
                    <span>🎥 F2L Intermediate</span>
                    <span class="muted">Video • Intermediate</span>
                </div>

                <div class="divider"></div>

                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <span class="muted">3 item tersimpan</span>
                    <button class="btn btn-secondary btn-sm">
                        Kelola Bookmark
                    </button>
                </div>
            </div>

    </section>
@endsection
