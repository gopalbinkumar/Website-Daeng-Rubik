@extends('admin.layouts.app')

@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Materi Pembelajaran</h2>
        <button class="btn btn-primary" onclick="openModal('modalAddMateri')">
            + Tambah Materi
        </button>
    </div>

    <div class="table-wrapper">
        <div class="table-toolbar">
            <select class="filter-select">
                <option>Level: Semua</option>
                <option>Basic</option>
                <option>Intermediate</option>
                <option>Advanced</option>
            </select>
            <select class="filter-select">
                <option>Kategori: Semua</option>
                <option>3x3</option>
                <option>4x4</option>
                <option>5x5</option>
            </select>
            <input type="text" class="search-input" placeholder="🔍 Search materi...">
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 100px;">Thumbnail</th>
                    <th>Judul Materi</th>
                    <th>Level</th>
                    <th>Kategori</th>
                    <th>Durasi</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><img src="https://via.placeholder.com/80x60" alt="Video" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px;"></td>
                    <td><strong>Pengenalan Rubik 3x3</strong><br><small style="color: var(--admin-text-muted);">1.2K views</small></td>
                    <td><span class="badge badge-success">Basic</span></td>
                    <td>3x3</td>
                    <td>5:30</td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditMateri')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Pengenalan Rubik 3x3', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://via.placeholder.com/80x60" alt="Video" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px;"></td>
                    <td><strong>F2L Intermediate</strong><br><small style="color: var(--admin-text-muted);">980 views</small></td>
                    <td><span class="badge badge-warning">Intermediate</span></td>
                    <td>3x3</td>
                    <td>18:20</td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditMateri')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('F2L Intermediate', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><img src="https://via.placeholder.com/80x60" alt="Video" style="width: 80px; height: 60px; object-fit: cover; border-radius: 8px;"></td>
                    <td><strong>Full OLL Strategy</strong><br><small style="color: var(--admin-text-muted);">720 views</small></td>
                    <td><span class="badge badge-danger">Advanced</span></td>
                    <td>3x3</td>
                    <td>24:10</td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditMateri')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Full OLL Strategy', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">Menampilkan 1-10 dari 75 materi</div>
            <div class="pagination-controls">
                <button class="page-btn">‹</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">›</button>
            </div>
        </div>
    </div>

    <!-- Modal Add Materi -->
    <div id="modalAddMateri" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Materi</h3>
            <button class="modal-close" onclick="closeModal('modalAddMateri')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Thumbnail Video</label>
                    <div class="upload-area" onclick="document.getElementById('materiThumbnail').click()">
                        <p>Drag & Drop atau Klik untuk Upload</p>
                    </div>
                    <input type="file" id="materiThumbnail" accept="image/*" style="display: none;">
                    <div class="upload-preview"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul Materi <span class="required">*</span></label>
                    <input type="text" class="form-input" placeholder="Contoh: Pengenalan Rubik 3x3">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea" placeholder="Deskripsi materi..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Pilih Level</option>
                            <option>Basic</option>
                            <option>Intermediate</option>
                            <option>Advanced</option>
                        </select>
                    </div>
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
                </div>

                <div class="form-group">
                    <label class="form-label">Video URL atau Upload <span class="required">*</span></label>
                    <input type="text" class="form-input" placeholder="YouTube URL atau upload file video">
                    <input type="file" accept="video/*" class="form-input" style="margin-top: 8px;">
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi (menit) <span class="required">*</span></label>
                    <input type="number" class="form-input" placeholder="5">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalAddMateri')">Batal</button>
            <button class="btn btn-primary">Simpan Materi</button>
        </div>
    </div>

    <!-- Modal Edit Materi -->
    <div id="modalEditMateri" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Materi</h3>
            <button class="modal-close" onclick="closeModal('modalEditMateri')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Thumbnail Video</label>
                    <div class="upload-area" onclick="document.getElementById('editMateriThumbnail').click()">
                        <p>Drag & Drop atau Klik untuk Upload</p>
                    </div>
                    <input type="file" id="editMateriThumbnail" accept="image/*" style="display: none;">
                    <div class="upload-preview">
                        <img src="https://via.placeholder.com/200" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul Materi <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Pengenalan Rubik 3x3">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea">Deskripsi materi...</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Basic</option>
                            <option>Intermediate</option>
                            <option>Advanced</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select class="form-select">
                            <option>3x3</option>
                            <option>4x4</option>
                            <option>5x5</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Video URL atau Upload <span class="required">*</span></label>
                    <input type="text" class="form-input" value="https://youtube.com/watch?v=...">
                    <input type="file" accept="video/*" class="form-input" style="margin-top: 8px;">
                </div>

                <div class="form-group">
                    <label class="form-label">Durasi (menit) <span class="required">*</span></label>
                    <input type="number" class="form-input" value="5">
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalEditMateri')">Batal</button>
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
@endsection
