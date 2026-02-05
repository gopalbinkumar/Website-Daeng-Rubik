@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-box"></i></div>
            <div class="stat-value">{{ $totalProducts }}</div>
            <div class="stat-label">Total Produk</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-calendar"></i></div>
            <div class="stat-value">{{ $totalEvents }}</div>
            <div class="stat-label">Total Event</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-book"></i></div>
            <div class="stat-value">{{ $totalMaterials }}</div>
            <div class="stat-label">Total Materi</div>
        </div>

        <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-user"></i></div>
            <div class="stat-value">{{ $totalAdmins }}</div>
            <div class="stat-label">Total Admin</div>
        </div>
    </div>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        {{-- AKTIVITAS TERKINI --}}
        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Aktivitas Terkini</h3>
            </div>
            <div class="card-body">

                @forelse ($activities as $act)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="fa-solid {{ $act['icon'] }}"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-text">{{ $act['text'] }}</div>
                            <div class="activity-time">
                                {{ $act['time']->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="activity-item">
                        <div class="activity-content">
                            <div class="activity-text">Belum ada aktivitas</div>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>

        {{-- PRODUK TERBARU --}}
        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Produk Terbaru</h3>
            </div>
            <div class="card-body">

                @forelse ($latestProducts as $product)
                    <div
                        style="display: flex; gap: 12px; padding: 12px 0;
                        {{ !$loop->last ? 'border-bottom: 1px solid var(--admin-border);' : '' }}">

                        <img src="{{ $product->primaryImage
                            ? asset('storage/' . $product->primaryImage->image_path)
                            : 'https://via.placeholder.com/60' }}"
                            alt="Product" class="table-img">


                        <div style="flex: 1;">
                            <div style="font-weight: 600; margin-bottom: 4px;">
                                {{ $product->name }}
                            </div>
                            <div style="font-size: 14px; color: var(--admin-text-muted);">
                                Rp {{ number_format($product->price) }} • Stok: {{ $product->stock }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding: 12px 0; color: var(--admin-text-muted);">
                        Belum ada produk
                    </div>
                @endforelse

            </div>
        </div>
    </div>
@endsection
