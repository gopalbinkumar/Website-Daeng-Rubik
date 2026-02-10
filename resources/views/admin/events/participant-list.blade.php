@extends('admin.layouts.app')

@section('title', 'Daftar Peserta Kompetisi')
@section('page-title', 'Daftar Peserta Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/events-results-index.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h2 class="page-title">Daftar Peserta Kompetisi</h2>
    </div>

    <div class="table-wrapper">
        <form method="GET" action="{{ route('admin.events.participants.index') }}">
            <div class="table-toolbar">
                <select name="event_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Kompetisi</option>
                    @foreach ($competitionEvents as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->title }}
                        </option>
                    @endforeach
                </select>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                @if ($summaryMode)
                    <tr>
                        <th>Nama Kompetisi</th>
                        <th>Jumlah Pendaftar</th>
                        <th>Tanggal Kompetisi</th>
                    </tr>
                @else
                    <tr>
                        <th>Nama Peserta</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th style="width: 120px;">Aksi</th>
                    </tr>
                @endif
            </thead>

            <tbody>
                @if ($summaryMode)
                    @forelse ($eventSummaries as $event)
                        <tr>
                            <td><strong>{{ $event->title }}</strong></td>
                            <td>{{ $event->registrations_count }}</td>
                            <td>{{ $event->start_datetime->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center;color:var(--admin-text-muted);">
                                Belum ada event kompetisi.
                            </td>
                        </tr>
                    @endforelse
                @else
                    @forelse ($participants as $row)
                        <tr>
                            <td><strong>{{ $row->participant_name }}</strong></td>
                            <td>
                                <span style="color:var(--admin-text-muted);">
                                    {{ $row->participant_email ?? '-' }}
                                </span>
                            </td>
                            <td>{{ $row->created_at->format('d M Y') }}</td>
                            <td>
                                @if ($row->status === 'accepted')
                                    <span class="badge badge-success">Accepted</span>
                                @elseif ($row->status === 'rejected')
                                    <span class="badge badge-danger">Rejected</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button type="button" class="btn btn-icon btn-secondary"
                                        onclick="openEditParticipantModal({{ $row->id }})" title="Edit Peserta">
                                        <i class="fa-solid fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-icon btn-primary"
                                        onclick="openParticipantModal({{ $row->id }})" title="Detail Peserta">
                                        <i class="fa-solid fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center;color:var(--admin-text-muted);">
                                Belum ada peserta kompetisi.
                            </td>
                        </tr>
                    @endforelse
                @endif
            </tbody>
        </table>

        @php
            $pager = $summaryMode ? $eventSummaries : $participants;
            $label = $summaryMode ? 'kompetisi' : 'peserta';
        @endphp

        <div class="pagination">
            <div class="pagination-info">
                Menampilkan
                {{ $pager->firstItem() ?? 0 }}–{{ $pager->lastItem() ?? 0 }}
                dari {{ $pager->total() }} {{ $label }}
            </div>

            <div class="pagination-controls">
                {{-- PREV --}}
                @if ($pager->onFirstPage())
                    <button class="page-btn" disabled>‹</button>
                @else
                    <a href="{{ $pager->previousPageUrl() }}">
                        <button class="page-btn">‹</button>
                    </a>
                @endif

                {{-- PAGE NUMBERS --}}
                @for ($i = 1; $i <= $pager->lastPage(); $i++)
                    <a href="{{ $pager->url($i) }}">
                        <button class="page-btn {{ $pager->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </button>
                    </a>
                @endfor

                {{-- NEXT --}}
                @if ($pager->hasMorePages())
                    <a href="{{ $pager->nextPageUrl() }}">
                        <button class="page-btn">›</button>
                    </a>
                @else
                    <button class="page-btn" disabled>›</button>
                @endif
            </div>
        </div>

    </div>

    {{-- MODAL DETAIL PESERTA --}}
    <div id="modalParticipant" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Detail Peserta</h3>
            <button class="modal-close" onclick="closeModal('modalParticipant')">×</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label class="form-label">Nama Peserta</label>
                <div id="modalParticipantName"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <div id="modalParticipantEmail"></div>
            </div>

            <div class="form-group">
                <label class="form-label">WhatsApp</label>
                <div id="modalParticipantWhatsapp"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Kategori Lomba Diikuti</label>
                <div id="modalParticipantCategories"></div>
            </div>

            <div class="modal-footer">
                <form id="acceptForm" method="POST">@csrf
                    <button type="submit" class="btn btn-primary">Accept</button>
                </form>
                <form id="rejectForm" method="POST">@csrf
                    <button type="submit" class="btn btn-secondary">Reject</button>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT PESERTA --}}
    <div id="modalEditParticipant" class="modal">
        <div class="modal-header">
            <h3 class="modal-title">Edit Peserta</h3>
            <button class="modal-close" onclick="closeModal('modalEditParticipant')">×</button>
        </div>

        <div class="modal-body">
            <form id="editParticipantForm" method="POST">
                @csrf
                @method('PUT')

                {{-- Kategori Lomba (EDIT PESERTA) --}}
                <div class="form-group" id="editCompetitionCategoryGroup">
                    <label class="form-label">Kategori Lomba</label>

                    <div class="category-tags">
                        <div class="tag-list" id="editTagList"></div>

                        <div class="tag-input-row">
                            <select class="form-select" id="editCompetitionCategorySelect">
                                <option value="">Pilih Kategori</option>
                                {{-- OPTION DIISI VIA JS --}}
                            </select>

                            <input type="text" class="form-input" id="editCompetitionCategoryInput"
                                placeholder="Tambah kategori, mis. BLD" style="display:none;">

                            <button type="button" class="btn btn-secondary btn-small" id="editAddCategoryBtn">
                                + Tambah
                            </button>
                        </div>

                        <small class="form-helper">
                            Ubah, tambah, atau hapus kategori lomba sesuai kebutuhan peserta.
                        </small>
                    </div>
                </div>


                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modalEditParticipant')">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const participants = @json($participants ?? []);

        /* =========================
         | MODAL DETAIL
         ========================= */
        function openParticipantModal(id) {
            const data = participants.data.find(p => p.id === id);
            if (!data) return;

            document.getElementById('modalParticipantName').innerText =
                data.participant_name;

            document.getElementById('modalParticipantEmail').innerText =
                data.participant_email;

            document.getElementById('modalParticipantWhatsapp').innerText =
                data.participant_whatsapp;

            const categoryBox = document.getElementById('modalParticipantCategories');
            categoryBox.innerHTML = '';

            if (data.competition_categories?.length) {
                data.competition_categories.forEach(cat => {
                    categoryBox.innerHTML += '• ' + cat.name + '<br>';
                });
            } else {
                categoryBox.innerText = '-';
            }

            document.getElementById('acceptForm').action =
                `/admin/events/participants/${id}/accept`;

            document.getElementById('rejectForm').action =
                `/admin/events/participants/${id}/reject`;

            openModal('modalParticipant');
        }

        /* =========================
         | MODAL EDIT
         ========================= */
        function openEditParticipantModal(id) {
            const data = participants.data.find(p => p.id === id);
            if (!data) return;

            const form = document.getElementById('editParticipantForm');
            const tagList = document.getElementById('editTagList');
            const select = document.getElementById('editCompetitionCategorySelect');
            const addBtn = document.getElementById('editAddCategoryBtn');

            // SET ACTION FORM (PENTING)
            form.action = `/admin/events/participants/${id}`;

            // RESET TAG & OPTION
            tagList.innerHTML = '';
            form.querySelectorAll('input[name="categories[]"]').forEach(i => i.remove());
            select.innerHTML = '<option value="">Pilih Kategori</option>';

            // OPTION KATEGORI DARI EVENT
            if (data.event?.competition_categories) {
                data.event.competition_categories.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.textContent = cat.name;
                    select.appendChild(opt);
                });
            }

            // TAG KATEGORI YANG SUDAH DIPILIH PESERTA
            if (data.competition_categories) {
                data.competition_categories.forEach(cat => {
                    addEditCategoryTag(cat.id, cat.name);
                });
            }

            // TOMBOL TAMBAH (SAMA DENGAN JS LAMA)
            addBtn.onclick = function() {
                const categoryId = select.value;
                const categoryName =
                    select.options[select.selectedIndex]?.text || '';

                if (!categoryId || !categoryName) return;

                addEditCategoryTag(categoryId, categoryName);
                select.value = '';
            };

            openModal('modalEditParticipant');
        }

        /* =========================
         | TAG HELPER (SAMA PERSIS DENGAN EDIT EVENT)
         ========================= */
        function addEditCategoryTag(id, name) {
            const form = document.getElementById('editParticipantForm');
            const tagList = document.getElementById('editTagList');

            // CEGAR DUPLIKAT (CEK HIDDEN INPUT)
            if (
                form.querySelector(
                    `input[name="categories[]"][value="${id}"]`
                )
            ) return;

            // TAG (SAMA PERSIS DENGAN EVENT)
            const tag = document.createElement('span');
            tag.className = 'tag';
            tag.innerHTML = `${name} ✕`;

            // HIDDEN INPUT
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'categories[]';
            hidden.value = id;

            // KLIK TAG → HAPUS TAG & INPUT
            tag.addEventListener('click', () => {
                tag.remove();
                hidden.remove();
            });

            tagList.appendChild(tag);
            form.appendChild(hidden);
        }
    </script>
@endpush
