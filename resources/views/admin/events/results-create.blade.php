@extends('admin.layouts.app')

@section('body-class', 'hide-admin-sidebar')

@section('title', 'Input Hasil Kompetisi')
@section('page-title', 'Input Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/events-results-create.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h2 class="page-title">{{ $event->title }}</h2>
        <a href="{{ route('admin.events.competition.index') }}" class="btn btn-secondary">
            Kembali
        </a>
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
                        <div class="form-group">
                            <select name="competition_category_id" class="form-select" required>
                                <option value="">Pilih Kategori</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" @selected($selectedCategory == $cat->id)>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- ROUND --}}
                    <h3 class="form-section-title">Round</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <select name="round_number" class="form-select" required>
                                <option value="">Pilih Round</option>
                                @for ($i = 1; $i <= 3; $i++)
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
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">Batal</a>
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
            const input = document.getElementById('participantInput');
            const dropdown = document.getElementById('participantDropdown');
            const hiddenUserId = document.getElementById('participantUserId');

            let cache = []; // simpan semua peserta
            let loaded = false; // penanda sudah fetch atau belum

            async function loadAllParticipants() {
                if (loaded) return;

                const eventId = input.dataset.eventId;

                try {
                    // 🔥 ambil SEMUA peserta (tanpa query)
                    const res = await fetch(`/admin/events/${eventId}/accepted-participants`);
                    cache = await res.json();
                    loaded = true;
                } catch (e) {
                    console.error('Gagal load peserta', e);
                }
            }

            function render(list) {
                dropdown.innerHTML = '';

                if (!list.length) {
                    dropdown.style.display = 'none';
                    return;
                }

                list.forEach(p => {
                    const div = document.createElement('div');
                    div.textContent = p.name;
                    div.style.padding = '10px 12px';
                    div.style.cursor = 'pointer';

                    div.onmouseenter = () => div.style.background = '#f3f4f6';
                    div.onmouseleave = () => div.style.background = '#fff';

                    div.onclick = () => {
                        input.value = p.name;
                        hiddenUserId.value = p.user_id;
                        dropdown.style.display = 'none';
                    };

                    dropdown.appendChild(div);
                });

                dropdown.style.display = 'block';
            }

            // 🔥 KLIK INPUT → TAMPILKAN SEMUA
            input.addEventListener('focus', async () => {
                hiddenUserId.value = '';
                await loadAllParticipants();
                render(cache);
            });

            // 🔥 KETIK → FILTER
            input.addEventListener('input', () => {
                const q = input.value.trim().toLowerCase();
                hiddenUserId.value = '';

                if (!loaded) return;

                const filtered = q ?
                    cache.filter(p => p.name.toLowerCase().includes(q)) :
                    cache;

                render(filtered);
            });

            // klik di luar → tutup
            document.addEventListener('click', e => {
                if (!e.target.closest('.form-group')) {
                    dropdown.style.display = 'none';
                }
            });

            // validasi submit
            document.querySelector('form').addEventListener('submit', e => {
                if (!hiddenUserId.value) {
                    e.preventDefault();
                    alert('Silakan pilih kompetitor dari daftar');
                }
            });
        })();
    </script>


    <script>
        document.querySelectorAll('.attempt-input').forEach(input => {
            input.addEventListener('input', () => {
                let v = input.value.toUpperCase();

                // DNF / DNS
                if (v === 'DNF' || v === 'DNS') {
                    input.value = v;
                    return;
                }

                // hanya angka
                v = v.replace(/\D/g, '');

                if (!v) {
                    input.value = '';
                    return;
                }

                // 1–2 digit → tampil apa adanya
                if (v.length <= 2) {
                    input.value = v;
                    return;
                }

                // 3–4 digit → ss.xx
                if (v.length <= 4) {
                    const sec = v.slice(0, -2);
                    const dec = v.slice(-2);
                    input.value = `${sec}.${dec}`;
                    return;
                }

                // ≥5 digit → m:ss.xx
                const dec = v.slice(-2);
                const sec = v.slice(-4, -2);
                const min = v.slice(0, -4);

                input.value = `${min}:${sec}.${dec}`;
            });

            // shortcut keyboard
            input.addEventListener('keydown', e => {
                const key = e.key.toLowerCase();

                if (key === 'd') {
                    e.preventDefault();
                    input.value = 'DNF';
                }

                if (key === 's') {
                    e.preventDefault();
                    input.value = 'DNS';
                }
            });
        });
    </script>
    <script>
        (function() {
            const attempts = document.querySelectorAll('.attempt-input');
            const bestEl = document.getElementById('bestValue');
            const avgEl = document.getElementById('avgValue');
            const bestInput = document.getElementById('bestInput');
            const avgInput = document.getElementById('avgInput');

            function toCS(v) {
                if (!v) return null;
                v = v.toUpperCase();
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
                    `${m}:${String(s).padStart(2,'0')}.${String(d).padStart(2,'0')}` :
                    `${s}.${String(d).padStart(2,'0')}`;
            }

            function calc() {
                const vals = [];
                attempts.forEach(i => {
                    const v = toCS(i.value);
                    if (v !== null) vals.push(v);
                });

                if (!vals.length) {
                    bestEl.textContent = avgEl.textContent = '-';
                    bestInput.value = avgInput.value = '';
                    return;
                }

                vals.sort((a, b) => a - b);
                bestEl.textContent = vals[0] === Infinity ? 'DNF' : fromCS(vals[0]);
                bestInput.value = bestEl.textContent;

                const dnf = vals.filter(v => v === Infinity).length;
                if (vals.length < 5 || dnf >= 2) {
                    avgEl.textContent = avgInput.value = 'DNF';
                    return;
                }

                const mid = vals.slice(1, 4);
                if (mid.some(v => v === Infinity)) {
                    avgEl.textContent = avgInput.value = 'DNF';
                    return;
                }

                const avg = Math.round(mid.reduce((a, b) => a + b, 0) / 3);
                avgEl.textContent = avgInput.value = fromCS(avg);
            }

            attempts.forEach(i => {
                i.addEventListener('keyup', calc);
                i.addEventListener('blur', calc);
            });
        })();
    </script>

    <script>
        (function() {
            const categorySelect = document.querySelector('[name="competition_category_id"]');
            const roundSelect = document.querySelector('[name="round_number"]');
            const userIdInput = document.getElementById('participantUserId');
            const participantInput = document.getElementById('participantInput');
            const attempts = document.querySelectorAll('.attempt-input');

            function setAttemptState() {
                const ready =
                    categorySelect.value &&
                    roundSelect.value &&
                    userIdInput.value;

                attempts.forEach(input => {
                    input.readOnly = !ready;
                    input.classList.toggle('is-disabled', !ready);

                    if (!ready) {
                        input.value = '';
                    }
                });
            }

            // kondisi awal (LOCK)
            setAttemptState();

            // pantau perubahan
            categorySelect.addEventListener('change', setAttemptState);
            roundSelect.addEventListener('change', setAttemptState);

            // peserta dipilih dari dropdown
            const observer = new MutationObserver(setAttemptState);
            observer.observe(userIdInput, {
                attributes: true,
                attributeFilter: ['value']
            });

            // kalau user hapus nama peserta manual
            participantInput.addEventListener('input', () => {
                if (!participantInput.value.trim()) {
                    userIdInput.value = '';
                    setAttemptState();
                }
            });
        })();
    </script>
    <script>
        (function() {
            const attempts = document.querySelectorAll('.attempt-input');

            attempts.forEach(input => {
                // KEYDOWN – CAPTURE PHASE (PENTING)
                input.addEventListener('keydown', function(e) {
                    if (input.readOnly) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        return false;
                    }
                }, true); // ← TRUE = CAPTURE PHASE

                // INPUT – CAPTURE PHASE
                input.addEventListener('input', function(e) {
                    if (input.readOnly) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        input.value = '';
                        return false;
                    }
                }, true);

                // PASTE – CAPTURE PHASE
                input.addEventListener('paste', function(e) {
                    if (input.readOnly) {
                        e.stopImmediatePropagation();
                        e.preventDefault();
                        return false;
                    }
                }, true);
            });
        })();
    </script>

    <script>
        (function() {
            const categorySelect = document.querySelector('[name="competition_category_id"]');
            const roundSelect = document.querySelector('[name="round_number"]');
            const userIdInput = document.getElementById('participantUserId');

            const attempts = document.querySelectorAll('.attempt-input');
            const bestEl = document.getElementById('bestValue');
            const avgEl = document.getElementById('avgValue');
            const bestInput = document.getElementById('bestInput');
            const avgInput = document.getElementById('avgInput');

            let lastRequestKey = null;

            async function checkAndPrefill() {
                if (!categorySelect.value || !roundSelect.value || !userIdInput.value) {
                    return;
                }

                const key = `${categorySelect.value}-${roundSelect.value}-${userIdInput.value}`;
                if (key === lastRequestKey) return;
                lastRequestKey = key;

                try {
                    const res = await fetch(
                        `{{ route('admin.events.competition.check', $event) }}` +
                        `?competition_category_id=${categorySelect.value}` +
                        `&round_number=${roundSelect.value}` +
                        `&user_id=${userIdInput.value}`
                    );

                    const json = await res.json();

                    // RESET dulu (default = CREATE)
                    attempts.forEach(i => i.value = '');
                    bestEl.textContent = '-';
                    avgEl.textContent = '-';
                    bestInput.value = '';
                    avgInput.value = '';

                    if (!json.exists) {
                        return; // CREATE MODE
                    }

                    // EDIT MODE → PREFILL
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

            categorySelect.addEventListener('change', checkAndPrefill);
            roundSelect.addEventListener('change', checkAndPrefill);

            // saat peserta dipilih dari autocomplete
            new MutationObserver(checkAndPrefill)
                .observe(userIdInput, {
                    attributes: true,
                    attributeFilter: ['value']
                });
        })();
    </script>
    <script>
        let isFirstLoad = true;

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

        async function loadTable() {
            const categorySelect = document.querySelector('[name="competition_category_id"]');
            const roundSelect = document.querySelector('[name="round_number"]');
            const tbody = document.getElementById('resultsTableBody');
            const titleEl = document.getElementById('resultsTitle');

            if (!categorySelect.value || !roundSelect.value) {
                if (isFirstLoad) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="9" style="text-align:center;color:#9ca3af">
                            Pilih kategori dan round
                        </td>
                    </tr>`;
                }
                return;
            }

            // 🔹 Update judul
            const categoryName =
                categorySelect.options[categorySelect.selectedIndex].text;
            titleEl.textContent = `${categoryName} – Round ${roundSelect.value}`;

            try {
                const res = await fetch(
                    `{{ route('admin.events.competition.results', $event) }}` +
                    `?competition_category_id=${categorySelect.value}` +
                    `&round_number=${roundSelect.value}`
                );

                let data = await res.json();

                if (!data.length) {
                    tbody.innerHTML = `
                    <tr>
                        <td colspan="9" style="text-align:center;color:#9ca3af">
                            Belum ada data hasil
                        </td>
                    </tr>`;
                    return;
                }

                // 🔥 SORTING SESUAI WCA
                data.sort((a, b) => {
                    const avgA = timeToCS(a.average);
                    const avgB = timeToCS(b.average);

                    if (avgA !== avgB) return avgA - avgB;

                    const bestA = timeToCS(a.best);
                    const bestB = timeToCS(b.best);

                    return bestA - bestB;
                });

                // 🔥 Render tabel + ranking
                tbody.innerHTML = data.map((r, i) => `
                <tr>
                    <td class="text-center">${i + 1}</td>
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

        // 🔥 Trigger reload
        document.querySelector('[name="competition_category_id"]')
            .addEventListener('change', loadTable);

        document.querySelector('[name="round_number"]')
            .addEventListener('change', loadTable);

        // 🔥 Auto load tanpa flicker
        if (
            document.querySelector('[name="competition_category_id"]').value &&
            document.querySelector('[name="round_number"]').value
        ) {
            requestAnimationFrame(loadTable);
        }
    </script>
    <script>
        (function() {
            const form = document.getElementById('resultForm');
            const participantInput = document.getElementById('participantInput');
            const hiddenUserId = document.getElementById('participantUserId');
            const attempts = document.querySelectorAll('.attempt-input');
            const bestEl = document.getElementById('bestValue');
            const avgEl = document.getElementById('avgValue');
            const bestInput = document.getElementById('bestInput');
            const avgInput = document.getElementById('avgInput');

            form.addEventListener('submit', async function(e) {
                e.preventDefault(); // stop reload

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

                    // 🔥 reset cache edit/create agar prefill bisa jalan lagi
                    if (typeof lastRequestKey !== 'undefined') {
                        lastRequestKey = null;
                    }

                    // 🔥 refresh tabel
                    if (typeof loadTable === 'function') {
                        loadTable();
                    }

                    // 🔥 KOSONGKAN INPUT PESERTA
                    participantInput.value = '';
                    hiddenUserId.value = '';

                    // 🔥 KOSONGKAN ATTEMPT
                    attempts.forEach(i => {
                        i.value = '';
                        i.readOnly = true; // balik ke kondisi terkunci
                        i.classList.add('is-disabled');
                    });

                    // 🔥 RESET HASIL
                    bestEl.textContent = '-';
                    avgEl.textContent = '-';
                    bestInput.value = '';
                    avgInput.value = '';

                    // optional UX
                    participantInput.focus();

                } catch (e) {
                    console.error(e);
                    alert('Terjadi kesalahan saat menyimpan');
                }
            });
        })();
    </script>


@endsection
