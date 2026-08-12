@extends('admin.layouts.app')

@section('body-class', 'hide-admin-sidebar hide-admin-topbar')

@section('title', 'Input Hasil Kompetisi')
@section('page-title', 'Input Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ assetVersion('assets/admin/events-results-create.css') }}">
@endpush

@section('content')
    <style>
        .category-icon-list {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            flex-wrap: wrap;
        }

        .category-icon-choice {
            width: 36px;
            /* sebelumnya 42px */
            height: 36px;
            /* sebelumnya 42px */
            border: none;
            background: transparent;
            padding: 0;
            margin: 0;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            cursor: pointer;
            border-radius: 8px;
            color: #bfc1c3;
            opacity: 1;

            transition: color .15s ease, transform .15s ease;
        }

        .category-icon-choice .cubing-category-icon {
            width: 30px !important;
            /* ukuran icon asli */
            height: 30px !important;
            display: block;
            flex-shrink: 0;

            background-color: currentColor;

            mask-image: var(--icon-url);
            mask-repeat: no-repeat;
            mask-position: center;
            mask-size: contain;

            -webkit-mask-image: var(--icon-url);
            -webkit-mask-repeat: no-repeat;
            -webkit-mask-position: center;
            -webkit-mask-size: contain;
        }

        /* Tidak terpilih = abu-abu muted */
        .category-icon-choice {
            color: #bfc1c3;
        }

        /* Terpilih = hitam */
        .category-icon-choice.is-active {
            color: #000;
        }

        /* Hover boleh sedikit lebih gelap */
        .category-icon-choice:hover,
        .category-icon-choice:focus {
            color: #000;
            transform: scale(1.06);
            outline: none;
        }
    </style>
    <div class="page-header">
        <h2 class="page-title">{{ $event->title }}</h2>
        {{-- <a href="{{ route('admin.events.competition.index') }}" class="btn btn-secondary">
            Kembali
        </a> --}}
    </div>

    <div class="card admin-results-create">
        <div class="results-layout">
            {{-- ================= LEFT : FORM ================= --}}
            <div class="results-form">
                <form id="resultForm" method="POST" action="{{ route('admin.events.competition.store') }}">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                    <input type="hidden" name="result_id" id="resultId">

                    <h3 class="form-section-title">Kategori</h3>

                    <div class="form-row">
                        <div class="form-group category-icon-select-list" data-category-icon-list>
                            {{-- Select asli tetap dipakai untuk submit dan JS --}}
                            <select name="competition_category_id" class="form-select category-native-select" required
                                style="display: none;">
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" data-code="{{ $cat->code }}"
                                        @selected(($selectedCategory && $selectedCategory == $cat->id) || (!$selectedCategory && $loop->first))>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Semua kategori langsung tampil sebagai icon --}}
                            <div class="category-icon-list">
                                @foreach ($categories as $cat)
                                    <button type="button"
                                        class="category-icon-choice {{ ($selectedCategory && $selectedCategory == $cat->id) || (!$selectedCategory && $loop->first) ? 'is-active' : '' }}"
                                        data-category-choice data-id="{{ $cat->id }}" data-name="{{ $cat->name }}"
                                        title="{{ $cat->name }}" aria-label="{{ $cat->name }}">
                                        <x-category-icon :code="$cat->code" :name="$cat->name" size="34" />
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- ROUND --}}
                    <h3 class="form-section-title">Round</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <select name="round_number" class="form-select" required>
                                <option value="1" @selected($selectedRound == 1)>Round 1</option>
                                @for ($i = 2; $i <= 3; $i++)
                                    <option value="{{ $i }}" @selected($selectedRound == $i)>
                                        Round {{ $i }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <label class="manual-competitor-toggle"
                        style="display:inline-flex;align-items:center;gap:8px;width:fit-content;margin-top:8px;font-size:13px;color:#111827;cursor:pointer;user-select:none;">
                        <input type="checkbox" name="manual_competitor" id="manualCompetitorCheckbox" value="1"
                            style="width:15px;height:15px;margin:0;accent-color:#1976d2;cursor:pointer;">
                        <span style="line-height:1.3;">Tambah kompetitor baru</span>
                    </label>

                    {{-- PESERTA --}}
                    <h3 class="form-section-title">Kompetitor</h3>
                    <div class="form-row">
                        <div class="form-group" style="position:relative">
                            <input type="text" id="participantInput" class="form-input"
                                data-event-id="{{ $event->id }}" autocomplete="off" placeholder="Nama kompetitor"
                                name="participant_name" required>

                            <input type="hidden" name="user_id" id="participantUserId">

                            <div id="participantDropdown"
                                style="
                                    position:absolute;
                                    top:100%;
                                    left:0;
                                    right:0;
                                    background:#fff;
                                    border:1px solid var(--admin-border);
                                    border-radius:8px;
                                    z-index:999;
                                    display:none;
                                    max-height:220px;
                                    overflow-y:auto;
                                ">
                            </div>
                        </div>
                    </div>

                    {{-- ATTEMPT --}}
                    <h3 class="form-section-title">Attempt</h3>
                    <div class="form-row attempt-grid">
                        @for ($i = 1; $i <= 5; $i++)
                            <div class="form-group">
                                <input type="text" name="attempt{{ $i }}" class="form-input attempt-input"
                                    placeholder="Attempt {{ $i }}">
                            </div>
                        @endfor
                    </div>

                    {{-- HASIL --}}
                    <div class="result-summary-fixed">
                        <div class="result-box">
                            <div class="result-label">Best</div>
                            <div class="result-value" id="bestValue">-</div>
                        </div>

                        <div class="result-box">
                            <div class="result-label">Average</div>
                            <div class="result-value" id="avgValue">-</div>
                        </div>
                    </div>
                    {{-- Hidden input untuk submit --}}
                    <input type="hidden" name="best" id="bestInput">
                    <input type="hidden" name="average" id="avgInput">


                    <div class="modal-footer">
                        <a href="{{ url()->previous() }}" class="btn btn-danger">Batal</a>
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>

            {{-- ================= RIGHT : TABLE ================= --}}
            <div class="results-preview">
                <h3 id="resultsTitle">
                    <br>
                </h3>

                <div class="table-wrapper">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th class="text-end">1</th>
                                <th class="text-end">2</th>
                                <th class="text-end">3</th>
                                <th class="text-end">4</th>
                                <th class="text-end">5</th>
                                <th class="text-end">Average</th>
                                <th class="text-end">Best</th>
                            </tr>
                        </thead>
                        <tbody id="resultsTableBody">
                            <tr>
                                <td colspan="9" style="text-align:center;color:var(--admin-text-muted);">
                                    Pilih kategori dan round
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div id="resultContextMenu" class="result-context-menu"
        style="position:fixed;display:none;min-width:120px;padding:6px;border:1px solid #e5e7eb;border-radius:8px;background:#fff;box-shadow:0 12px 28px rgba(15,23,42,.14);z-index:99999;">
        <button type="button" data-context-action="edit"
            style="display:block;width:100%;border:0;border-radius:6px;background:transparent;padding:8px 10px;color:#111827;font-size:13px;text-align:left;cursor:pointer;">
            Edit
        </button>
        <button type="button" data-context-action="delete" class="is-danger"
            style="display:block;width:100%;border:0;border-radius:6px;background:transparent;padding:8px 10px;color:#dc2626;font-size:13px;text-align:left;cursor:pointer;">
            Hapus
        </button>
    </div>

    <script>
        (function() {
            const form = document.getElementById('resultForm');
            const resultIdInput = document.getElementById('resultId');

            const participantInput = document.getElementById('participantInput');
            const participantDropdown = document.getElementById('participantDropdown');
            const participantUserId = document.getElementById('participantUserId');
            const participantGroup = participantInput?.closest('.form-group');
            const manualCompetitorCheckbox = document.getElementById('manualCompetitorCheckbox');

            const categorySelect = document.querySelector('[name="competition_category_id"]');
            const roundSelect = document.querySelector('[name="round_number"]');

            const attempts = Array.from(document.querySelectorAll('.attempt-input'));

            const bestEl = document.getElementById('bestValue');
            const avgEl = document.getElementById('avgValue');
            const bestInput = document.getElementById('bestInput');
            const avgInput = document.getElementById('avgInput');

            const resultsTableBody = document.getElementById('resultsTableBody');
            const resultsTitle = document.getElementById('resultsTitle');
            const resultContextMenu = document.getElementById('resultContextMenu');
            const saveButton = form?.querySelector('button.btn-primary');

            let participantCache = [];
            let loadedParticipantCategoryId = null;
            let lastRequestKey = null;
            let isFirstLoad = true;
            let currentResults = [];
            let contextResult = null;

            if (
                !form ||
                !resultIdInput ||
                !participantInput ||
                !participantDropdown ||
                !participantUserId ||
                !manualCompetitorCheckbox ||
                !categorySelect ||
                !roundSelect ||
                !resultContextMenu ||
                !attempts.length
            ) {
                return;
            }

            // =====================================================
            // UTIL RESULT
            // =====================================================
            function resetResultSummary() {
                bestEl.textContent = '-';
                avgEl.textContent = '-';
                bestInput.value = '';
                avgInput.value = '';
            }

            function escapeHtml(value) {
                return String(value ?? '').replace(/[&<>"']/g, char => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;',
                } [char]));
            }

            function clearEditTarget() {
                resultIdInput.value = '';
            }

            function hideContextMenu() {
                resultContextMenu.style.display = 'none';
                contextResult = null;
            }

            function styleContextMenuButtons() {
                resultContextMenu.querySelectorAll('[data-context-action]').forEach(button => {
                    const isDanger = button.dataset.contextAction === 'delete';
                    const defaultBg = 'transparent';
                    const hoverBg = isDanger ? '#fef2f2' : '#f3f4f6';

                    button.style.display = 'block';
                    button.style.width = '100%';
                    button.style.border = '0';
                    button.style.borderRadius = '6px';
                    button.style.background = defaultBg;
                    button.style.padding = '8px 10px';
                    button.style.color = isDanger ? '#dc2626' : '#111827';
                    button.style.fontSize = '13px';
                    button.style.textAlign = 'left';
                    button.style.cursor = 'pointer';

                    button.addEventListener('mouseenter', () => {
                        button.style.background = hoverBg;
                    });

                    button.addEventListener('mouseleave', () => {
                        button.style.background = defaultBg;
                    });
                });
            }

            function getContextResultFromEvent(e) {
                const row = e.target.closest('#resultsTableBody tr[data-result-id]');

                if (!row) {
                    return null;
                }

                const result = currentResults.find(item => String(item.id ?? '') === String(row.dataset.resultId));

                return result || currentResults[Number(row.dataset.resultIndex)] || null;
            }

            function resultDeleteUrl(resultId) {
                return `{{ route('admin.events.competition.destroy-result-post', [$event, '__RESULT_ID__']) }}`
                    .replace('__RESULT_ID__', encodeURIComponent(resultId));
            }

            function showAdminAlert(options) {
                if (window.Swal) {
                    return Swal.fire({
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn btn-secondary',
                            ...(options.customClass || {}),
                        },
                        ...options,
                    });
                }

                console.error(options.text || options.title || 'Terjadi kesalahan');
                return Promise.resolve({
                    isConfirmed: false
                });
            }

            function showAdminSuccess(message) {
                return showAdminAlert({
                    icon: 'success',
                    title: 'Berhasil',
                    text: message,
                    showConfirmButton: false,
                    timer: 1500,
                });
            }

            function showAdminError(message) {
                return showAdminAlert({
                    icon: 'error',
                    title: 'Gagal',
                    text: message,
                });
            }

            function toCS(v) {
                if (!v) return null;

                v = String(v).toUpperCase();

                if (v === 'DNF' || v === 'DNS') return Infinity;

                if (v.includes(':')) {
                    const [m, r] = v.split(':');
                    const [s, d] = r.split('.');

                    return (+m * 60 + +s) * 100 + +d;
                }

                if (v.includes('.')) {
                    const [s, d] = v.split('.');

                    return (+s) * 100 + +d;
                }

                return null;
            }

            function fromCS(cs) {
                const m = Math.floor(cs / 6000);
                const s = Math.floor((cs % 6000) / 100);
                const d = cs % 100;

                return m > 0 ?
                    `${m}:${String(s).padStart(2, '0')}.${String(d).padStart(2, '0')}` :
                    `${s}.${String(d).padStart(2, '0')}`;
            }

            function timeToCS(v) {
                if (!v) return Infinity;

                v = String(v).toUpperCase();

                if (v === 'DNF' || v === 'DNS') return Infinity;

                if (v.includes(':')) {
                    const [m, r] = v.split(':');
                    const [s, d] = r.split('.');

                    return (+m * 60 + +s) * 100 + +d;
                }

                if (v.includes('.')) {
                    const [s, d] = v.split('.');

                    return (+s) * 100 + +d;
                }

                return Infinity;
            }

            function calc() {
                const vals = [];

                attempts.forEach(input => {
                    const v = toCS(input.value);

                    if (v !== null) {
                        vals.push(v);
                    }
                });

                if (!vals.length) {
                    resetResultSummary();
                    return;
                }

                vals.sort((a, b) => a - b);

                bestEl.textContent = vals[0] === Infinity ? 'DNF' : fromCS(vals[0]);
                bestInput.value = bestEl.textContent;

                const dnfCount = vals.filter(v => v === Infinity).length;

                if (vals.length < 5 || dnfCount >= 2) {
                    avgEl.textContent = 'DNF';
                    avgInput.value = 'DNF';
                    return;
                }

                const mid = vals.slice(1, 4);

                if (mid.some(v => v === Infinity)) {
                    avgEl.textContent = 'DNF';
                    avgInput.value = 'DNF';
                    return;
                }

                const avg = Math.round(mid.reduce((a, b) => a + b, 0) / 3);

                avgEl.textContent = fromCS(avg);
                avgInput.value = avgEl.textContent;
            }

            // =====================================================
            // LOCK / UNLOCK ATTEMPT
            // =====================================================
            function isManualCompetitor() {
                return manualCompetitorCheckbox.checked;
            }

            function isReadyToInputAttempt() {
                return Boolean(
                    categorySelect.value &&
                    roundSelect.value &&
                    (
                        isManualCompetitor() ?
                        participantInput.value.trim() :
                        participantUserId.value
                    )
                );
            }

            function setAttemptState() {
                const ready = isReadyToInputAttempt();

                attempts.forEach(input => {
                    input.readOnly = !ready;
                    input.classList.toggle('is-disabled', !ready);

                    if (!ready) {
                        input.value = '';
                    }
                });

                if (!ready) {
                    resetResultSummary();
                }
            }

            function focusAttempt(index) {
                if (!attempts.length) return;

                if (index < 0) {
                    attempts[0].focus();
                    attempts[0].select();
                    return;
                }

                if (index >= attempts.length) {
                    saveButton?.focus();
                    return;
                }

                if (attempts[index].readOnly) return;

                attempts[index].focus();
                attempts[index].select();
            }

            function allAttemptsFilled() {
                return attempts.every(input => input.value.trim() !== '');
            }

            function focusFirstEmptyAttempt() {
                const firstEmptyIndex = attempts.findIndex(input => input.value.trim() === '');

                if (firstEmptyIndex !== -1) {
                    focusAttempt(firstEmptyIndex);
                }
            }

            // =====================================================
            // PARTICIPANT AUTOCOMPLETE
            // =====================================================
            let activeParticipantIndex = -1;

            function hideParticipantDropdown() {
                participantDropdown.innerHTML = '';
                participantDropdown.style.display = 'none';
                activeParticipantIndex = -1;
            }

            function applyManualCompetitorMode(clearName = true, focusInput = true) {
                const isManual = isManualCompetitor();

                participantInput.placeholder = isManual ? 'Nama kompetitor baru' : 'Nama kompetitor';
                participantInput.classList.toggle('is-manual-competitor', isManual);

                participantUserId.value = '';
                participantUserId.removeAttribute('value');
                hideParticipantDropdown();

                if (clearName) {
                    clearEditTarget();
                    participantInput.value = '';

                    attempts.forEach(input => {
                        input.value = '';
                    });

                    resetResultSummary();
                }

                lastRequestKey = null;
                setAttemptState();

                if (focusInput) {
                    participantInput.focus();
                }
            }

            async function loadAllParticipants() {
                if (isManualCompetitor()) return;

                const eventId = participantInput.dataset.eventId;
                const categoryId = categorySelect.value;

                if (!categoryId) return;

                if (loadedParticipantCategoryId === String(categoryId)) {
                    return;
                }

                try {
                    const params = new URLSearchParams({
                        competition_category_id: categoryId
                    });

                    const res = await fetch(`/admin/events/${eventId}/accepted-participants?${params.toString()}`);

                    participantCache = await res.json();
                    loadedParticipantCategoryId = String(categoryId);
                } catch (e) {
                    console.error('Gagal load peserta', e);
                }
            }

            function getParticipantItems() {
                return Array.from(participantDropdown.querySelectorAll('.participant-option'));
            }

            function updateActiveParticipant() {
                const items = getParticipantItems();

                items.forEach((item, index) => {
                    const isActive = index === activeParticipantIndex;

                    item.style.background = isActive ? '#f3f4f6' : '#fff';

                    if (isActive) {
                        item.scrollIntoView({
                            block: 'nearest'
                        });
                    }
                });
            }

            async function selectParticipant(p) {
                if (isManualCompetitor()) return;

                clearEditTarget();
                participantInput.value = p.name;
                participantUserId.value = p.user_id;
                participantUserId.setAttribute('value', p.user_id);

                participantDropdown.style.display = 'none';
                participantDropdown.innerHTML = '';
                activeParticipantIndex = -1;

                setAttemptState();
                lastRequestKey = null;

                await checkAndPrefill();

                if (isReadyToInputAttempt()) {
                    focusAttempt(0);
                }
            }

            function renderParticipantDropdown(list) {
                participantDropdown.innerHTML = '';
                activeParticipantIndex = -1;

                if (!list.length) {
                    participantDropdown.style.display = 'none';
                    return;
                }

                list.forEach((p, index) => {
                    const div = document.createElement('div');

                    div.className = 'participant-option';
                    div.textContent = p.name;
                    div.style.padding = '10px 12px';
                    div.style.cursor = 'pointer';
                    div.style.background = '#fff';

                    div.addEventListener('mouseenter', () => {
                        activeParticipantIndex = index;
                        updateActiveParticipant();
                    });

                    div.addEventListener('mouseleave', () => {
                        activeParticipantIndex = -1;
                        updateActiveParticipant();
                    });

                    div.addEventListener('click', () => {
                        selectParticipant(p);
                    });

                    participantDropdown.appendChild(div);
                });

                participantDropdown.style.display = 'block';
            }

            participantInput.addEventListener('focus', async () => {
                if (isManualCompetitor()) {
                    hideParticipantDropdown();
                    return;
                }

                await loadAllParticipants();

                // Jangan tampilkan dropdown saat input baru diklik
                participantDropdown.style.display = 'none';
            });

            participantInput.addEventListener('input', async () => {
                const q = participantInput.value.trim().toLowerCase();

                if (!isManualCompetitor()) {
                    clearEditTarget();
                    participantUserId.value = '';
                    participantUserId.removeAttribute('value');
                }

                lastRequestKey = null;
                setAttemptState();

                if (isManualCompetitor()) {
                    hideParticipantDropdown();
                    return;
                }

                await loadAllParticipants();

                // Minimal 2 karakter baru tampilkan dropdown
                if (q.length < 2) {
                    participantDropdown.innerHTML = '';
                    participantDropdown.style.display = 'none';
                    activeParticipantIndex = -1;
                    return;
                }

                const filtered = participantCache.filter(p =>
                    p.name.toLowerCase().includes(q)
                );

                renderParticipantDropdown(filtered);
            });

            function resetParticipantSelection() {
                clearEditTarget();
                participantInput.value = '';
                participantUserId.value = '';
                participantUserId.removeAttribute('value');

                hideParticipantDropdown();

                participantCache = [];
                loadedParticipantCategoryId = null;

                attempts.forEach(input => {
                    input.value = '';
                });

                resetResultSummary();
                setAttemptState();
            }

            participantInput.addEventListener('keydown', async e => {
                if (isManualCompetitor()) {
                    if (e.key === 'Enter') {
                        e.preventDefault();

                        if (isReadyToInputAttempt()) {
                            focusAttempt(0);
                        }
                    }

                    if (e.key === 'Escape') {
                        hideParticipantDropdown();
                    }

                    return;
                }

                const q = participantInput.value.trim().toLowerCase();
                const items = getParticipantItems();
                const dropdownIsOpen = participantDropdown.style.display === 'block';

                // ArrowDown → pilih nama berikutnya
                if (e.key === 'ArrowDown') {
                    e.preventDefault();

                    if (q.length < 2) return;

                    await loadAllParticipants();

                    if (!dropdownIsOpen || !items.length) {
                        const filtered = participantCache.filter(p =>
                            p.name.toLowerCase().includes(q)
                        );

                        renderParticipantDropdown(filtered);
                    }

                    const updatedItems = getParticipantItems();

                    if (!updatedItems.length) return;

                    activeParticipantIndex =
                        activeParticipantIndex < updatedItems.length - 1 ?
                        activeParticipantIndex + 1 :
                        0;

                    updateActiveParticipant();
                    return;
                }

                // ArrowUp → pilih nama sebelumnya
                if (e.key === 'ArrowUp') {
                    e.preventDefault();

                    const updatedItems = getParticipantItems();

                    if (!dropdownIsOpen || !updatedItems.length) return;

                    activeParticipantIndex =
                        activeParticipantIndex > 0 ?
                        activeParticipantIndex - 1 :
                        updatedItems.length - 1;

                    updateActiveParticipant();
                    return;
                }

                // Enter → pilih nama yang sedang disorot
                if (e.key === 'Enter') {
                    const updatedItems = getParticipantItems();

                    if (dropdownIsOpen && updatedItems.length) {
                        e.preventDefault();

                        if (activeParticipantIndex < 0) {
                            activeParticipantIndex = 0;
                            updateActiveParticipant();
                        }

                        updatedItems[activeParticipantIndex].click();
                        return;
                    }

                    // Cegah submit form saat masih di input nama kompetitor
                    if (!participantUserId.value) {
                        e.preventDefault();
                    }
                }

                // Escape → tutup dropdown
                if (e.key === 'Escape') {
                    participantDropdown.style.display = 'none';
                    activeParticipantIndex = -1;
                }
            });

            document.addEventListener('click', e => {
                if (participantGroup && !participantGroup.contains(e.target)) {
                    hideParticipantDropdown();
                }
            });

            // =====================================================
            // FORMAT ATTEMPT + KEYBOARD NAVIGATION
            // =====================================================
            function formatAttemptInput(input) {
                let v = input.value.toUpperCase();

                if (v === 'DNF' || v === 'DNS') {
                    input.value = v;
                    return;
                }

                v = v.replace(/\D/g, '');

                if (!v) {
                    input.value = '';
                    return;
                }

                if (v.length <= 2) {
                    input.value = v;
                    return;
                }

                if (v.length <= 4) {
                    const sec = v.slice(0, -2);
                    const dec = v.slice(-2);

                    input.value = `${sec}.${dec}`;
                    return;
                }

                const dec = v.slice(-2);
                const sec = v.slice(-4, -2);
                const min = v.slice(0, -4);

                input.value = `${min}:${sec}.${dec}`;
            }

            attempts.forEach((input, index) => {
                input.addEventListener('keydown', function(e) {
                    if (input.readOnly) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        return false;
                    }

                    const key = e.key.toLowerCase();

                    // ENTER → langsung simpan, tapi hanya kalau semua attempt terisi
                    if (e.key === 'Enter') {
                        e.preventDefault();

                        formatAttemptInput(input);
                        calc();

                        if (!allAttemptsFilled()) {
                            focusFirstEmptyAttempt();
                            return;
                        }

                        form.requestSubmit();
                        return;
                    }

                    // Arrow Down → pindah ke attempt berikutnya
                    if (e.key === 'ArrowDown') {
                        e.preventDefault();
                        focusAttempt(index + 1);
                        return;
                    }

                    // Arrow Up → pindah ke attempt sebelumnya
                    if (e.key === 'ArrowUp') {
                        e.preventDefault();
                        focusAttempt(index - 1);
                        return;
                    }

                    // D → DNF lalu lanjut ke attempt berikutnya
                    if (key === 'd') {
                        e.preventDefault();
                        input.value = 'DNF';
                        calc();
                        focusAttempt(index + 1);
                        return;
                    }

                    // S → DNS lalu lanjut ke attempt berikutnya
                    if (key === 's') {
                        e.preventDefault();
                        input.value = 'DNS';
                        calc();
                        focusAttempt(index + 1);
                        return;
                    }
                }, true);

                input.addEventListener('input', function(e) {
                    if (input.readOnly) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        input.value = '';
                        return false;
                    }

                    formatAttemptInput(input);
                    calc();
                }, true);

                input.addEventListener('paste', function(e) {
                    if (input.readOnly) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        return false;
                    }
                }, true);

                input.addEventListener('keyup', calc);
                input.addEventListener('blur', calc);
            });

            // =====================================================
            // CHECK EXISTING RESULT / PREFILL EDIT MODE
            // =====================================================
            async function checkAndPrefill() {
                if (isManualCompetitor() || !categorySelect.value || !roundSelect.value || !participantUserId.value) {
                    return;
                }

                const key = `${categorySelect.value}-${roundSelect.value}-${participantUserId.value}`;

                if (key === lastRequestKey) return;

                lastRequestKey = key;

                try {
                    const params = new URLSearchParams({
                        competition_category_id: categorySelect.value,
                        round_number: roundSelect.value,
                        user_id: participantUserId.value
                    });

                    const res = await fetch(
                        `{{ route('admin.events.competition.check', $event) }}?${params.toString()}`
                    );

                    const json = await res.json();

                    attempts.forEach(input => input.value = '');
                    resetResultSummary();

                    if (!json.exists) {
                        return;
                    }

                    attempts[0].value = json.data.attempt1 ?? '';
                    attempts[1].value = json.data.attempt2 ?? '';
                    attempts[2].value = json.data.attempt3 ?? '';
                    attempts[3].value = json.data.attempt4 ?? '';
                    attempts[4].value = json.data.attempt5 ?? '';

                    bestEl.textContent = json.data.best ?? '-';
                    avgEl.textContent = json.data.average ?? '-';
                    bestInput.value = json.data.best ?? '';
                    avgInput.value = json.data.average ?? '';
                    resultIdInput.value = json.data.id ?? '';

                } catch (e) {
                    console.error('Gagal cek hasil kompetisi', e);
                }
            }

            // =====================================================
            // LOAD TABLE RESULT
            // =====================================================
            async function loadTable() {
                if (!categorySelect.value || !roundSelect.value) {
                    if (isFirstLoad) {
                        resultsTableBody.innerHTML = `
                        <tr>
                            <td colspan="9" style="text-align:center;color:#9ca3af">
                                Pilih kategori dan round
                            </td>
                        </tr>
                    `;
                    }

                    return;
                }

                const categoryName = categorySelect.options[categorySelect.selectedIndex].text;
                resultsTitle.textContent = `${categoryName} – Round ${roundSelect.value}`;

                try {
                    const params = new URLSearchParams({
                        competition_category_id: categorySelect.value,
                        round_number: roundSelect.value
                    });

                    const res = await fetch(
                        `{{ route('admin.events.competition.results', $event) }}?${params.toString()}`
                    );

                    currentResults = await res.json();

                    if (!currentResults.length) {
                        resultsTableBody.innerHTML = `
                        <tr>
                            <td colspan="9" style="text-align:center;color:#9ca3af">
                                Belum ada hasil
                            </td>
                        </tr>
                    `;

                        isFirstLoad = false;
                        return;
                    }

                    // data.sort((a, b) => {
                    //     const avgA = timeToCS(a.average);
                    //     const avgB = timeToCS(b.average);

                    //     if (avgA !== avgB) return avgA - avgB;

                    //     const bestA = timeToCS(a.best);
                    //     const bestB = timeToCS(b.best);

                    //     return bestA - bestB;
                    // });

                    resultsTableBody.innerHTML = currentResults.map((r, i) => `
                    <tr data-result-id="${escapeHtml(r.id ?? '')}" data-result-index="${i}">
                        <td class="text-center">${r.rank ?? i + 1}</td>
                        <td>${escapeHtml(r.name)}</td>
                        <td class="text-end">${escapeHtml(r.attempt1 ?? '-')}</td>
                        <td class="text-end">${escapeHtml(r.attempt2 ?? '-')}</td>
                        <td class="text-end">${escapeHtml(r.attempt3 ?? '-')}</td>
                        <td class="text-end">${escapeHtml(r.attempt4 ?? '-')}</td>
                        <td class="text-end">${escapeHtml(r.attempt5 ?? '-')}</td>
                        <td class="text-end"><strong>${escapeHtml(r.average ?? '-')}</strong></td>
                        <td class="text-end">${escapeHtml(r.best ?? '-')}</td>
                    </tr>
                `).join('');

                    isFirstLoad = false;

                } catch (e) {
                    console.error(e);
                }
            }

            function fillFormFromResult(result) {
                resultIdInput.value = result.id ?? '';
                manualCompetitorCheckbox.checked = !result.user_id;
                applyManualCompetitorMode(false, false);

                participantInput.value = result.name ?? '';

                if (result.user_id) {
                    participantUserId.value = result.user_id;
                    participantUserId.setAttribute('value', result.user_id);
                } else {
                    participantUserId.value = '';
                    participantUserId.removeAttribute('value');
                }

                attempts[0].value = result.attempt1 ?? '';
                attempts[1].value = result.attempt2 ?? '';
                attempts[2].value = result.attempt3 ?? '';
                attempts[3].value = result.attempt4 ?? '';
                attempts[4].value = result.attempt5 ?? '';

                bestEl.textContent = result.best ?? '-';
                avgEl.textContent = result.average ?? '-';
                bestInput.value = result.best ?? '';
                avgInput.value = result.average ?? '';

                lastRequestKey = null;
                hideContextMenu();
                setAttemptState();
                focusAttempt(0);
            }

            async function deleteResult(result) {
                hideContextMenu();

                if (!result?.id) return;

                const confirmation = await showAdminAlert({
                    title: 'Yakin ingin menghapus?',
                    text: `Hasil ${result.name} tidak bisa dikembalikan!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-danger',
                        cancelButton: 'btn btn-primary',
                    },
                });

                if (!confirmation.isConfirmed) {
                    return;
                }

                try {
                    const formData = new FormData(form);

                    const res = await fetch(resultDeleteUrl(result.id), {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formData.get('_token'),
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!res.ok) {
                        showAdminError(`Gagal menghapus data (${res.status})`);
                        return;
                    }

                    if (String(resultIdInput.value) === String(result.id)) {
                        resetParticipantSelection();
                    }

                    await loadTable();
                    showAdminSuccess('Hasil kompetisi berhasil dihapus');
                } catch (e) {
                    console.error(e);
                    showAdminError('Terjadi kesalahan saat menghapus');
                }
            }

            function handleCategoryOrRoundChange() {
                lastRequestKey = null;
                clearEditTarget();

                if (isManualCompetitor()) {
                    attempts.forEach(input => {
                        input.value = '';
                    });

                    resetResultSummary();
                }

                setAttemptState();
                checkAndPrefill();
                loadTable();
            }

            categorySelect.addEventListener('change', function() {
                resetParticipantSelection();
                handleCategoryOrRoundChange();
            });

            roundSelect.addEventListener('change', handleCategoryOrRoundChange);

            function openResultContextMenu(e) {
                const row = e.target.closest('#resultsTableBody tr[data-result-id]');

                if (!row) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();

                contextResult = currentResults.find(result => String(result.id ?? '') === String(row.dataset.resultId));

                if (!contextResult && row.dataset.resultIndex !== undefined) {
                    contextResult = currentResults[Number(row.dataset.resultIndex)] ?? null;
                }

                if (!contextResult) {
                    return;
                }

                resultContextMenu.style.position = 'fixed';
                resultContextMenu.style.zIndex = '99999';
                resultContextMenu.style.display = 'block';
                resultContextMenu.style.minWidth = '120px';
                resultContextMenu.style.padding = '6px';
                resultContextMenu.style.border = '1px solid #e5e7eb';
                resultContextMenu.style.borderRadius = '8px';
                resultContextMenu.style.background = '#fff';
                resultContextMenu.style.boxShadow = '0 12px 28px rgba(15,23,42,.14)';

                const menuRect = resultContextMenu.getBoundingClientRect();
                const left = Math.min(e.clientX, window.innerWidth - menuRect.width - 8);
                const top = Math.min(e.clientY, window.innerHeight - menuRect.height - 8);

                resultContextMenu.style.left = `${Math.max(8, left)}px`;
                resultContextMenu.style.top = `${Math.max(8, top)}px`;
            }

            document.addEventListener('contextmenu', function(e) {
                const result = getContextResultFromEvent(e);

                if (!result) {
                    return;
                }

                e.preventDefault();
                e.stopPropagation();
                contextResult = result;
                openResultContextMenu(e);
            }, true);

            resultsTableBody.addEventListener('contextmenu', openResultContextMenu);

            resultsTableBody.addEventListener('mouseup', function(e) {
                if (e.button === 2) {
                    openResultContextMenu(e);
                }
            });

            resultContextMenu.addEventListener('click', function(e) {
                const action = e.target.closest('[data-context-action]')?.dataset.contextAction;

                if (!action || !contextResult) return;

                if (action === 'edit') {
                    fillFormFromResult(contextResult);
                    return;
                }

                if (action === 'delete') {
                    deleteResult(contextResult);
                }
            });

            document.addEventListener('click', function(e) {
                if (!resultContextMenu.contains(e.target)) {
                    hideContextMenu();
                }
            });

            window.addEventListener('scroll', hideContextMenu, true);
            window.addEventListener('resize', hideContextMenu);
            // =====================================================
            // AJAX SUBMIT
            // =====================================================
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (isManualCompetitor()) {
                    if (!participantInput.value.trim()) {
                        showAdminError('Silakan isi nama kompetitor baru');
                        participantInput.focus();
                        return;
                    }

                    participantUserId.value = '';
                    participantUserId.removeAttribute('value');
                } else if (!participantUserId.value) {
                    showAdminError('Silakan pilih kompetitor dari daftar');
                    participantInput.focus();
                    return;
                }

                calc();

                const formData = new FormData(form);

                try {
                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': formData.get('_token'),
                        },
                        body: formData
                    });

                    if (!res.ok) {
                        showAdminError('Gagal menyimpan data');
                        return;
                    }

                    lastRequestKey = null;

                    await loadTable();

                    participantInput.value = '';
                    clearEditTarget();
                    participantUserId.value = '';
                    participantUserId.removeAttribute('value');

                    hideParticipantDropdown();
                    hideContextMenu();

                    attempts.forEach(input => {
                        input.value = '';
                    });

                    setAttemptState();
                    resetResultSummary();

                    participantInput.focus();

                } catch (e) {
                    console.error(e);
                    showAdminError('Terjadi kesalahan saat menyimpan');
                }
            });

            manualCompetitorCheckbox.addEventListener('change', function() {
                applyManualCompetitorMode(true);
            });

            // =====================================================
            // CATEGORY ICON LIST SELECT
            // =====================================================
            function initCategoryIconList() {
                const wrapper = document.querySelector('[data-category-icon-list]');

                if (!wrapper) return;

                const select = wrapper.querySelector('[name="competition_category_id"]');
                const choices = Array.from(wrapper.querySelectorAll('[data-category-choice]'));

                if (!select || !choices.length) return;

                function setCategory(id, dispatchChange = true) {
                    select.value = id;

                    choices.forEach(choice => {
                        const isActive = choice.dataset.id === String(id);

                        choice.classList.toggle('is-active', isActive);
                        choice.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                    });

                    if (dispatchChange) {
                        select.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                }

                choices.forEach((choice, index) => {
                    choice.setAttribute('tabindex', '0');
                    choice.setAttribute('aria-pressed', choice.classList.contains('is-active') ? 'true' :
                        'false');

                    choice.addEventListener('click', function() {
                        setCategory(choice.dataset.id);
                    });

                    choice.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            setCategory(choice.dataset.id);
                            return;
                        }

                        if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                            e.preventDefault();

                            const next = choices[index + 1] || choices[0];
                            next.focus();
                            return;
                        }

                        if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                            e.preventDefault();

                            const prev = choices[index - 1] || choices[choices.length - 1];
                            prev.focus();
                        }
                    });
                });

                select.addEventListener('change', function() {
                    setCategory(select.value, false);
                });

                if (select.value) {
                    setCategory(select.value, false);
                } else if (choices[0]) {
                    setCategory(choices[0].dataset.id, true);
                }
            }

            // =====================================================
            // INITIAL STATE
            // =====================================================
            styleContextMenuButtons();
            initCategoryIconList();
            applyManualCompetitorMode(false, false);
            setAttemptState();

            if (categorySelect.value && roundSelect.value) {
                requestAnimationFrame(loadTable);
            }
        })();
    </script>


@endsection
