@extends('layouts.app')

@section('title', 'Kontak — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">
@endpush

@section('content')
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Kontak</div>
            <h1 class="page-title">Hubungi Kami</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Terhubung dengan Daeng Rubik lewat WhatsApp dan media sosial
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="grid-2">
                <div class="card card-pad">
                    <h2 style="margin:0 0 10px;font-size:18px;letter-spacing:-.02em">📝 Form Kontak</h2>
                    <form class="form" action="#" method="post">
                        <label>
                            <span class="sr-only">Nama</span>
                            <input class="input" placeholder="Nama lengkap" required>
                        </label>
                        <label>
                            <span class="sr-only">Email</span>
                            <input class="input" placeholder="Email" type="email" required>
                        </label>
                        <label>
                            <span class="sr-only">Subjek</span>
                            <input class="input" placeholder="Subjek" required>
                        </label>
                        <label>
                            <span class="sr-only">Pesan</span>
                            <textarea class="textarea" placeholder="Tulis pesan..." required></textarea>
                        </label>
                        <button class="btn btn-primary" type="button">Kirim Pesan (UI)</button>
                    </form>
                </div>

                <div class="card card-pad">
                    <h2 style="margin:0 0 10px;font-size:18px;letter-spacing:-.02em">📞 Informasi Kontak</h2>
                    <div class="kv" style="margin-top:10px">
                        <div><span class="k" aria-hidden="true">📍</span><span><b>Alamat</b><br><span class="muted">Jakarta, Indonesia</span></span></div>
                        <div><span class="k" aria-hidden="true">📞</span><span><b>Telepon</b><br><span class="muted">+62 812-3456-7890</span></span></div>
                        <div><span class="k" aria-hidden="true">✉️</span><span><b>Email</b><br><span class="muted">info@daengrubik.com</span></span></div>
                        <div><span class="k" aria-hidden="true">💬</span><span><b>WhatsApp</b><br><span class="muted">Chat cepat untuk tanya stok & event</span></span></div>
                    </div>
                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px">
                        <a class="btn btn-primary" href="#" style="flex:1;justify-content:center;">Chat WhatsApp</a>
                        <a class="btn btn-secondary" href="{{ route('products') }}" style="flex:1;justify-content:center;">Lihat Produk</a>
                    </div>

                    <div class="divider"></div>
                    <h3 style="margin:0 0 10px;font-size:14px;letter-spacing:-.01em">Media Sosial</h3>
                    <div class="social">
                        <a href="#" aria-label="Instagram">IG</a>
                        <a href="#" aria-label="Facebook">FB</a>
                        <a href="#" aria-label="YouTube">YT</a>
                        <a href="#" aria-label="TikTok">TT</a>
                        <a href="#" aria-label="WhatsApp">WA</a>
                    </div>
                </div>
            </div>

            <div style="height:16px"></div>
            <div class="card card-pad">
                <h2 style="margin:0 0 10px;font-size:18px;letter-spacing:-.02em">🗺️ Lokasi</h2>
                <div style="border:1px solid rgba(17,24,39,.10);border-radius:18px;overflow:hidden;background:linear-gradient(135deg,rgba(25,118,210,.10),rgba(253,216,53,.14),rgba(229,57,53,.10));min-height:260px;display:flex;align-items:center;justify-content:center">
                    <span class="muted" style="font-weight:800">Map placeholder (UI)</span>
                </div>
            </div>
        </div>
    </section>
@endsection

