@php
    $active = fn(string $p) => request()->is($p) ? 'active' : '';
@endphp

<header class="topbar">
    <div class="container">
        <div class="nav">
            <a class="brand" href="{{ url('/') }}" aria-label="Daeng Rubik - Beranda">
                <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik Logo" class="brand-logo" />
                <span>Daeng <span style="color:var(--red)">Rubik</span></span>
            </a>

            <nav class="nav-links" aria-label="Navigasi utama">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                <a class="nav-link {{ $active('produk') }}" href="{{ route('products') }}">Produk</a>
                <a class="nav-link {{ $active('event') }}" href="{{ route('events') }}">Event</a>
                <a class="nav-link {{ $active('belajar') }}" href="{{ route('learn') }}">Belajar</a>
                <a class="nav-link {{ $active('tentang') }}" href="{{ route('about') }}">Tentang</a>
                <a class="nav-link {{ $active('kontak') }}" href="{{ route('contact') }}">Kontak</a>
            </nav>

            <div class="nav-actions">
                <button class="icon-btn" type="button" aria-label="Cari">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 21l-4.3-4.3" />
                        <circle cx="11" cy="11" r="7" />
                    </svg>
                </button>
                <button class="icon-btn cart-badge" type="button" aria-label="Keranjang" data-count="3">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M6 6h15l-1.5 9h-12z" />
                        <path d="M6 6l-1-3H2" />
                        <circle cx="9" cy="20" r="1.6" />
                        <circle cx="18" cy="20" r="1.6" />
                    </svg>
                </button>

                <button id="openDrawer" class="icon-btn hamburger" type="button" aria-label="Buka menu">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</header>

<div id="drawerBackdrop" class="drawer-backdrop" aria-hidden="true"></div>
<aside id="mobileDrawer" class="drawer" aria-label="Menu mobile">
    <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;">
        <a class="brand" href="{{ url('/') }}">
            <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik Logo" class="brand-logo" />
            <span>Daeng <span style="color:var(--red)">Rubik</span></span>
        </a>
        <button id="closeDrawer" class="icon-btn" type="button" aria-label="Tutup menu">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 6l12 12M18 6l-12 12" />
            </svg>
        </button>
    </div>

    <nav class="drawer-links" aria-label="Navigasi mobile">
        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
        <a class="nav-link {{ $active('produk') }}" href="{{ route('products') }}">Produk</a>
        <a class="nav-link {{ $active('event') }}" href="{{ route('events') }}">Event</a>
        <a class="nav-link {{ $active('belajar') }}" href="{{ route('learn') }}">Belajar</a>
        <a class="nav-link {{ $active('tentang') }}" href="{{ route('about') }}">Tentang</a>
        <a class="nav-link {{ $active('kontak') }}" href="{{ route('contact') }}">Kontak</a>
    </nav>

    <div class="drawer-footer">
        <a class="btn btn-secondary" href="{{ route('contact') }}" style="flex:1;justify-content:center;">Chat WhatsApp</a>
        <a class="btn btn-outline" href="{{ route('products') }}" style="flex:1;justify-content:center;">Katalog</a>
    </div>
</aside>

