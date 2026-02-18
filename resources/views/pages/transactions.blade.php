@extends('layouts.app')

@section('title', 'Transaksi Saya — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/transactions.css') }}">
@endpush

@section('content')

    {{-- ================= PAGE HEAD ================= --}}
    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Transaksi</div>
            <h1 class="page-title">Transaksi Saya</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Riwayat transaksi pembelian produk rubik yang pernah kamu lakukan.
            </p>
        </div>
    </section>

    {{-- ================= MAIN SECTION ================= --}}
    <section class="section" style="padding-top:22px;">
        <div class="container">

            {{-- SORT / SEARCH BAR --}}
            <form method="GET">
                <div class="sortbar">
                    <div class="search-wrapper">
                        <input type="text" name="search" class="search-input" placeholder="Cari kode transaksi atau nama produk"
                            value="{{ request('search') }}">

                        <button type="submit" class="search-btn">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>

                    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                        <select class="select" name="status" onchange="this.form.submit()">
                            <option value="">Status: Semua</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi
                            </option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        </select>
                    </div>
                </div>
            </form>

            {{-- ================= TRANSACTION LIST ================= --}}
            <div class="grid-3">
                @forelse ($transactions as $trx)
                    <article class="card prod">
                        <div class="prod-body">

                            <p class="prod-name">
                                {{ $trx->code }}
                            </p>

                            @php
                                $statusText = match ($trx->status) {
                                    'paid' => 'Diverifikasi',
                                    'failed' => 'Ditolak',
                                    'pending' => 'Menunggu',
                                    default => ucfirst($trx->status),
                                };

                                $statusClass = match ($trx->status) {
                                    'paid' => 'badge-success',
                                    'failed' => 'badge-danger',
                                    'pending' => 'badge-warning',
                                    default => 'badge-secondary',
                                };
                            @endphp

                            <span class="badge {{ $statusClass }}">
                                {{ $statusText }}
                            </span>


                            <div class="prod-actions">
                                <button class="btn btn-primary" type="button" style="flex:1"
                                    onclick="openTransactionModal('{{ $trx->code }}')">
                                    Lihat Detail
                                </button>
                            </div>

                        </div>
                    </article>
                @empty
                    <p class="muted">Belum ada transaksi.</p>
                @endforelse
            </div>

            {{-- ================= PAGINATION ================= --}}
            @if ($transactions->total() > 9)
                <div class="pagination" aria-label="Pagination">

                    {{-- Prev --}}
                    @if ($transactions->onFirstPage())
                        <span class="page-chip disabled">‹</span>
                    @else
                        <a href="{{ $transactions->previousPageUrl() }}" class="page-chip">‹</a>
                    @endif

                    {{-- Page Numbers --}}
                    @for ($i = 1; $i <= $transactions->lastPage(); $i++)
                        @if ($i == $transactions->currentPage())
                            <span class="page-chip active">{{ $i }}</span>
                        @else
                            <a href="{{ $transactions->url($i) }}" class="page-chip">{{ $i }}</a>
                        @endif
                    @endfor

                    {{-- Next --}}
                    @if ($transactions->hasMorePages())
                        <a href="{{ $transactions->nextPageUrl() }}" class="page-chip">›</a>
                    @else
                        <span class="page-chip disabled">›</span>
                    @endif

                </div>
            @endif


        </div>
    </section>

    {{-- ================= MODAL ================= --}}
    <div id="transactionModalBackdrop" class="modal-backdrop" aria-hidden="true" onclick="closeTransactionModal()"></div>

    <div id="transactionModal" class="product-modal" role="dialog" aria-label="Detail Transaksi" aria-modal="true">

        <button class="modal-close" onclick="closeTransactionModal()">✕</button>

        <div id="transactionModalContent" class="product-modal-content"></div>
    </div>

    {{-- ================= PREPARE DATA (ANTI ERROR) ================= --}}
    @php
        $transactionsData = [];

        foreach ($transactions as $trx) {
            $items = [];

            foreach ($trx->items as $item) {
                $items[] = [
                    'name' => $item->product_name,
                    'qty' => $item->quantity,
                ];
            }

            $transactionsData[$trx->code] = [
                'code' => $trx->code,
                'status' => ucfirst($trx->status),
                'name' => $trx->receiver_name,
                'phone' => $trx->receiver_phone,
                'address' => $trx->receiver_address,
                'proof_image' => $trx->payment_proof_path
                    ? asset('storage/' . $trx->payment_proof_path)
                    : asset('assets/img/placeholder-product.png'),
                'items' => $items,
            ];
        }
    @endphp

    {{-- ================= JS DATA ================= --}}
    <script>
        const transactionsData = {!! json_encode($transactionsData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!};
    </script>

    {{-- ================= MODAL SCRIPT ================= --}}
    <script>
        function openTransactionModal(code) {
            const trx = transactionsData[code];
            if (!trx) return;

            const modal = document.getElementById('transactionModal');
            const backdrop = document.getElementById('transactionModalBackdrop');
            const content = document.getElementById('transactionModalContent');

            let itemsHtml = '';
            trx.items.forEach(item => {
                itemsHtml += `<li>${item.name} (x${item.qty})</li>`;
            });

            let badgeClass = 'badge-secondary';
            let badgeText = trx.status;

            if (trx.status.toLowerCase() === 'paid') {
                badgeClass = 'badge-success';
                badgeText = 'Diverifikasi';
            } else if (trx.status.toLowerCase() === 'failed') {
                badgeClass = 'badge-danger';
                badgeText = 'Ditolak';
            } else if (trx.status.toLowerCase() === 'pending') {
                badgeClass = 'badge-warning';
                badgeText = 'Menunggu';
            }


            content.innerHTML = `
        <div class="product-modal-image">
            <img src="${trx.proof_image}"
                style="width:100%;max-width:360px;
                object-fit:contain;
                border:6px solid var(--line);
                margin:0 auto;
                display:block;">
        </div>

        <div class="product-modal-body">
            <h2 class="product-modal-title">${trx.code}</h2>

            <span class="badge ${badgeClass}">${badgeText}</span>

            <div class="product-modal-description">
                <h3>Detail Pengiriman</h3>
                <p><b>Nama:</b> ${trx.name}</p>
                <p><b>No. WhatsApp:</b> ${trx.phone}</p>
                <p><b>Alamat:</b> ${trx.address}</p>
            </div>

            <div class="product-modal-specs">
                <h3>Produk Dikirim</h3>
                <ul style="margin:6px 0 0 16px;padding:0">
                    ${itemsHtml}
                </ul>
            </div>
        </div>
    `;

            modal.classList.add('open');
            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.remove('open');
            document.getElementById('transactionModalBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeTransactionModal();
        });
    </script>

@endsection
