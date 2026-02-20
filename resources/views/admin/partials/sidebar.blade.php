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

            @if ($pendingParticipantCount > 0)
                <span class="badge-dot"></span>
            @endif

            <i class="fa-solid fa-chevron-down arrow"></i>
        </a>


        <div id="event-submenu" class="submenu {{ $eventActive ? 'show' : '' }}">
            <a href="{{ route('admin.events.index') }}"
                class="submenu-item {{ request()->routeIs('admin.events.index') ? 'active' : '' }}">
                Daftar Event
            </a>

            <a href="{{ route('admin.events.participants.index') }}"
                class="submenu-item {{ request()->routeIs('admin.events.participants.*') ? 'active' : '' }}">

                <span class="submenu-text">
                    Daftar Peserta

                    @if ($pendingParticipantCount > 0)
                        <span class="badge-dot-sub"></span>
                    @endif
                </span>
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

        <a href="{{ route('admin.users.index') }}"
            class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user"></i>
            <span>Pengguna</span>
        </a>

        <a href="{{ route('admin.settings') }}"
            class="menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}" style="display: none">
            <i class="fa-solid fa-gear"></i>
            <span>Pengaturan Website</span>
        </a>

        <a href="{{ route('admin.reports.sales') }}"
            class="menu-item {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">

            <i class="fa-solid fa-chart-line"></i>
            <span>Laporan Penjualan</span>

            @if ($pendingTransactionCount > 0)
                <span class="badge-dot"></span>
            @endif
        </a>
    </nav>
    <div class="sidebar-footer">
        <form id="logoutForm" action="{{ route('auth.logout') }}" method="POST">
            @csrf
            <button type="button" class="logout-btn" onclick="confirmLogout()">
                <i class="fa-solid fa-right-from-bracket"></i>
                Logout
            </button>
        </form>
    </div>
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

<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Yakin ingin logout?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal',
            customClass: {
                confirmButton: 'btn btn-danger',
                cancelButton: 'btn btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }
</script>
