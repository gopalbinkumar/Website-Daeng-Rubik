@extends('admin.layouts.app')

@section('title', 'Materi Pembelajaran')
@section('page-title', 'Materi Pembelajaran')

@section('content')

    <style>
        .form-input[readonly] {
            background-color: #ffffff;
            cursor: not-allowed;
        }
    </style>
    <div class="page-header">
        <h2 class="page-title">Materi Pembelajaran</h2>
        <button class="btn btn-primary" onclick="openModal('modalAddMateri')">
            + Tambah Materi
        </button>
    </div>

    <div class="table-wrapper">
        <form id="filterForm" method="GET" action="{{ route('admin.learn.index') }}">
            <div class="table-toolbar">
                <input type="text" name="search" class="search-input" placeholder="Search materi..."
                    value="{{ request('search') }}">

                <select name="level" class="filter-select">
                    <option value="">Level: Semua</option>
                    <option value="beginner" {{ request('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ request('level') == 'intermediate' ? 'selected' : '' }}>Intermediate
                    </option>
                    <option value="advanced" {{ request('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>

                <select name="category" class="filter-select">
                    <option value="">Kategori: Semua</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>


        <table class="table">
            <thead>
                <tr>
                    <th style="width: 100px;">Thumbnail</th>
                    <th>Judul Materi</th>
                    <th>Jenis Materi</th>
                    <th>Level</th>
                    <th>Kategori</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($materials as $materi)
                    <tr>
                        <!-- Thumbnail -->
                        <td style="text-align:center;">
                            @if ($materi->type === 'video')
                                <img src="{{ $materi->youtube_thumbnail }}"
                                    style="width:80px;height:60px;object-fit:cover;border-radius:8px;">
                            @else
                                @php
                                    $ext = strtolower(pathinfo($materi->module_path, PATHINFO_EXTENSION));
                                @endphp

                                @if ($ext === 'pdf')
                                    <i class="fa-solid fa-file-pdf" style="font-size:42px;color:#e63946;"></i>
                                @elseif (in_array($ext, ['doc', 'docx']))
                                    <i class="fa-solid fa-file-word" style="font-size:42px;color:#2b579a;"></i>
                                @elseif (in_array($ext, ['xls', 'xlsx']))
                                    <i class="fa-solid fa-file-excel" style="font-size:42px;color:#2e7d32;"></i>
                                @else
                                    <i class="fa-solid fa-file" style="font-size:42px;color:#6c757d;"></i>
                                @endif
                            @endif
                        </td>


                        <!-- Judul -->
                        <td>
                            <strong>{{ $materi->title }}</strong>
                        </td>

                        <!-- Jenis -->
                        <td>
                            {{ ucfirst($materi->type) }}
                        </td>

                        <!-- Level -->
                        <td>
                            <span
                                class="badge badge-{{ $materi->level === 'beginner' ? 'success' : ($materi->level === 'intermediate' ? 'warning' : 'danger') }}">
                                {{ ucfirst($materi->level) }}
                            </span>
                        </td>

                        <!-- Kategori -->
                        <td>
                            {{ $materi->category?->name ?? '-' }}
                        </td>

                        <!-- Aksi -->
                        <td>
                            <div class="table-actions">
                                <button class="btn btn-icon btn-secondary"
                                    onclick='openEditMateri(@json($materi))' title="Edit">
                                    <i class="fa-solid fa-edit"></i>
                                </button>

                                <form method="POST" action="{{ route('admin.learn.destroy', $materi) }}"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-icon btn-danger"
                                        onclick="return confirm('Hapus materi {{ $materi->title }}?')" title="Hapus">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            Belum ada materi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan
                {{ $materials->firstItem() }}–{{ $materials->lastItem() }}
                dari {{ $materials->total() }} materi
            </div>

            <div class="pagination-controls">
                {{-- PREV --}}
                @if ($materials->onFirstPage())
                    <button class="page-btn" disabled>‹</button>
                @else
                    <a href="{{ $materials->previousPageUrl() }}">
                        <button class="page-btn">‹</button>
                    </a>
                @endif

                {{-- PAGE --}}
                @for ($i = 1; $i <= $materials->lastPage(); $i++)
                    <a href="{{ $materials->url($i) }}">
                        <button class="page-btn {{ $materials->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </button>
                    </a>
                @endfor

                {{-- NEXT --}}
                @if ($materials->hasMorePages())
                    <a href="{{ $materials->nextPageUrl() }}">
                        <button class="page-btn">›</button>
                    </a>
                @else
                    <button class="page-btn" disabled>›</button>
                @endif
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
            <form method="POST" action="{{ route('admin.learn.store') }}" enctype="multipart/form-data">
                @csrf

                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label">Judul Materi <span class="required">*</span></label>
                    <input type="text" name="title" class="form-input" placeholder="Contoh: Pengenalan Rubik 3x3"
                        required>
                </div>

                <!-- Jenis Materi -->
                <div class="form-group">
                    <label class="form-label">Jenis Materi <span class="required">*</span></label>
                    <select name="type" class="form-select" id="jenisMateri" required>
                        <option value="">Pilih Jenis</option>
                        <option value="video">Video (YouTube)</option>
                        <option value="modul">Modul (Upload)</option>
                    </select>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea name="description" class="form-textarea" placeholder="Deskripsi materi..." required></textarea>
                </div>

                <!-- Level & Kategori -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level <span class="required">*</span></label>
                        <select name="level" class="form-select" required>
                            <option value="">Pilih Level</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori <span class="required">*</span></label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Video URL -->
                <div class="form-group" id="videoUrlGroup">
                    <label class="form-label">Video URL (YouTube) <span class="required">*</span></label>
                    <input type="text" name="video_url" class="form-input"
                        placeholder="https://www.youtube.com/watch?v=xxxx">
                </div>

                <!-- Modul Upload -->
                <div class="form-group" id="videoUploadGroup">
                    <label class="form-label">Upload Modul <span class="required">*</span></label>
                    <input type="file" name="module_file" class="form-input" accept=".pdf,.doc,.docx">
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalAddMateri')">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan Materi
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Modal Edit Materi -->
    <div id="modalEditMateri" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Materi</h3>
            <button class="modal-close" onclick="closeModal('modalEditMateri')">×</button>
        </div>

        <div class="modal-body">
            <form id="formEditMateri" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- HIDDEN TYPE (WAJIB) -->
                <input type="hidden" name="type" id="editTypeValue">

                <!-- Judul -->
                <div class="form-group">
                    <label class="form-label">Judul Materi *</label>
                    <input type="text" name="title" id="editTitle" class="form-input" required>
                </div>

                <!-- Jenis Materi (READONLY) -->
                <div class="form-group">
                    <label class="form-label">Jenis Materi</label>
                    <input type="text" id="editTypeText" class="form-input" readonly>
                </div>

                <!-- Deskripsi -->
                <div class="form-group">
                    <label class="form-label">Deskripsi *</label>
                    <textarea name="description" id="editDescription" class="form-textarea"></textarea>
                </div>

                <!-- Level & Kategori -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Level *</label>
                        <select name="level" id="editLevel" class="form-select" required>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Kategori *</label>
                        <select name="category_id" id="editCategory" class="form-select">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- VIDEO -->
                <div class="form-group" id="editVideoGroup">
                    <label class="form-label">Video URL (YouTube)</label>
                    <input type="text" name="video_url" id="editVideoUrl" class="form-input">
                </div>

                <!-- MODUL -->
                <div class="form-group" id="editModuleGroup">
                    <label class="form-label">Upload Modul (opsional)</label>
                    <input type="file" name="module_file" class="form-input" accept=".pdf,.doc,.docx,.xls,.xlsx">
                    <small class="text-muted">Kosongkan jika tidak ingin mengganti file</small>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        onclick="closeModal('modalEditMateri')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        const jenisMateri = document.getElementById('jenisMateri');
        const videoUrlGroup = document.getElementById('videoUrlGroup');
        const videoUploadGroup = document.getElementById('videoUploadGroup');

        // default sembunyi
        videoUrlGroup.style.display = 'none';
        videoUploadGroup.style.display = 'none';

        jenisMateri.addEventListener('change', function() {
            if (this.value === 'video') {
                videoUrlGroup.style.display = 'block';
                videoUploadGroup.style.display = 'none';
            } else if (this.value === 'modul') {
                videoUrlGroup.style.display = 'none';
                videoUploadGroup.style.display = 'block';
            } else {
                videoUrlGroup.style.display = 'none';
                videoUploadGroup.style.display = 'none';
            }
        });
    </script>
    <script>
        function openEditMateri(materi) {
            const form = document.getElementById('formEditMateri');

            // ROUTE UPDATE YANG BENAR
            form.action = "{{ url('admin/learn') }}/" + materi.id;

            // isi field
            document.getElementById('editTitle').value = materi.title;
            document.getElementById('editDescription').value = materi.description ?? '';
            document.getElementById('editLevel').value = materi.level;
            document.getElementById('editCategory').value = materi.category_id ?? '';

            // TYPE (WAJIB)
            document.getElementById('editTypeValue').value = materi.type;
            document.getElementById('editTypeText').value =
                materi.type === 'video' ? 'Video (YouTube)' : 'Modul (File)';

            const videoGroup = document.getElementById('editVideoGroup');
            const moduleGroup = document.getElementById('editModuleGroup');

            if (materi.type === 'video') {
                videoGroup.style.display = 'block';
                moduleGroup.style.display = 'none';
                document.getElementById('editVideoUrl').value = materi.video_url ?? '';
            } else {
                videoGroup.style.display = 'none';
                moduleGroup.style.display = 'block';
                document.getElementById('editVideoUrl').value = '';
            }

            openModal('modalEditMateri');
        }
    </script>
    <script>
    const form = document.getElementById('filterForm');

    // auto submit saat select berubah
    form.querySelectorAll('select').forEach(select => {
        select.addEventListener('change', () => {
            form.submit();
        });
    });

    // submit saat tekan Enter di search
    form.querySelector('input[name="search"]').addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            e.preventDefault();
            form.submit();
        }
    });
</script>



@endsection
