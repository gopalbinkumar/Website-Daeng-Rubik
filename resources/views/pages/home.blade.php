@extends('layouts.app')

@section('title', 'Daeng Rubik — Solve, Learn, Compete')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/home.css') }}">
@endpush

@section('content')
    @php
        $rupiah = fn(int $n) => 'Rp ' . number_format($n, 0, ',', '.');

        $events = [
            [
                'title' => 'Kompetisi Rubik Nasional',
                'date' => '15 Feb 2026 • 08:00 WIB',
                'location' => 'Jakarta Convention Center',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Featured'],
                'desc' => 'Kompetisi rubik tingkat nasional dengan berbagai kategori lomba dan hadiah menarik.',
            ],
            [
                'title' => 'Workshop Basic 3x3',
                'date' => '20 Feb 2026 • 13:00 WIB',
                'location' => 'Bandung',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'desc' => 'Belajar dasar rubik 3x3 dari nol, cocok untuk pemula.',
            ],
            [
                'title' => 'Speedcubing Meetup',
                'date' => '25 Feb 2026 • 16:00 WIB',
                'location' => 'Surabaya',
                'status' => 'Upcoming',
                'badge' => ['muted', 'Meetup'],
                'desc' => 'Meetup komunitas, sharing teknik, dan mini challenge.',
            ],
            [
                'title' => 'Rubik Fun Competition',
                'date' => '1 Mar 2026 • 09:00 WIB',
                'location' => 'Yogyakarta',
                'status' => 'Upcoming',
                'badge' => ['warn', 'Competition'],
                'desc' => 'Kompetisi santai untuk semua level, seru dan ramah pemula.',
            ],
        ];
    @endphp

    <section class="hero">
        <div class="container">
            <div class="hero-grid">
                <div>
                    <span class="badge">
                        <span aria-hidden="true"><i class="fa-solid fa-puzzle-piece"></i></span>
                        Rubik Store • Event • Belajar
                    </span>

                    <h1 class="hero-title" style="margin-top:14px;">
                        Daeng <span class="grad">Rubik</span><br>
                        Solve, Learn, Compete
                    </h1>
                    <p class="hero-sub">
                        Semua kebutuhan rubik kamu dalam satu platform: belanja rubik berkualitas, ikut event rubik, dan
                        belajar dari basic sampai advanced.
                    </p>
                    <div class="hero-cta">
                        <a class="btn btn-primary" href="{{ route('products') }}">Jelajahi Produk</a>
                        <a class="btn btn-secondary" href="{{ route('events') }}">Lihat Event</a>
                        <a class="btn btn-outline" href="{{ route('learn.index') }}">Mulai Belajar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <div>
                    <h2>Event rubik terdekat</h2>
                    <p class="muted">Ikuti kompetisi, meetup, dan workshop.</p>
                </div>
                {{-- <a class="btn btn-secondary" href="{{ route('events') }}">Semua event</a> --}}
            </div>

            @if ($featuredEvent)
                <div class="card featured" data-status="{{ $featuredEvent->status }}" data-featured="main">
                    <div class="featured-media"
                        style="--bg:url('{{ $featuredEvent->cover_image ? asset('storage/' . $featuredEvent->cover_image) : asset('assets/img/event-default.jpg') }}')">
                        <img src="{{ $featuredEvent->cover_image ? asset('storage/' . $featuredEvent->cover_image) : asset('assets/img/event-default.jpg') }}"
                            alt="{{ $featuredEvent->title }}">
                    </div>

                    <div class="featured-body">
                        <span class="badge hot">Featured</span>
                        <h3>{{ $featuredEvent->title }}</h3>
                        <p class="muted">{{ Str::limit($featuredEvent->description, 120) }}</p>

                        <div class="kv">
                            <div><i class="fa-regular fa-calendar-days"></i>
                                {{ $featuredEvent->start_datetime->translatedFormat('d M Y • H:i') }}</div>
                            <div><i class="fa-solid fa-location-dot"></i> {{ $featuredEvent->location }}</div>
                        </div>

                        <div style="display:flex;gap:12px;">

                            @if ($featuredEvent->category === 'kompetisi')
                                <a href="{{ route('events') }}" class="btn btn-primary" style="flex:1">
                                    Daftar sekarang
                                </a>
                            @else
                                <a href="{{ route('events') }}" class="btn btn-primary" style="flex:1">
                                    Lihat detail
                                </a>
                            @endif

                        </div>
                    </div>
                </div>
            @endif
        </div>


    </section>

    <section class="section" style="padding-top:0;">
        <div class="container">
            <div class="section-title">
                <div>
                    <h2>Produk unggulan</h2>
                    <p class="muted">Pilihan favorit komunitas Daeng Rubik.</p>
                </div>
                {{-- <a class="btn btn-secondary" href="{{ route('products') }}">Lihat katalog</a> --}}
            </div>

            <div class="grid-4">
                @foreach ($featuredProducts as $p)
                    <article class="card prod">
                        <div class="prod-img">
                            <div style="width:72%;max-width:220px;">
                                <img src="{{ $p->primaryImage
                                    ? asset('storage/' . $p->primaryImage->image_path)
                                    : asset('assets/img/placeholder-product.png') }}"
                                    alt="{{ $p->name }}"
                                    style="
                                    width:100%;
                                    aspect-ratio:1/1;
                                    object-fit:cover;
                                    border-radius:18px;
                                    border:6px solid var(--line);
                                ">
                            </div>
                        </div>

                        <div class="prod-body">
                            <p class="prod-name">{{ $p->name }}</p>

                            <div class="prod-meta">
                                <span class="price">
                                    Rp {{ number_format($p->price, 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="prod-actions">
                                <a class="btn btn-primary" href="{{ route('products') }}" style="flex:1;">
                                    Lihat detail
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>




    {{-- <section class="section" style="background:var(--bg-soft);border-top:1px solid rgba(17,24,39,.06);border-bottom:1px solid rgba(17,24,39,.06)">
        <div class="container">
            <div class="section-title">
                <div>
                    <h2>Pencapaian Daeng Rubik</h2>
                    <p class="muted">Data ringkas (dummy UI).</p>
                </div>
            </div>
            <div class="grid-4">
                <div class="stat red"><b>100+</b><small>Produk rubik</small></div>
                <div class="stat blue"><b>50+</b><small>Event terselenggara</small></div>
                <div class="stat yellow"><b>500+</b><small>Peserta belajar</small></div>
                <div class="stat green"><b>4.9★</b><small>Rating komunitas</small></div>
            </div>
        </div>
    </section> --}}
@endsection
