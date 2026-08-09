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
                    Beranda &gt; Event &gt; Hasil Kompetisi
                </div>

                <h1 class="page-title" style="margin-bottom:4px;">
                    {{ $event->title }}
                </h1>

                <div style="display:flex;flex-wrap:wrap;gap:10px;align-items:center;margin-top:6px;">
                    <p class="muted" style="margin:0;">
                        <i class="fa-regular fa-calendar"></i>

                        @if ($event->start_datetime->isSameDay($event->end_datetime))
                            {{ $event->start_datetime->format('d M Y') }}
                        @else
                            {{ $event->start_datetime->format('d M Y') }} –
                            {{ $event->end_datetime->format('d M Y') }}
                        @endif

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
                <div class="card card-pad competition-filter-card">
                    <div class="competition-filter-head">

                        @php
                            $selectedCategory = $competitionCategories->firstWhere('id', request('category'));
                            $selectedRound = $rounds->firstWhere('round_number', request('round'));
                        @endphp


                        <form method="GET" class="competition-filter-form">
                            {{-- KATEGORI --}}
                            <div class="competition-filter-field">
                                {{-- <label class="muted" style="font-size:13px;margin:0;">
                                    Kategori
                                </label> --}}

                                <select name="category" id="categorySelect" class="select">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($competitionCategories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ request('category') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ROUND: hanya tampil jika kategori dipilih --}}
                            @if (request('category'))
                                <div id="roundFilterWrap" class="competition-filter-field">
                                    {{-- <label class="muted" style="font-size:13px;margin:0;">
                                        Round
                                    </label> --}}

                                    <select name="round" id="roundSelect" class="select">
                                        <option value="">Semua Round</option>
                                        @foreach ($rounds as $round)
                                            <option value="{{ $round->round_number }}"
                                                {{ request('round') == $round->round_number ? 'selected' : '' }}>
                                                {{ $round->name ?? 'Round ' . $round->round_number }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="competition-results-gap"></div>

                {{-- MODE FILTER AKTIF (Kategori + Round dipilih) --}}

                @forelse ($groupedResults as $categoryId => $roundGroups)
                    @php
                        $categoryName = $competitionCategories->firstWhere('id', $categoryId)->name ?? '';
                    @endphp

                    @forelse ($roundGroups as $roundNumber => $rows)
                        <div class="card card-pad competition-result-card">
                            <div class="competition-result-head">
                                @if ($categoryName)
                                    <h2 class="competition-category-title">
                                        {{ $categoryName }}
                                    </h2>
                                @endif

                                {{-- Judul Round --}}
                                <h4 class="competition-round-title">
                                    {{ $rounds->firstWhere('round_number', $roundNumber)->name ?? 'Round ' . $roundNumber }}
                                </h4>
                            </div>

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
                                        @foreach ($rows as $row)
                                            <tr class="result-row" data-category="{{ $categoryName }}"
                                                data-round="{{ $rounds->firstWhere('round_number', $roundNumber)->name ?? 'Round ' . $roundNumber }}"
                                                data-rank="{{ $row->rank ?? '-' }}" data-name="{{ $row->user->name }}"
                                                data-a1="{{ $row->attempt1 ?? 'DNF' }}"
                                                data-a2="{{ $row->attempt2 ?? 'DNF' }}"
                                                data-a3="{{ $row->attempt3 ?? 'DNF' }}"
                                                data-a4="{{ $row->attempt4 ?? 'DNF' }}"
                                                data-a5="{{ $row->attempt5 ?? 'DNF' }}"
                                                data-average="{{ $row->average ?? '-' }}"
                                                data-best="{{ $row->best ?? '-' }}">

                                                <td
                                                    class="text-end rank-cell {{ auth()->check() && $row->user_id === auth()->id() ? 'my-rank-cell' : '' }}">
                                                    {{ $row->rank ?? '-' }}
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
                        </div>

                    @empty
                        <div class="card card-pad competition-result-card">
                            @if ($categoryName)
                                <h2 class="competition-category-title">
                                    {{ $categoryName }}
                                </h2>
                            @endif
                            <p class="muted" style="margin:0;">Belum ada hasil.</p>
                        </div>
                    @endforelse
                @empty
                    <div class="card card-pad competition-result-card">
                        <p class="muted" style="margin:0;">Belum ada hasil kompetisi.</p>
                    </div>
                @endforelse

            </div>
        </section>
    </div>
    <div id="attemptModalBackdrop" class="modal-backdrop"></div>

    <div id="attemptModal" class="attempt-modal">
        <div class="attempt-modal-content">
            <div class="attempt-modal-head">
                <div>
                    <p id="modalCategoryRound" class="attempt-modal-meta"></p>
                    <h3 id="modalRankName">Detail Waktu</h3>
                </div>
            </div>

            <div class="attempt-summary">
                <div>
                    <span>Average</span>
                    <strong id="modalAverage">-</strong>
                </div>

                <div>
                    <span>Best</span>
                    <strong id="modalBest">-</strong>
                </div>
            </div>

            <div class="attempt-list-wrap">
                <ul id="attemptList"></ul>
            </div>

            <button onclick="closeAttemptModal()" class="attempt-modal-close" aria-label="Tutup detail">
                <i class="fa-solid fa-chevron-down"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const categorySelect = document.getElementById("categorySelect");
            const roundSelect = document.getElementById("roundSelect");

            categorySelect.addEventListener("change", function() {
                const url = new URL(window.location.href);

                url.searchParams.set('category', this.value);
                url.searchParams.delete('round');

                if (!this.value) {
                    url.searchParams.delete('category');
                }

                window.location.href = url.toString();
            });

            if (roundSelect) {
                roundSelect.addEventListener("change", function() {
                    const url = new URL(window.location.href);

                    if (this.value) {
                        url.searchParams.set('round', this.value);
                    } else {
                        url.searchParams.delete('round');
                    }

                    window.location.href = url.toString();
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.result-row').forEach(row => {
                row.addEventListener('click', function() {
                    if (window.innerWidth > 768) return;

                    const category = row.dataset.category || '-';
                    const round = row.dataset.round || '-';
                    const rank = row.dataset.rank || '-';
                    const name = row.dataset.name || '-';
                    const average = row.dataset.average || '-';
                    const best = row.dataset.best || '-';

                    document.getElementById('modalCategoryRound').textContent = category + ' • ' +
                        round;
                    document.getElementById('modalRankName').innerHTML = `
                        <strong>#${rank}</strong>
                        <span>${name}</span>
                    `;
                    document.getElementById('modalAverage').textContent = average;
                    document.getElementById('modalBest').textContent = best;

                    const list = document.getElementById('attemptList');
                    list.innerHTML = '';

                    for (let i = 1; i <= 5; i++) {
                        const val = row.dataset['a' + i] || 'DNF';

                        const li = document.createElement('li');
                        li.innerHTML = `
                        <span>Attempt ${i}</span>
                        <strong>${val}</strong>
                    `;

                        list.appendChild(li);
                    }

                    document.getElementById('attemptModal').classList.add('open');
                    document.getElementById('attemptModalBackdrop').classList.add('open');
                    document.body.style.overflow = 'hidden';
                });
            });

            document.getElementById('attemptModalBackdrop').addEventListener('click', closeAttemptModal);
        });

        function closeAttemptModal() {
            document.getElementById('attemptModal').classList.remove('open');
            document.getElementById('attemptModalBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }
    </script>


@endsection
