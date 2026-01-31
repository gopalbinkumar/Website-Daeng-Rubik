@extends('layouts.app')

@section('title', 'Belajar Rubik — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/learn.css') }}">
@endpush

@section('content')
    @php
        $tutorials = [
            'basic' => [
                ['title' => 'Pengenalan Rubik 3x3', 'duration' => '5:30', 'views' => '1.2K', 'stars' => 5],
                ['title' => 'Notasi Rubik (Singkat & Jelas)', 'duration' => '8:15', 'views' => '2.5K', 'stars' => 5],
                ['title' => 'Cross Putih', 'duration' => '12:45', 'views' => '3.8K', 'stars' => 4],
                ['title' => 'First Layer (F2L Dasar)', 'duration' => '14:10', 'views' => '1.1K', 'stars' => 4],
                ['title' => 'Second Layer Edges', 'duration' => '10:20', 'views' => '950', 'stars' => 4],
                ['title' => 'Yellow Cross (OLL Dasar)', 'duration' => '11:30', 'views' => '1.8K', 'stars' => 5],
            ],
            'intermediate' => [
                ['title' => 'F2L Intermediate', 'duration' => '18:20', 'views' => '980', 'stars' => 5],
                ['title' => 'OLL Pengenalan', 'duration' => '16:05', 'views' => '1.6K', 'stars' => 4],
                ['title' => 'PLL Dasar', 'duration' => '19:40', 'views' => '1.3K', 'stars' => 5],
                ['title' => 'Finger Tricks & TPS', 'duration' => '15:50', 'views' => '1.1K', 'stars' => 5],
                ['title' => 'Cross Optimization', 'duration' => '13:25', 'views' => '890', 'stars' => 4],
            ],
            'advanced' => [
                ['title' => 'Full OLL Strategy', 'duration' => '24:10', 'views' => '720', 'stars' => 5],
                ['title' => 'Full PLL Drills', 'duration' => '22:55', 'views' => '640', 'stars' => 5],
                ['title' => 'Lookahead & TPS', 'duration' => '20:30', 'views' => '510', 'stars' => 4],
                ['title' => 'Advanced F2L Cases', 'duration' => '26:15', 'views' => '420', 'stars' => 5],
                ['title' => 'Competition Strategies', 'duration' => '18:40', 'views' => '380', 'stars' => 5],
            ],
        ];
    @endphp

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
        <div class="container">

            {{-- HEADER --}}
            <div class="card card-pad"
                style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge">📄 PDF Learning Hub</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        Materi PDF Pembelajaran
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Ringkasan materi dalam bentuk PDF yang bisa dibaca & diunduh.
                    </p>
                </div>
            </div>

            <div style="height:14px"></div>

            {{-- LEVEL TABS (TETAP SAMA) --}}
            <div class="tabs" aria-label="Pilih level">
                <button class="tab basic active" type="button" data-tab="pdf-basic">Basic</button>
                <button class="tab intermediate" type="button" data-tab="pdf-intermediate">Intermediate</button>
                <button class="tab advanced" type="button" data-tab="pdf-advanced">Advanced</button>
            </div>

            <div style="height:16px"></div>

            {{-- ================= PDF BASIC ================= --}}
            <div data-panel="pdf-basic">
                <div class="section-title">
                    <div>
                        <h2>PDF Basic</h2>
                        <p class="muted">Materi dasar dalam bentuk dokumen.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @foreach ($tutorials['basic'] as $t)
                        <article class="card prod">
                            <div class="prod-img"
                                style="aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;">
                                <span class="badge ok">PDF</span>
                                <div style="font-size:56px">📄</div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $t['title'] }}</p>
                                <p class="muted" style="margin:0 0 8px;">
                                    Ringkasan materi {{ strtolower($t['title']) }}.
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <button class="btn btn-secondary" style="flex:1" data-bs-toggle="modal"
                                        data-bs-target="#pdfModal">
                                        👁 Lihat
                                    </button>
                                    <a href="/pdf/{{ Str::slug($t['title']) }}.pdf" class="btn btn-primary" style="flex:1"
                                        download>
                                        ⬇ Download
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- ================= PDF INTERMEDIATE ================= --}}
            <div data-panel="pdf-intermediate" style="display:none">
                <div class="section-title">
                    <div>
                        <h2>PDF Intermediate</h2>
                        <p class="muted">Dokumen untuk level menengah.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @foreach ($tutorials['intermediate'] as $t)
                        <article class="card prod">
                            <div class="prod-img" style="display:flex;align-items:center;justify-content:center;">
                                <span class="badge warn">PDF</span>
                                <div style="font-size:56px">📄</div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $t['title'] }}</p>
                                <div style="display:flex;gap:10px;">
                                    <button class="btn btn-secondary" style="flex:1">👁 Lihat</button>
                                    <button class="btn btn-primary" style="flex:1">⬇ Download</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            {{-- ================= PDF ADVANCED ================= --}}
            <div data-panel="pdf-advanced" style="display:none">
                <div class="section-title">
                    <div>
                        <h2>PDF Advanced</h2>
                        <p class="muted">Strategi lanjutan dalam format PDF.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @foreach ($tutorials['advanced'] as $t)
                        <article class="card prod">
                            <div class="prod-img" style="display:flex;align-items:center;justify-content:center;">
                                <span class="badge hot">PDF</span>
                                <div style="font-size:56px">📄</div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $t['title'] }}</p>
                                <div style="display:flex;gap:10px;">
                                    <button class="btn btn-secondary" style="flex:1">👁 Lihat</button>
                                    <button class="btn btn-primary" style="flex:1">⬇ Download</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

@endsection
