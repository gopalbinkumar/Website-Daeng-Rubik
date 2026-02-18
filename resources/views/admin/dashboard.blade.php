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
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-label">Total Pengguna</div>
        </div>
    </div>
    {{-- @php
        $ranking = [
            ['product' => 'Rubik 3x3', 'score' => 10.0],
            ['product' => 'Rubik 4x4', 'score' => 6.93],
            ['product' => 'Pyraminx', 'score' => 6.25],
            // ['product' => 'Rubik 2x2', 'score' => 5.76],
            // ['product' => 'Megaminx', 'score' => 4.42],
        ];
    @endphp --}}


    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
        {{-- weighted scoring --}}
        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">
                    Rekomendasi Prioritas Restock
                </h3>
            </div>
            <div class="card-body" id="restockCard">

                <div class="activity-item">
                    <div class="activity-content">
                        <div class="activity-text">
                            Memuat data...
                        </div>
                    </div>
                </div>

            </div>
        </div>



        <div class="card">
            <div class="card-header">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Pendapatan Penjualan </h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="120"></canvas>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        fetch("{{ route('admin.reports.monthly-revenue') }}")
            .then(res => res.json())
            .then(data => {

                // === build 12 bulan terakhir (label + key) ===
                const labels = [];
                const map = {};

                for (let i = 11; i >= 0; i--) {
                    const d = new Date();
                    d.setMonth(d.getMonth() - i);

                    const month = d.getMonth() + 1;
                    const year = d.getFullYear();

                    const key = `${year}-${month}`;
                    map[key] = 0;

                    labels.push(
                        d.toLocaleString('id-ID', {
                            month: 'short',
                            year: 'numeric'
                        })
                    );
                }

                // === isi data dari backend ===
                data.forEach(item => {
                    const key = `${item.year}-${item.month}`;
                    if (map.hasOwnProperty(key)) {
                        map[key] = item.total;
                    }
                });

                const values = Object.values(map);

                new Chart(document.getElementById('revenueChart'), {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            data: values,
                            backgroundColor: '#2563eb'
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: ctx =>
                                        'Rp ' + ctx.raw.toLocaleString('id-ID')
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => 'Rp ' + v.toLocaleString('id-ID')
                                }
                            }
                        }
                    }
                });
            });
    </script>
    <script>
        fetch("{{ route('admin.weighted.index') }}")
            .then(res => res.json())
            .then(data => {

                const container = document.getElementById('restockCard');
                container.innerHTML = '';

                if (data.length === 0) {
                    container.innerHTML = `
                <div class="activity-item">
                    <div class="activity-content">
                        <div class="activity-text">
                            Belum ada data rekomendasi
                        </div>
                    </div>
                </div>
            `;
                    return;
                }

                data.forEach((row, index) => {

                    let icon = 'fa-cube';
                    if (index === 0) icon = 'fa-trophy';
                    else if (index === 1) icon = 'fa-medal';
                    else if (index === 2) icon = 'fa-award';

                    container.innerHTML += `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fa-solid ${icon}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-text">
                            <strong>#${index + 1}</strong>
                            ${row.product}
                            <span style="float:right; font-weight:600;">
                                ${parseFloat(row.score).toFixed(2)}
                            </span>
                        </div>
                        <div class="activity-time">
                            Skor Weighted Scoring
                        </div>
                    </div>
                </div>
            `;
                });

            });
    </script>


@endsection
