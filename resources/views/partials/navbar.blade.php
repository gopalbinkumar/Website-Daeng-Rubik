@php
    $active = fn(string $p) => request()->is($p) ? 'active' : '';
@endphp

<style>
    .user-dropdown {
        position: relative;
    }

    .dropdown-menu {
        position: absolute;
        top: 120%;
        right: 0;
        width: 220px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid rgba(17, 24, 39, .08);
        box-shadow: 0 20px 40px rgba(0, 0, 0, .15);
        padding: 8px;
        display: none;
        z-index: 100;
    }

    .dropdown-header {
        padding: 10px 12px;
    }

    .dropdown-header strong {
        display: block;
        font-size: 14px;
    }

    .dropdown-header small {
        font-size: 12px;
        color: var(--muted);
    }

    .dropdown-divider {
        height: 1px;
        background: rgba(17, 24, 39, .08);
        margin: 6px 0;
    }

    .dropdown-menu a,
    .dropdown-menu button {
        display: flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        padding: 10px 12px;
        font-size: 14px;
        border-radius: 10px;
        color: var(--text);
        background: none;
        border: none;
        text-decoration: none;
        cursor: pointer;
    }

    .dropdown-menu a:hover,
    .dropdown-menu button:hover {
        background: rgba(17, 24, 39, .05);
    }

    .dropdown-danger {
        color: #dc2626;
    }

    .dropdown-danger:hover {
        background: rgba(220, 38, 38, .08);
    }
 
    
</style>

<header class="topbar">
    <div class="container">
        <div class="nav">
            <a class="brand" href="{{ url('/') }}" aria-label="Daeng Rubik - Beranda">
                <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik Logo" class="brand-logo" />
                <span>Daeng <span style="color:var(--red)">Rubik</span></span>
            </a>

            <nav class="nav-links" aria-label="Navigasi utama">
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                    href="{{ route('home') }}">Beranda</a>
                <a class="nav-link {{ $active('produk') }}" href="{{ route('products') }}">Produk</a>
                <a class="nav-link {{ $active('event') }}" href="{{ route('events') }}">Event</a>
                <a class="nav-link {{ request()->routeIs('learn.*') ? 'active' : '' }}"
                    href="{{ route('learn.index') }}">
                    Belajar
                </a>
                <a class="nav-link {{ $active('tentang') }}" href="{{ route('about') }}">Tentang</a>
                <a class="nav-link {{ $active('kontak') }}" href="{{ route('contact') }}">Kontak</a>
            </nav>

            <div class="nav-actions">
                <a href="{{ route('cart') }}" class="icon-btn cart-badge" aria-label="Keranjang">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if ($cartItemCount > 0)
                        <span class="cart-count">{{ $cartItemCount }}</span>
                    @endif
                </a>


                <button class="icon-btn" type="button" aria-label="Cari">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 21l-4.3-4.3" />
                        <circle cx="11" cy="11" r="7" />
                    </svg>
                </button>

                <div class="user-dropdown">
                    <button class="icon-btn" id="userDropdownBtn" aria-label="User menu">
                        <i class="fa-solid fa-user"></i>
                    </button>

                    <div class="dropdown-menu" id="userDropdownMenu">

                        @auth
                            <div class="dropdown-header">
                                <strong>{{ auth()->user()->name }}</strong>
                                <small>{{ auth()->user()->email }}</small>
                            </div>

                            <div class="dropdown-divider"></div>

                            <a href="">
                                👤 Profil Saya
                            </a>

                            <a href="">
                                🧩 Event Saya
                            </a>

                            <a href="">
                                💳 Transaksi
                            </a>

                            <div class="dropdown-divider"></div>

                            <form method="POST" action="{{ route('auth.logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-danger">
                                    🚪 Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('auth.login') }}">
                                🔑 Login
                            </a>
                            <a href="{{ route('auth.register') }}">
                                📝 Daftar Akun
                            </a>
                        @endauth

                    </div>
                </div>


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
        <a class="nav-link {{ $active('belajar') }}" href="{{ route('learn.index') }}">Belajar</a>
        <a class="nav-link {{ $active('tentang') }}" href="{{ route('about') }}">Tentang</a>
        <a class="nav-link {{ $active('kontak') }}" href="{{ route('contact') }}">Kontak</a>
    </nav>

    <div class="drawer-footer">
        <a class="btn btn-primary" href="{{ route('auth.login') }}" style="flex:1;justify-content:center;">Login</a>
        <a class="btn btn-outline" href="{{ route('auth.register') }}"
            style="flex:1;justify-content:center;">Daftar</a>
    </div>
</aside>

<script>
    const userBtn = document.getElementById('userDropdownBtn');
    const userMenu = document.getElementById('userDropdownMenu');

    userBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        userMenu.style.display =
            userMenu.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', () => {
        userMenu.style.display = 'none';
    });
</script>
