@extends('layouts.app')

@section('title', 'Katalog Produk — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
@endpush

@section('content')
    @php
        $rupiah = fn(int $n) => 'Rp ' . number_format($n, 0, ',', '.');
        $products = [
            ['name' => 'Rubik 3x3 Budget', 'price' => 35000, 'stars' => 4, 'badge' => ['muted', 'Value']],
            ['name' => 'Rubik 3x3 Magnetic', 'price' => 70000, 'stars' => 5, 'badge' => ['hot', 'Bestseller']],
            ['name' => 'Rubik 4x4 Beginner', 'price' => 65000, 'stars' => 4, 'badge' => ['muted', 'Starter']],
            ['name' => 'Rubik 4x4 Pro', 'price' => 110000, 'stars' => 5, 'badge' => ['new', 'New']],
            ['name' => 'Rubik 5x5', 'price' => 125000, 'stars' => 4, 'badge' => ['muted', 'Smooth']],
            ['name' => 'Pyraminx', 'price' => 60000, 'stars' => 5, 'badge' => ['muted', 'Fun']],
            ['name' => 'Skewb', 'price' => 75000, 'stars' => 4, 'badge' => ['muted', 'Tricky']],
            ['name' => 'Megaminx', 'price' => 165000, 'stars' => 5, 'badge' => ['hot', 'Hot']],
            ['name' => 'Rubik 2x2 Speed', 'price' => 45000, 'stars' => 5, 'badge' => ['muted', 'Compact']],
            ['name' => 'Rubik 6x6', 'price' => 180000, 'stars' => 4, 'badge' => ['muted', 'Challenge']],
            ['name' => 'Square-1', 'price' => 90000, 'stars' => 4, 'badge' => ['muted', 'Unique']],
            ['name' => 'Clock Puzzle', 'price' => 55000, 'stars' => 4, 'badge' => ['muted', 'Classic']],
        ];
    @endphp

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Produk</div>
            <h1 class="page-title">Katalog Produk</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Temukan rubik favoritmu meski ruang jualan offline terbatas. Filter kategori, brand, dan harga (UI dulu).
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="two-col">
                <aside class="card filter desktop-only" aria-label="Filter produk">
                    <h3>🔎 Filter</h3>
                    <div class="divider"></div>

                    <h3>Kategori</h3>
                    <label class="field"><input type="checkbox" checked> 3x3</label>
                    <label class="field"><input type="checkbox"> 4x4</label>
                    <label class="field"><input type="checkbox"> 5x5</label>
                    <label class="field"><input type="checkbox"> Megaminx</label>
                    <label class="field"><input type="checkbox"> Pyraminx</label>

                    <div class="divider"></div>
                    <h3>Harga</h3>
                    <div class="range"><span>Rp 0</span><span>Rp 500.000</span></div>
                    <input type="range" min="0" max="500000" value="250000" style="width:100%;margin-top:8px">

                    <div class="divider"></div>
                    <h3>Brand</h3>
                    <label class="field"><input type="checkbox" checked> MoYu</label>
                    <label class="field"><input type="checkbox"> GAN</label>
                    <label class="field"><input type="checkbox"> QiYi</label>
                    <label class="field"><input type="checkbox"> YJ</label>

                    <div class="divider"></div>
                    <button class="btn btn-outline" type="button" style="width:100%">Reset filter</button>
                </aside>

                <div>
                    <div class="sortbar">
                        <div class="muted" style="font-weight:700;">
                            Menampilkan <b style="color:var(--text)">8</b> dari <b style="color:var(--text)">100</b> produk
                        </div>
                        <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                            <button id="openFilter" class="btn btn-secondary mobile-filter-btn" type="button">Filter</button>
                            <select class="select" aria-label="Urutkan">
                                <option>Terbaru</option>
                                <option>Harga Terendah</option>
                                <option>Harga Tertinggi</option>
                                <option>Rating</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid-3">
                        @foreach($products as $p)
                            <article class="card prod">
                                <div class="prod-img">
                                    <span class="badge {{ $p['badge'][0] }}">{{ $p['badge'][1] }}</span>
                                    <div style="width:74%;max-width:240px;">
                                        <div class="cube" style="border-radius:18px;border-width:6px"></div>
                                    </div>
                                </div>
                                <div class="prod-body">
                                    <p class="prod-name">{{ $p['name'] }}</p>
                                    <div class="prod-meta">
                                        <span class="price">{{ $rupiah($p['price']) }}</span>
                                        <span class="stars">★ {{ $p['stars'] }}.0</span>
                                    </div>
                                    <div class="prod-actions">
                                        <button class="btn btn-primary" type="button" style="flex:1">Lihat detail</button>
                                        <button class="btn btn-secondary" type="button" style="flex:1">+ Keranjang</button>
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <div class="pagination" aria-label="Pagination">
                        <span class="page-chip">‹</span>
                        <span class="page-chip active">1</span>
                        <span class="page-chip">2</span>
                        <span class="page-chip">3</span>
                        <span class="page-chip">›</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="sheetBackdrop" class="drawer-backdrop" aria-hidden="true"></div>
    <div id="filterSheet" class="filter-sheet" role="dialog" aria-label="Filter produk (mobile)" aria-modal="true">
        <div class="sheet-head">
            <b>Filter Produk</b>
            <button id="closeFilter" class="icon-btn" type="button" aria-label="Tutup filter">✕</button>
        </div>
        <div class="card" style="padding:14px;border-radius:18px;box-shadow:none">
            <h3>Kategori</h3>
            <label class="field"><input type="checkbox" checked> 3x3</label>
            <label class="field"><input type="checkbox"> 4x4</label>
            <label class="field"><input type="checkbox"> 5x5</label>
            <label class="field"><input type="checkbox"> Megaminx</label>
            <label class="field"><input type="checkbox"> Pyraminx</label>
            <div class="divider"></div>
            <h3>Harga</h3>
            <div class="range"><span>Rp 0</span><span>Rp 500.000</span></div>
            <input type="range" min="0" max="500000" value="250000" style="width:100%;margin-top:8px">
            <div class="divider"></div>
            <h3>Brand</h3>
            <label class="field"><input type="checkbox" checked> MoYu</label>
            <label class="field"><input type="checkbox"> GAN</label>
            <label class="field"><input type="checkbox"> QiYi</label>
            <label class="field"><input type="checkbox"> YJ</label>
            <div class="divider"></div>
            <div style="display:flex;gap:10px;">
                <button class="btn btn-outline" type="button" style="flex:1">Reset</button>
                <button class="btn btn-primary" type="button" style="flex:1">Terapkan</button>
            </div>
        </div>
    </div>
@endsection

