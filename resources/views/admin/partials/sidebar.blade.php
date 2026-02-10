<aside class="admin-sidebar">
    <div class="sidebar-logo">
        <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik">
        <span>Daeng Rubik</span>
    </div>

    <nav class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}"
            class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Dashboard</span>
        </a>

        <a href="{{ route('admin.products.index') }}"
            class="menu-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="fa-solid fa-box"></i>
            <span>Produk Rubik</span>
        </a>

        @php
            $eventActive = request()->routeIs('admin.events.*');
        @endphp

        <a href="javascript:void(0)" class="menu-item {{ $eventActive ? 'active open' : '' }}"
            onclick="toggleSubmenu('event-submenu')">
            <i class="fa-solid fa-calendar"></i>
            <span>Event</span>
            <i class="fa-solid fa-chevron-down arrow"></i>
        </a>

        <div id="event-submenu" class="submenu {{ $eventActive ? 'show' : '' }}">
            <a href="{{ route('admin.events.index') }}"
                class="submenu-item {{ request()->routeIs('admin.events.index') ? 'active' : '' }}">
                Daftar Event
            </a>

            <a href="{{ route('admin.events.participants.index') }}"
                class="submenu-item {{ request()->routeIs('admin.events.participants.*') ? 'active' : '' }}">
                Daftar Peserta
            </a>

            <a href="{{ route('admin.events.competition.index') }}"
                class="submenu-item {{ request()->routeIs('admin.events.competition.*') ? 'active' : '' }}">
                Hasil Kompetisi
            </a>
        </div>


        <a href="{{ route('admin.learn.index') }}"
            class="menu-item {{ request()->routeIs('admin.learn.*') ? 'active' : '' }}">
            <i class="fa-solid fa-book"></i>
            <span>Materi Pembelajaran</span>
        </a>


        <a href="{{ route('admin.admins.index') }}"
            class="menu-item {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>Pengguna</span>
        </a>

        <a href="{{ route('admin.settings') }}"
            class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="fa-solid fa-gear"></i>
            <span>Pengaturan Website</span>
        </a>

        <a href="{{ route('admin.reports.sales') }}"
            class="menu-item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i>
            <span>Laporan Penjualan</span>
        </a>
    </nav>
</aside>

<script>
    function toggleSubmenu(id) {
        const submenu = document.getElementById(id);
        if (!submenu) return;

        submenu.classList.toggle('show');

        // toggle arrow
        const trigger = submenu.previousElementSibling;
        if (trigger) {
            trigger.classList.toggle('open');
        }
    }
</script>
