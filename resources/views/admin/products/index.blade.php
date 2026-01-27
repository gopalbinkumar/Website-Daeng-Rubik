@extends('admin.layouts.app')

@section('title', 'Produk Rubik')
@section('page-title', 'Produk Rubik')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Produk Rubik</h2>
        <button class="btn btn-primary" onclick="openModal('modalAddProduct')">
            + Tambah Produk
        </button>
    </div>

    <div class="table-wrapper">
        <div class="table-toolbar">
            <input type="text" class="search-input" placeholder="🔍 Search produk...">
            <select class="filter-select">
                <option>Filter: Semua</option>
                <option>3x3</option>
                <option>4x4</option>
                <option>5x5</option>
            </select>
            <select class="sort-select">
                <option>Sort: Terbaru</option>
                <option>Harga Terendah</option>
                <option>Harga Tertinggi</option>
            </select>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="https://via.placeholder.com/60" alt="Product" class="table-img"></td>
                    <td><strong>Rubik 3x3 Speed Cube</strong></td>
                    <td>Rp 50.000</td>
                    <td>25</td>
                    <td><span class="badge badge-success">Aktif</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditProduct')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Rubik 3x3 Speed Cube', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://via.placeholder.com/60" alt="Product" class="table-img"></td>
                    <td><strong>Rubik 4x4 Magnetic</strong></td>
                    <td>Rp 85.000</td>
                    <td>15</td>
                    <td><span class="badge badge-success">Aktif</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditProduct')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Rubik 4x4 Magnetic', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://via.placeholder.com/60" alt="Product" class="table-img"></td>
                    <td><strong>Rubik 5x5 Smooth Turn</strong></td>
                    <td>Rp 120.000</td>
                    <td>8</td>
                    <td><span class="badge badge-success">Aktif</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditProduct')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Rubik 5x5 Smooth Turn', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">Menampilkan 1-10 dari 100 produk</div>
            <div class="pagination-controls">
                <button class="page-btn">‹</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">›</button>
            </div>
        </div>
    </div>

    <!-- Modal Add Product -->
    <div id="modalAddProduct" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Produk</h3>
            <button class="modal-close" onclick="closeModal('modalAddProduct')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Gambar Produk <span class="required">*</span></label>
                    <div class="upload-area" onclick="document.getElementById('productImage').click()">
                        <p>Drag & Drop atau Klik untuk Upload</p>
                        <p style="font-size: 12px; color: var(--admin-text-muted);">Format: JPG, PNG (Max 2MB)</p>
                    </div>
                    <input type="file" id="productImage" accept="image/*" style="display: none;">
                    <div class="upload-preview"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" class="form-input" placeholder="Contoh: Rubik 3x3 Speed Cube">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea" placeholder="Deskripsi produk..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga <span class="required">*</span></label>
                        <input type="number" class="form-input" placeholder="50000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok <span class="required">*</span></label>
                        <input type="number" class="form-input" placeholder="25">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Pilih Kategori</option>
                            <option>3x3</option>
                            <option>4x4</option>
                            <option>5x5</option>
                            <option>Megaminx</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Brand <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Pilih Brand</option>
                            <option>MoYu</option>
                            <option>GAN</option>
                            <option>QiYi</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" checked> Aktif
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Link Marketplace (Opsional)</label>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:500;font-size:13px;">Tokopedia</label>
                            <input type="url" class="form-input" placeholder="https://tokopedia.com/daengrubik/...">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:500;font-size:13px;">Shopee</label>
                            <input type="url" class="form-input" placeholder="https://shopee.co.id/...">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:8px;">
                        <label class="form-label" style="font-weight:500;font-size:13px;">TikTok Shop</label>
                        <input type="url" class="form-input" placeholder="https://www.tiktok.com/@akunmu/shop/...">
                        <small class="form-helper">
                            Jika link diisi, ikon marketplace terkait akan muncul di detail produk sisi user. Jika kosong, ikon tidak ditampilkan.
                        </small>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalAddProduct')">Batal</button>
            <button class="btn btn-primary">Simpan Produk</button>
        </div>
    </div>

    <!-- Modal Edit Product -->
    <div id="modalEditProduct" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Produk</h3>
            <button class="modal-close" onclick="closeModal('modalEditProduct')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Gambar Produk</label>
                    <div class="upload-area" onclick="document.getElementById('editProductImage').click()">
                        <p>Drag & Drop atau Klik untuk Upload</p>
                    </div>
                    <input type="file" id="editProductImage" accept="image/*" style="display: none;">
                    <div class="upload-preview">
                        <img src="https://via.placeholder.com/200" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Rubik 3x3 Speed Cube">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea">Deskripsi produk...</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga <span class="required">*</span></label>
                        <input type="number" class="form-input" value="50000">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stok <span class="required">*</span></label>
                        <input type="number" class="form-input" value="25">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select class="form-select">
                            <option>3x3</option>
                            <option>4x4</option>
                            <option>5x5</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Brand <span class="required">*</span></label>
                        <select class="form-select">
                            <option>MoYu</option>
                            <option>GAN</option>
                            <option>QiYi</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status</label>
                    <div>
                        <label style="display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" checked> Aktif
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Link Marketplace (Opsional)</label>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:500;font-size:13px;">Tokopedia</label>
                            <input type="url" class="form-input" value="https://tokopedia.com/daengrubik/produk-3x3">
                        </div>
                        <div class="form-group">
                            <label class="form-label" style="font-weight:500;font-size:13px;">Shopee</label>
                            <input type="url" class="form-input" value="https://shopee.co.id/daengrubik/produk-3x3">
                        </div>
                    </div>
                    <div class="form-group" style="margin-top:8px;">
                        <label class="form-label" style="font-weight:500;font-size:13px;">TikTok Shop</label>
                        <input type="url" class="form-input" placeholder="https://www.tiktok.com/@akunmu/shop/...">
                        <small class="form-helper">
                            Kosongkan jika produk belum tersedia di marketplace tertentu.
                        </small>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalEditProduct')">Batal</button>
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
@endsection
