<div class="admin-topbar">
    <div class="topbar-left">
        <button id="sidebarToggle" class="btn btn-icon" style="display:none;" aria-label="Toggle sidebar">
            ☰
        </button>
        {{-- <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1> --}}
        <h1 class="topbar-title">Admin Panel</h1>
    </div>

    <div class="topbar-right">
        {{-- <div class="topbar-notification">
            <span>🔔</span>
            <span class="notification-badge">3</span>
        </div> --}}

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
            <span class="user-name">Admin: {{ $displayName }}</span>
        </div>

    </div>
</div>
