@extends('layouts.app')

@section('title', 'Event Rubik — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
@endpush

@section('content')

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Event</div>
            <h1 class="page-title">Event Rubik</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Daftar event rubik: kompetisi, workshop, meetup. Informasi tanggal, lokasi, dan pendaftaran.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container" data-tabs>

            <div class="tabs" aria-label="Filter status event">
                <button class="tab active" type="button" data-tab="all">Semua</button>
                <button class="tab" type="button" data-tab="upcoming">Upcoming</button>
                <button class="tab" type="button" data-tab="ongoing">Berlangsung</button>
                <button class="tab" type="button" data-tab="finished">Selesai</button>
            </div>

            <div style="height:16px"></div>

            {{-- FEATURED --}}
            @if ($featured)
                <div class="card featured" data-status="{{ $featured->status }}" data-featured="main">
                    <div class="featured-media"
                        style="--bg:url('{{ $featured->cover_image ? asset('storage/' . $featured->cover_image) : asset('assets/img/event-default.jpg') }}')">
                        <img src="{{ $featured->cover_image ? asset('storage/' . $featured->cover_image) : asset('assets/img/event-default.jpg') }}"
                            alt="{{ $featured->title }}">
                    </div>

                    <div class="featured-body">
                        <span class="badge hot">Featured</span>
                        <h3>{{ $featured->title }}</h3>
                        <p class="muted">{{ Str::limit($featured->description, 120) }}</p>

                        <div class="kv">
                            <div><i class="fa-regular fa-calendar-days"></i>
                                {{ $featured->start_datetime->translatedFormat('d M Y • H:i') }}</div>
                            <div><i class="fa-solid fa-location-dot"></i> {{ $featured->location }}</div>
                        </div>

                        <div style="display:flex;gap:12px;">
                            <a href="{{ route('events.register', $featured->slug) }}" class="btn btn-primary"
                                style="flex:1">
                                Daftar sekarang
                            </a>
                            {{-- <button class="btn btn-secondary" onclick="openEventModal({{ $featured->id }})" style="flex:1">
                                Lihat detail
                            </button> --}}
                            <a href="{{ route('events.competition.show', [$featured->id, $featured->slug]) }}"
                                class="btn btn-secondary" style="flex:1">
                                Hasil
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <div style="height:16px"></div>

            {{-- EVENT LIST --}}
            <div class="grid-3">
                @foreach ($events as $ev)
                    <article class="card prod" data-status="{{ $ev->status }}"
                        data-featured="{{ $featured && $featured->id === $ev->id ? '1' : '0' }}">
                        <div class="prod-img"
                            style="--bg:url('{{ $ev->cover_image ? asset('storage/' . $ev->cover_image) : asset('assets/img/event-default.jpg') }}')">

                            <img src="{{ $ev->cover_image ? asset('storage/' . $ev->cover_image) : asset('assets/img/event-default.jpg') }}"
                                alt="{{ $ev->title }}" class="prod-cover">

                            <span class="badge {{ $ev->category === 'kompetisi' ? 'hot' : 'ok' }}">
                                {{ $ev->category === 'lainnya' ? $ev->custom_category : ucfirst($ev->category) }}
                            </span>

                            @if (!$ev->cover_image)
                                <div class="cube"></div>
                            @endif
                        </div>


                        <div class="prod-body">
                            <h3 class="prod-name">{{ $ev->title }}</h3>
                            <p class="muted">
                                <i class="fa-regular fa-calendar-days"></i>
                                {{ $ev->start_datetime->translatedFormat('d M Y • H:i') }}<br>
                                <i class="fa-solid fa-location-dot"></i> {{ $ev->location }}
                            </p>

                            <div style="display:flex;gap:10px;">
                                @if ($ev->category === 'kompetisi')
                                    <a href="{{ route('events.register', $ev->slug) }}" class="btn btn-primary"
                                        style="flex:1">
                                        Daftar
                                    </a>

                                    <a href="{{ route('events.competition.show', [$ev->id, $ev->slug]) }}"
                                        class="btn btn-secondary" style="flex:1">
                                        Hasil
                                    </a>
                                @else
                                    <button class="btn btn-secondary" style="flex:1"
                                        onclick="openEventModal({{ $ev->id }})">
                                        Detail
                                    </button>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

        </div>
    </section>
    <!-- Event Detail Modal -->
    <div id="eventModalBackdrop" class="modal-backdrop" aria-hidden="true" onclick="closeEventModal()"></div>
    <div id="eventModal" class="event-modal" role="dialog" aria-label="Detail Event" aria-modal="true">
        <div id="eventModalContent" class="event-modal-content">
            <!-- Content akan diisi via JavaScript -->
        </div>
    </div>

    <script>
        const eventsData = [
            @foreach ($events as $ev)
                {
                    id: {{ $ev->id }},
                    type: "{{ $ev->category }}", // ⬅️ PENTING

                    title: @json($ev->title),

                    // ⏰ WAKTU & TANGGAL (DIPISAH)
                    startDate: "{{ $ev->start_datetime->translatedFormat('d M Y') }}",
                    startTime: "{{ $ev->start_datetime->translatedFormat('H:i') }}",
                    endDate: "{{ $ev->end_datetime ? $ev->end_datetime->translatedFormat('d M Y') : '-' }}",
                    endTime: "{{ $ev->end_datetime ? $ev->end_datetime->translatedFormat('H:i') : '-' }}",

                    location: @json($ev->location),

                    desc: @json(Str::limit($ev->description, 120)),
                    fullDesc: @json($ev->description),

                    prize: "{{ $ev->total_prize ? 'Rp ' . number_format($ev->total_prize) : '-' }}",

                    categories: @json($ev->category === 'kompetisi' ? $ev->competitionCategories->pluck('name') : []),

                    badge: [
                        "{{ $ev->category === 'kompetisi' ? 'hot' : 'ok' }}",
                        "{{ $ev->category === 'lainnya' ? $ev->custom_category : ucfirst($ev->category) }}"
                    ]
                },
            @endforeach

        ];
    </script>

    <script>
        function openEventModal(eventId) {
            const event = eventsData.find(e => e.id === eventId);
            if (!event) return;

            const modal = document.getElementById('eventModal');
            const backdrop = document.getElementById('eventModalBackdrop');
            const content = document.getElementById('eventModalContent');

            // 🔥 HIGHLIGHT KHUSUS KOMPETISI
            const competitionHighlight = event.type === 'kompetisi' ?
                `
        <div class="event-modal-highlights">
            <div class="highlight-card">
                <span class="highlight-label">Total Hadiah</span>
                <span class="highlight-value">${event.prize}</span>
            </div>

            <div class="highlight-card">
                <span class="highlight-label">Kategori Lomba</span>
                <div class="categories-list">
                    ${event.categories.map(cat =>
                        `<span class="category-chip">${cat}</span>`
                    ).join('')}
                </div>
            </div>
        </div>
        ` :
                ``;

            const isMultiDay =
                event.endDate !== '-' && event.endDate !== event.startDate;

            // 📅 TANGGAL
            const dateInfo = isMultiDay ?
                `${event.startDate} – ${event.endDate}` :
                event.startDate;

            // ⏰ JAM
            const timeInfo = isMultiDay ?
                `${event.startTime} WIB` :
                event.endTime !== '-' ?
                `${event.startTime} – ${event.endTime} WIB` :
                `${event.startTime} WIB`;

            content.innerHTML = `
        <div class="event-modal-body">
            <h2 class="event-modal-title">${event.title}</h2>
            <div class="event-modal-info">
                <div class="info-item">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>${dateInfo}</span>
                </div>

                <div class="info-item">
                    <i class="fa-regular fa-clock"></i>
                    <span>${timeInfo}</span>
                </div>

                <div class="info-item">
                    <i class="fa-solid fa-location-dot"></i>
                    <span>${event.location}</span>
                </div>
            </div>
            <div class="event-modal-description">
                <p style="color:var(--muted);line-height:1.7;">
                    ${event.fullDesc}
                </p>
            </div>

            ${competitionHighlight}

            <div class="event-modal-actions">
                <button class="btn btn-secondary" onclick="closeEventModal()">Tutup</button>
            </div>
        </div>
    `;

            modal.classList.add('open');
            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.remove('open');
            document.getElementById('eventModalBackdrop').classList.remove('open');
            document.body.style.overflow = '';
        }

        // close modal with ESC
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeEventModal();
            }
        });
    </script>



    {{-- TAB SCRIPT (FINAL & STABIL) --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const tabs = document.querySelectorAll('.tab');
            const cards = document.querySelectorAll('.card.prod');
            const featuredMain = document.querySelector('.card.featured');

            function applyTab(target) {

                // FEATURED CARD BESAR
                if (featuredMain) {
                    featuredMain.style.display = target === 'all' ? '' : 'none';
                }

                // EVENT CARD GRID
                cards.forEach(card => {
                    const status = card.dataset.status;
                    const isFeatured = card.dataset.featured === '1';

                    if (target === 'all') {
                        card.style.display = isFeatured ? 'none' : '';
                        return;
                    }

                    card.style.display = status === target ? '' : 'none';
                });

            }

            // CLICK TAB
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.dataset.tab;

                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    applyTab(target);
                });
            });

            // 🔥 PAKSA STATE AWAL = TAB "ALL"
            applyTab('all');

        });
    </script>



@endsection
