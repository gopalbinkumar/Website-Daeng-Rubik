@extends('layouts.app')

@section('title', 'Tentang Kami — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ assetVersion('assets/css/about.css') }}">
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
                        <a class="btn btn-primary" href="{{ route('learn.index') }}">Mulai belajar</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
