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
                Lihat ringkasan produk yang sudah kamu tambahkan sebelum lanjut ke checkout. Saat ini masih tampilan UI (belum tersambung backend).
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="cart-layout">
                <div class="cart-main">
                    <div class="cart-card">
                        <div class="cart-card-header">
                            <h2>Daftar Produk</h2>
                            <button type="button" class="link-clear" onclick="DaengCartUI.clearCart()">
                                Hapus semua
                            </button>
                        </div>

                        <div id="cartEmptyState" class="cart-empty">
                            <div class="empty-illustration">
                                <div class="cube"></div>
                            </div>
                            <h3>Keranjang masih kosong</h3>
                            <p>Tambahkan produk dari halaman katalog untuk melihatnya di sini.</p>
                            <a href="{{ route('products') }}" class="btn btn-primary">Lihat Katalog Produk</a>
                        </div>

                        <div id="cartTableWrapper" class="cart-table-wrapper" style="display:none;">
                            <table class="cart-table">
                                <thead>
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
                                    <!-- Diisi via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

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
                        <button type="button" class="btn btn-primary" style="width:100%;" onclick="DaengCartUI.goToCheckout()">
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

    <script>
        const DaengCartUI = {
            formatRupiah(num) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(num || 0);
            },
            render() {
                if (!window.DaengCart) return;
                const items = window.DaengCart.getItems();
                const tbody = document.getElementById('cartTableBody');
                const emptyState = document.getElementById('cartEmptyState');
                const tableWrapper = document.getElementById('cartTableWrapper');
                const totalItemsEl = document.getElementById('cartTotalItems');
                const totalHargaEl = document.getElementById('cartTotalHarga');

                if (!tbody || !emptyState || !tableWrapper) return;

                if (!items.length) {
                    emptyState.style.display = '';
                    tableWrapper.style.display = 'none';
                    totalItemsEl.textContent = '0';
                    totalHargaEl.textContent = this.formatRupiah(0);
                    return;
                }

                emptyState.style.display = 'none';
                tableWrapper.style.display = '';

                let totalItems = 0;
                let totalHarga = 0;

                tbody.innerHTML = items.map(item => {
                    const qty = item.qty || 1;
                    const subtotal = (item.price || 0) * qty;
                    totalItems += qty;
                    totalHarga += subtotal;
                    return `
                        <tr>
                            <td>
                                <div class="cart-item-info">
                                    <div class="thumb">
                                        <div class="cube"></div>
                                    </div>
                                    <div>
                                        <div class="name">${item.name || 'Produk Rubik'}</div>
                                        <div class="meta">ID #${item.id}</div>
                                    </div>
                                </div>
                            </td>
                            <td>${this.formatRupiah(item.price)}</td>
                            <td>
                                <div class="qty-control">
                                    <button type="button" onclick="DaengCartUI.changeQty(${item.id}, -1)">-</button>
                                    <span>${qty}</span>
                                    <button type="button" onclick="DaengCartUI.changeQty(${item.id}, 1)">+</button>
                                </div>
                            </td>
                            <td>${this.formatRupiah(subtotal)}</td>
                            <td>
                                <button type="button" class="link-remove" onclick="DaengCartUI.removeItem(${item.id})">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');

                totalItemsEl.textContent = totalItems;
                totalHargaEl.textContent = this.formatRupiah(totalHarga);
            },
            changeQty(id, delta) {
                if (!window.DaengCart) return;
                window.DaengCart.changeQty(id, delta);
                this.render();
            },
            removeItem(id) {
                if (!window.DaengCart) return;
                window.DaengCart.remove(id);
                this.render();
            },
            clearCart() {
                if (!window.DaengCart) return;
                window.DaengCart.clear();
                this.render();
            },
            goToCheckout() {
                if (!window.DaengCart) {
                    window.location.href = "{{ route('checkout') }}";
                    return;
                }
                const first = window.DaengCart.getItems()[0];
                const checkoutUrl = "{{ url('/checkout') }}";
                if (first) {
                    window.location.href = `${checkoutUrl}?id=${first.id}`;
                } else {
                    window.location.href = checkoutUrl;
                }
            }
        };

        document.addEventListener('DOMContentLoaded', () => {
            DaengCartUI.render();
        });
    </script>
@endsection

