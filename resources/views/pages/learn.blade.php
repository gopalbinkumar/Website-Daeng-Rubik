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
        <div class="container" data-tabs>
            <div class="card card-pad" style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge">📚 Learning Hub</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">Mulai perjalanan belajar kamu</h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Rekomendasi alur: Basic → Intermediate → Advanced.
                    </p>
                </div>
                <a class="btn btn-primary" href="#materi">Mulai sekarang</a>
            </div>

            <div style="height:14px"></div>

            <div class="tabs" aria-label="Pilih level" style="margin-top:0">
                <button class="tab basic active" type="button" data-tab="basic">Basic</button>
                <button class="tab intermediate" type="button" data-tab="intermediate">Intermediate</button>
                <button class="tab advanced" type="button" data-tab="advanced">Advanced</button>
            </div>

            <div style="height:16px"></div>

            <div class="card card-pad">
                <div class="section-title" style="margin-bottom:10px;">
                    <div>
                        <h2 style="font-size:18px;margin:0">Kategori tutorial</h2>
                        <p class="muted">Pilih jenis rubik yang kamu pelajari.</p>
                    </div>
                </div>
                <div class="grid-4">
                    <div class="stat" style="box-shadow:none"><b>3x3</b><small>15 materi</small></div>
                    <div class="stat" style="box-shadow:none"><b>4x4</b><small>8 materi</small></div>
                    <div class="stat" style="box-shadow:none"><b>5x5</b><small>5 materi</small></div>
                    <div class="stat" style="box-shadow:none"><b>Megaminx</b><small>3 materi</small></div>
                </div>
            </div>

            <div style="height:16px"></div>

            <div id="materi" data-panel="basic">
                <div class="section-title">
                    <div>
                        <h2>Materi Basic</h2>
                        <p class="muted">Fondasi untuk pemula.</p>
                    </div>
                </div>
                <div class="grid-3">
                    @foreach($tutorials['basic'] as $t)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio: 16/10;">
                                <span class="badge ok">Basic</span>
                                <div style="width:82%;max-width:260px;">
                                    <div class="cube" style="border-radius:18px;border-width:6px"></div>
                                </div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $t['title'] }}</p>
                                <p class="muted" style="margin:0 0 8px;line-height:1.6">
                                    ⏱ {{ $t['duration'] }} • 👁 {{ $t['views'] }} • <span class="stars">★ {{ $t['stars'] }}.0</span>
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <button class="btn btn-primary" type="button" style="flex:1">▶ Mulai</button>
                                    <button class="btn btn-secondary" type="button" style="flex:1">Simpan</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div data-panel="intermediate" style="display:none">
                <div class="section-title">
                    <div>
                        <h2>Materi Intermediate</h2>
                        <p class="muted">Naik level untuk efisiensi dan kecepatan.</p>
                    </div>
                </div>
                <div class="grid-3">
                    @foreach($tutorials['intermediate'] as $t)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio: 16/10;">
                                <span class="badge warn">Intermediate</span>
                                <div style="width:82%;max-width:260px;">
                                    <div class="cube" style="border-radius:18px;border-width:6px"></div>
                                </div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $t['title'] }}</p>
                                <p class="muted" style="margin:0 0 8px;line-height:1.6">
                                    ⏱ {{ $t['duration'] }} • 👁 {{ $t['views'] }} • <span class="stars">★ {{ $t['stars'] }}.0</span>
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <button class="btn btn-primary" type="button" style="flex:1">▶ Mulai</button>
                                    <button class="btn btn-secondary" type="button" style="flex:1">Simpan</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div data-panel="advanced" style="display:none">
                <div class="section-title">
                    <div>
                        <h2>Materi Advanced</h2>
                        <p class="muted">Strategi lengkap & drills.</p>
                    </div>
                </div>
                <div class="grid-3">
                    @foreach($tutorials['advanced'] as $t)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio: 16/10;">
                                <span class="badge hot">Advanced</span>
                                <div style="width:82%;max-width:260px;">
                                    <div class="cube" style="border-radius:18px;border-width:6px"></div>
                                </div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $t['title'] }}</p>
                                <p class="muted" style="margin:0 0 8px;line-height:1.6">
                                    ⏱ {{ $t['duration'] }} • 👁 {{ $t['views'] }} • <span class="stars">★ {{ $t['stars'] }}.0</span>
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <button class="btn btn-primary" type="button" style="flex:1">▶ Mulai</button>
                                    <button class="btn btn-secondary" type="button" style="flex:1">Simpan</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div style="height:18px"></div>
            {{-- <div class="card card-pad">
                <b>📊 Progress belajar (UI)</b>
                <p class="muted" style="margin:8px 0 0;line-height:1.7">
                    Nantinya progress akan tersimpan per akun. Untuk sekarang, ini placeholder tampilan.
                </p>
                <div class="divider"></div>
                <div class="range">
                    <span>Basic</span><span><b style="color:var(--text)">8/10</b> selesai</span>
                </div>
                <div style="height:10px;background:rgba(17,24,39,.08);border-radius:999px;overflow:hidden">
                    <div style="width:80%;height:100%;background:linear-gradient(90deg,var(--green),var(--yellow));"></div>
                </div>
            </div> --}}
        </div>
    </section>
@endsection

