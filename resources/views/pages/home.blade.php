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
                        <span aria-hidden="true">🧩</span>
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

                <div class="hero-art">
                    <div class="cube" aria-hidden="true"></div>
                    <div class="hero-mini">
                        <div class="mini">
                            <b>Produk lengkap</b>
                            <small>Katalog online, mudah dicari</small>
                        </div>
                        <div class="mini">
                            <b>Event rutin</b>
                            <small>Kompetisi & workshop</small>
                        </div>
                        <div class="mini">
                            <b>Materi belajar</b>
                            <small>Dari pemula sampai pro</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="container">
            <div class="section-title">
                <div>
                    <h2>Produk unggulan</h2>
                    <p class="muted">Pilihan favorit komunitas Daeng Rubik.</p>
                </div>
                <a class="btn btn-secondary" href="{{ route('products') }}">Lihat katalog</a>
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


    <section class="section" style="padding-top:0;">
        <div class="container">
            <div class="section-title">
                <div>
                    <h2>Event rubik terdekat</h2>
                    <p class="muted">Ikuti kompetisi, meetup, dan workshop.</p>
                </div>
                <a class="btn btn-secondary" href="{{ route('events') }}">Semua event</a>
            </div>

            @php($e = $events[0])
            <div class="card featured">
                <div class="featured-media" aria-hidden="true"></div>
                <div class="featured-body">
                    <span class="badge {{ $e['badge'][0] }}">{{ $e['badge'][1] }}</span>
                    <h3 style="margin:10px 0 6px;font-size:20px;letter-spacing:-.02em">{{ $e['title'] }}</h3>
                    <p class="muted" style="margin:0;line-height:1.7">{{ $e['desc'] }}</p>
                    <div class="kv">
                        <div><span class="k" aria-hidden="true">📅</span><span>{{ $e['date'] }}</span></div>
                        <div><span class="k" aria-hidden="true">📍</span><span>{{ $e['location'] }}</span></div>
                        <div><span class="k" aria-hidden="true">🧾</span><span>Pendaftaran online (UI dulu)</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        <a class="btn btn-primary" href="{{ route('events') }}">Daftar sekarang</a>
                        <a class="btn btn-secondary" href="{{ route('events') }}">Lihat detail</a>
                    </div>
                </div>
            </div>

            <div style="height:16px"></div>

            <div class="grid-4">
                @foreach (array_slice($events, 1) as $ev)
                    <article class="card prod">
                        <div class="prod-img" style="aspect-ratio: 16/10;">
                            <span class="badge {{ $ev['badge'][0] }}">{{ $ev['badge'][1] }}</span>
                            <div style="width:82%;max-width:260px;">
                                <div class="cube" style="border-radius:18px;border-width:6px"></div>
                            </div>
                        </div>
                        <div class="prod-body">
                            <p class="prod-name">{{ $ev['title'] }}</p>
                            <p class="muted" style="margin:0 0 8px;line-height:1.6">
                                📅 {{ $ev['date'] }}<br>
                                📍 {{ $ev['location'] }}
                            </p>
                            <a class="btn btn-primary" href="{{ route('events') }}" style="width:100%;">Lihat detail</a>
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
