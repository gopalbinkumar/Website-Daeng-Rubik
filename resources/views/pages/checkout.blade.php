@extends('layouts.app')

@section('title', 'Checkout Produk — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
@ENDPUSH

@section('content')
    @php
        $rupiah = fn(int $n) => 'Rp ' . number_format($n, 0, ',', '.');
        $productId = (int) request()->get('id', 1);
        $products = [
            1 => ['name' => 'Rubik 3x3 Budget', 'price' => 35000],
            2 => ['name' => 'Rubik 3x3 Magnetic', 'price' => 70000],
            3 => ['name' => 'Rubik 4x4 Beginner', 'price' => 65000],
            4 => ['name' => 'Rubik 4x4 Pro', 'price' => 110000],
            5 => ['name' => 'Rubik 5x5', 'price' => 125000],
            6 => ['name' => 'Pyraminx', 'price' => 60000],
            7 => ['name' => 'Skewb', 'price' => 75000],
            8 => ['name' => 'Megaminx', 'price' => 165000],
            9 => ['name' => 'Rubik 2x2 Speed', 'price' => 45000],
            10 => ['name' => 'Rubik 6x6', 'price' => 180000],
            11 => ['name' => 'Square-1', 'price' => 90000],
            12 => ['name' => 'Clock Puzzle', 'price' => 55000],
        ];
        $product = $products[$productId] ?? $products[1];
        // Default ongkir: Kota Makassar
        $ongkir = 15000;
        $total = $product['price'] + $ongkir;
    @endphp

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Produk &gt; Checkout</div>
            <h1 class="page-title">Checkout Produk</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Periksa kembali detail pesanan dan isi data penerima dengan benar. Pembayaran dilakukan melalui transfer bank.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="checkout-layout">
                <!-- Left Column: Form -->
                <div class="checkout-form-column">
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">Data Penerima</h2>
                        <form id="checkoutForm" class="checkout-form">
                            <div class="form-group">
                                <label class="form-label">Nama Penerima <span class="required">*</span></label>
                                <input type="text" class="form-input" placeholder="Masukkan nama penerima" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp Penerima <span class="required">*</span></label>
                                <input type="tel" class="form-input" placeholder="+62 812-3456-7890" required>
                                <small class="form-helper">Digunakan untuk konfirmasi pesanan.</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                                <textarea class="form-input" rows="4" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kode Pos <span class="required">*</span></label>
                                <input type="text" class="form-input" placeholder="Contoh: 90222" required>
                            </div>
                        </form>
                    </div>

                    <div class="checkout-card">
                        <h2 class="checkout-card-title">Pengiriman</h2>
                        <div class="shipping-info">
                            <div class="form-group">
                                <label class="form-label">Lokasi Pengiriman <span class="required">*</span></label>
                                <select id="shippingOption" class="form-input">
                                    <option value="makassar" selected>Kota Makassar (Ongkir Rp15.000)</option>
                                    <option value="sulsel">Luar Kota (Sulawesi Selatan) – Ongkir Rp25.000</option>
                                    <option value="luarprov">Luar Provinsi – Ongkir Rp40.000</option>
                                </select>
                            </div>

                            <div id="shippingExtraSulsel" class="form-group" style="display:none;">
                                <label class="form-label">Nama Kota / Kabupaten</label>
                                <input type="text" class="form-input" placeholder="Contoh: Gowa, Maros">
                            </div>

                            <div id="shippingExtraLuarProv" style="display:none;">
                                <div class="form-group">
                                    <label class="form-label">Nama Provinsi</label>
                                    <input type="text" class="form-input" placeholder="Contoh: Jawa Barat">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Nama Kota / Kabupaten</label>
                                    <input type="text" class="form-input" placeholder="Contoh: Bandung">
                                </div>
                            </div>

                            <div class="shipping-row">
                                <span class="label">Estimasi jarak pengiriman</span>
                                <span class="value">5–10 km dari Makassar (contoh UI)</span>
                            </div>
                            <div class="shipping-row">
                                <span class="label">Ongkir</span>
                                <span class="value" id="shippingOngkir">{{ $rupiah($ongkir) }}</span>
                            </div>
                            <div class="shipping-row total">
                                <span class="label">Total Pembayaran</span>
                                <span class="value" id="shippingTotal">{{ $rupiah($total) }}</span>
                            </div>
                            <p class="muted" style="margin-top:8px;font-size:13px;">
                                Ongkir dihitung estimasi untuk tampilan UI. Nilai aktual dapat disesuaikan saat backend aktif.
                            </p>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h2 class="checkout-card-title">Upload Bukti Pembayaran</h2>
                        <div class="upload-area" onclick="document.getElementById('buktiTransfer').click()">
                            <p>Drag & Drop atau klik untuk upload bukti transfer (JPG/PNG, maks. 5MB)</p>
                        </div>
                        <input type="file" id="buktiTransfer" accept="image/*" style="display:none;">
                        <div id="buktiPreview" class="upload-preview" aria-live="polite"></div>
                    </div>
                </div>

                <!-- Right Column: Summary & Payment Info -->
                <aside class="checkout-summary-column">
                    <div class="checkout-card">
                        <h2 class="checkout-card-title">Ringkasan Produk</h2>
                        <div class="summary-product">
                            <div class="summary-thumb" aria-hidden="true">
                                <div class="cube" style="width:60px;height:60px;border-radius:14px;border-width:5px;"></div>
                            </div>
                            <div class="summary-info">
                                <p class="summary-name">{{ $product['name'] }}</p>
                                <p class="summary-price">{{ $rupiah($product['price']) }}</p>
                            </div>
                        </div>
                        <div class="summary-totals">
                            <div class="row">
                                <span>Harga Produk</span>
                                <span>{{ $rupiah($product['price']) }}</span>
                            </div>
                            <div class="row">
                                <span>Ongkir (estimasi)</span>
                                    <span id="summaryOngkir">{{ $rupiah($ongkir) }}</span>
                            </div>
                            <div class="row total">
                                <span>Total Pembayaran</span>
                                    <span id="summaryTotal">{{ $rupiah($total) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h2 class="checkout-card-title">Informasi Pembayaran</h2>
                        <div class="payment-info">
                            <div class="row">
                                <span class="label">Bank</span>
                                <span class="value">BCA</span>
                            </div>
                            <div class="row">
                                <span class="label">No. Rekening</span>
                                <span class="value">1234567890</span>
                            </div>
                            <div class="row">
                                <span class="label">Atas Nama</span>
                                <span class="value">Daeng Rubik</span>
                            </div>
                        </div>
                        <p class="muted" style="margin-top:8px;font-size:13px;">
                            Silakan transfer sesuai <strong>total pembayaran</strong> di atas, lalu upload bukti transfer pada form.
                        </p>
                    </div>

                    <div class="checkout-card actions-card">
                        <div class="checkout-actions">
                            <a href="{{ route('products') }}" class="btn btn-secondary" style="width:100%;">Kembali ke Produk</a>
                            <button type="button" class="btn btn-primary" style="width:100%;" onclick="submitCheckoutDemo()">
                                Konfirmasi Pesanan
                            </button>
                        </div>
                        <p class="muted" style="margin-top:8px;font-size:12px;">
                            (UI demo) Pesanan belum benar-benar diproses, fitur backend belum diaktifkan.
                        </p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <div id="checkoutToast" class="checkout-toast" role="alert" aria-live="polite">
        <div class="toast-content">
            <span class="toast-icon">✓</span>
            <div>
                <strong>Pesanan tercatat!</strong>
                <p style="margin:4px 0 0;font-size:13px;">Admin akan menghubungi kamu via WhatsApp setelah pembayaran diverifikasi. (UI demo)</p>
            </div>
        </div>
    </div>

    <script>
        const buktiInput = document.getElementById('buktiTransfer');
        const buktiPreview = document.getElementById('buktiPreview');

        if (buktiInput) {
            buktiInput.addEventListener('change', (e) => {
                const file = e.target.files[0];
                if (!file) {
                    buktiPreview.innerHTML = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = () => {
                    buktiPreview.innerHTML = `
                        <div class="preview-item">
                            <img src="${reader.result}" alt="Bukti transfer" />
                            <div class="preview-meta">
                                <strong>${file.name}</strong>
                                <span>${(file.size / 1024).toFixed(1)} KB</span>
                            </div>
                        </div>
                    `;
                };
                reader.readAsDataURL(file);
            });
        }

        const productPrice = {{ $product['price'] }};

        function formatRupiah(num) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
        }

        const shippingSelect = document.getElementById('shippingOption');
        const shippingOngkirEl = document.getElementById('shippingOngkir');
        const shippingTotalEl = document.getElementById('shippingTotal');
        const summaryOngkirEl = document.getElementById('summaryOngkir');
        const summaryTotalEl = document.getElementById('summaryTotal');
        const extraSulsel = document.getElementById('shippingExtraSulsel');
        const extraLuarProv = document.getElementById('shippingExtraLuarProv');

        function updateShippingUI() {
            if (!shippingSelect) return;
            const value = shippingSelect.value;
            let ongkir = 15000;
            if (value === 'sulsel') ongkir = 25000;
            if (value === 'luarprov') ongkir = 40000;

            const total = productPrice + ongkir;

            if (shippingOngkirEl) shippingOngkirEl.textContent = formatRupiah(ongkir);
            if (shippingTotalEl) shippingTotalEl.textContent = formatRupiah(total);
            if (summaryOngkirEl) summaryOngkirEl.textContent = formatRupiah(ongkir);
            if (summaryTotalEl) summaryTotalEl.textContent = formatRupiah(total);

            if (extraSulsel && extraLuarProv) {
                extraSulsel.style.display = value === 'sulsel' ? '' : 'none';
                extraLuarProv.style.display = value === 'luarprov' ? '' : 'none';
            }
        }

        if (shippingSelect) {
            shippingSelect.addEventListener('change', updateShippingUI);
            updateShippingUI();
        }

        function submitCheckoutDemo() {
            const toast = document.getElementById('checkoutToast');
            toast.classList.add('show');

            setTimeout(() => {
                toast.classList.remove('show');
            }, 4000);
        }
    </script>
@endsection

