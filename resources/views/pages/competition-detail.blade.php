@extends('layouts.app')

@section('title', $event->title . ' — Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/competition-detail.css') }}">
@endpush

@section('content')
    <div class="competition-detail-page">

        {{-- HEADER EVENT --}}
        <section class="page-head">
            <div class="container">
                <div class="breadcrumb">
                    Beranda &gt; Event &gt; Event Saya &gt; Detail Kompetisi
                </div>

                <h1 class="page-title" style="margin-bottom:4px;">
                    {{ $event->title }}
                </h1>

                <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:6px;">
                    <p class="muted" style="margin:0;">
                        <i class="fa-regular fa-calendar"></i>
                        {{ $event->start_datetime->format('d M Y') }} –
                        {{ $event->end_datetime->format('d M Y') }}
                        • {{ $event->start_datetime->format('H:i') }} WIB
                        <br>
                        <i class="fa-solid fa-location-dot"></i>
                        {{ $event->location }}
                    </p>

                </div>
            </div>
        </section>

        <section style="border-bottom:1px solid rgba(17,24,39,.06);"></section>

        <section class="section">
            <div class="container">

                {{-- ========================= --}}
                {{-- FILTER CARD --}}
                {{-- ========================= --}}
                <div class="card card-pad" style="padding:20px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;">

                        <div>
                            @php
                                $selectedCategory = $competitionCategories->firstWhere('id', request('category'));
                                $selectedRound = $rounds->firstWhere('round_number', request('round'));
                            @endphp
                        </div>

                        <form method="GET" style="display:flex;gap:16px;align-items:center;flex-wrap:wrap;width:100%;">
                            {{-- KATEGORI --}}
                            <div style="display:flex;gap:6px;align-items:center;">
                                <label class="muted" style="font-size:13px;margin:0;">
                                    Kategori
                                </label>
                                <select name="category" id="categorySelect" class="select">
                                    <option value="">Semua</option>
                                    @foreach ($competitionCategories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ request('category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ROUND --}}
                            <div style="display:flex;gap:6px;align-items:center;">
                                <label class="muted" style="font-size:13px;margin:0;">
                                    Round
                                </label>
                                <select name="round" id="roundSelect" class="select"
                                    {{ request('category') ? '' : 'disabled' }}>
                                    <option value="">Semua</option>
                                    @foreach ($rounds as $round)
                                        <option value="{{ $round->round_number }}"
                                            {{ request('round') == $round->round_number ? 'selected' : '' }}>
                                            {{ $round->name ?? 'Round ' . $round->round_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                        </form>
                    </div>
                </div>

                <div style="height:22px;"></div>

                {{-- MODE FILTER AKTIF (Kategori + Round dipilih) --}}

                @forelse ($groupedResults as $categoryId => $roundGroups)
                    @php
                        $categoryName = $competitionCategories->firstWhere('id', $categoryId)->name ?? '';
                    @endphp

                    <div class="card card-pad" style="padding:20px;margin-bottom:22px;">

                        {{-- Judul Kategori --}}
                        @if ($categoryName)
                            <h2 style="margin-bottom:12px;">
                                {{ $categoryName }}
                            </h2>
                        @endif

                        @forelse ($roundGroups as $roundNumber => $rows)
                            {{-- Judul Round --}}
                            <h4 style="font-weight:650;margin:12px 0 6px;">
                                {{ $rounds->firstWhere('round_number', $roundNumber)->name ?? 'Round ' . $roundNumber }}
                            </h4>

                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th class="text-end">#</th>
                                            <th class="text-start">Nama</th>

                                            @for ($i = 1; $i <= 5; $i++)
                                                <th class="text-end attempt-col">{{ $i }}</th>
                                            @endfor

                                            <th class="text-end">Average</th>
                                            <th class="text-end">Best</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($rows as $index => $row)
                                            <tr class="result-row" data-a1="{{ $row->attempt1 ?? 'DNF' }}"
                                                data-a2="{{ $row->attempt2 ?? 'DNF' }}"
                                                data-a3="{{ $row->attempt3 ?? 'DNF' }}"
                                                data-a4="{{ $row->attempt4 ?? 'DNF' }}"
                                                data-a5="{{ $row->attempt5 ?? 'DNF' }}">

                                                <td class="text-end rank-cell">
                                                    {{ $row->rank ?? $index + 1 }}
                                                </td>

                                                <td class="text-start name-cell">
                                                    {{ $row->user->name }}
                                                </td>

                                                <td class="text-end attempt-col">{{ $row->attempt1 ?? 'DNF' }}</td>
                                                <td class="text-end attempt-col">{{ $row->attempt2 ?? 'DNF' }}</td>
                                                <td class="text-end attempt-col">{{ $row->attempt3 ?? 'DNF' }}</td>
                                                <td class="text-end attempt-col">{{ $row->attempt4 ?? 'DNF' }}</td>
                                                <td class="text-end attempt-col">{{ $row->attempt5 ?? 'DNF' }}</td>

                                                <td class="text-end">
                                                    <strong>{{ $row->average ?? '-' }}</strong>
                                                </td>

                                                <td class="text-end">
                                                    {{ $row->best ?? '-' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        @empty
                            <p class="muted">Belum ada hasil.</p>
                        @endforelse
                    </div>
                @empty
                    <div class="card card-pad" style="padding:20px;">
                        <p class="muted">Belum ada hasil kompetisi.</p>
                    </div>
                @endforelse

            </div>
        </section>
    </div>
    <div id="attemptModalBackdrop" class="modal-backdrop"></div>

    <div id="attemptModal" class="attempt-modal">
        <div class="attempt-modal-content">
            <h3>Detail Waktu</h3>
            <ul id="attemptList"></ul>
            <button onclick="closeAttemptModal()" class="btn btn-primary" style="width:100%;margin-top:12px;">
                Tutup
            </button>
        </div>
    </div>


    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const categorySelect = document.getElementById("categorySelect");
            const roundSelect = document.getElementById("roundSelect");

            function toggleRound() {
                if (categorySelect.value === "") {
                    roundSelect.disabled = true;
                    roundSelect.value = "";
                } else {
                    roundSelect.disabled = false;
                }
            }

            toggleRound();

            categorySelect.addEventListener("change", function() {
                toggleRound();
                this.form.submit();
            });

            roundSelect.addEventListener("change", function() {
                this.form.submit();
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.result-row').forEach(row => {

                row.addEventListener('click', function() {

                    if (window.innerWidth > 768) return;

                    const list = document.getElementById('attemptList');
                    list.innerHTML = '';

                    for (let i = 1; i <= 5; i++) {
                        const val = row.dataset['a' + i] || 'DNF';
                        const li = document.createElement('li');
                        li.textContent = 'Attempt ' + i + ': ' + val;
                        list.appendChild(li);
                    }

                    document.getElementById('attemptModal').classList.add('open');
                    document.getElementById('attemptModalBackdrop').classList.add('open');
                    document.body.style.overflow = 'hidden';
                });

            });

        });

        function closeAttemptModal() {
            document.getElementById('attemptModal').classList.remove('open');
            document.getElementById('attemptModalBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }
    </script>


@endsection
