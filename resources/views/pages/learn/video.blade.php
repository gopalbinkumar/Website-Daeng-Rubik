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

            {{-- HEADER --}}
            <div class="card card-pad"
                style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge">🎥 Learning Videos</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        Video Pembelajaran Rubik
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Tonton materi pembelajaran rubik langkah demi langkah dari tingkat dasar hingga lanjutan.
                    </p>
                </div>
            </div>

            <div style="height:14px"></div>

            {{-- TABS LEVEL --}}
            <div class="tabs" aria-label="Pilih level" style="margin-top:0">
                <button class="tab beginner active" type="button" data-tab="beginner">Beginner</button>
                <button class="tab intermediate" type="button" data-tab="intermediate">Intermediate</button>
                <button class="tab advanced" type="button" data-tab="advanced">Advanced</button>
            </div>

            <div style="height:16px"></div>

            {{-- KATEGORI --}}
            @if ($categories->where('videos_count', '>', 0)->count())
                <div class="card card-pad">
                    <div class="section-title" style="margin-bottom:10px;">
                        <div>
                            <h2 style="font-size:18px;margin:0">Kategori tutorial</h2>
                            <p class="muted">Pilih jenis rubik yang kamu pelajari.</p>
                        </div>
                    </div>

                    <div class="grid-4">
                        @foreach ($categories->where('videos_count', '>', 0) as $cat)
                            <div class="stat" style="box-shadow:none">
                                <b>{{ $cat->name }}</b>
                                <small>{{ $cat->videos_count }} materi</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif


            <div style="height:16px"></div>

            {{-- BEGINNER --}}
            <div data-panel="beginner">
                <div class="section-title">
                    <div>
                        <h2>Materi Beginner</h2>
                        <p class="muted">Fondasi untuk pemula.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @forelse ($videos['beginner'] ?? [] as $video)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio:16/10;">
                                <span class="badge ok">Beginner</span>
                                <img src="{{ $video->youtube_thumbnail }}" alt="{{ $video->title }}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $video->title }}</p>
                                <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                    {{ Str::limit($video->description, 120) }}
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <a href="{{ $video->video_url }}" target="_blank" rel="noopener" class="btn btn-primary"
                                        style="flex:1">
                                        <i class="fa-solid fa-circle-play"></i> Mulai
                                    </a>
                                    <button class="btn btn-secondary" style="flex:1">Simpan</button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="muted">Belum ada materi beginner.</p>
                    @endforelse
                </div>
            </div>

            {{-- INTERMEDIATE --}}
            <div data-panel="intermediate" style="display:none">
                <div class="section-title">
                    <div>
                        <h2>Materi Intermediate</h2>
                        <p class="muted">Naik level untuk efisiensi dan kecepatan.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @forelse ($videos['intermediate'] ?? [] as $video)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio:16/10;">
                                <span class="badge warn">Intermediate</span>
                                <img src="{{ $video->youtube_thumbnail }}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $video->title }}</p>
                                <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                    {{ Str::limit($video->description, 120) }}
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <a href="{{ $video->video_url }}" target="_blank" rel="noopener" class="btn btn-primary"
                                        style="flex:1"><i class="fa-solid fa-circle-play"></i>
                                        Mulai</a>
                                    <button class="btn btn-secondary" style="flex:1">Simpan</button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="muted">Belum ada materi intermediate.</p>
                    @endforelse
                </div>
            </div>

            {{-- ADVANCED --}}
            <div data-panel="advanced" style="display:none">
                <div class="section-title">
                    <div>
                        <h2>Materi Advanced</h2>
                        <p class="muted">Strategi lengkap & drills.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @forelse ($videos['advanced'] ?? [] as $video)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio:16/10;">
                                <span class="badge hot">Advanced</span>
                                <img src="{{ $video->youtube_thumbnail }}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $video->title }}</p>
                                <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                    {{ Str::limit($video->description, 120) }}
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <a href="{{ $video->video_url }}" target="_blank" rel="noopener" class="btn btn-primary"
                                        style="flex:1"><i class="fa-solid fa-circle-play"></i>
                                        Mulai</a>
                                    <button class="btn btn-secondary" style="flex:1">Simpan</button>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="muted">Belum ada materi advanced.</p>
                    @endforelse
                </div>
            </div>

            <div style="height:18px"></div>
        </div>
    </section>

@endsection
