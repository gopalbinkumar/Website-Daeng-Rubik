@extends('layouts.app')

@section('title', 'Kontak — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ assetVersion('assets/css/contact.css') }}">
@endpush

@section('content')
    @php
        $mapsQuery = $contact->maps_query;
    @endphp

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
                    <h2 style="margin:0 0 10px;font-size:18px;letter-spacing:-.02em"> <i class="fa-solid fa-phone"></i>
                        Informasi Kontak</h2>
                    <div class="kv" style="margin-top:10px">
                        <div>
                            <span class="k" aria-hidden="true">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <span>
                                <b>Alamat</b><br>
                                <span class="muted">{{ $contact->address ?: '-' }}</span>
                            </span>
                        </div>

                        <div>
                            <span class="k" aria-hidden="true">
                                <i class="fa-solid fa-phone"></i>
                            </span>
                            <span>
                                <b>Telepon</b><br>
                                <span class="muted">{{ $contact->phone ?: '-' }}</span>
                            </span>
                        </div>

                        <div>
                            <span class="k" aria-hidden="true">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                            <span>
                                <b>Email</b><br>
                                <span class="muted">{{ $contact->email ?: '-' }}</span>
                            </span>
                        </div>

                        <div>
                            <span class="k" aria-hidden="true">
                                <i class="fa-brands fa-whatsapp"></i>
                            </span>
                            <span>
                                <b>WhatsApp</b><br>
                                <span class="muted">{{ $contact->whatsapp_number ?: 'Chat cepat untuk tanya stok & event' }}</span>
                            </span>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:12px">
                        @if ($contact->whatsapp_url)
                            <a class="btn btn-primary" href="{{ $contact->whatsapp_url }}" target="_blank" rel="noopener"
                                style="flex:1;justify-content:center;">Chat WhatsApp</a>
                        @endif
                        <a class="btn btn-secondary" href="{{ route('products') }}"
                            style="flex:1;justify-content:center;">Lihat Produk</a>
                    </div>

                    <div class="divider"></div>
                    <h3 style="margin:0 0 10px;font-size:14px;letter-spacing:-.01em">Media Sosial</h3>
                    <div class="social">
                        @if ($contact->instagram_url)
                            <a href="{{ $contact->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        @endif

                        @if ($contact->facebook_url)
                            <a href="{{ $contact->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        @endif

                        @if ($contact->youtube_url)
                            <a href="{{ $contact->youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube">
                                <i class="fa-brands fa-youtube"></i>
                            </a>
                        @endif

                        @if ($contact->tiktok_url)
                            <a href="{{ $contact->tiktok_url }}" target="_blank" rel="noopener" aria-label="TikTok">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        @endif

                        @if ($contact->whatsapp_url)
                            <a href="{{ $contact->whatsapp_url }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                                <i class="fa-brands fa-whatsapp"></i>
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card card-pad">
                    <h2 style="margin:0 0 10px;font-size:18px;letter-spacing:-.02em">
                        <i class="fa-solid fa-map-location-dot"></i>
                        Lokasi
                    </h2>

                    @if ($mapsQuery)
                        <div style="border:1px solid rgba(17,24,39,.10);border-radius:18px;overflow:hidden;">
                            <iframe src="https://www.google.com/maps?q={{ urlencode($mapsQuery) }}&z=17&output=embed"
                                width="100%" height="320" style="border:0;display:block;" loading="lazy" allowfullscreen
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>

                        <div style="margin-top:14px;">
                            <a href="https://www.google.com/maps?q={{ urlencode($mapsQuery) }}" target="_blank" rel="noopener"
                                class="btn btn-secondary" style="width:100%;justify-content:center;">
                                <i class="fa-solid fa-location-arrow"></i>
                                Buka di Google Maps
                            </a>
                        </div>
                    @else
                        <p class="muted" style="margin:0;">Lokasi belum diatur.</p>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
