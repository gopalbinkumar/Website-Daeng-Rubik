@extends('layouts.app')

@section('title', 'Katalog Produk — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/products.css') }}">
@endpush

@section('content')
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Produk</div>
            <h1 class="page-title">Katalog Produk</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Temukan rubik favoritmu meski ruang jualan offline terbatas. Filter kategori, brand, dan harga.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="two-col">
                <div>
                    <form method="GET">
                        <div class="sortbar">
                            {{-- <div class="muted" style="font-weight:700;">
                            Menampilkan <b style="color:var(--text)">{{ $products->count() }}</b> dari <b
                                style="color:var(--text)">{{ $products->total() }}</b> produk
                        </div> --}}
                            <input type="text" name="search" class="search-input" placeholder="Cari produk"
                                value="{{ request('search') }}">
                            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                                <button id="openFilter" class="btn btn-secondary mobile-filter-btn"
                                    type="button">Filter</button>
                                <select class="select" onchange="location=this.value">
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => null]) }}">
                                        Terbaru
                                    </option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_low']) }}"
                                        {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                                        Harga Terendah
                                    </option>
                                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'price_high']) }}"
                                        {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                        Harga Tertinggi
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                    <div class="grid-3">
                        @foreach ($products as $p)
                            <article class="card prod">
                                <div class="prod-img">

                                    {{-- BADGE (opsional, kalau mau nanti) --}}
                                    {{-- <span class="badge badge-success">Aktif</span> --}}

                                    <div style="width:74%;max-width:240px;">
                                        {{-- GAMBAR PRODUK (ganti cube) --}}
                                        <img src="{{ $p->primaryImage
                                            ? asset('storage/' . $p->primaryImage->image_path)
                                            : asset('assets/img/placeholder-product.png') }}"
                                            alt="{{ $p->name }}"
                                            style="
                                            width:100%;
                                            aspect-ratio: 1 / 1;
                                            object-fit: cover;
                                            border-radius:18px;
                                            border:6px solid var(--line);
                                            ">
                                    </div>
                                </div>
                                <div class="prod-body">
                                    <p class="prod-name">{{ $p->name }}</p>

                                    <div class="prod-meta">
                                        <span class="price">
                                            Rp {{ number_format($p->price, 0, ',', '.') }}
                                        </span>
                                    </div>

                                    <div class="prod-actions">
                                        <button class="btn btn-primary" type="button" style="flex:1"
                                            onclick="openProductModal({{ $p->id }})">
                                            Lihat detail
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                    </div>

                    @if ($products->total() > 9)
                        <div class="pagination" aria-label="Pagination">

                            {{-- Prev --}}
                            @if ($products->onFirstPage())
                                <span class="page-chip disabled">‹</span>
                            @else
                                <a href="{{ $products->previousPageUrl() }}" class="page-chip">‹</a>
                            @endif

                            {{-- Page Numbers --}}
                            @for ($i = 1; $i <= $products->lastPage(); $i++)
                                @if ($i == $products->currentPage())
                                    <span class="page-chip active">{{ $i }}</span>
                                @else
                                    <a href="{{ $products->url($i) }}" class="page-chip">{{ $i }}</a>
                                @endif
                            @endfor

                            {{-- Next --}}
                            @if ($products->hasMorePages())
                                <a href="{{ $products->nextPageUrl() }}" class="page-chip">›</a>
                            @else
                                <span class="page-chip disabled">›</span>
                            @endif

                        </div>
                    @endif


                </div>

                <aside class="card filter desktop-only" aria-label="Filter produk">
                    <form method="GET" action="{{ route('products') }}">
                        <h3><i class="fa-solid fa-filter"></i>
                            Filter</h3>
                        <div class="divider"></div>

                        {{-- KATEGORI --}}
                        <h3>Kategori</h3>

                        <div class="category-grid">
                            @foreach ($cubeCategories as $cat)
                                <label class="field">
                                    <input type="checkbox" name="category[]" value="{{ $cat->id }}"
                                        {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                                    {{ $cat->name }}
                                </label>
                            @endforeach
                        </div>

                        <div class="divider"></div>

                        {{-- HARGA --}}
                        <h3>Harga</h3>
                        <div class="range">
                            <span id="priceMinLabel">Rp.0</span>
                            <span id="priceMaxLabel">Rp.2000000</span>
                        </div>

                        <input type="hidden" name="min_price" id="minPriceInput" value="0">
                        <input type="hidden" name="max_price" id="maxPriceInput"
                            value="{{ request('max_price', 2000000) }}">


                        <input type="range" id="priceRange" min="0" max="2000000" step="5000"
                            value="{{ request('max_price', 2000000) }}" style="width:100%;margin-top:8px">

                        <div class="divider"></div>


                        {{-- BRAND --}}
                        <h3>Brand</h3>
                        @foreach (['MoYu', 'GAN', 'QiYi', 'YJ'] as $brand)
                            <label class="field">
                                <input type="checkbox" name="brand[]" value="{{ $brand }}"
                                    {{ in_array($brand, request('brand', [])) ? 'checked' : '' }}>
                                {{ $brand }}
                            </label>
                        @endforeach

                        <div class="divider"></div>

                        <button class="btn btn-primary" type="submit" style="width:100%">
                            Terapkan Filter
                        </button>

                        <a href="{{ route('products') }}" class="btn btn-outline" style="width:100%;margin-top:8px">
                            Reset Filter
                        </a>
                    </form>
                </aside>
            </div>
    </section>

    <div id="sheetBackdrop" class="drawer-backdrop" aria-hidden="true"></div>
    <div id="filterSheet" class="filter-sheet">
        <div class="sheet-head">
            <b>Filter Produk</b>
            <button id="closeFilter" type="button">✕</button>
        </div>

        <div class="card" style="padding:14px">

            <form method="GET" action="{{ route('products') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">
                {{-- ================= KATEGORI ================= --}}
                <h3>Kategori</h3>
                @foreach ($cubeCategories as $cat)
                    <label class="field">
                        <input type="checkbox" name="category[]" value="{{ $cat->id }}"
                            {{ in_array($cat->id, request('category', [])) ? 'checked' : '' }}>
                        {{ $cat->name }}
                    </label>
                @endforeach

                <div class="divider"></div>

                {{-- ================= HARGA ================= --}}
                <h3>Harga</h3>
                <div class="range">
                    <span id="mPriceMinLabel">Rp 0</span>
                    <span id="mPriceMaxLabel">Rp {{ number_format(request('max_price', 2000000), 0, ',', '.') }}</span>
                </div>

                <input type="hidden" name="min_price" id="mMinPriceInput" value="0">
                <input type="hidden" name="max_price" id="mMaxPriceInput" value="{{ request('max_price', 2000000) }}">

                <input type="range" id="mPriceRange" min="0" max="2000000" step="5000"
                    value="{{ request('max_price', 2000000) }}" style="width:100%;margin-top:8px">

                <div class="divider"></div>

                {{-- ================= BRAND ================= --}}
                <h3>Brand</h3>
                @foreach (['MoYu', 'GAN', 'QiYi', 'YJ'] as $brand)
                    <label class="field">
                        <input type="checkbox" name="brand[]" value="{{ $brand }}"
                            {{ in_array($brand, request('brand', [])) ? 'checked' : '' }}>
                        {{ $brand }}
                    </label>
                @endforeach

                <div class="divider"></div>

                <button class="btn btn-primary" type="submit" style="width:100%">
                    Terapkan Filter
                </button>

                <a href="{{ route('products') }}" class="btn btn-outline" style="width:100%;margin-top:8px">
                    Reset Filter
                </a>

            </form>

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
        /* =========================
                                       GLOBAL STATE
                                    ========================= */
        let activeProduct = null;
        let currentImageIndex = 0;

        // swipe state
        let startX = 0;
        let isDragging = false;

        /* =========================
           DATA DARI LARAVEL
        ========================= */
        const productsData = {!! json_encode(
            $products->map(function ($p) {
                    return [
                        'id' => $p->id,
                        'name' => $p->name,
                        'price' => $p->price,
                        'description' => $p->description,
                        'cube_category' => $p->cubeCategory?->name,
                        'brand' => $p->brand,
                        'difficulty_level' => $p->difficulty_level,
        
                        // 🔥 SEMUA GAMBAR URUT POSITION
                        'images' => $p->images->sortBy('position')->map(fn($img) => asset('storage/' . $img->image_path))->values()->toArray(),
        
                        // 🔗 MARKETPLACE
                        'marketplace' => $p->marketplaceLinks->mapWithKeys(fn($m) => [$m->marketplace => $m->url])->toArray(),
                    ];
                })->values()->toArray(),
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE,
        ) !!};

        const checkoutUrl = "{{ url('/checkout') }}";

        /* =========================
           HELPER
        ========================= */
        function capitalizeFirst(text) {
            return text ? text.charAt(0).toUpperCase() + text.slice(1) : '';
        }

        /* =========================
           OPEN MODAL
        ========================= */
        function openProductModal(productId) {
            const product = productsData.find(p => p.id === productId);
            if (!product) return;

            activeProduct = {
                ...product,
                images: Array.isArray(product.images) ? product.images : []
            };
            currentImageIndex = 0;

            const modal = document.getElementById('productModal');
            const backdrop = document.getElementById('productModalBackdrop');
            const content = document.getElementById('productModalContent');

            /* MARKETPLACE BUTTONS */
            const m = activeProduct.marketplace || {};
            let marketplaceButtons = '';

            if (m.tokopedia) marketplaceButtons += `
        <button class="marketplace-icon tokopedia"
            onclick="openMarketplace('${m.tokopedia}')"></button>`;
            if (m.shopee) marketplaceButtons += `
        <button class="marketplace-icon shopee"
            onclick="openMarketplace('${m.shopee}')"></button>`;
            if (m.tiktok_shop) marketplaceButtons += `
        <button class="marketplace-icon tiktok"
            onclick="openMarketplace('${m.tiktok_shop}')"></button>`;

            content.innerHTML = `
            <div class="product-modal-image">
                <div id="imageSlider"
                    style="width:100%;max-width:400px;aspect-ratio:1/1;margin:0 auto;
                        position:relative;overflow:hidden;border-radius:18px;
                        border:6px solid var(--line);">

                    <img id="modalProductImage"
                        src="${activeProduct.images[0] || ''}"
                        style="width:100%;height:100%;object-fit:cover;">

                    ${activeProduct.images.length > 1 ? `
                                                            <button onclick="prevImage()"
                                                                style="position:absolute;left:10px;top:50%;
                                                                transform:translateY(-50%);
                                                                width:36px;height:36px;border-radius:50%;
                                                                border:none;background:rgba(0,0,0,.45);
                                                                color:#fff;font-size:22px;cursor:pointer;">‹</button>

                                                            <button onclick="nextImage()"
                                                                style="position:absolute;right:10px;top:50%;
                                                                transform:translateY(-50%);
                                                                width:36px;height:36px;border-radius:50%;
                                                                border:none;background:rgba(0,0,0,.45);
                                                                color:#fff;font-size:22px;cursor:pointer;">›</button>
                                                        ` : ''}
                </div>
            </div>

            <div class="product-modal-body">
                <h2 class="product-modal-title">${activeProduct.name}</h2>

                <span class="badge badge-secondary">${activeProduct.cube_category ?? ''}</span>

                <div class="product-modal-price">
                    ${activeProduct.price.toLocaleString('id-ID', {
                        style: 'currency', currency: 'IDR', minimumFractionDigits: 0
                    })}
                </div>

                <div class="product-modal-description">
                    <h3>Deskripsi</h3>
                    <p>${activeProduct.description}</p>
                </div>

                <div class="product-modal-specs">
                    <div class="specs-grid">
                        <div><b>Brand</b> ${activeProduct.brand}</div>
                        <div><b>Level</b> ${capitalizeFirst(activeProduct.difficulty_level)}</div>
                    </div>
                </div>

        <div class="product-modal-actions">
        <button class="checkout-btn" onclick="goToCheckout(${activeProduct.id})">
            <i class="fa-solid fa-bag-shopping"></i> Beli Sekarang
        </button>

        <form action="{{ route('cart.add') }}" method="POST" style="width:100%;">
    @csrf
    <input type="hidden" name="product_id" value="${activeProduct.id}">
    <button type="submit" class="add-cart-btn" style="width:100%;">
        <i class="fa-solid fa-cart-arrow-down"></i> Tambah ke Keranjang
    </button>
</form>


        ${marketplaceButtons ? `<div class="marketplace-row">${marketplaceButtons}</div>` : ''}

        <p class="cart-feedback"></p>
        </div>
            </div>
            `;

            attachSwipe();
            renderImage();

            modal.classList.add('open');
            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function renderImage() {
            const img = document.getElementById('modalProductImage');
            if (!img || !activeProduct || !activeProduct.images.length) return;

            if (currentImageIndex < 0) currentImageIndex = activeProduct.images.length - 1;
            if (currentImageIndex >= activeProduct.images.length) currentImageIndex = 0;

            img.src = activeProduct.images[currentImageIndex];
        }

        function nextImage() {
            currentImageIndex++;
            renderImage();
        }

        function prevImage() {
            currentImageIndex--;
            renderImage();
        }

        function attachSwipe() {
            const slider = document.getElementById('imageSlider');
            if (!slider) return;

            slider.onmousedown = e => {
                isDragging = true;
                startX = e.clientX;
            };

            slider.ontouchstart = e => {
                startX = e.touches[0].clientX;
            };

            slider.ontouchend = e => {
                handleSwipe(startX, e.changedTouches[0].clientX);
            };
        }

        window.addEventListener('mouseup', e => {
            if (!isDragging) return;
            isDragging = false;
            handleSwipe(startX, e.clientX);
        });

        function handleSwipe(start, end) {
            const diff = end - start;
            if (Math.abs(diff) < 50) return;
            diff < 0 ? nextImage() : prevImage();
        }

        function closeProductModal() {
            document.getElementById('productModal').classList.remove('open');
            document.getElementById('productModalBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }

        function goToCheckout(id) {
            window.location.href = `${checkoutUrl}?id=${id}`;
        }

        function openMarketplace(url) {
            if (url) window.open(url, '_blank');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeProductModal();
        });



        /* =========================
           PRICE RANGE DESKTOP
        ========================= */
        const priceRange = document.getElementById('priceRange');
        const priceMinLabel = document.getElementById('priceMinLabel');
        const priceMaxLabel = document.getElementById('priceMaxLabel');
        const minPriceInput = document.getElementById('minPriceInput');
        const maxPriceInput = document.getElementById('maxPriceInput');

        function formatRp(n) {
            return 'Rp ' + Number(n).toLocaleString('id-ID');
        }

        if (priceRange) {
            const update = () => {
                priceMinLabel.textContent = formatRp(0);
                priceMaxLabel.textContent = formatRp(priceRange.value);
                minPriceInput.value = 0;
                maxPriceInput.value = priceRange.value;
            };
            update();
            priceRange.addEventListener('input', update);
        }

        const mRange = document.getElementById('mPriceRange');
        const mMinLabel = document.getElementById('mPriceMinLabel');
        const mMaxLabel = document.getElementById('mPriceMaxLabel');
        const mMinInput = document.getElementById('mMinPriceInput');
        const mMaxInput = document.getElementById('mMaxPriceInput');

        if (mRange) {
            const updateM = () => {
                mMinLabel.textContent = formatRp(0);
                mMaxLabel.textContent = formatRp(mRange.value);
                mMinInput.value = 0;
                mMaxInput.value = mRange.value;
            };
            updateM();
            mRange.addEventListener('input', updateM);
        }
    </script>
    <script>
        function addToCart(productId) {
            const feedback = document.querySelector('.cart-feedback');
            if (feedback) feedback.textContent = '⏳ Menambahkan ke keranjang...';

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('product_id', productId);

            fetch("{{ route('cart.add') }}", {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin' // ⬅️ INI KUNCI
                })
                .then(res => {
                    if (!res.ok) throw new Error('Request failed');
                    return res.json();
                })
                .then(data => {
                    if (feedback) {
                        feedback.textContent = '✅ Produk berhasil ditambahkan ke keranjang';
                    }
                })
                .catch(err => {
                    console.error(err);
                    if (feedback) {
                        feedback.textContent = '❌ Gagal menambahkan ke keranjang';
                    }
                    alert('Gagal menambahkan ke keranjang');
                });
        }
    </script>




@endsection
