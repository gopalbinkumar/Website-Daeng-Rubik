@extends('layouts.app')

@section('title', 'Belajar Rubik — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/learn.css') }}">
@endpush

@section('content')

    {{-- PAGE HEAD (SAMA DENGAN VIDEO) --}}
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Belajar</div>
            <h1 class="page-title">Belajar Rubik</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Materi pembelajaran dari tingkat dasar hingga lanjutan. Pilih level dan mulai latihan.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        {{-- ⬇️ WAJIB data-tabs (SAMA SEPERTI VIDEO) --}}
        <div class="container" data-tabs>

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
        

            {{-- TABS LEVEL (STRUKTUR SAMA DENGAN VIDEO) --}}
            <div class="tabs" aria-label="Pilih level" style="margin-top:0">
                <button class="tab beginner active" type="button" data-tab="pdf-beginner">Beginner</button>
                <button class="tab intermediate" type="button" data-tab="pdf-intermediate">Intermediate</button>
                <button class="tab advanced" type="button" data-tab="pdf-advanced">Advanced</button>
            </div>
            
            <div style="height:14px"></div>

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

            {{-- ================= BEGINNER ================= --}}
            <div data-panel="pdf-beginner">
                <div class="section-title">
                    <div>
                        <h2>PDF Beginner</h2>
                        <p class="muted">Materi dasar dalam bentuk dokumen.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @forelse ($modules['beginner'] ?? [] as $m)
                        <article class="card prod">
                            <div class="prod-img"
                                style="aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;">
                                <span class="badge ok">PDF</span>
                                <div style="font-size:56px">📄</div>
                            </div>

                            <div class="prod-body">
                                <p class="prod-name">{{ $m->title }}</p>
                                <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                    {{ Str::limit($m->description, 120) }}
                                </p>

                                <div style="display:flex;gap:10px;">
                                    <a href="{{ asset('storage/' . $m->module_path) }}" target="_blank"
                                        class="btn btn-secondary" style="flex:1">
                                        👁 Lihat
                                    </a>

                                    <a href="{{ asset('storage/' . $m->module_path) }}" class="btn btn-primary" style="flex:1"
                                        download>
                                        ⬇ Download
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="muted">Belum ada modul beginner.</p>
                    @endforelse
                </div>
            </div>

            {{-- ================= INTERMEDIATE ================= --}}
            <div data-panel="pdf-intermediate">
                <div class="section-title">
                    <div>
                        <h2>PDF Intermediate</h2>
                        <p class="muted">Dokumen untuk level menengah.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @forelse ($modules['intermediate'] ?? [] as $m)
                        <article class="card prod">
                            <div class="prod-img"
                                style="aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;">
                                <span class="badge warn">PDF</span>
                                <div style="font-size:56px">📄</div>
                            </div>

                            <div class="prod-body">
                                <p class="prod-name">{{ $m->title }}</p>
                                <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                    {{ Str::limit($m->description, 120) }}
                                </p>

                                <div style="display:flex;gap:10px;">
                                    <a href="{{ asset('storage/' . $m->module_path) }}" target="_blank"
                                        class="btn btn-secondary" style="flex:1">
                                        👁 Lihat
                                    </a>

                                    <a href="{{ asset('storage/' . $m->module_path) }}" class="btn btn-primary" style="flex:1"
                                        download>
                                        ⬇ Download
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="muted">Belum ada modul intermediate.</p>
                    @endforelse
                </div>
            </div>

            {{-- ================= ADVANCED ================= --}}
            <div data-panel="pdf-advanced">
                <div class="section-title">
                    <div>
                        <h2>PDF Advanced</h2>
                        <p class="muted">Strategi lanjutan dalam format PDF.</p>
                    </div>
                </div>

                <div class="grid-3">
                    @forelse ($modules['advanced'] ?? [] as $m)
                        <article class="card prod">
                            <div class="prod-img"
                                style="aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;">
                                <span class="badge hot">PDF</span>
                                <div style="font-size:56px">📄</div>
                            </div>

                            <div class="prod-body">
                                <p class="prod-name">{{ $m->title }}</p>
                                <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                    {{ Str::limit($m->description, 120) }}
                                </p>

                                <div style="display:flex;gap:10px;">
                                    <a href="{{ asset('storage/' . $m->module_path) }}" target="_blank"
                                        class="btn btn-secondary" style="flex:1">
                                        👁 Lihat
                                    </a>

                                    <a href="{{ asset('storage/' . $m->module_path) }}" class="btn btn-primary"
                                        style="flex:1" download>
                                        ⬇ Download
                                    </a>
                                </div>
                            </div>
                        </article>
                    @empty
                        <p class="muted">Belum ada modul advanced.</p>
                    @endforelse
                </div>
            </div>

            <div style="height:18px"></div>
        </div>
    </section>

@endsection
