@extends('admin.layouts.app')

@section('title', 'Laporan Penjualan')
@section('page-title', 'Laporan Penjualan')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Laporan Penjualan</h2>
    </div>

    <div class="table-wrapper">
        <div class="table-toolbar">
            <div class="report-filters">
                <div class="filter-pills">
                    <button class="pill active">Harian</button>
                    <button class="pill">Bulanan</button>
                    <button class="pill">Tahunan</button>
                </div>
                <div class="filter-row">
                    <div class="date-range">
                        <input type="date" class="search-input" style="max-width:180px;">
                        <span style="font-size:13px;color:var(--admin-text-muted);">sampai</span>
                        <input type="date" class="search-input" style="max-width:180px;">
                    </div>
                    <select class="filter-select">
                        <option>Produk: Semua</option>
                        <option>Rubik 3x3 Magnetic</option>
                        <option>Rubik 4x4 Pro</option>
                        <option>Megaminx</option>
                    </select>
                    <select class="filter-select">
                        <option>Status: Semua</option>
                        <option>Menunggu</option>
                        <option>Terverifikasi</option>
                        <option>Gagal</option>
                    </select>
                </div>
            </div>
            <div class="report-actions">
                <button class="btn btn-secondary">Reset</button>
                <button class="btn btn-primary">Terapkan Filter</button>
                <button class="btn btn-secondary" type="button" onclick="openModal('modalManualTransaction')" style="display:inline-flex;align-items:center;gap:6px;">
                    ➕ Tambah Transaksi Manual
                </button>
                <button class="btn btn-secondary" style="display:inline-flex;align-items:center;gap:6px;">
                    📄 Export PDF
                </button>
            </div>
        </div>

        <div class="report-summary">
            <div class="summary-item">
                <span class="summary-label">Total Pendapatan</span>
                <span class="summary-value">Rp 405.000</span>
                <span class="summary-caption">Berdasarkan filter saat ini</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Jumlah Transaksi</span>
                <span class="summary-value">12</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Rata-rata Transaksi</span>
                <span class="summary-value">Rp 33.750</span>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Nama Produk</th>
                    <th>Nama Pembeli</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Pembayaran</th>
                    <th>Ongkir</th>
                    <th>Status Pembayaran</th>
                    <th style="width: 80px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Rubik 3x3 Magnetic</strong></td>
                    <td>Andi Saputra</td>
                    <td>12 Jan 2026, 14:32</td>
                    <td><strong>Rp 90.000</strong></td>
                    <td>Rp 15.000</td>
                    <td><span class="badge badge-success">Terverifikasi</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalViewTransfer')" title="Lihat bukti">👁️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Rubik 4x4 Pro</strong></td>
                    <td>Budi Santoso</td>
                    <td>12 Jan 2026, 10:15</td>
                    <td><strong>Rp 130.000</strong></td>
                    <td>Rp 25.000</td>
                    <td><span class="badge badge-warning">Menunggu</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" title="Detail">👁️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Megaminx</strong></td>
                    <td>Siti Aulia</td>
                    <td>11 Jan 2026, 16:47</td>
                    <td><strong>Rp 185.000</strong></td>
                    <td>Rp 40.000</td>
                    <td><span class="badge badge-danger">Gagal</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" title="Detail">👁️</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">Menampilkan 1-10 dari 120 transaksi</div>
            <div class="pagination-controls">
                <button class="page-btn">‹</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">›</button>
            </div>
        </div>
    </div>

    <!-- Modal View Transfer -->
    <div id="modalViewTransfer" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Bukti Transfer</h3>
            <button class="modal-close" onclick="closeModal('modalViewTransfer')">×</button>
        </div>
        <div class="modal-body">
            <div style="display:flex;flex-direction:column;gap:12px;">
                <img src="https://via.placeholder.com/400x220" alt="Bukti transfer" style="max-width:100%;border-radius:8px;border:1px solid var(--admin-border);">
                <div style="font-size:14px;color:var(--admin-text);">
                    <div><strong>Nama Pembeli:</strong> Andi Saputra</div>
                    <div><strong>Produk:</strong> Rubik 3x3 Magnetic</div>
                    <div><strong>Total Pembayaran:</strong> Rp 90.000</div>
                    <div><strong>Tanggal Upload:</strong> 12 Jan 2026, 14:32</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalViewTransfer')">Tutup</button>
            <button class="btn btn-primary">Tandai Terverifikasi</button>
        </div>
    </div>

    <!-- Modal Tambah Transaksi Manual -->
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
@endsection

