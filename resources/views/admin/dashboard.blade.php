@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
            <div class="stat-value">100</div>
            <div class="stat-label">Total Produk</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-calendar"></i></div>
            <div class="stat-value">50</div>
            <div class="stat-label">Total Event</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-book"></i></div>
            <div class="stat-value">75</div>
            <div class="stat-label">Total Materi</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-user"></i></div>
            <div class="stat-value">5</div>
            <div class="stat-label">Total Admin</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Aktivitas Terkini</h3>
            </div>
            <div class="card-body">
                <div class="activity-item">
                    <div class="activity-icon"><i class="fa-solid fa-box"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">Produk baru ditambahkan</div>
                        <div class="activity-time">2 jam yang lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fa-solid fa-calendar"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">Event baru dibuat</div>
                        <div class="activity-time">5 jam yang lalu</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fa-solid fa-book"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">Materi baru diupload</div>
                        <div class="activity-time">1 hari yang lalu</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Produk Terbaru</h3>
            </div>
            <div class="card-body">
                <div style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--admin-border);">
                    <img src="https://via.placeholder.com/60" alt="Product" class="table-img">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; margin-bottom: 4px;">Rubik 3x3 Speed Cube</div>
                        <div style="font-size: 14px; color: var(--admin-text-muted);">Rp 50.000 • Stok: 25</div>
                    </div>
                </div>
                <div style="display: flex; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--admin-border);">
                    <img src="https://via.placeholder.com/60" alt="Product" class="table-img">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; margin-bottom: 4px;">Rubik 4x4 Magnetic</div>
                        <div style="font-size: 14px; color: var(--admin-text-muted);">Rp 85.000 • Stok: 15</div>
                    </div>
                </div>
                <div style="display: flex; gap: 12px; padding: 12px 0;">
                    <img src="https://via.placeholder.com/60" alt="Product" class="table-img">
                    <div style="flex: 1;">
                        <div style="font-weight: 600; margin-bottom: 4px;">Megaminx Pro</div>
                        <div style="font-size: 14px; color: var(--admin-text-muted);">Rp 150.000 • Stok: 8</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
