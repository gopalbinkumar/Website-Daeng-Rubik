@extends('layouts.app')

@section('title', 'Checkout Produk — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}">
@endpush

@section('content')
    @php
        $rupiah = fn($n) => 'Rp ' . number_format($n, 0, ',', '.');
    @endphp

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Produk &gt; Checkout</div>
            <h1 class="page-title">Checkout Produk</h1>
            <p class="muted">
                Periksa kembali detail pesanan dan isi data penerima dengan benar.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="checkout-layout">

                <form id="checkoutForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="items" id="checkoutItems">
                    <input type="hidden" name="shipping_zone" id="shippingZone">

                    {{-- ================= LEFT ================= --}}
                    <div class="checkout-form-column">

                        <div class="checkout-card">
                            <h2 class="checkout-card-title">Data Penerima</h2>

                            <div class="form-group">
                                <label class="form-label">Nama Penerima *</label>
                                <input type="text" name="receiver_name" class="form-input"
                                    value="{{ old('receiver_name', $user->name ?? '') }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp *</label>
                                <input type="tel" name="receiver_phone" class="form-input"
                                    value="{{ old('receiver_phone', $user->whatsapp ?? '') }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Alamat Lengkap *</label>
                                <textarea name="receiver_address" class="form-input" rows="4" required></textarea>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kode Pos *</label>
                                <input type="text" name="receiver_postal_code" class="form-input" required>
                            </div>
                        </div>

                        <div class="checkout-card">
                            <h2 class="checkout-card-title">Pengiriman</h2>

                            <div class="form-group">
                                <label class="form-label">Lokasi Pengiriman *</label>
                                <select id="shippingOption" class="form-input">
                                    <option value="makassar">Kota Makassar (Rp15.000)</option>
                                    <option value="sulsel">Luar Kota Sulsel (Rp25.000)</option>
                                    <option value="luar_provinsi">Luar Provinsi (Rp40.000)</option>
                                </select>
                            </div>

                            <div id="shippingExtraSulsel" class="form-group" style="display:none;">
                                <label class="form-label">Nama Kota / Kabupaten</label>
                                <input type="text" name="shipping_city" id="shippingCitySulsel" class="form-input"
                                    disabled>
                            </div>

                            <div id="shippingExtraLuarProv" style="display:none;">
                                <div class="form-group">
                                    <label class="form-label">Nama Provinsi</label>
                                    <input type="text" name="shipping_province" id="shippingProvince" class="form-input"
                                        disabled>
                                </div>

                                <div class="form-group">
                                    <label class="form-label">Nama Kota / Kabupaten</label>
                                    <input type="text" name="shipping_city" id="shippingCityLuarProv" class="form-input"
                                        disabled>
                                </div>
                            </div>


                            <div class="shipping-row">
                                <span>Ongkir</span>
                                <span id="shippingOngkir">{{ $rupiah($ongkir) }}</span>
                            </div>
                        </div>

                        <div class="checkout-card">
                            <h2 class="checkout-card-title">Upload Bukti Pembayaran</h2>

                            <div class="upload-area" id="uploadArea"
                                onclick="document.getElementById('buktiTransfer').click()">
                                <p id="uploadText">Klik untuk upload bukti transfer</p>
                            </div>

                            <input type="file" name="payment_proof" id="buktiTransfer" accept="image/*" hidden>

                        </div>

                    </div>
                </form>

                {{-- ================= RIGHT ================= --}}
                <aside class="checkout-summary-column">

                    <div class="checkout-card">
                        <h2 class="checkout-card-title">Ringkasan Produk</h2>

                        @foreach ($checkoutItems as $item)
                            <div class="summary-product">
                                <div class="summary-thumb">
                                    @if ($item->product->images->count())
                                        <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}"
                                            style="width:60px;height:60px;object-fit:cover;border-radius:12px;">
                                    @else
                                        <div class="cube"></div>
                                    @endif
                                </div>
                                <div class="summary-info">
                                    <p class="summary-name">{{ $item->product->name }}</p>
                                    <p class="summary-price">
                                        {{ $rupiah($item->unit_price * $item->quantity) }}
                                        <span style="font-size:13px;">× {{ $item->quantity }}</span>
                                    </p>
                                </div>
                            </div>
                        @endforeach

                        <div class="summary-totals">
                            <div class="row">
                                <span>Subtotal</span>
                                <span>{{ $rupiah($subtotal) }}</span>
                            </div>
                            <div class="row">
                                <span>Ongkir</span>
                                <span id="summaryOngkir">{{ $rupiah($ongkir) }}</span>
                            </div>
                            <div class="row total">
                                <span>Total</span>
                                <span id="summaryTotal">{{ $rupiah($total) }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="checkout-card">
                        <h2 class="checkout-card-title">Informasi Pembayaran</h2>
                        <div class="payment-info">
                            <div class="row"><span>Bank</span><span>BCA</span></div>
                            <div class="row"><span>No Rek</span><span>1234567890</span></div>
                            <div class="row"><span>Atas Nama</span><span>Daeng Rubik</span></div>
                        </div>
                    </div>

                    <div class="checkout-card actions-card">
                        <button type="button" class="btn btn-primary" onclick="submitCheckoutDemo()">Konfirmasi</button>
                    </div>

                </aside>
            </div>
        </div>
        <!-- Modal Alert (Reuse Event Modal) -->
        <div id="eventModalBackdrop" class="modal-backdrop" onclick="closeEventModal()"></div>

        <div id="eventModal" class="event-modal" role="dialog" aria-modal="true">

            <div id="eventModalContent" class="event-modal-content">
                <!-- Diisi via JavaScript -->
            </div>
        </div>

    </section>

    <script>
        const ship = document.getElementById('shippingOption');

        const extraSulsel = document.getElementById('shippingExtraSulsel');
        const extraLuarProv = document.getElementById('shippingExtraLuarProv');

        const citySulsel = document.querySelector('#shippingExtraSulsel input[name="shipping_city"]');
        const cityLuarProv = document.querySelector('#shippingExtraLuarProv input[name="shipping_city"]');
        const provinceInput = document.querySelector('#shippingExtraLuarProv input[name="shipping_province"]');

        const shippingZoneInput = document.getElementById('shippingZone');

        function rupiah(n) {
            return 'Rp ' + n.toLocaleString('id-ID');
        }

        function updateShip() {
            let ongkir = 15000;

            extraSulsel.style.display = 'none';
            extraLuarProv.style.display = 'none';

            citySulsel.disabled = true;
            cityLuarProv.disabled = true;
            provinceInput.disabled = true;

            citySulsel.value = '';
            cityLuarProv.value = '';
            provinceInput.value = '';

            if (ship.value === 'makassar') ongkir = 15000;

            if (ship.value === 'sulsel') {
                ongkir = 25000;
                extraSulsel.style.display = 'block';
                citySulsel.disabled = false;
            }

            if (ship.value === 'luar_provinsi') {
                ongkir = 40000;
                extraLuarProv.style.display = 'block';
                cityLuarProv.disabled = false;
                provinceInput.disabled = false;
            }

            document.getElementById('shippingOngkir').textContent = rupiah(ongkir);
            document.getElementById('summaryOngkir').textContent = rupiah(ongkir);
            document.getElementById('summaryTotal').textContent =
                rupiah({{ $subtotal }} + ongkir);

            shippingZoneInput.value = ship.value;
        }

        ship.addEventListener('change', updateShip);
        updateShip();

        /* ================= MODAL STATES ================= */

        function openModal() {
            document.getElementById('eventModalBackdrop').classList.add('open');
            document.getElementById('eventModal').classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeEventModal() {
            document.getElementById('eventModalBackdrop').classList.remove('open');
            document.getElementById('eventModal').classList.remove('open');
            document.body.style.overflow = '';
        }

        function showLoadingModal() {
            const content = document.getElementById('eventModalContent');

            content.innerHTML = `
            <div class="event-modal-body">
                <div class="modal-spinner"></div>
                <p style="font-size:15px;color:#555">
                    Mengirim checkout...
                </p>
            </div>
        `;

            openModal();
        }

        function showSuccessModal(message) {
            const content = document.getElementById('eventModalContent');

            content.innerHTML = `
        <div class="event-modal-body" style="text-align:center">
            <i class="fa-solid fa-circle-check"
               style="font-size:48px;color:#2e7d32;margin-bottom:12px"></i>

            <h3 class="event-modal-title">Berhasil</h3>

            <p style="font-size:15px;color:#2e7d32">
                ${message}
            </p>

            <p style="font-size:13px;color:#777;margin-top:8px">
                Mengalihkan ke halaman transaksi...
            </p>
            </div>
            `;

            // ⏱️ tampilkan modal sebentar lalu redirect
            setTimeout(() => {
                window.location.href = '/my-transactions';
            }, 1500); // 1.5 detik
        }


        function showErrorModal(message) {
            const content = document.getElementById('eventModalContent');

            content.innerHTML = `
                <div class="event-modal-body" style="text-align:center">
                    <i class="fa-solid fa-circle-exclamation"
                    style="font-size:48px;color:#e53935;margin-bottom:12px"></i>

                    <h3 class="event-modal-title">Perhatian</h3>

                    <p style="font-size:15px;color:#e53935">
                        ${message}
                    </p>

                    <div class="event-modal-actions">
                        <button class="btn btn-primary" onclick="closeEventModal()">
                            OK
                        </button>
                    </div>
                </div>
            `;

            openModal();
        }


        /* ================= SUBMIT ================= */

        function submitCheckoutDemo() {
            const items = new URLSearchParams(window.location.search).get('items');
            if (!items) {
                showErrorModal('Tidak ada produk dipilih');
                return;
            }

            document.getElementById('checkoutItems').value = items;
            const form = document.getElementById('checkoutForm');

            // ================= 1️⃣ DATA PENERIMA =================
            const receiverFields = [
                'receiver_name',
                'receiver_phone',
                'receiver_address',
                'receiver_postal_code'
            ];

            for (const name of receiverFields) {
                const field = form.querySelector(`[name="${name}"]`);
                if (!field || !field.value.trim()) {
                    showErrorModal('Data penerima belum lengkap');
                    return;
                }
            }

            // ================= 2️⃣ DATA PENGIRIMAN =================
            const shippingZone = document.getElementById('shippingZone').value;

            if (!shippingZone) {
                showErrorModal('Data pengiriman belum lengkap');
                return;
            }

            if (shippingZone === 'sulsel' && !citySulsel.value.trim()) {
                showErrorModal('Data pengiriman belum lengkap');
                return;
            }

            if (
                shippingZone === 'luar_provinsi' &&
                (!cityLuarProv.value.trim() || !provinceInput.value.trim())
            ) {
                showErrorModal('Data pengiriman belum lengkap');
                return;
            }

            // ================= 3️⃣ BUKTI PEMBAYARAN =================
            const fileInput = document.getElementById('buktiTransfer');
            if (!fileInput.files || fileInput.files.length === 0) {
                showErrorModal('Silakan upload bukti pembayaran terlebih dahulu');
                return;
            }

            // ================= 4️⃣ SUBMIT =================
            const formData = new FormData(form);

            // ⏳ tampilkan loading modal
            showLoadingModal();

            fetch('{{ route('checkout.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(async res => {
                    if (!res.ok) {
                        const error = await res.json();
                        throw error;
                    }
                    return res.json();
                })
                .then(() => {
                    showSuccessModal('Checkout berhasil');
                })
                .catch(err => {
                    if (err.errors) {
                        showErrorModal(Object.values(err.errors)[0][0]);
                    } else {
                        showErrorModal('Terjadi kesalahan saat checkout');
                    }
                });
        }
    </script>


    <script>
        const fileInput = document.getElementById('buktiTransfer');
        const uploadArea = document.getElementById('uploadArea');
        const uploadText = document.getElementById('uploadText');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;

            if (!file.type.startsWith('image/')) {
                alert('File harus berupa gambar');
                this.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                uploadArea.innerHTML = `
            <img 
                src="${e.target.result}"
                alt="Preview Bukti Transfer"
                style="
                    width:100%;
                    height:100%;
                    object-fit:cover;
                    border-radius:12px;
                "
            >
        `;
            };

            reader.readAsDataURL(file);
        });
    </script>



@endsection
