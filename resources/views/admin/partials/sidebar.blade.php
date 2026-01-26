<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik">
        <span>Daeng Rubik</span>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span class="menu-item-icon">🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.products.index') }}" class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <span class="menu-item-icon">📦</span>
            <span>Produk Rubik</span>
        </a>

        <a href="{{ route('admin.events.index') }}" class="menu-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
            <span class="menu-item-icon">🎉</span>
            <span>Event Rubik</span>
        </a>

        <a href="{{ route('admin.learn.index') }}" class="menu-item {{ request()->routeIs('admin.learn.*') ? 'active' : '' }}">
            <span class="menu-item-icon">📚</span>
            <span>Materi Pembelajaran</span>
        </a>

        <a href="{{ route('admin.admins.index') }}" class="menu-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
            <span class="menu-item-icon">👥</span>
            <span>Admin</span>
        </a>

        <a href="{{ route('admin.settings') }}" class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <span class="menu-item-icon">⚙️</span>
            <span>Pengaturan Website</span>
        </a>
    </nav>
</aside>
