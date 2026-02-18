@extends('layouts.app')

@section('title', 'Keranjang — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/cart.css') }}">
@endpush

@section('content')
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Keranjang</div>
            <h1 class="page-title">Keranjang Belanja</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Lihat ringkasan produk yang sudah kamu tambahkan sebelum lanjut ke checkout.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="cart-layout">
                <div class="cart-main">
                    <div class="cart-card">
                        {{-- EMPTY STATE --}}
                        @if (!$cart || $cart->items->isEmpty())
                            <div class="cart-empty">
                                <h3>Keranjang masih kosong</h3>
                                <p>Tambahkan produk dari halaman katalog untuk melihatnya di sini.</p>
                                <a href="{{ route('products') }}" class="btn btn-primary">
                                    Lihat Katalog Produk
                                </a>
                            </div>
                        @else
                            <div class="cart-card-header">
                                <h2>Daftar Produk</h2>
                            </div>
                            <div class="cart-table-wrapper">
                                <table class="cart-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="checkAll">
                                            </th>
                                            <th>Produk</th>
                                            <th>Jumlah</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cart->items as $item)
                                            @php
                                                $subtotal = $item->unit_price * $item->quantity;
                                            @endphp
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="cart-check" data-id="{{ $item->id }}"
                                                        data-qty="{{ $item->quantity }}"
                                                        data-price="{{ $item->unit_price }}">
                                                </td>

                                                <td>
                                                    <div class="cart-item-info">
                                                        <div class="thumb">
                                                            @if ($item->product->images->count())
                                                                <img src="{{ asset('storage/' . $item->product->images->sortBy('position')->first()->image_path) }}"
                                                                    alt="{{ $item->product->name }}"
                                                                    style="width:100%;height:100%;object-fit:cover;border-radius:10px;">
                                                            @else
                                                                <div class="cube"></div>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="name">{{ $item->product->name }}</div>
                                                            <div class="meta">Rp
                                                                {{ number_format($item->unit_price, 0, ',', '.') }}</div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td>
                                                    <div class="qty-control" data-id="{{ $item->id }}">

                                                        <button type="button" class="qty-btn" data-action="dec">
                                                            −
                                                        </button>

                                                        <span class="qty-value">{{ $item->quantity }}</span>

                                                        <button type="button" class="qty-btn" data-action="inc">
                                                            +
                                                        </button>

                                                    </div>
                                                </td>

                                                <td>
                                                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="link-remove" type="submit">
                                                            <i class="fa-solid fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- SUMMARY --}}
                <aside class="cart-summary">
                    <div class="cart-card">
                        <h2>Ringkasan Keranjang</h2>
                        <div class="summary-row">
                            <span>Total item</span>
                            <span id="cartTotalItems">0</span>
                        </div>
                        <div class="summary-row">
                            <span>Total harga produk</span>
                            <span id="cartTotalHarga">Rp 0</span>
                        </div>
                        <p class="muted" style="margin:10px 0 14px;font-size:13px;">
                            Total ini belum termasuk ongkir. Ongkir akan dihitung di halaman checkout.
                        </p>
                        <button type="button" class="btn btn-primary" style="width:100%;" onclick="checkoutSelected()">
                            Lanjut ke Checkout
                        </button>
                        <a href="{{ route('products') }}" class="btn btn-secondary" style="width:100%;margin-top:8px;">
                            Kembali ke Produk
                        </a>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    {{-- ================= JS ================= --}}
    <script>
        function formatRp(num) {
            return 'Rp ' + Number(num || 0).toLocaleString('id-ID');
        }

        function updateSummary() {
            let totalItems = 0;
            let totalHarga = 0;

            document.querySelectorAll('.cart-check:checked').forEach(cb => {
                const qty = Number(cb.dataset.qty);
                const price = Number(cb.dataset.price);
                totalItems += qty;
                totalHarga += qty * price;
            });

            document.getElementById('cartTotalItems').textContent = totalItems;
            document.getElementById('cartTotalHarga').textContent = formatRp(totalHarga);
        }

        // checkbox per item
        document.querySelectorAll('.cart-check').forEach(cb => {
            cb.addEventListener('change', updateSummary);
        });

        // check all
        document.getElementById('checkAll')?.addEventListener('change', function() {
            document.querySelectorAll('.cart-check').forEach(cb => cb.checked = this.checked);
            updateSummary();
        });

        // checkout hanya item terpilih
        function checkoutSelected() {
            const selected = [];
            document.querySelectorAll('.cart-check:checked').forEach(cb => {
                selected.push(cb.dataset.id);
            });

            if (selected.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Belum Ada Produk',
                    text: 'Silakan pilih minimal 1 produk untuk melanjutkan ke checkout.',
                    confirmButtonColor: '#E53935'
                });

                return;
            }


            window.location.href = `{{ route('checkout') }}?items=${selected.join(',')}`;
        }
    </script>

    <script>
        document.querySelectorAll('.qty-btn').forEach(btn => {

            btn.addEventListener('click', async function() {

                const wrapper = this.closest('.qty-control');
                const itemId = wrapper.dataset.id;
                const action = this.dataset.action;
                const qtySpan = wrapper.querySelector('.qty-value');

                try {

                    const res = await fetch(
                        `{{ url('keranjang/item') }}/${itemId}/qty`, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                action
                            })
                        }
                    );

                    const data = await res.json();

                    // ❌ Jika error dari backend
                    if (!res.ok) {

                        Swal.fire({
                            icon: 'warning',
                            // title: 'Perhatian',
                            text: data.message || 'Tidak dapat mengupdate jumlah.',
                            confirmButtonColor: '#E53935',
                            cancelButtonColor: '#fff',
                            customClass: {
                                confirmButton: 'btn btn-primary',
                                cancelButton: 'btn btn-secondary'
                            }
                        });

                        return;
                    }

                    // ✅ Update quantity UI
                    qtySpan.textContent = data.quantity;

                    // Update summary checkbox dataset
                    const checkbox = document.querySelector(`.cart-check[data-id="${itemId}"]`);
                    if (checkbox) {
                        checkbox.dataset.qty = data.quantity;
                    }

                    updateSummary();

                } catch (err) {

                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal mengupdate jumlah produk.',
                        confirmButtonColor: '#E53935',
                        cancelButtonColor: '#fff',
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-secondary'
                        }
                    });

                }

            });

        });
    </script>





@endsection
