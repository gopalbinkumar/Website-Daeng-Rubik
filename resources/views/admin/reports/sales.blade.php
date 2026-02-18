@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
    @php
        $statusMap = [
            'pending' => [
                'label' => 'Menunggu',
                'class' => 'badge-warning',
            ],
            'paid' => [
                'label' => 'Diverifikasi',
                'class' => 'badge-success',
            ],
            'failed' => [
                'label' => 'Ditolak',
                'class' => 'badge-danger',
            ],
        ];
    @endphp


    <div class="page-header">
        <h2 class="page-title">Laporan Penjualan</h2>
    </div>

    <div class="table-wrapper">

        {{-- TOOLBAR (TETAP) --}}
        <form method="GET" action="{{ route('admin.reports.sales') }}" id="filterForm">
            <div class="table-toolbar">
                <div class="report-filters">
                    <div class="filter-pills">
                        <button type="button" class="pill {{ request('period', 'daily') === 'daily' ? 'active' : '' }}"
                            data-period="daily">
                            Semua
                        </button>

                        <button type="button" class="pill {{ request('period') === 'monthly' ? 'active' : '' }}"
                            data-period="monthly">
                            Bulanan
                        </button>

                        <button type="button" class="pill {{ request('period') === 'yearly' ? 'active' : '' }}"
                            data-period="yearly">
                            Tahunan
                        </button>

                        <button type="button" class="pill {{ request('period') === 'custom' ? 'active' : '' }}"
                            data-period="custom">
                            Custom
                        </button>


                        <input type="hidden" name="period" id="periodInput" value="{{ request('period', 'daily') }}">
                    </div>


                    <div class="filter-row">
                        <div class="date-range" id="customDateRange" style="display:none;">
                            <input type="date" name="start_date" class="search-input" value="{{ request('start_date') }}"
                                style="max-width:180px;">
                            <span style="font-size:13px;color:var(--admin-text-muted);">sampai</span>
                            <input type="date" name="end_date" class="search-input" value="{{ request('end_date') }}"
                                style="max-width:180px;">
                        </div>
                        <select name="status" class="filter-select">
                            <option value="">Status: Semua</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu
                            </option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Terverifikasi
                            </option>
                            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Ditolak</option>
                            </option>
                        </select>
                        <select name="product" class="filter-select">
                            <option value="">Produk: Semua</option>
                            @foreach ($products as $product)
                                <option value="{{ $product }}"
                                    {{ request('product') == $product ? 'selected' : '' }}>
                                    {{ $product }}
                                </option>
                            @endforeach
                        </select>
                        <input type="text" name="search" class="search-input filter-search"
                            placeholder="Search kode transaksi..." value="{{ request('search') }}">
                    </div>
                </div>

                <div class="report-actions">
                    <a href="{{ route('admin.reports.sales') }}" class="btn btn-secondary"><i
                            class="fa-solid fa-rotate-left"></i> Reset</a>
                    <button class="btn btn-primary" type="submit"><i class="fa-solid fa-filter"></i> Filter</button>
                    {{-- <button class="btn btn-secondary" type="button" onclick="openModal('modalManualTransaction')">
                        <i class="fa-solid fa-plus"></i> Tambah Transaksi
                    </button> --}}
                    <button type="button" class="btn btn-secondary" onclick="openPdfPreview()">
                        <i class="fa-solid fa-file-pdf"></i> Export PDF
                    </button>


                </div>
            </div>
        </form>
        <div id="reportContent">

            {{-- SUMMARY (DINAMIS, UI TETAP) --}}
            <div class="report-summary">
                <div class="summary-item">
                    <span class="summary-label">Total Pendapatan</span>
                    <span class="summary-value">
                        Rp {{ number_format($totalRevenue, 0, ',', '.') }}
                    </span>
                    <span class="summary-caption">Berdasarkan filter saat ini</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Transaksi Berhasil</span>
                    <span class="summary-value">{{ $totalTransaction }}</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Rata-rata Pendapatan</span>
                    <span class="summary-value">
                        Rp {{ number_format($avgTransaction, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- TABLE (UI TETAP, DATA DINAMIS) --}}
            <table class="table">
                <thead>
                    <tr>
                        <th>Kode Transaksi</th>
                        <th>Nama Pembeli</th>
                        <th>Tanggal Transaksi</th>
                        <th>Total Pembayaran</th>
                        <th>Ongkir</th>
                        <th>Status Pembayaran</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transactions as $trx)
                        <tr>
                            <td><strong>{{ $trx->code }}</strong></td>
                            <td>{{ $trx->receiver_name }}</td>
                            <td>{{ $trx->created_at->format('d M Y, H:i') }}</td>
                            <td><strong>Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</strong></td>
                            <td>Rp {{ number_format($trx->shipping_cost, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $status = strtolower($trx->status);
                                    $currentStatus = $statusMap[$status] ?? [
                                        'label' => ucfirst($status),
                                        'class' => 'badge-secondary',
                                    ];
                                @endphp

                                <span class="badge {{ $currentStatus['class'] }}">
                                    {{ $currentStatus['label'] }}
                                </span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    {{-- View bukti --}}
                                    <button class="btn btn-icon btn-secondary"
                                        onclick="openModal('modalViewTransfer-{{ $trx->id }}')" title="Lihat Bukti">
                                        <i class="fa-solid fa-receipt"></i>
                                    </button>

                                    {{-- Detail transaksi --}}
                                    <button class="btn btn-icon btn-primary"
                                        onclick="openModal('modalTransactionDetail-{{ $trx->id }}')"
                                        title="Detail Transaksi">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>

                        {{-- MODAL VIEW TRANSFER (UI TETAP) --}}
                        <div id="modalViewTransfer-{{ $trx->id }}" class="modal">
                            <div class="modal-header">
                                <h3 class="modal-title">Bukti Transfer</h3>
                                <button class="modal-close"
                                    onclick="closeModal('modalViewTransfer-{{ $trx->id }}')">×</button>
                            </div>
                            <div class="modal-body">
                                <div style="display:flex;flex-direction:column;gap:12px;">

                                    {{-- GAMBAR --}}
                                    <img src="{{ asset('storage/' . $trx->payment_proof_path) }}"
                                        style="max-width:100%;border-radius:8px;border:1px solid var(--admin-border);">

                                    {{-- GRID INFO --}}
                                    <div
                                        style="
                                                display:grid;
                                                grid-template-columns: 1fr 220px;
                                                gap:16px;
                                                font-size:14px;
                                                align-items:stretch;
                                            ">
                                        {{-- KIRI --}}
                                        <div style="display:flex;flex-direction:column;gap:8px;">
                                            <div>
                                                <strong>Nama Pembeli:</strong><br>
                                                {{ $trx->receiver_name }}
                                            </div>

                                            <div>
                                                <strong>Produk:</strong>
                                                <ol style="margin:4px 0 0 16px;padding:0;">
                                                    @foreach ($trx->items as $item)
                                                        <li>
                                                            {{ $item->product_name }}
                                                            (x{{ $item->quantity }})
                                                            — Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            </div>

                                            <div>
                                                <strong>Biaya Pengiriman</strong><br>
                                                <span>
                                                    Rp {{ number_format($trx->shipping_cost, 0, ',', '.') }} </span>
                                            </div>
                                        </div>

                                        {{-- KANAN --}}
                                        <div
                                            style="
                                                    display:flex;
                                                    flex-direction:column;
                                                    justify-content:flex-end;
                                                    text-align:right;
                                                ">

                                            <div>
                                                <strong>Total Pembayaran</strong><br>
                                                <span style="font-size:16px;">
                                                    Rp {{ number_format($trx->total_amount, 0, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">

                                {{-- Jika PENDING → tampilkan kedua button --}}
                                @if ($trx->status === 'pending')
                                    {{-- Tolak --}}
                                    <form method="POST" action="{{ route('admin.transactions.reject', $trx->id) }}"
                                        class="form-confirm" data-title="Tolak Pembayaran?"
                                        data-text="Yakin ingin menolak pembayaran ini?" data-confirm="Ya, Tolak"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            Tolak
                                        </button>
                                    </form>

                                    {{-- Verifikasi --}}
                                    <form method="POST" action="{{ route('admin.transactions.verify', $trx->id) }}"
                                        class="form-confirm" data-title="Verifikasi Pembayaran?"
                                        data-text="Pastikan bukti transfer sudah valid." data-confirm="Ya, Verifikasi">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            Verifikasi
                                        </button>
                                    </form>

                                    {{-- Jika PAID → hanya Tolak --}}
                                @elseif ($trx->status === 'paid')
                                    <form method="POST" action="{{ route('admin.transactions.reject', $trx->id) }}"
                                        class="form-confirm" data-title="Batalkan Pembayaran?"
                                        data-text="Pembayaran sudah diverifikasi. Yakin ingin membatalkan?"
                                        data-confirm="Ya, Tolak" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            Tolak
                                        </button>
                                    </form>

                                    {{-- Jika FAILED → hanya Verifikasi --}}
                                @elseif ($trx->status === 'failed')
                                    <form method="POST" action="{{ route('admin.transactions.verify', $trx->id) }}"
                                        class="form-confirm" data-title="Verifikasi Ulang Pembayaran?"
                                        data-text="Yakin ingin memverifikasi ulang pembayaran ini?"
                                        data-confirm="Ya, Verifikasi">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            Verifikasi
                                        </button>
                                    </form>
                                @endif
                            </div>
                            {{-- <div class="modal-footer">
                                @if ($trx->status === 'pending')
                                    <form method="POST" action="{{ route('admin.transactions.reject', $trx->id) }}"
                                        class="form-confirm" data-title="Tolak Pembayaran?"
                                        data-text="Yakin ingin menolak pembayaran ini?" data-confirm="Ya, Tolak"
                                        style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">
                                            Tolak
                                        </button>
                                    </form>
                                @endif

                                @if ($trx->status === 'pending')
                                    <form method="POST" action="{{ route('admin.transactions.verify', $trx->id) }}"
                                        class="form-confirm" data-title="Verifikasi Pembayaran?"
                                        data-text="Pastikan bukti transfer sudah valid." data-confirm="Ya, Verifikasi">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            Verifikasi
                                        </button>
                                    </form>
                                @endif
                            </div> --}}
                        </div>

                        <div id="modalTransactionDetail-{{ $trx->id }}" class="modal">
                            <div class="modal-header">
                                <h3 class="modal-title">Detail Transaksi</h3>
                                <button class="modal-close"
                                    onclick="closeModal('modalTransactionDetail-{{ $trx->id }}')">×</button>
                            </div>

                            <div class="modal-body">
                                <div style="display:flex;flex-direction:column;gap:12px;">

                                    {{-- GRID INFO --}}
                                    <div
                                        style="
                                        display:grid;
                                        grid-template-columns: 1fr 220px;
                                        gap:16px;
                                        font-size:14px;
                                    ">
                                        {{-- KIRI --}}
                                        <div style="display:flex;flex-direction:column;gap:8px;">
                                            <div>
                                                <strong>Nama Pembeli:</strong><br>
                                                {{ $trx->receiver_name }}
                                            </div>

                                            <div>
                                                <strong>Produk:</strong>
                                                <ol style="margin:4px 0 0 16px;padding:0;">
                                                    @foreach ($trx->items as $item)
                                                        <li>
                                                            {{ $item->product_name }} (x{{ $item->quantity }})
                                                        </li>
                                                    @endforeach
                                                </ol>
                                            </div>

                                            <div>
                                                <strong>No WhatsApp:</strong><br>
                                                {{ $trx->receiver_phone }}
                                            </div>

                                            <div>
                                                <strong>Alamat Lengkap:</strong><br>
                                                {{ $trx->receiver_address }}<br>
                                                Kota/Kab {{ $trx->shipping_city }}<br>
                                                Prov {{ $trx->shipping_province }}
                                            </div>

                                            <div>
                                                <strong>Kode Pos:</strong><br>
                                                {{ $trx->receiver_postal_code }}
                                            </div>

                                            <div>
                                                <strong>Tanggal Transaksi:</strong><br>
                                                {{ $trx->created_at->format('d M Y, H:i') }}
                                            </div>
                                        </div>

                                        {{-- KANAN --}}
                                        <div
                                            style="
                                            display:flex;
                                            flex-direction:column;
                                            justify-content:flex-start;
                                            text-align:right;
                                        ">
                                            <div>
                                                @php
                                                    $status = strtolower($trx->status);
                                                    $currentStatus = $statusMap[$status] ?? [
                                                        'label' => ucfirst($status),
                                                        'class' => 'badge-secondary',
                                                    ];
                                                @endphp

                                                <span class="badge {{ $currentStatus['class'] }}">
                                                    {{ $currentStatus['label'] }}
                                                </span>

                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary"
                                    onclick="closeModal('modalTransactionDetail-{{ $trx->id }}')">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>

            {{-- PAGINATION (UI TETAP) --}}
            <x-admin-pagination :paginator="$transactions" />
        </div>

        <div id="modalManualTransaction" class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Tambah Transaksi Manual</h3>
                <button class="modal-close" onclick="closeModal('modalManualTransaction')">×</button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label class="form-label">Nama Produk <span class="required">*</span></label>
                        <input type="text" class="form-input" placeholder="Contoh: Rubik 3x3 Magnetic">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Sumber Transaksi <span class="required">*</span></label>
                            <select class="form-select">
                                <option>Website</option>
                                <option>Shopee</option>
                                <option>Tokopedia</option>
                                <option>TikTok Shop</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Transaksi <span class="required">*</span></label>
                            <input type="date" class="form-input">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Nama Pembeli <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="Nama lengkap pembeli">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jumlah Pembelian <span class="required">*</span></label>
                            <input type="number" class="form-input" placeholder="1">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Total Pembayaran <span class="required">*</span></label>
                            <input type="number" class="form-input" placeholder="90000">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ongkir</label>
                            <input type="number" class="form-input" placeholder="15000">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Metode Pembayaran</label>
                            <select class="form-select">
                                <option>Transfer Bank</option>
                                <option>COD</option>
                                <option>E-Wallet</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status Pembayaran</label>
                            <select class="form-select">
                                <option>Terverifikasi</option>
                                <option>Menunggu</option>
                                <option>Gagal</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea class="form-textarea" placeholder="Contoh: Pesanan dari Shopee, sudah dikirim tanggal 12 Jan."></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('modalManualTransaction')">Batal</button>
                <button class="btn btn-primary">Simpan Transaksi</button>
            </div>
        </div>

    </div>

    {{-- PDF PREVIEW MODAL --}}
    <div id="pdfPreviewModal" style=" display:none; position:fixed; inset:0; background:rgba(0,0,0,.55); z-index:9999;">
        <div
            style="background:#fff; width:65%; height:90%; margin:5vh auto; border-radius:8px; display:flex; flex-direction:column;  overflow:hidden;">

            {{-- HEADER --}}
            <div
                style="
        position:relative;
        height:34px;
        border-bottom:1px solid #e5e7eb;
        background:#fff;
    ">

                <button onclick="closePdfPreview()" title="Tutup"
                    style="
            position:absolute;
            top:6px;
            right:8px;
            width:22px;
            height:22px;
            border:none;
            background:transparent;
            font-size:20px;
            line-height:1;
            cursor:pointer;
            color:#555;
        ">
                    ×
                </button>
            </div>




            {{-- BODY --}}
            <div style="flex:1;">
                <object id="pdfObject" type="application/pdf" style="width:100%; height:100%;">
                    <p>
                        Browser tidak mendukung preview PDF.
                        <a id="pdfFallback" target="_blank">Buka PDF</a>
                    </p>
                </object>
            </div>
        </div>
    </div>

    {{-- MODAL MANUAL (TETAP, BELUM AKTIF) --}}
    @includeIf('admin.reports.partials.modal-manual-transaction')
    <script>
        document.querySelectorAll('.pill').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.pill').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                document.getElementById('periodInput').value = this.dataset.period;
                document.getElementById('filterForm').submit();
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const pills = document.querySelectorAll('.pill');
            const periodInput = document.getElementById('periodInput');
            const dateRange = document.getElementById('customDateRange');

            function updateDateRange(period) {
                dateRange.style.display = (period === 'custom') ? 'flex' : 'none';
            }

            // initial load (refresh / back)
            updateDateRange(periodInput.value);

            pills.forEach(pill => {
                pill.addEventListener('click', () => {
                    const period = pill.dataset.period;

                    // active state
                    pills.forEach(p => p.classList.remove('active'));
                    pill.classList.add('active');

                    // set value
                    periodInput.value = period;

                    // toggle date range
                    updateDateRange(period);
                });
            });
        });
    </script>
    <script>
        function openPdfPreview() {
            const form = document.getElementById('filterForm');

            const params = new URLSearchParams(new FormData(form));
            params.append('export', 'pdf');

            const url = "{{ route('admin.reports.sales') }}?" + params.toString();

            document.getElementById('pdfObject').data = url;
            document.getElementById('pdfFallback').href = url;

            document.getElementById('pdfPreviewModal').style.display = 'block';
            document.body.style.overflow = 'hidden';
        }

        function closePdfPreview() {
            document.getElementById('pdfPreviewModal').style.display = 'none';
            document.body.style.overflow = '';
        }
    </script>

@endsection
