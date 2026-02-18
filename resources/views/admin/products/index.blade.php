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
        <form method="GET" action="{{ route('admin.products.index') }}">
            <div class="table-toolbar">
                <input type="text" name="search" class="search-input" placeholder="Search produk..."
                    value="{{ request('search') }}">

                <select class="filter-select" name="category" onchange="this.form.submit()">
                    <option value="">Filter: Semua</option>
                    @foreach ($cubeCategories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>

                <select name="sort" class="sort-select" onchange="this.form.submit()">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>
                        Sort: Terbaru
                    </option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>
                        Harga Terendah
                    </option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>
                        Harga Tertinggi
                    </option>
                </select>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 80px;">Gambar</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Status</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <img src="{{ $product->primaryImage
                                ? asset('storage/' . $product->primaryImage->image_path)
                                : 'https://via.placeholder.com/60' }}"
                                class="table-img">
                        </td>

                        <td><strong>{{ $product->name }}</strong></td>
                        <td>{{ $product->cubeCategory->name }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>

                        <td>
                            @if ($product->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Diarsipkan</span>
                            @endif
                        </td>

                        <td>
                            <div class="table-actions">
                                <button class="btn btn-icon btn-secondary" onclick="openEditProduct(this)"
                                    data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}" data-stock="{{ $product->stock }}"
                                    data-cube-category="{{ $product->cube_category_id }}"
                                    data-brand="{{ $product->brand }}" data-difficulty="{{ $product->difficulty_level }}"
                                    data-description="{{ $product->description }}" data-active="{{ $product->is_active }}"
                                    data-images='@json($product->images->map(fn($img) => asset('storage/' . $img->image_path)))' {{-- MARKETPLACE --}}
                                    data-tokopedia="{{ optional($product->marketplaceLinks->where('marketplace', 'tokopedia')->first())->url }}"
                                    data-shopee="{{ optional($product->marketplaceLinks->where('marketplace', 'shopee')->first())->url }}"
                                    data-tiktok="{{ optional($product->marketplaceLinks->where('marketplace', 'tiktok_shop')->first())->url }}">
                                    <i class="fa-solid fa-edit"></i>
                                </button>

                                <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}"
                                    class="form-delete" style="display:inline">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-icon btn-danger">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>

        <x-admin-pagination :paginator="$products" />

    </div>

    <!-- Modal Add Product -->
    <div id="modalAddProduct" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Produk</h3>
            <button class="modal-close" onclick="closeModal('modalAddProduct')">×</button>
        </div>
        <div class="modal-body">
            <form id="formAddProduct" method="POST" action="{{ route('admin.products.store') }}"
                enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        Gambar Produk <span class="required">*</span>
                        <small>(Maks. 3 gambar)</small>
                    </label>

                    <div class="image-upload-grid">
                        <!-- Gambar 1 -->
                        <label class="upload-box">
                            <input type="file" name="images[0]" accept="image/*" data-preview>
                            <img class="upload-preview-img" hidden>
                            <span>Gambar Utama</span>
                        </label>

                        <!-- Gambar 2 -->
                        <label class="upload-box">
                            <input type="file" name="images[1]" accept="image/*" data-preview>
                            <img class="upload-preview-img" hidden>
                            <span>Gambar 2</span>
                        </label>

                        <!-- Gambar 3 -->
                        <label class="upload-box">
                            <input type="file" name="images[2]" accept="image/*" data-preview>
                            <img class="upload-preview-img" hidden>
                            <span>Gambar 3</span>
                        </label>
                    </div>


                    <small class="form-helper">
                        Format JPG/PNG • Maks 2MB per gambar
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Produk <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input" placeholder="Contoh: Rubik 3x3 Speed Cube"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea name="description" class="form-textarea" placeholder="Deskripsi produk..." required></textarea>
                </div>

                <!-- HARGA & STOK -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga <span class="required">*</span></label>
                        <input type="number" name="price" class="form-input" placeholder="50000" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok <span class="required">*</span></label>
                        <input type="number" name="stock" class="form-input" placeholder="25" required>
                    </div>
                </div>

                <!-- KATEGORI, LEVEL, BRAND, STATUS -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="cube_category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($cubeCategories as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tingkat Kesulitan <span class="required">*</span></label>
                        <select name="difficulty_level" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="none">Tidak ada</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Brand <span class="required">*</span></label>
                        <select name="brand" class="form-select" required>
                            <option value="">Pilih Brand</option>
                            <option value="MoYu">MoYu</option>
                            <option value="GAN">GAN</option>
                            <option value="QiYi">QiYi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="is_active" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Diarsipkan</option>
                        </select>
                    </div>
                </div>

                <!-- MARKETPLACE -->
                <div class="form-group">
                    <label class="form-label">Link Marketplace (Opsional)</label>

                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label" style="font-weight:500;font-size:13px;">Tokopedia</label>
                            <input type="url" name="marketplace_links[tokopedia]" class="form-input"
                                placeholder="https://tokopedia.com/...">
                        </div>

                        <div class="form-group">
                            <label class="form-label" style="font-weight:500;font-size:13px;">Shopee</label>
                            <input type="url" name="marketplace_links[shopee]" class="form-input"
                                placeholder="https://shopee.co.id/...">
                        </div>
                    </div>

                    <div class="form-group" style="margin-top:8px;">
                        <label class="form-label" style="font-weight:500;font-size:13px;">TikTok Shop</label>
                        <input type="url" name="marketplace_links[tiktok_shop]" class="form-input"
                            placeholder="https://www.tiktok.com/shop/...">
                        <small class="form-helper">
                            Jika link diisi, ikon marketplace terkait akan muncul di detail produk sisi user.
                            Jika kosong, ikon tidak ditampilkan.
                        </small>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalAddProduct')">Batal</button>
            <button type="submit" form="formAddProduct" class="btn btn-primary">
                Simpan Produk
            </button>
        </div>
    </div>

    <!-- Modal Edit Product -->
    <div id="modalEditProduct" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Produk</h3>
            <button class="modal-close" onclick="closeModal('modalEditProduct')">×</button>
        </div>

        <div class="modal-body">
            <form id="formEditProduct" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- GAMBAR --}}
                <div class="form-group">
                    <label class="form-label">
                        Gambar Produk <small>(Opsional, maks 3)</small>
                    </label>

                    <div class="image-upload-grid">
                        @for ($i = 0; $i < 3; $i++)
                            <label class="upload-box">
                                <input type="file" name="images[{{ $i }}]"
                                    data-position="{{ $i }}" accept="image/*" data-preview>
                                <img class="upload-preview-img" hidden>
                            </label>
                        @endfor

                    </div>
                </div>

                {{-- NAMA --}}
                <div class="form-group">
                    <label class="form-label">Nama Produk *</label>
                    <input type="text" name="name" class="form-input" required>
                </div>

                {{-- DESKRIPSI --}}
                <div class="form-group">
                    <label class="form-label">Deskripsi *</label>
                    <textarea name="description" class="form-textarea" required></textarea>
                </div>

                {{-- HARGA & STOK --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Harga *</label>
                        <input type="number" name="price" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stok *</label>
                        <input type="number" name="stock" class="form-input" required>
                    </div>
                </div>

                {{-- KATEGORI, LEVEL, BRAND, STATUS --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori *</label>
                        <select name="cube_category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($cubeCategories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tingkat Kesulitan *</label>
                        <select name="difficulty_level" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="none">Tidak ada</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Brand *</label>
                        <select name="brand" class="form-select" required>
                            <option value="">Pilih</option>
                            <option value="MoYu">MoYu</option>
                            <option value="GAN">GAN</option>
                            <option value="QiYi">QiYi</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="is_active" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Diarsipkan</option>
                        </select>
                    </div>
                </div>

                {{-- MARKETPLACE --}}
                <div class="form-group">
                    <label class="form-label">Link Marketplace</label>

                    <input type="url" name="marketplace_links[tokopedia]" class="form-input"
                        placeholder="Tokopedia">

                    <input type="url" name="marketplace_links[shopee]" class="form-input" placeholder="Shopee"
                        style="margin-top:6px">

                    <input type="url" name="marketplace_links[tiktok_shop]" class="form-input"
                        placeholder="TikTok Shop" style="margin-top:6px">
                </div>
            </form>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditProduct')">Batal</button>
            <button type="submit" form="formEditProduct" class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>

@endsection
