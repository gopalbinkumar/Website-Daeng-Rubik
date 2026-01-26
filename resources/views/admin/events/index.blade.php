@extends('admin.layouts.app')

@section('title', 'Event Rubik')
@section('page-title', 'Event Rubik')

@section('content')
    <div class="page-header">
        <h2 class="page-title">Event Rubik</h2>
        <button class="btn btn-primary" onclick="openModal('modalAddEvent')">
            + Tambah Event
        </button>
    </div>

    <div class="table-wrapper">
        <div class="table-toolbar">
            <input type="text" class="search-input" placeholder="🔍 Search event...">
            <select class="filter-select">
                <option>Status: Semua</option>
                <option>Upcoming</option>
                <option>Ongoing</option>
                <option>Finished</option>
            </select>
            <select class="sort-select">
                <option>Sort: Terbaru</option>
                <option>Terdekat</option>
                <option>Popular</option>
            </select>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Judul Event</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><strong>Kompetisi Rubik Nasional</strong></td>
                    <td>15 Feb 2026<br><small style="color: var(--admin-text-muted);">08:00 WIB</small></td>
                    <td>Jakarta</td>
                    <td><span class="badge badge-warning">Upcoming</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditEvent')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Kompetisi Rubik Nasional', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Workshop Basic 3x3</strong></td>
                    <td>20 Feb 2026<br><small style="color: var(--admin-text-muted);">13:00 WIB</small></td>
                    <td>Bandung</td>
                    <td><span class="badge badge-warning">Upcoming</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditEvent')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Workshop Basic 3x3', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><strong>Speedcubing Meetup</strong></td>
                    <td>25 Feb 2026<br><small style="color: var(--admin-text-muted);">16:00 WIB</small></td>
                    <td>Surabaya</td>
                    <td><span class="badge badge-warning">Upcoming</span></td>
                    <td>
                        <div class="table-actions">
                            <button class="btn btn-icon btn-secondary" onclick="openModal('modalEditEvent')" title="Edit">✏️</button>
                            <button class="btn btn-icon btn-danger" onclick="confirmDelete('Speedcubing Meetup', () => alert('Deleted'))" title="Hapus">🗑️</button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">Menampilkan 1-10 dari 50 event</div>
            <div class="pagination-controls">
                <button class="page-btn">‹</button>
                <button class="page-btn active">1</button>
                <button class="page-btn">2</button>
                <button class="page-btn">3</button>
                <button class="page-btn">›</button>
            </div>
        </div>
    </div>

    <!-- Modal Add Event -->
    <div id="modalAddEvent" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Tambah Event</h3>
            <button class="modal-close" onclick="closeModal('modalAddEvent')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Cover Event</label>
                    <div class="upload-area" onclick="document.getElementById('eventCover').click()">
                        <p>Drag & Drop atau Klik untuk Upload</p>
                    </div>
                    <input type="file" id="eventCover" accept="image/*" style="display: none;">
                    <div class="upload-preview"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul Event <span class="required">*</span></label>
                    <input type="text" class="form-input" placeholder="Contoh: Kompetisi Rubik Nasional">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea" placeholder="Deskripsi event..."></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" class="form-input">
                        <input type="time" class="form-input" style="margin-top: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Akhir <span class="required">*</span></label>
                        <input type="date" class="form-input">
                        <input type="time" class="form-input" style="margin-top: 8px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi <span class="required">*</span></label>
                    <input type="text" class="form-input" placeholder="Contoh: Jakarta Convention Center">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Pilih Kategori</option>
                            <option>Kompetisi</option>
                            <option>Workshop</option>
                            <option>Meetup</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Tiket</label>
                        <input type="number" class="form-input" placeholder="50000">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Max Peserta <span class="required">*</span></label>
                        <input type="number" class="form-input" placeholder="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Upcoming</option>
                            <option>Ongoing</option>
                            <option>Finished</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalAddEvent')">Batal</button>
            <button class="btn btn-primary">Simpan Event</button>
        </div>
    </div>

    <!-- Modal Edit Event -->
    <div id="modalEditEvent" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Event</h3>
            <button class="modal-close" onclick="closeModal('modalEditEvent')">×</button>
        </div>
        <div class="modal-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Cover Event</label>
                    <div class="upload-area" onclick="document.getElementById('editEventCover').click()">
                        <p>Drag & Drop atau Klik untuk Upload</p>
                    </div>
                    <input type="file" id="editEventCover" accept="image/*" style="display: none;">
                    <div class="upload-preview">
                        <img src="https://via.placeholder.com/200" alt="Preview" style="max-width: 200px; border-radius: 8px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Judul Event <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Kompetisi Rubik Nasional">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea">Deskripsi event...</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" class="form-input" value="2026-02-15">
                        <input type="time" class="form-input" value="08:00" style="margin-top: 8px;">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Akhir <span class="required">*</span></label>
                        <input type="date" class="form-input" value="2026-02-15">
                        <input type="time" class="form-input" value="17:00" style="margin-top: 8px;">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Jakarta Convention Center">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Kompetisi</option>
                            <option>Workshop</option>
                            <option>Meetup</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga Tiket</label>
                        <input type="number" class="form-input" value="50000">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Max Peserta <span class="required">*</span></label>
                        <input type="number" class="form-input" value="200">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select class="form-select">
                            <option>Upcoming</option>
                            <option>Ongoing</option>
                            <option>Finished</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('modalEditEvent')">Batal</button>
            <button class="btn btn-primary">Simpan Perubahan</button>
        </div>
    </div>
@endsection
