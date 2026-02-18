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
                Materi pembelajaran dari tingkat dasar hingga lanjutan. Pilih level dan mulai latihan.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container" data-tabs>

            {{-- HEADER --}}
            <div class="card card-pad"
                style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge"><i class="fa-solid fa-file-lines"></i> Module Learning</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        Modul Pembelajaran
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Ringkasan materi dalam bentuk PDF yang bisa dibaca & diunduh.
                    </p>
                </div>
            </div>

            <div style="height:16px"></div>

            {{-- TABS --}}
            <div class="tabs">
                <button class="tab beginner active" type="button" data-tab="pdf-beginner">Beginner</button>
                <button class="tab intermediate" type="button" data-tab="pdf-intermediate">Intermediate</button>
                <button class="tab advanced" type="button" data-tab="pdf-advanced">Advanced</button>
            </div>

            <div style="height:20px"></div>

            {{-- ================= BEGINNER ================= --}}
            <div data-panel="pdf-beginner">
                <div class="grid-3">
                    @forelse ($modules['beginner'] ?? [] as $m)
                        @php
                            $isBookmarked = $m->bookmarks->count() > 0;
                        @endphp

                        <article class="card prod">
                            <div class="prod-img"
                                style="aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;position:relative;">

                                {{-- Bookmark Badge --}}
                                <span class="badge btn-bookmark" data-id="{{ $m->id }}"
                                    style="
                                    position:absolute;
                                    top:10px;
                                    left:10px;
                                    cursor:pointer;
                                    background: {{ $isBookmarked ? '#facc15' : 'rgba(255,255,255,.85)' }};
                                    color: {{ $isBookmarked ? '#000' : '#374151' }};
                                    backdrop-filter: blur(6px);
                                ">
                                    <i class="{{ $isBookmarked ? 'fa-solid' : 'fa-regular' }} fa-bookmark"></i>
                                </span>

                                <div style="font-size:56px"><i class="fa-solid fa-file-lines"></i></div>
                            </div>

                            <div class="prod-body">
                                <p class="prod-name">{{ $m->title }}</p>
                                <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                    {{ Str::limit($m->description, 120) }}
                                </p>

                                <div style="display:flex;gap:10px;">
                                    <a href="{{ asset('storage/' . $m->module_path) }}" target="_blank"
                                        class="btn btn-primary" style="flex:1">
                                        <i class="fa-solid fa-eye"></i> Lihat
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
            <div data-panel="pdf-intermediate" style="display:none">
                <div class="grid-3">
                    @forelse ($modules['intermediate'] ?? [] as $m)
                        @php $isBookmarked = $m->bookmarks->count() > 0; @endphp

                        <article class="card prod">
                            <div class="prod-img"
                                style="aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;position:relative;">

                                <span class="badge btn-bookmark" data-id="{{ $m->id }}"
                                    style="
                                    position:absolute;
                                    top:10px;
                                    left:10px;
                                    cursor:pointer;
                                    background: {{ $isBookmarked ? '#facc15' : 'rgba(255,255,255,.85)' }};
                                    color: {{ $isBookmarked ? '#000' : '#374151' }};
                                    backdrop-filter: blur(6px);
                                ">
                                    <i class="{{ $isBookmarked ? 'fa-solid' : 'fa-regular' }} fa-bookmark"></i>
                                </span>

                                <div style="font-size:56px"><i class="fa-solid fa-file-lines"></i>
                                </div>
                            </div>

                            <div class="prod-body">
                                <p class="prod-name">{{ $m->title }}</p>
                                <p class="muted">{{ Str::limit($m->description, 120) }}</p>
                                <div style="display:flex;gap:10px;">

                                    <a href="{{ asset('storage/' . $m->module_path) }}" target="_blank"
                                        class="btn btn-primary" style="flex:1">
                                        <i class="fa-solid fa-eye"></i> Lihat
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
            <div data-panel="pdf-advanced" style="display:none">
                <div class="grid-3">
                    @forelse ($modules['advanced'] ?? [] as $m)
                        @php $isBookmarked = $m->bookmarks->count() > 0; @endphp

                        <article class="card prod">
                            <div class="prod-img"
                                style="aspect-ratio:16/10;display:flex;align-items:center;justify-content:center;position:relative;">

                                <span class="badge btn-bookmark" data-id="{{ $m->id }}"
                                    style="
                                    position:absolute;
                                    top:10px;
                                    left:10px;
                                    cursor:pointer;
                                    background: {{ $isBookmarked ? '#facc15' : 'rgba(255,255,255,.85)' }};
                                    color: {{ $isBookmarked ? '#000' : '#374151' }};
                                    backdrop-filter: blur(6px);
                                ">
                                    <i class="{{ $isBookmarked ? 'fa-solid' : 'fa-regular' }} fa-bookmark"></i>
                                </span>

                                <div style="font-size:56px"><i class="fa-solid fa-file-lines"></i>
                                </div>
                            </div>

                            <div class="prod-body">
                                <p class="prod-name">{{ $m->title }}</p>
                                <p class="muted">{{ Str::limit($m->description, 120) }}</p>
                                <div style="display:flex;gap:10px;">

                                    <a href="{{ asset('storage/' . $m->module_path) }}" target="_blank"
                                        class="btn btn-primary" style="flex:1">
                                        <i class="fa-solid fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                        </article>

                    @empty
                        <p class="muted">Belum ada modul advanced.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </section>

    {{-- SCRIPT TOGGLE BOOKMARK --}}
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.btn-bookmark').forEach(button => {
                button.addEventListener('click', function() {

                    let materialId = this.dataset.id;
                    let icon = this.querySelector('i');

                    fetch(`/learn/${materialId}/bookmark`, {
                            method: "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                "Accept": "application/json",
                            }
                        })
                        .then(res => res.json())
                        .then(data => {

                            if (data.status === "added") {
                                icon.classList.remove('fa-regular');
                                icon.classList.add('fa-solid');
                                this.style.background = '#facc15';
                                this.style.color = '#000';
                            }

                            if (data.status === "removed") {
                                icon.classList.remove('fa-solid');
                                icon.classList.add('fa-regular');
                                this.style.background = 'rgba(255,255,255,.85)';
                                this.style.color = '#374151';
                            }

                        });

                });
            });

        });
    </script>

@endsection
