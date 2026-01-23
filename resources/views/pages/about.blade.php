@extends('layouts.app')

@section('title', 'Tentang Kami — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
@endpush

@section('content')
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Tentang Kami</div>
            <h1 class="page-title">Tentang Daeng Rubik</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                UMKM rubik yang fokus pada penjualan rubik, penyelenggaraan event, dan pembelajaran rubik untuk semua level.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="card featured">
                <div class="featured-media" aria-hidden="true"></div>
                <div class="featured-body">
                    <span class="badge">🧩 Profil UMKM</span>
                    <h3 style="margin:10px 0 6px;font-size:20px;letter-spacing:-.02em">Dari komunitas, untuk komunitas</h3>
                    <p class="muted" style="margin:0;line-height:1.7">
                        Daeng Rubik berawal dari kecintaan pada puzzle dan speedcubing. Kami ingin membuat rubik lebih mudah diakses:
                        lewat katalog produk yang rapi, event yang seru, dan materi belajar yang terstruktur.
                    </p>
                    <div class="kv">
                        <div><span class="k" aria-hidden="true">🎯</span><span><b>Visi:</b> jadi pusat rubik yang ramah pemula & kompetitif.</span></div>
                        <div><span class="k" aria-hidden="true">🧭</span><span><b>Misi:</b> produk berkualitas, event rutin, materi belajar bertingkat.</span></div>
                        <div><span class="k" aria-hidden="true">🤝</span><span><b>Komunitas:</b> meetup, coaching, dan kolaborasi.</span></div>
                    </div>
                    <div style="display:flex;gap:12px;flex-wrap:wrap;">
                        <a class="btn btn-primary" href="{{ route('products') }}">Lihat Produk</a>
                        <a class="btn btn-secondary" href="{{ route('events') }}">Lihat Event</a>
                    </div>
                </div>
            </div>

            <div style="height:18px"></div>

            <div class="section-title">
                <div>
                    <h2>Layanan kami</h2>
                    <p class="muted">Tiga pilar utama Daeng Rubik.</p>
                </div>
            </div>

            <div class="grid-3">
                <div class="card card-pad">
                    <span class="badge">🛒 Penjualan Rubik</span>
                    <h3 style="margin:10px 0 6px;font-size:18px;letter-spacing:-.02em">Katalog lengkap</h3>
                    <p class="muted" style="margin:0;line-height:1.7">
                        Mulai dari rubik pemula sampai speed cube, lengkap dengan filter kategori & brand (UI).
                    </p>
                    <div style="margin-top:12px;">
                        <a class="btn btn-primary" href="{{ route('products') }}">Buka katalog</a>
                    </div>
                </div>
                <div class="card card-pad">
                    <span class="badge">🎉 Event Rubik</span>
                    <h3 style="margin:10px 0 6px;font-size:18px;letter-spacing:-.02em">Kompetisi & workshop</h3>
                    <p class="muted" style="margin:0;line-height:1.7">
                        Event dengan info tanggal, lokasi, dan pendaftaran yang mudah (UI).
                    </p>
                    <div style="margin-top:12px;">
                        <a class="btn btn-primary" href="{{ route('events') }}">Lihat event</a>
                    </div>
                </div>
                <div class="card card-pad">
                    <span class="badge">📚 Belajar Rubik</span>
                    <h3 style="margin:10px 0 6px;font-size:18px;letter-spacing:-.02em">Materi bertingkat</h3>
                    <p class="muted" style="margin:0;line-height:1.7">
                        Basic, intermediate, advanced—dengan tampilan card materi yang enak dibaca (UI).
                    </p>
                    <div style="margin-top:12px;">
                        <a class="btn btn-primary" href="{{ route('learn') }}">Mulai belajar</a>
                    </div>
                </div>
            </div>

            <div style="height:18px"></div>

            <div class="section-title">
                <div>
                    <h2>Tim</h2>
                    <p class="muted">Placeholder profil tim (UI).</p>
                </div>
            </div>

            <div class="grid-4">
                @foreach([
                    ['name' => 'Daeng', 'role' => 'Founder'],
                    ['name' => 'Rani', 'role' => 'Event Manager'],
                    ['name' => 'Bima', 'role' => 'Trainer'],
                    ['name' => 'Sari', 'role' => 'Admin'],
                ] as $m)
                    <div class="card card-pad">
                        <div style="width:56px;height:56px;border-radius:18px;background:linear-gradient(135deg,rgba(229,57,53,.18),rgba(25,118,210,.14),rgba(253,216,53,.22));border:1px solid rgba(17,24,39,.10);display:flex;align-items:center;justify-content:center;font-weight:900">
                            {{ mb_substr($m['name'], 0, 1) }}
                        </div>
                        <h3 style="margin:12px 0 2px;font-size:16px;letter-spacing:-.01em">{{ $m['name'] }}</h3>
                        <p class="muted" style="margin:0">{{ $m['role'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

