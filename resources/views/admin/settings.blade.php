@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan Website')

@push('styles')
    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: minmax(0, 1.35fr) minmax(320px, .65fr);
            gap: 24px;
            align-items: start;
        }

        .settings-stack {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .settings-card-title {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
            color: var(--admin-text);
        }

        .settings-card-subtitle {
            margin: 6px 0 0;
            font-size: 13px;
            color: var(--admin-text-muted);
            line-height: 1.5;
        }

        .settings-header-action {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .banner-list {
            display: grid;
            gap: 14px;
        }

        .banner-item {
            display: grid;
            grid-template-columns: 220px minmax(0, 1fr) auto;
            gap: 16px;
            align-items: center;
            padding: 14px;
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            background: #fff;
        }

        .banner-thumb {
            height: 112px;
            border-radius: 10px;
            overflow: hidden;
            background: linear-gradient(135deg, rgba(229, 57, 53, .88), rgba(25, 118, 210, .85));
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 14px;
        }

        .banner-thumb.secondary {
            background: linear-gradient(135deg, #111827, #43a047);
        }

        .banner-thumb strong {
            font-size: 15px;
            line-height: 1.3;
        }

        .banner-thumb span {
            margin-top: 4px;
            font-size: 12px;
            opacity: .86;
        }

        .settings-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .settings-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .brand-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px;
        }

        .brand-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border: 1px solid var(--admin-border);
            border-radius: 8px;
            background: #fff;
        }

        .brand-name {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
            min-width: 0;
        }

        .brand-logo-mark {
            width: 28px;
            height: 28px;
            border-radius: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(25, 118, 210, .1);
            color: var(--admin-primary);
            font-size: 12px;
            flex: 0 0 auto;
        }

        .compact-settings-actions {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .compact-settings-actions .btn-icon {
            width: 34px;
            height: 34px;
        }

        .contact-link-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .preview-box {
            border: 1px solid var(--admin-border);
            border-radius: 10px;
            padding: 14px;
            background: var(--admin-bg);
        }

        .preview-box-title {
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: 700;
            color: var(--admin-text-muted);
        }

        .settings-divider {
            height: 1px;
            background: var(--admin-border);
            margin: 4px 0 20px;
        }

        @media (max-width: 1100px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }

            .banner-item {
                grid-template-columns: 180px minmax(0, 1fr);
            }

            .banner-item .settings-actions {
                grid-column: 1 / -1;
                justify-content: flex-end;
            }
        }

        @media (max-width: 680px) {
            .settings-header-action,
            .banner-item,
            .brand-item {
                align-items: stretch;
                flex-direction: column;
            }

            .banner-item {
                display: flex;
            }

            .banner-thumb {
                width: 100%;
            }

            .contact-link-grid {
                grid-template-columns: 1fr;
            }

            .settings-actions {
                justify-content: flex-start;
                flex-wrap: wrap;
            }

            .brand-item {
                grid-template-columns: 1fr;
            }

            .brand-list {
                grid-template-columns: 1fr;
            }

            .compact-settings-actions {
                justify-content: flex-start;
            }
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <div>
            <h2 class="page-title">Pengaturan Website</h2>
            <p class="settings-card-subtitle">
                Kelola tampilan beranda, daftar merk produk, dan tautan kontak yang tampil di halaman user.
            </p>
        </div>
    </div>

    <div class="settings-grid">
        <div class="settings-stack">
            <div class="card">
                <div class="card-header settings-header-action">
                    <div>
                        <h3 class="settings-card-title">Banner Home User</h3>
                        <p class="settings-card-subtitle">Atur banner promosi yang akan muncul di halaman beranda.</p>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="openModal('modalAddBanner')">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Banner
                    </button>
                </div>
                <div class="card-body">
                    <div class="banner-list">
                        <div class="banner-item">
                            <div class="banner-thumb">
                                <strong>Promo Speed Cube Minggu Ini</strong>
                                <span>Diskon sampai 20%</span>
                            </div>
                            <div>
                                <strong>Promo Speed Cube Minggu Ini</strong>
                                <p class="settings-card-subtitle">
                                    Banner utama untuk menampilkan promo produk unggulan di halaman home.
                                </p>
                                <div class="settings-meta">
                                    <span class="badge badge-success">Aktif</span>
                                    <span class="badge badge-info">Urutan 1</span>
                                    <span class="badge badge-gray">/produk</span>
                                </div>
                            </div>
                            <div class="settings-actions">
                                <button type="button" class="btn btn-icon btn-secondary" onclick="openModal('modalEditBanner')">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="banner-item">
                            <div class="banner-thumb secondary">
                                <strong>Event Rubik Terdekat</strong>
                                <span>Daftar kompetisi dan gathering</span>
                            </div>
                            <div>
                                <strong>Event Rubik Terdekat</strong>
                                <p class="settings-card-subtitle">
                                    Banner pendukung untuk mengarahkan user ke halaman event.
                                </p>
                                <div class="settings-meta">
                                    <span class="badge badge-warning">Draft</span>
                                    <span class="badge badge-info">Urutan 2</span>
                                    <span class="badge badge-gray">/event</span>
                                </div>
                            </div>
                            <div class="settings-actions">
                                <button type="button" class="btn btn-icon btn-secondary" onclick="openModal('modalEditBanner')">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                                <button type="button" class="btn btn-icon btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header settings-header-action">
                    <div>
                        <h3 class="settings-card-title">Merk Rubik di Produk</h3>
                        <p class="settings-card-subtitle">Daftar merk yang dapat dipilih ketika ingin menambah atau mengubah produk.</p>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="openModal('modalAddBrand')">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Merk
                    </button>
                </div>
                <div class="card-body">
                    <div class="brand-list">
                        @foreach ($productBrands as $brand)
                            <div class="brand-item">
                                <div class="brand-name">
                                    <span class="brand-logo-mark">
                                        <i class="fa-solid fa-cube"></i>
                                    </span>
                                    {{ $brand->name }}
                                </div>
                                <div class="compact-settings-actions">
                                    <span class="badge {{ $brand->is_active ? 'badge-success' : 'badge-gray' }}">
                                        {{ $brand->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <button type="button" class="btn btn-icon btn-secondary"
                                        onclick="openEditBrandModal(this)"
                                        data-action="{{ route('admin.settings.brands.update', $brand) }}"
                                        data-name="{{ $brand->name }}"
                                        data-active="{{ $brand->is_active ? 1 : 0 }}"
                                        data-sort-order="{{ $brand->sort_order }}">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.settings.brands.destroy', $brand) }}"
                                        class="form-delete" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header settings-header-action">
                    <div>
                        <h3 class="settings-card-title">Kategori Rubik di Produk</h3>
                        <p class="settings-card-subtitle">Daftar kategori yang dapat dipilih ketika ingin menambah atau mengubah produk.</p>
                    </div>
                    <button type="button" class="btn btn-primary" onclick="openModal('modalAddCategory')">
                        <i class="fa-solid fa-plus"></i>
                        Tambah Kategori
                    </button>
                </div>
                <div class="card-body">
                    <div class="brand-list">
                        @foreach ($productCategories as $category)
                            <div class="brand-item">
                                <div class="brand-name">
                                    <span class="brand-logo-mark">
                                        <i class="fa-solid fa-shapes"></i>
                                    </span>
                                    {{ $category->name }}
                                </div>
                                <div class="compact-settings-actions">
                                    <span class="badge {{ $category->is_active ? 'badge-success' : 'badge-gray' }}">
                                        {{ $category->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                    <button type="button" class="btn btn-icon btn-secondary"
                                        onclick="openEditCategoryModal(this)"
                                        data-action="{{ route('admin.settings.categories.update', $category) }}"
                                        data-name="{{ $category->name }}"
                                        data-active="{{ $category->is_active ? 1 : 0 }}"
                                        data-sort-order="{{ $category->sort_order }}">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.settings.categories.destroy', $category) }}"
                                        class="form-delete" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-icon btn-danger">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-stack">
            <div class="card">
                <div class="card-header">
                    <h3 class="settings-card-title">Kontak Kami</h3>
                    <p class="settings-card-subtitle">Masukkan URL atau nomor yang akan dipakai pada halaman kontak dan footer.</p>
                </div>
                <div class="card-body">
                    <form id="contactSettingsForm" method="POST" action="{{ route('admin.settings.contact.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Alamat</label>
                            <textarea name="address" class="form-textarea" placeholder="Alamat toko atau lokasi usaha">{{ old('address', $contact->address) }}</textarea>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="phone" class="form-input"
                                    value="{{ old('phone', $contact->phone) }}" placeholder="+62 812-3456-7890">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-input"
                                    value="{{ old('email', $contact->email) }}" placeholder="info@daengrubik.com">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="text" name="whatsapp_number" class="form-input"
                                    value="{{ old('whatsapp_number', $contact->whatsapp_number) }}"
                                    placeholder="+62 819-1462-9111">
                            </div>

                            <div class="form-group">
                                <label class="form-label">URL WhatsApp</label>
                                <input type="url" name="whatsapp_url" class="form-input"
                                    value="{{ old('whatsapp_url', $contact->whatsapp_url) }}"
                                    placeholder="https://wa.me/6281914629111">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-input"
                                    value="{{ old('latitude', $contact->latitude) }}" placeholder="-5.123456">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-input"
                                    value="{{ old('longitude', $contact->longitude) }}" placeholder="119.123456">
                            </div>
                        </div>

                        <div class="settings-divider"></div>

                        <div class="contact-link-grid">
                            <div class="form-group">
                                <label class="form-label"><i class="fa-brands fa-tiktok"></i> TikTok</label>
                                <input type="url" name="tiktok_url" class="form-input"
                                    value="{{ old('tiktok_url', $contact->tiktok_url) }}"
                                    placeholder="https://tiktok.com/@daengrubik">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fa-brands fa-instagram"></i> Instagram</label>
                                <input type="url" name="instagram_url" class="form-input"
                                    value="{{ old('instagram_url', $contact->instagram_url) }}"
                                    placeholder="https://instagram.com/daengrubik">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fa-brands fa-youtube"></i> YouTube</label>
                                <input type="url" name="youtube_url" class="form-input"
                                    value="{{ old('youtube_url', $contact->youtube_url) }}"
                                    placeholder="https://youtube.com/@daengrubik">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fa-brands fa-facebook"></i> Facebook</label>
                                <input type="url" name="facebook_url" class="form-input"
                                    value="{{ old('facebook_url', $contact->facebook_url) }}"
                                    placeholder="https://facebook.com/daengrubik">
                            </div>
                        </div>
                    </form>

                    <div style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap;">
                        @if ($contact->exists)
                            <form method="POST" action="{{ route('admin.settings.contact.destroy') }}" class="form-delete">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">
                                    <i class="fa-solid fa-trash"></i>
                                    Hapus Kontak
                                </button>
                            </form>
                        @else
                            <span></span>
                        @endif

                        <button type="submit" form="contactSettingsForm" class="btn btn-primary">
                            <i class="fa-solid fa-save"></i>
                            Simpan Kontak
                        </button>
                    </div>
                </div>
            </div>

            <div class="preview-box">
                <p class="preview-box-title">Ringkasan Pengaturan</p>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fa-solid fa-image"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">2 banner home</div>
                        <div class="activity-time">1 aktif, 1 draft</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fa-solid fa-cube"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">{{ $productBrands->count() }} merk produk</div>
                        <div class="activity-time">Tampil di produk rubik</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fa-solid fa-shapes"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">{{ $productCategories->count() }} kategori produk</div>
                        <div class="activity-time">Tampil di filter produk</div>
                    </div>
                </div>
                <div class="activity-item">
                    <div class="activity-icon"><i class="fa-solid fa-link"></i></div>
                    <div class="activity-content">
                        <div class="activity-text">Kontak & sosial media</div>
                        <div class="activity-time">WhatsApp, TikTok, Instagram, YouTube</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="modalAddBanner" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Banner</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalAddBanner')">×</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Gambar Banner</label>
                <label class="upload-box" style="max-width:none;height:190px;">
                    <input type="file" accept="image/*" data-preview>
                    <img class="upload-preview-img" hidden>
                    <span>Upload gambar banner</span>
                </label>
                <small class="form-helper">Rekomendasi rasio 16:5 atau 16:6.</small>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Banner</label>
                    <input type="text" class="form-input" placeholder="Promo Speed Cube Minggu Ini">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option>Aktif</option>
                        <option>Draft</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea class="form-textarea" placeholder="Teks pendukung banner"></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">URL Tujuan</label>
                    <input type="text" class="form-input" placeholder="/produk">
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-input" placeholder="1">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalAddBanner')">Batal</button>
            <button type="button" class="btn btn-primary">Simpan Banner</button>
        </div>
    </div>

    <div id="modalEditBanner" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Banner</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalEditBanner')">×</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Gambar Banner</label>
                <label class="upload-box" style="max-width:none;height:190px;">
                    <input type="file" accept="image/*" data-preview>
                    <img class="upload-preview-img" hidden>
                    <span>Ganti gambar banner</span>
                </label>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Judul Banner</label>
                    <input type="text" class="form-input" value="Promo Speed Cube Minggu Ini">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select class="form-select">
                        <option selected>Aktif</option>
                        <option>Draft</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea class="form-textarea">Diskon sampai 20% untuk produk pilihan.</textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">URL Tujuan</label>
                    <input type="text" class="form-input" value="/produk">
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" class="form-input" value="1">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditBanner')">Batal</button>
            <button type="button" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>

    <div id="modalAddBrand" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Merk</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalAddBrand')">×</button>
        </div>
        <div class="modal-body">
            <form id="formAddBrand" method="POST" action="{{ route('admin.settings.brands.store') }}">
                @csrf
            <div class="form-group">
                <label class="form-label">Nama Merk</label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: MoYu" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" class="form-input" placeholder="1" min="0">
                </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalAddBrand')">Batal</button>
            <button type="submit" form="formAddBrand" class="btn btn-primary">Simpan Merk</button>
        </div>
    </div>

    <div id="modalEditBrand" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Merk</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalEditBrand')">×</button>
        </div>
        <div class="modal-body">
            <form id="formEditBrand" method="POST">
                @csrf
                @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Merk</label>
                <input type="text" name="name" class="form-input" value="MoYu" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" class="form-input" value="1" min="0">
                </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditBrand')">Batal</button>
            <button type="submit" form="formEditBrand" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>

    <div id="modalAddCategory" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Kategori</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalAddCategory')">×</button>
        </div>
        <div class="modal-body">
            <form id="formAddCategory" method="POST" action="{{ route('admin.settings.categories.store') }}">
                @csrf
            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-input" placeholder="Contoh: 3x3" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" class="form-input" placeholder="1" min="0">
                </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalAddCategory')">Batal</button>
            <button type="submit" form="formAddCategory" class="btn btn-primary">Simpan Kategori</button>
        </div>
    </div>

    <div id="modalEditCategory" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Kategori</h3>
            <button type="button" class="modal-close" onclick="closeModal('modalEditCategory')">×</button>
        </div>
        <div class="modal-body">
            <form id="formEditCategory" method="POST">
                @csrf
                @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-input" value="3x3" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="is_active" class="form-select" required>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Urutan</label>
                    <input type="number" name="sort_order" class="form-input" value="1" min="0">
                </div>
            </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditCategory')">Batal</button>
            <button type="submit" form="formEditCategory" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>

    <script>
        function openEditBrandModal(button) {
            const form = document.getElementById('formEditBrand');
            form.action = button.dataset.action;
            form.querySelector('[name="name"]').value = button.dataset.name || '';
            form.querySelector('[name="is_active"]').value = button.dataset.active || '1';
            form.querySelector('[name="sort_order"]').value = button.dataset.sortOrder || 0;
            openModal('modalEditBrand');
        }

        function openEditCategoryModal(button) {
            const form = document.getElementById('formEditCategory');
            form.action = button.dataset.action;
            form.querySelector('[name="name"]').value = button.dataset.name || '';
            form.querySelector('[name="is_active"]').value = button.dataset.active || '1';
            form.querySelector('[name="sort_order"]').value = button.dataset.sortOrder || 0;
            openModal('modalEditCategory');
        }
    </script>
@endsection
