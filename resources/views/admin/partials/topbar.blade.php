<div class="admin-topbar">
    <div class="topbar-left">
        <button id="sidebarToggle" class="btn btn-icon" style="display:none;" aria-label="Toggle sidebar">
            ☰
        </button>
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
    </div>

    <div class="topbar-right">
        <div class="topbar-notification">
            <span>🔔</span>
            <span class="notification-badge">3</span>
        </div>

        <div class="topbar-user">
            <div class="user-avatar">A</div>
            <span class="user-name">Admin</span>
        </div>

        <button class="logout-btn" onclick="if(confirm('Yakin ingin logout?')) window.location.href='{{ route('admin.logout') }}'">
            Logout
        </button>
    </div>
</div>
