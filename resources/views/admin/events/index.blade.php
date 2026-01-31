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
                    <th>Kategori Event</th>
                    <th>Tanggal</th>
                    <th>Lokasi</th>
                    <th>Status</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($events as $event)
                    <tr>
                        {{-- Judul --}}
                        <td>
                            <strong>{{ $event->title }}</strong>
                        </td>

                        {{-- Kategori Event --}}
                        <td>
                            @if ($event->category === 'kompetisi')
                                Kompetisi
                            @elseif ($event->category === 'gathering')
                                Gathering
                            @else
                                {{ $event->custom_category ?? 'Event Lainnya' }}
                            @endif
                        </td>

                        {{-- Tanggal --}}
                        <td>
                            {{ $event->start_datetime->format('d M Y') }}<br>
                            <small style="color: var(--admin-text-muted);">
                                {{ $event->start_datetime->format('H:i') }} WIB
                            </small>
                        </td>

                        {{-- Lokasi --}}
                        <td>
                            {{ $event->location }}
                        </td>

                        {{-- Status --}}
                        <td>
                            @php
                                $badgeClass = match ($event->status) {
                                    'upcoming' => 'badge-warning',
                                    'ongoing' => 'badge-success',
                                    'finished' => 'badge-secondary',
                                    default => 'badge-warning',
                                };
                            @endphp

                            <span class="badge {{ $badgeClass }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </td>

                        {{-- Aksi --}}
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-icon btn-secondary" onclick="openEditEvent(this)"
                                    data-id="{{ $event->id }}" data-title="{{ $event->title }}"
                                    data-category="{{ $event->category }}"
                                    data-custom-category="{{ $event->custom_category }}"
                                    data-description="{{ $event->description }}"
                                    data-start-date="{{ $event->start_datetime->format('Y-m-d') }}"
                                    data-start-time="{{ $event->start_datetime->format('H:i') }}"
                                    data-end-date="{{ $event->end_datetime->format('Y-m-d') }}"
                                    data-end-time="{{ $event->end_datetime->format('H:i') }}"
                                    data-location="{{ $event->location }}" data-ticket-price="{{ $event->ticket_price }}"
                                    data-max-participants="{{ $event->max_participants }}"
                                    data-total-prize="{{ $event->total_prize }}" data-status="{{ $event->status }}"
                                    data-cover="{{ $event->cover_image ? asset('storage/'.$event->cover_image) : '' }}"

                                    {{-- ⬇️ INI PENTING --}} data-competition-categories='@json(
                                        $event->competitionCategories->map(fn($c) => [
                                                'id' => $c->id,
                                                'name' => $c->name,
                                            ]))'>
                                    <i class="fa-solid fa-edit"></i>
                                </button>

                                <form method="POST" action="{{ route('admin.events.destroy', $event->id) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-icon btn-danger"
                                        onclick="return confirm('Hapus event {{ $event->title }}?')" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; color:var(--admin-text-muted);">
                            Belum ada event
                        </td>
                    </tr>
                @endforelse
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
            <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Cover -->
                <div class="form-group">
                    <label class="form-label">Cover Event</label>
                    <div class="image-upload-grid"> <label class="upload-box">
                            <input type="file" name="cover_image" accept="image/*" data-preview>
                            <img class="upload-preview-img" hidden>
                            <span>Gambar Utama</span>
                        </label>
                    </div>
                </div>

                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label">Judul Event <span class="required">*</span></label>
                    <input type="text" class="form-input" name="title" placeholder="Contoh: Kompetisi Rubik Nasional"
                        required>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select class="form-select" id="eventCategory" name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="kompetisi">Kompetisi</option>
                        <option value="gathering">Gathering</option>
                        <option value="lainnya">Event Lainnya</option>
                    </select>
                </div>

                <!-- Kategori Lainnya -->
                <div class="form-group" id="customCategoryGroup" style="display:none;">
                    <label class="form-label">Nama Kategori Lainnya <span class="required">*</span></label>
                    <input type="text" class="form-input" name="custom_category" placeholder="Meetup">
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea" name="description" placeholder="Deskripsi event..." required></textarea>
                </div>

                <!-- Tanggal & Jam -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" class="form-input" name="start_date" required>
                        <input type="time" class="form-input" name="start_time" style="margin-top:8px;" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Akhir <span class="required">*</span></label>
                        <input type="date" class="form-input" name="end_date" required>
                        <input type="time" class="form-input" name="end_time" style="margin-top:8px;" required>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="form-group">
                    <label class="form-label">Lokasi <span class="required">*</span></label>
                    <input type="text" class="form-input" name="location"
                        placeholder="Contoh: Jakarta Convention Center" required>
                </div>

                <!-- Detail Kompetisi -->
                <h4 class="form-section-title" id="competitionTitle" style="display:none;">
                    Detail Kompetisi
                </h4>

                <div class="form-row">
                    <div class="form-group" id="priceGroup" style="display:none;">
                        <label class="form-label">Harga Tiket</label>
                        <input type="number" class="form-input" name="ticket_price" min="0">
                    </div>
                    <div class="form-group" id="quotaGroup" style="display:none;">
                        <label class="form-label">Max Peserta <span class="required">*</span></label>
                        <input type="number" class="form-input" name="max_participants" min="1">
                    </div>
                </div>

                <div class="form-group" id="prizeGroup" style="display:none;">
                    <label class="form-label">Total Hadiah</label>
                    <input type="number" class="form-input" name="total_prize" min="0">
                </div>

                <!-- Kategori Lomba -->
                <div class="form-group" id="competitionCategoryGroup" style="display:none;">
                    <label class="form-label">Kategori Lomba</label>
                    <div class="category-tags">
                        <div class="tag-list"></div>

                        <div class="tag-input-row">
                            <select class="form-select" id="competitionCategorySelect">
                                <option value="">Pilih Kategori</option>
                                @foreach ($competitionCategories ?? [] as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" class="form-input" id="competitionCategoryInput"
                                placeholder="Tambah kategori, mis. BLD" style="display:none;">
                            <button type="button" class="btn btn-secondary btn-small" id="addCategoryBtn">
                                + Tambah
                            </button>
                        </div>

                        <small class="form-helper">
                            Ubah, tambah, atau hapus kategori lomba sesuai kebutuhan event.
                        </small>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group" id="statusGroup" style="display:none;">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select class="form-select" name="status">
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="finished">Finished</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalAddEvent')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Event</button>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal Edit Event -->
    <div id="modalEditEvent" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Event</h3>
            <button class="modal-close" onclick="closeModal('modalEditEvent')">×</button>
        </div>

        <div class="modal-body">
            <form method="POST" id="editEventForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Cover -->
                <div class="form-group">
                    <label class="form-label">Cover Event</label>
                    <div class="image-upload-grid">
                        <label class="upload-box">
                            <input type="file" name="cover_image" accept="image/*" data-preview>
                            <img class="upload-preview-img" id="editCoverPreview">
                        </label>
                    </div>
                </div>

                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label">Judul Event <span class="required">*</span></label>
                    <input type="text" class="form-input" name="title" required>
                </div>

                <!-- Kategori -->
                <div class="form-group">
                    <label class="form-label">Kategori <span class="required">*</span></label>
                    <select class="form-select" id="editEventCategory" name="category" required>
                        <option value="">Pilih Kategori</option>
                        <option value="kompetisi">Kompetisi</option>
                        <option value="gathering">Gathering</option>
                        <option value="lainnya">Event Lainnya</option>
                    </select>
                </div>

                <!-- Kategori Lainnya -->
                <div class="form-group" id="editCustomCategoryGroup" style="display:none;">
                    <label class="form-label">Nama Kategori Lainnya <span class="required">*</span></label>
                    <input type="text" class="form-input" name="custom_category">
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea" name="description" required></textarea>
                </div>

                <!-- Tanggal & Jam -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" class="form-input" name="start_date" required>
                        <input type="time" class="form-input" name="start_time" style="margin-top:8px;" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Akhir <span class="required">*</span></label>
                        <input type="date" class="form-input" name="end_date" required>
                        <input type="time" class="form-input" name="end_time" style="margin-top:8px;" required>
                    </div>
                </div>

                <!-- Lokasi -->
                <div class="form-group">
                    <label class="form-label">Lokasi <span class="required">*</span></label>
                    <input type="text" class="form-input" name="location" required>
                </div>

                <!-- Detail Kompetisi -->
                <h4 class="form-section-title" id="editCompetitionTitle" style="display:none;">
                    Detail Kompetisi
                </h4>

                <div class="form-row">
                    <div class="form-group" id="editPriceGroup" style="display:none;">
                        <label class="form-label">Harga Tiket</label>
                        <input type="number" class="form-input" name="ticket_price" min="0">
                    </div>
                    <div class="form-group" id="editQuotaGroup" style="display:none;">
                        <label class="form-label">Max Peserta <span class="required">*</span></label>
                        <input type="number" class="form-input" name="max_participants" min="1">
                    </div>
                </div>

                <div class="form-group" id="editPrizeGroup" style="display:none;">
                    <label class="form-label">Total Hadiah</label>
                    <input type="number" class="form-input" name="total_prize" min="0">
                </div>

                <!-- Kategori Lomba -->
                <div class="form-group" id="editCompetitionCategoryGroup" style="display:none;">
                    <label class="form-label">Kategori Lomba</label>
                    <div class="category-tags">
                        <div class="tag-list" id="editTagList"></div>

                        <div class="tag-input-row">
                            <select class="form-select" id="editCompetitionCategorySelect">
                                <option value="">Pilih Kategori</option>
                                @foreach ($competitionCategories ?? [] as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="text" class="form-input" id="editCompetitionCategoryInput"
                                placeholder="Tambah kategori, mis. BLD" style="display:none;">
                            <button type="button" class="btn btn-secondary btn-small" id="editAddCategoryBtn">
                                + Tambah
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="form-group" id="editStatusGroup" style="display:none;">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select class="form-select" name="status">
                        <option value="upcoming">Upcoming</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="finished">Finished</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditEvent')">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
