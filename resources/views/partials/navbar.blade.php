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

    /* ===== CART RESPONSIVE ===== */
    .desktop-cart {
        display: inline-flex;
    }

    .drawer-cart {
        display: none;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .drawer-cart-inner {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .drawer-cart .cart-count {
        position: static;
        margin-left: auto;
        min-width: 20px;
        height: 20px;
        line-height: 20px;
        box-shadow: none;
    }

    @media (max-width: 720px) {
        .desktop-cart {
            display: none !important;
        }

        .drawer-cart {
            display: flex !important;
        }
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
                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                    Beranda
                </a>

                <a class="nav-link {{ $active('produk') }}" href="{{ route('products') }}">
                    Produk
                </a>

                <a class="nav-link {{ $active('event') }}" href="{{ route('events') }}">
                    Event
                </a>

                <a class="nav-link {{ request()->routeIs('learn.*') ? 'active' : '' }}"
                    href="{{ route('learn.index') }}">
                    Tutorial
                </a>

                <a class="nav-link {{ $active('tentang') }}" href="{{ route('about') }}">
                    Tentang
                </a>

                <a class="nav-link {{ $active('kontak') }}" href="{{ route('contact') }}">
                    Kontak
                </a>
            </nav>

            <div class="nav-actions">

                {{-- ===== CART DESKTOP ONLY ===== --}}
                <a href="{{ route('cart.index') }}" class="icon-btn cart-badge desktop-cart" aria-label="Keranjang">
                    <i class="fa-solid fa-cart-shopping"></i>

                    @if (($cartItemCount ?? 0) > 0)
                        <span class="cart-count">{{ $cartItemCount }}</span>
                    @endif
                </a>

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

                            <a href="{{ route('profile') }}">
                                <i class="fa-regular fa-user-circle"></i> Profil
                            </a>

                            <a href="{{ route('user.competitions') }}">
                                <i class="fa-regular fa-calendar-check"></i> Event
                            </a>

                            <a href="{{ route('transactions') }}">
                                <i class="fa-solid fa-wallet"></i> Transaksi
                            </a>

                            <div class="dropdown-divider"></div>

                            <form id="userLogoutForm" action="{{ route('auth.logout') }}" method="POST">
                                @csrf
                                <button type="button" class="dropdown-danger" onclick="confirmUserLogout()">
                                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                                </button>
                            </form>
                        @else
                            <a href="{{ route('auth.login') }}">
                                <i class="fa-solid fa-right-to-bracket"></i> Login
                            </a>

                            <a href="{{ route('auth.register') }}">
                                <i class="fa-solid fa-user-plus"></i> Daftar Akun
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

    {{-- ===== USER INFO ===== --}}
    @auth
        <div style="margin:20px 0;padding:14px;border-radius:12px;background:rgba(17,24,39,.04);">
            <strong style="display:block;font-size:14px;">
                {{ auth()->user()->name }}
            </strong>

            <small style="color:var(--muted);font-size:12px;">
                {{ auth()->user()->email }}
            </small>
        </div>
    @endauth

    {{-- ===== NAV LINKS ===== --}}
    <nav class="drawer-links" aria-label="Navigasi mobile">

        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
            Beranda
        </a>

        <a class="nav-link {{ $active('produk') }}" href="{{ route('products') }}">
            Produk
        </a>

        <a class="nav-link {{ $active('event') }}" href="{{ route('events') }}">
            Event
        </a>

        <a class="nav-link {{ request()->routeIs('learn.*') ? 'active' : '' }}" href="{{ route('learn.index') }}">
            Tutorial
        </a>

        <a class="nav-link {{ $active('tentang') }}" href="{{ route('about') }}">
            Tentang
        </a>

        <a class="nav-link {{ $active('kontak') }}" href="{{ route('contact') }}">
            Kontak
        </a>

        {{-- ===== MENU USER MOBILE ===== --}}
        @auth
            <div class="dropdown-divider" style="margin:14px 0;"></div>

            <a class="nav-link" href="{{ route('profile') }}">
                <i class="fa-regular fa-user-circle"></i> Profil
            </a>

            {{-- ===== CART MOBILE ONLY ===== --}}
            <a href="{{ route('cart.index') }}"
                class="nav-link drawer-cart {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                <span class="drawer-cart-inner">
                    <i class="fa-solid fa-cart-shopping"></i>
                    Keranjang
                </span>

                @if (($cartItemCount ?? 0) > 0)
                    <span class="cart-count">{{ $cartItemCount }}</span>
                @endif
            </a>

            <a class="nav-link" href="{{ route('user.competitions') }}">
                <i class="fa-regular fa-calendar-check"></i> Event
            </a>

            <a class="nav-link" href="{{ route('transactions') }}">
                <i class="fa-solid fa-wallet"></i> Transaksi
            </a>
        @endauth

    </nav>

    {{-- ===== FOOTER BUTTON ===== --}}
    <div class="drawer-footer">

        @auth
            <form id="mobileLogoutForm" action="{{ route('auth.logout') }}" method="POST" style="width:100%;">
                @csrf

                <button type="button" onclick="confirmMobileLogout()" class="btn btn-outline"
                    style="width:100%;justify-content:center;">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </form>
        @else
            <a class="btn btn-primary" href="{{ route('auth.login') }}" style="width:100%;justify-content:center;">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
        @endauth

    </div>

</aside>

<script>
    const userBtn = document.getElementById('userDropdownBtn');
    const userMenu = document.getElementById('userDropdownMenu');

    if (userBtn && userMenu) {
        userBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            userMenu.style.display =
                userMenu.style.display === 'block' ? 'none' : 'block';
        });

        document.addEventListener('click', () => {
            userMenu.style.display = 'none';
        });

        userMenu.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    }
</script>

<script>
    const openDrawerBtn = document.getElementById('openDrawer');
    const closeDrawerBtn = document.getElementById('closeDrawer');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const drawerBackdrop = document.getElementById('drawerBackdrop');

    function openMobileDrawer() {
        mobileDrawer?.classList.add('open');
        drawerBackdrop?.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileDrawer() {
        mobileDrawer?.classList.remove('open');
        drawerBackdrop?.classList.remove('open');
        document.body.style.overflow = '';
    }

    openDrawerBtn?.addEventListener('click', openMobileDrawer);
    closeDrawerBtn?.addEventListener('click', closeMobileDrawer);
    drawerBackdrop?.addEventListener('click', closeMobileDrawer);

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeMobileDrawer();
        }
    });
</script>

<script>
    function confirmUserLogout() {
        Swal.fire({
            title: 'Yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#E53935',
            cancelButtonColor: '#fff',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('userLogoutForm').submit();
            }
        });
    }
</script>

<script>
    function confirmMobileLogout() {
        Swal.fire({
            title: 'Yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#E53935',
            customClass: {
                confirmButton: 'btn btn-primary',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('mobileLogoutForm').submit();
            }
        });
    }
</script>
