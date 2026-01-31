<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik">
        <span>Daeng Rubik</span>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i>
            <span>Produk Rubik</span>
        </a>

        <a href="{{ route('admin.events.index') }}" class="menu-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <i class="fa-solid fa-calendar"></i>
            <span>Event Rubik</span>
        </a>

        <a href="{{ route('admin.learn.index') }}" class="menu-item {{ request()->routeIs('admin.learn.*') ? 'active' : '' }}">
            <i class="fa-solid fa-book"></i>
            <span>Materi Pembelajaran</span>
        </a>

        <a href="{{ route('admin.admins.index') }}" class="menu-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>Pengguna</span>
        </a>

        <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Pengaturan Website</span>
        </a>

        <a href="{{ route('admin.reports.sales') }}" class="menu-item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>Laporan Penjualan</span>
        </a>
    </nav>
</aside>
