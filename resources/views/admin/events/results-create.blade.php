@extends('admin.layouts.app')

@section('body-class', 'hide-admin-sidebar hide-admin-topbar')

@section('title', 'Input Hasil Kompetisi')
@section('page-title', 'Input Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/events-results-create.css') }}">
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

                    {{-- PESERTA --}}
                    <h3 class="form-section-title">Kompetitor</h3>
                    <div class="form-row">
                        <div class="form-group" style="position:relative">
                            <input type="text" id="participantInput" class="form-input"
                                data-event-id="{{ $event->id }}" autocomplete="off" placeholder="Nama kompetitor"
                                required>

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

    <script>
        (function() {
            const form = document.getElementById('resultForm');

            const participantInput = document.getElementById('participantInput');
            const participantDropdown = document.getElementById('participantDropdown');
            const participantUserId = document.getElementById('participantUserId');
            const participantGroup = participantInput?.closest('.form-group');

            const categorySelect = document.querySelector('[name="competition_category_id"]');
            const roundSelect = document.querySelector('[name="round_number"]');

            const attempts = Array.from(document.querySelectorAll('.attempt-input'));

            const bestEl = document.getElementById('bestValue');
            const avgEl = document.getElementById('avgValue');
            const bestInput = document.getElementById('bestInput');
            const avgInput = document.getElementById('avgInput');

            const resultsTableBody = document.getElementById('resultsTableBody');
            const resultsTitle = document.getElementById('resultsTitle');
            const saveButton = form?.querySelector('button.btn-primary');

            let participantCache = [];
            let loadedParticipantCategoryId = null;
            let lastRequestKey = null;
            let isFirstLoad = true;

            if (
                !form ||
                !participantInput ||
                !participantDropdown ||
                !participantUserId ||
                !categorySelect ||
                !roundSelect ||
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
            function isReadyToInputAttempt() {
                return Boolean(
                    categorySelect.value &&
                    roundSelect.value &&
                    participantUserId.value
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

            async function loadAllParticipants() {
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
                await loadAllParticipants();

                // Jangan tampilkan dropdown saat input baru diklik
                participantDropdown.style.display = 'none';
            });

            participantInput.addEventListener('input', async () => {
                const q = participantInput.value.trim().toLowerCase();

                participantUserId.value = '';
                participantUserId.removeAttribute('value');

                lastRequestKey = null;
                setAttemptState();

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
                participantInput.value = '';
                participantUserId.value = '';
                participantUserId.removeAttribute('value');

                participantDropdown.innerHTML = '';
                participantDropdown.style.display = 'none';

                participantCache = [];
                loadedParticipantCategoryId = null;

                attempts.forEach(input => {
                    input.value = '';
                });

                resetResultSummary();
                setAttemptState();
            }

            participantInput.addEventListener('keydown', async e => {
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
                    participantDropdown.style.display = 'none';
                    activeParticipantIndex = -1;
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
                if (!categorySelect.value || !roundSelect.value || !participantUserId.value) {
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

                    let data = await res.json();

                    if (!data.length) {
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

                    resultsTableBody.innerHTML = data.map((r, i) => `
                    <tr>
                        <td class="text-center">${r.rank ?? i + 1}</td>
                        <td>${r.name}</td>
                        <td class="text-end">${r.attempt1 ?? '-'}</td>
                        <td class="text-end">${r.attempt2 ?? '-'}</td>
                        <td class="text-end">${r.attempt3 ?? '-'}</td>
                        <td class="text-end">${r.attempt4 ?? '-'}</td>
                        <td class="text-end">${r.attempt5 ?? '-'}</td>
                        <td class="text-end"><strong>${r.average ?? '-'}</strong></td>
                        <td class="text-end">${r.best ?? '-'}</td>
                    </tr>
                `).join('');

                    isFirstLoad = false;

                } catch (e) {
                    console.error(e);
                }
            }

            function handleCategoryOrRoundChange() {
                lastRequestKey = null;

                setAttemptState();
                checkAndPrefill();
                loadTable();
            }

            categorySelect.addEventListener('change', function() {
                resetParticipantSelection();
                handleCategoryOrRoundChange();
            });

            roundSelect.addEventListener('change', handleCategoryOrRoundChange);
            // =====================================================
            // AJAX SUBMIT
            // =====================================================
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                if (!participantUserId.value) {
                    alert('Silakan pilih kompetitor dari daftar');
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
                        alert('Gagal menyimpan data');
                        return;
                    }

                    lastRequestKey = null;

                    await loadTable();

                    participantInput.value = '';
                    participantUserId.value = '';
                    participantUserId.removeAttribute('value');

                    participantDropdown.style.display = 'none';

                    attempts.forEach(input => {
                        input.value = '';
                    });

                    setAttemptState();
                    resetResultSummary();

                    participantInput.focus();

                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan saat menyimpan');
                }
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
            initCategoryIconList();
            setAttemptState();

            if (categorySelect.value && roundSelect.value) {
                requestAnimationFrame(loadTable);
            }
        })();
    </script>


@endsection
