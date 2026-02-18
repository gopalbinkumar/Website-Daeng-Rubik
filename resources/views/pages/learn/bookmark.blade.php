@extends('layouts.app')

@section('title', 'Bookmark Belajar — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/learn.css') }}">
@endpush

@section('content')
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Belajar &gt; Bookmark</div>
            <h1 class="page-title">Bookmark Materi</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Kumpulan materi yang sudah kamu simpan untuk dipelajari kembali.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container" data-tabs>

            {{-- HEADER --}}
            <div class="card card-pad"
                style="display:flex;align-items:center;justify-content:space-between;gap:14px;flex-wrap:wrap;">
                <div>
                    <span class="badge"><i class="fa-solid fa-bookmark"></i> Bookmark</span>
                    <h2 style="margin:10px 0 0;font-size:22px;letter-spacing:-.02em">
                        Materi Tersimpan
                    </h2>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">
                        Video yang sudah kamu simpan akan muncul di sini.
                    </p>
                </div>
            </div>

            <div style="height:16px"></div>

            {{-- GRID BOOKMARK --}}
            <div class="grid-3">

                @forelse($materials as $m)
                    <article class="card prod">
                        <div class="prod-img"
                            style="aspect-ratio:16/10;position:relative;
    display:flex;align-items:center;justify-content:center;">

                            {{-- Badge Remove Bookmark --}}
                            <span class="badge btn-bookmark" data-id="{{ $m->id }}"
                                style="
            position:absolute;
            top:10px;
            left:10px;
            cursor:pointer;
            background:#facc15;
            color:#000;
            backdrop-filter: blur(6px);
            z-index:10;
        ">
                                <i class="fa-solid fa-bookmark"></i>
                            </span>

                            {{-- Jika Video --}}
                            @if ($m->type === 'video')
                                <img src="{{ $m->youtube_thumbnail }}"
                                    style="width:100%;height:100%;object-fit:cover;border-radius:18px;">
                            @else
                                <div style="font-size:56px"><i class="fa-solid fa-file-lines"></i>
                                </div>
                            @endif

                        </div>


                        <div class="prod-body">
                            <p class="prod-name">{{ $m->title }}</p>
                            <p class="muted" style="margin:0 0 10px;line-height:1.6">
                                {{ Str::limit($m->description, 120) }}
                            </p>

                            <div style="display:flex;gap:10px;">

                                {{-- Jika Video --}}
                                @if ($m->type === 'video')
                                    <a href="{{ $m->video_url }}" target="_blank" class="btn btn-primary" style="flex:1">
                                        <i class="fa-solid fa-circle-play"></i> Mulai
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $m->module_path) }}" target="_blank"
                                        class="btn btn-primary" style="flex:1">
                                        <i class="fa-solid fa-eye"></i> Lihat
                                    </a>
                                @endif

                            </div>
                        </div>
                    </article>

                @empty

                    <div class="card card-pad" style="text-align:center;">
                        <p class="muted" style="margin:0;">
                            Kamu belum menyimpan materi apapun.
                        </p>
                    </div>
                @endforelse
            </div>
            <div style="height:20px"></div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.querySelectorAll('.btn-bookmark').forEach(button => {
                button.addEventListener('click', function() {

                    let materialId = this.dataset.id;
                    let card = this.closest('.card');
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

                            if (data.status === "removed") {

                                // animasi kecil
                                card.style.transition = "0.3s ease";
                                card.style.opacity = "0";
                                card.style.transform = "scale(0.95)";

                                setTimeout(() => {
                                    card.remove();
                                }, 300);
                            }

                        });

                });
            });

        });
    </script>

@endsection
