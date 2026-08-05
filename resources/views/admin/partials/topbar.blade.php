<div class="admin-topbar">
    <div class="topbar-left">
        <button id="sidebarToggle" class="btn btn-icon btn-secondary sidebar-toggle" type="button"
            aria-label="Buka menu admin" aria-expanded="false">
            <i class="fa-solid fa-bars"></i>
        </button>
        <h1 class="topbar-title">@yield('page-title', 'Admin Panel')</h1>
    </div>

    <div class="topbar-right">
        @php
            $fullName = Auth::user()->name ?? 'Admin';

            $words = explode(' ', trim($fullName));

            // ==== NAMA (maksimal 3 kata) ====
            $displayName = implode(' ', array_slice($words, 0, 3));

            // ==== AVATAR ====
            if (count($words) > 1) {
                $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
            } else {
                $initials = strtoupper(substr($words[0], 0, 2));
            }
        @endphp

        <div class="topbar-user">
            <div class="user-avatar">{{ $initials }}</div>
            {{-- <span class="user-name">Admin: {{ $displayName }}</span> --}}
        </div>

    </div>
</div>
