@extends('layouts.app')

@section('title', 'Katalog Produk — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
@endpush

@section('content')
    @php
        $rupiah = fn(int $n) => 'Rp ' . number_format($n, 0, ',', '.');
        $products = [
            [
                'id' => 1,
                'name' => 'Rubik 3x3 Budget',
                'price' => 35000,
                'stars' => 4,
                'badge' => ['muted', 'Value'],
                'category' => '3x3',
                'description' => 'Rubik 3x3 dengan harga terjangkau namun tetap berkualitas. Cocok untuk pemula yang baru belajar atau sebagai hadiah. Desain ergonomis dengan corner cutting yang baik untuk latihan speedcubing.',
                'specs' => [
                    'ukuran' => '56mm',
                    'level' => 'Beginner',
                    'brand' => 'MoYu',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 2,
                'name' => 'Rubik 3x3 Magnetic',
                'price' => 70000,
                'stars' => 5,
                'badge' => ['hot', 'Bestseller'],
                'category' => '3x3',
                'description' => 'Rubik 3x3 dengan sistem magnetik untuk kontrol yang lebih presisi. Sangat cocok untuk speedcubing dan kompetisi. Smooth turning dengan corner cutting hingga 45 derajat. Pilihan favorit para speedcuber profesional.',
                'specs' => [
                    'ukuran' => '56mm',
                    'level' => 'Advanced',
                    'brand' => 'GAN',
                    'material' => 'ABS Plastic + Magnets'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 3,
                'name' => 'Rubik 4x4 Beginner',
                'price' => 65000,
                'stars' => 4,
                'badge' => ['muted', 'Starter'],
                'category' => '4x4',
                'description' => 'Rubik 4x4 untuk pemula dengan mekanisme yang mudah diputar. Desain ramah pemula dengan warna yang kontras untuk memudahkan identifikasi. Perfect untuk upgrade dari 3x3 ke 4x4.',
                'specs' => [
                    'ukuran' => '60mm',
                    'level' => 'Intermediate',
                    'brand' => 'QiYi',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 4,
                'name' => 'Rubik 4x4 Pro',
                'price' => 110000,
                'stars' => 5,
                'badge' => ['new', 'New'],
                'category' => '4x4',
                'description' => 'Rubik 4x4 profesional dengan performa tinggi. Anti-pop mechanism untuk stabilitas maksimal saat speedcubing. Corner cutting yang sangat baik dan smooth turning di semua layer.',
                'specs' => [
                    'ukuran' => '60mm',
                    'level' => 'Advanced',
                    'brand' => 'MoYu',
                    'material' => 'ABS Plastic + Magnets'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 5,
                'name' => 'Rubik 5x5',
                'price' => 125000,
                'stars' => 4,
                'badge' => ['muted', 'Smooth'],
                'category' => '5x5',
                'description' => 'Rubik 5x5 dengan mekanisme yang halus dan stabil. Cocok untuk challenge yang lebih tinggi setelah menguasai 4x4. Desain ergonomis untuk tangan yang nyaman saat solving.',
                'specs' => [
                    'ukuran' => '64mm',
                    'level' => 'Advanced',
                    'brand' => 'YJ',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 6,
                'name' => 'Pyraminx',
                'price' => 60000,
                'stars' => 5,
                'badge' => ['muted', 'Fun'],
                'category' => 'Pyraminx',
                'description' => 'Pyraminx dengan bentuk piramida yang unik. Puzzle yang menyenangkan dan berbeda dari rubik kubus biasa. Cocok untuk koleksi atau challenge baru.',
                'specs' => [
                    'ukuran' => '90mm',
                    'level' => 'Beginner',
                    'brand' => 'MoYu',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 7,
                'name' => 'Skewb',
                'price' => 75000,
                'stars' => 4,
                'badge' => ['muted', 'Tricky'],
                'category' => 'Skewb',
                'description' => 'Skewb dengan mekanisme rotasi yang unik. Puzzle menantang yang membutuhkan strategi berbeda dari rubik kubus. Perfect untuk menambah variasi koleksi puzzle Anda.',
                'specs' => [
                    'ukuran' => '65mm',
                    'level' => 'Intermediate',
                    'brand' => 'QiYi',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 8,
                'name' => 'Megaminx',
                'price' => 165000,
                'stars' => 5,
                'badge' => ['hot', 'Hot'],
                'category' => 'Megaminx',
                'description' => 'Megaminx dengan 12 sisi dan bentuk dodecahedron yang menawan. Challenge tingkat tinggi untuk para puzzle enthusiast. Kualitas premium dengan turning yang smooth.',
                'specs' => [
                    'ukuran' => '70mm',
                    'level' => 'Expert',
                    'brand' => 'GAN',
                    'material' => 'ABS Plastic + Magnets'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 9,
                'name' => 'Rubik 2x2 Speed',
                'price' => 45000,
                'stars' => 5,
                'badge' => ['muted', 'Compact'],
                'category' => '2x2',
                'description' => 'Rubik 2x2 dengan performa speedcubing yang optimal. Ukuran compact, mudah dibawa kemana-mana. Perfect untuk latihan atau warm-up sebelum solving 3x3.',
                'specs' => [
                    'ukuran' => '50mm',
                    'level' => 'Beginner',
                    'brand' => 'MoYu',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 10,
                'name' => 'Rubik 6x6',
                'price' => 180000,
                'stars' => 4,
                'badge' => ['muted', 'Challenge'],
                'category' => '6x6',
                'description' => 'Rubik 6x6 dengan tingkat kesulitan yang sangat tinggi. Challenge ultimate untuk para expert. Anti-pop mechanism dan smooth turning untuk pengalaman solving yang optimal.',
                'specs' => [
                    'ukuran' => '68mm',
                    'level' => 'Expert',
                    'brand' => 'MoYu',
                    'material' => 'ABS Plastic + Magnets'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 11,
                'name' => 'Square-1',
                'price' => 90000,
                'stars' => 4,
                'badge' => ['muted', 'Unique'],
                'category' => 'Square-1',
                'description' => 'Square-1 dengan bentuk yang bisa berubah menjadi kubus atau bentuk lain. Puzzle unik dengan mekanisme rotasi yang berbeda. Menantang dan menyenangkan untuk dipecahkan.',
                'specs' => [
                    'ukuran' => '60mm',
                    'level' => 'Advanced',
                    'brand' => 'QiYi',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
            [
                'id' => 12,
                'name' => 'Clock Puzzle',
                'price' => 55000,
                'stars' => 4,
                'badge' => ['muted', 'Classic'],
                'category' => 'Clock',
                'description' => 'Clock Puzzle dengan 9 jam di setiap sisi. Puzzle klasik yang berbeda dari rubik kubus. Cocok untuk koleksi atau challenge baru yang unik.',
                'specs' => [
                    'ukuran' => '120mm',
                    'level' => 'Intermediate',
                    'brand' => 'MoYu',
                    'material' => 'ABS Plastic'
                ],
                'marketplace' => [
                    'shopee' => 'https://shopee.co.id/daengrubik',
                    'tokopedia' => 'https://tokopedia.com/daengrubik',
                    'tiktok' => 'https://tiktokshop.co.id/daengrubik'
                ]
            ],
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
                                        <button class="btn btn-primary" type="button" style="flex:1" onclick="openProductModal({{ $p['id'] }})">Lihat detail</button>
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

    <!-- Product Detail Modal -->
    <div id="productModalBackdrop" class="modal-backdrop" aria-hidden="true" onclick="closeProductModal()"></div>
    <div id="productModal" class="product-modal" role="dialog" aria-label="Detail Produk" aria-modal="true">
        <button class="modal-close" onclick="closeProductModal()" aria-label="Tutup modal">✕</button>
        <div id="productModalContent" class="product-modal-content">
            <!-- Content akan diisi via JavaScript -->
        </div>
    </div>

    <script>
        const productsData = @json($products);
        const checkoutUrl = "{{ url('/checkout') }}";
        
        function openProductModal(productId) {
            const product = productsData.find(p => p.id === productId);
            if (!product) return;
            
            const modal = document.getElementById('productModal');
            const backdrop = document.getElementById('productModalBackdrop');
            const content = document.getElementById('productModalContent');

            const marketplace = product.marketplace || {};
            let marketplaceButtons = '';
            if (marketplace.tokopedia) {
                marketplaceButtons += `<button type="button" class="marketplace-icon tokopedia" title="Buka di Tokopedia" onclick="openMarketplace('${marketplace.tokopedia}')"></button>`;
            }
            if (marketplace.shopee) {
                marketplaceButtons += `<button type="button" class="marketplace-icon shopee" title="Buka di Shopee" onclick="openMarketplace('${marketplace.shopee}')"></button>`;
            }
            if (marketplace.tiktok) {
                marketplaceButtons += `<button type="button" class="marketplace-icon tiktok" title="Buka di TikTok Shop" onclick="openMarketplace('${marketplace.tiktok}')"></button>`;
            }
            const hasMarketplace = marketplaceButtons.trim().length > 0;
            
            content.innerHTML = `
                <div class="product-modal-image">
                    <div class="cube" style="width:100%;max-width:400px;aspect-ratio:1/1;border-radius:18px;border-width:6px;margin:0 auto;"></div>
                    <span class="badge ${product.badge[0]}" style="position:absolute;top:16px;left:16px;">${product.badge[1]}</span>
                </div>
                <div class="product-modal-body">
                    <h2 class="product-modal-title">${product.name}</h2>
                    <span class="badge ${product.badge[0]}" style="margin-bottom:12px;">${product.category}</span>
                    <div class="product-modal-price">${product.price.toLocaleString('id-ID', {style: 'currency', currency: 'IDR', minimumFractionDigits: 0})}</div>
                    
                    <div class="product-modal-info">
                        <p style="color:var(--muted);font-size:14px;margin:16px 0;">
                            <strong>ℹ️</strong> Pembelian dilakukan melalui halaman checkout resmi Daeng Rubik atau marketplace resmi di bawah.
                        </p>
                    </div>
                    
                    <div class="product-modal-description">
                        <h3 style="font-size:16px;margin:0 0 8px;">Deskripsi</h3>
                        <p style="color:var(--muted);line-height:1.7;margin:0;">${product.description}</p>
                    </div>
                    
                    <div class="product-modal-specs">
                        <h3 style="font-size:16px;margin:16px 0 8px;">Spesifikasi</h3>
                        <div class="specs-grid">
                            <div class="spec-item">
                                <span class="spec-label">Ukuran</span>
                                <span class="spec-value">${product.specs.ukuran}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Level</span>
                                <span class="spec-value">${product.specs.level}</span>
                            </div>
                            <div class="spec-item">
                                <span class="spec-label">Brand</span>
                                <span class="spec-value">${product.specs.brand}</span>
                            </div>
                            ${product.specs.material ? `
                            <div class="spec-item">
                                <span class="spec-label">Material</span>
                                <span class="spec-value">${product.specs.material}</span>
                            </div>
                            ` : ''}
                        </div>
                    </div>
                    
                    <div class="product-modal-actions">
                        <button type="button" class="checkout-btn" onclick="goToCheckout(${product.id})">
                            🛒 Beli Sekarang
                        </button>
                        <div class="product-secondary-actions">
                            ${hasMarketplace ? `
                            <div class="marketplace-row">
                                ${marketplaceButtons}
                            </div>
                            ` : ''}
                            <button type="button" class="add-cart-btn" onclick="addToCart(${product.id})">
                                <span class="icon">➕</span>
                                <span>Tambah ke Keranjang</span>
                            </button>
                        </div>
                        <p id="cartFeedback" class="cart-feedback" aria-live="polite"></p>
                    </div>
                </div>
            `;
            
            modal.classList.add('open');
            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        
        function closeProductModal() {
            const modal = document.getElementById('productModal');
            const backdrop = document.getElementById('productModalBackdrop');
            modal.classList.remove('open');
            backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }

        function goToCheckout(productId) {
            if (!checkoutUrl) return;
            window.location.href = `${checkoutUrl}?id=${productId}`;
        }

        function openMarketplace(url) {
            if (!url) return;
            window.open(url, '_blank');
        }
        
        function addToCart(productId) {
            const feedback = document.getElementById('cartFeedback');
            const product = productsData.find(p => p.id === productId);
            if (!product || !window.DaengCart) return;
            window.DaengCart.add({
                id: product.id,
                name: product.name,
                price: product.price
            });
            if (feedback) {
                feedback.textContent = `✅ "${product.name}" ditambahkan ke keranjang.`;
            }
        }
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeProductModal();
            }
        });
    </script>
@endsection

