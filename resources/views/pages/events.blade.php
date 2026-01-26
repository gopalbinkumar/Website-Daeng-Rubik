@extends('layouts.app')

@section('title', 'Event Rubik — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
@endpush

@section('content')
    @php
        $events = [
            [
                'id' => 1,
                'title' => 'Kompetisi Rubik Nasional',
                'date' => '15 Feb 2026 • 08:00 WIB',
                'location' => 'Jakarta Convention Center',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Featured'],
                'desc' => 'Kompetisi rubik tingkat nasional dengan berbagai kategori lomba dan hadiah menarik.',
                'fullDesc' => 'Kompetisi Rubik Nasional adalah event terbesar tahunan yang menghadirkan para speedcuber dari seluruh Indonesia. Event ini menampilkan berbagai kategori lomba mulai dari 2x2, 3x3, 4x4, hingga kategori khusus seperti One-Handed dan Blindfolded. Acara ini juga dihadiri oleh para juara nasional dan internasional sebagai juri dan mentor.',
                'prize' => 'Rp 5.000.000',
                'categories' => ['3x3', '2x2', '4x4', 'OH', 'BLD'],
                'terms' => [
                    'Usia minimal 10 tahun',
                    'Wajib hadir 30 menit sebelum kompetisi dimulai',
                    'Biaya pendaftaran: Rp 50.000',
                    'Membawa rubik sendiri (atau bisa sewa di lokasi)',
                    'Mengikuti semua aturan WCA (World Cube Association)'
                ]
            ],
            [
                'id' => 2,
                'title' => 'Workshop Basic 3x3',
                'date' => '20 Feb 2026 • 13:00 WIB',
                'location' => 'Bandung',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'desc' => 'Belajar dasar rubik 3x3 dari nol, cocok untuk pemula.',
                'fullDesc' => 'Workshop Basic 3x3 adalah program pembelajaran yang dirancang khusus untuk pemula yang ingin belajar menyelesaikan rubik dari awal. Workshop ini akan mengajarkan metode layer-by-layer yang mudah dipahami, dengan instruktur berpengalaman yang akan membimbing step-by-step. Cocok untuk semua usia yang ingin memulai perjalanan speedcubing.',
                'prize' => 'Sertifikat & Materi',
                'categories' => ['Workshop'],
                'terms' => [
                    'Terbuka untuk semua usia',
                    'Biaya: Rp 75.000 (termasuk rubik untuk dibawa pulang)',
                    'Durasi: 3 jam',
                    'Maksimal 20 peserta per sesi',
                    'Materi dan snack disediakan'
                ]
            ],
            [
                'id' => 3,
                'title' => 'Speedcubing Meetup',
                'date' => '25 Feb 2026 • 16:00 WIB',
                'location' => 'Surabaya',
                'status' => 'Upcoming',
                'badge' => ['muted', 'Meetup'],
                'desc' => 'Meetup komunitas, sharing teknik, dan mini challenge.',
                'fullDesc' => 'Speedcubing Meetup adalah acara santai untuk para pecinta rubik berkumpul, berbagi teknik, dan mengikuti mini challenge. Acara ini gratis dan terbuka untuk semua level, dari pemula hingga expert. Perfect untuk networking dengan sesama speedcuber dan belajar teknik baru dari para senior.',
                'prize' => 'Mini Challenge Prize',
                'categories' => ['Meetup'],
                'terms' => [
                    'Gratis untuk semua peserta',
                    'Bawa rubik sendiri',
                    'Tidak ada batasan usia',
                    'Acara dimulai tepat waktu'
                ]
            ],
            [
                'id' => 4,
                'title' => 'Rubik Fun Competition',
                'date' => '1 Mar 2026 • 09:00 WIB',
                'location' => 'Yogyakarta',
                'status' => 'Upcoming',
                'badge' => ['warn', 'Competition'],
                'desc' => 'Kompetisi santai untuk semua level, seru dan ramah pemula.',
                'fullDesc' => 'Rubik Fun Competition adalah kompetisi yang dirancang untuk suasana yang lebih santai dan ramah pemula. Tidak ada tekanan untuk menang, fokus pada fun dan learning experience. Cocok untuk yang baru pertama kali ikut kompetisi atau yang ingin mencoba suasana kompetisi tanpa tekanan.',
                'prize' => 'Rp 2.000.000',
                'categories' => ['3x3', '2x2'],
                'terms' => [
                    'Terbuka untuk semua level',
                    'Biaya pendaftaran: Rp 30.000',
                    'Usia minimal 8 tahun',
                    'Membawa rubik sendiri',
                    'Hadiah untuk 3 terbaik setiap kategori'
                ]
            ],
            [
                'id' => 5,
                'title' => 'Advanced F2L Workshop',
                'date' => '5 Mar 2026 • 14:00 WIB',
                'location' => 'Jakarta',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'desc' => 'Workshop khusus teknik F2L untuk level intermediate ke advanced.',
                'fullDesc' => 'Advanced F2L Workshop adalah workshop khusus untuk mereka yang sudah menguasai basic solving dan ingin meningkatkan kecepatan dengan teknik F2L (First Two Layers) yang lebih advanced. Workshop ini akan membahas berbagai algoritma F2L, look-ahead techniques, dan tips untuk mencapai sub-20 detik.',
                'prize' => 'Sertifikat & E-Book',
                'categories' => ['Workshop'],
                'terms' => [
                    'Untuk level intermediate ke atas',
                    'Biaya: Rp 100.000',
                    'Durasi: 4 jam',
                    'Maksimal 15 peserta',
                    'Membawa rubik magnetic (disarankan)'
                ]
            ],
            [
                'id' => 6,
                'title' => 'Regional Championship',
                'date' => '12 Mar 2026 • 07:00 WIB',
                'location' => 'Bali',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Championship'],
                'desc' => 'Kejuaraan regional dengan sistem ranking resmi.',
                'fullDesc' => 'Regional Championship adalah kejuaraan resmi tingkat regional dengan sistem ranking yang diakui secara nasional. Event ini mengikuti standar WCA dan diikuti oleh para speedcuber terbaik dari berbagai daerah. Total hadiah mencapai puluhan juta rupiah dengan berbagai kategori lomba.',
                'prize' => 'Rp 15.000.000',
                'categories' => ['3x3', '2x2', '4x4', '5x5', 'OH', 'BLD', 'Mega'],
                'terms' => [
                    'WCA Official Competition',
                    'Biaya pendaftaran: Rp 100.000',
                    'Usia minimal 10 tahun',
                    'Wajib registrasi online sebelum deadline',
                    'Mengikuti semua aturan WCA',
                    'Rubik akan dicek sebelum kompetisi'
                ]
            ],
        ];
    @endphp

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
            <div class="sortbar">
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <span class="badge">📌 Daftar event</span>
                    <span class="muted" style="font-weight:700;">{{ count($events) }} event tersedia</span>
                </div>
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
                    <select class="select" aria-label="Urutkan event">
                        <option>Terbaru</option>
                        <option>Terdekat</option>
                        <option>Popular</option>
                    </select>
                </div>
            </div>

            <div class="tabs" aria-label="Filter status event">
                <button class="tab active" type="button" data-tab="all">Semua</button>
                <button class="tab" type="button" data-tab="upcoming">Upcoming</button>
                <button class="tab" type="button" data-tab="ongoing">Berlangsung</button>
                <button class="tab" type="button" data-tab="done">Selesai</button>
                <button class="tab" type="button" data-tab="mine">Daftar Saya</button>
            </div>

            <div style="height:16px"></div>

            <div data-panel="all">
                @php($featured = $events[0])
                <div class="card featured">
                    <div class="featured-media" aria-hidden="true"></div>
                    <div class="featured-body">
                        <span class="badge {{ $featured['badge'][0] }}">{{ $featured['badge'][1] }}</span>
                        <h3 style="margin:10px 0 6px;font-size:20px;letter-spacing:-.02em">{{ $featured['title'] }}</h3>
                        <p class="muted" style="margin:0;line-height:1.7">{{ $featured['desc'] }}</p>
                        <div class="kv">
                            <div><span class="k" aria-hidden="true">📅</span><span>{{ $featured['date'] }}</span></div>
                            <div><span class="k" aria-hidden="true">📍</span><span>{{ $featured['location'] }}</span></div>
                            <div><span class="k" aria-hidden="true">🧾</span><span>Pendaftaran online (UI)</span></div>
                        </div>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            <a href="{{ route('events.register', ['id' => $featured['id']]) }}" class="btn btn-primary">Daftar sekarang</a>
                            <button class="btn btn-secondary" type="button" onclick="openEventModal({{ $featured['id'] }})">Lihat detail</button>
                        </div>
                    </div>
                </div>

                <div style="height:16px"></div>

                <div class="grid-3">
                    @foreach(array_slice($events, 1) as $ev)
                        <article class="card prod">
                            <div class="prod-img" style="aspect-ratio: 16/10;">
                                <span class="badge {{ $ev['badge'][0] }}">{{ $ev['badge'][1] }}</span>
                                <div style="width:82%;max-width:260px;">
                                    <div class="cube" style="border-radius:18px;border-width:6px"></div>
                                </div>
                            </div>
                            <div class="prod-body">
                                <p class="prod-name">{{ $ev['title'] }}</p>
                                <p class="muted" style="margin:0 0 8px;line-height:1.6">
                                    📅 {{ $ev['date'] }}<br>
                                    📍 {{ $ev['location'] }}
                                </p>
                                <div style="display:flex;gap:10px;">
                                    <a href="{{ route('events.register', ['id' => $ev['id']]) }}" class="btn btn-primary" style="flex:1">Daftar</a>
                                    <button class="btn btn-secondary" type="button" style="flex:1" onclick="openEventModal({{ $ev['id'] }})">Detail</button>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <div data-panel="upcoming" style="display:none">
                <div class="card card-pad">
                    <b>Upcoming</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Untuk UI, status filter ini masih dummy (belum ada backend).</p>
                </div>
            </div>
            <div data-panel="ongoing" style="display:none">
                <div class="card card-pad">
                    <b>Berlangsung</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Nanti saat backend siap, event akan otomatis terfilter berdasarkan tanggal.</p>
                </div>
            </div>
            <div data-panel="done" style="display:none">
                <div class="card card-pad">
                    <b>Selesai</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Riwayat event (UI placeholder).</p>
                </div>
            </div>
            <div data-panel="mine" style="display:none">
                <div class="card card-pad">
                    <b>Daftar Saya</b>
                    <p class="muted" style="margin:8px 0 0;line-height:1.7">Menampilkan event yang kamu daftar (UI placeholder).</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Detail Modal -->
    <div id="eventModalBackdrop" class="modal-backdrop" aria-hidden="true" onclick="closeEventModal()"></div>
    <div id="eventModal" class="event-modal" role="dialog" aria-label="Detail Event" aria-modal="true">
        <button class="modal-close" onclick="closeEventModal()" aria-label="Tutup modal">✕</button>
        <div id="eventModalContent" class="event-modal-content">
            <!-- Content akan diisi via JavaScript -->
        </div>
    </div>

    <script>
        const eventsData = @json($events);
        
        function openEventModal(eventId) {
            const event = eventsData.find(e => e.id === eventId);
            if (!event) return;
            
            const modal = document.getElementById('eventModal');
            const backdrop = document.getElementById('eventModalBackdrop');
            const content = document.getElementById('eventModalContent');
            
            content.innerHTML = `
                <div class="event-modal-image">
                    <div style="width:100%;aspect-ratio:16/9;background:
                        radial-gradient(260px 200px at 30% 30%, rgba(25,118,210,.20), transparent 60%),
                        radial-gradient(260px 200px at 70% 70%, rgba(253,216,53,.26), transparent 60%),
                        linear-gradient(135deg, rgba(229,57,53,.16), rgba(255,255,255,.10));
                        border-radius:16px;display:flex;align-items:center;justify-content:center;">
                        <div class="cube" style="width:60%;max-width:200px;aspect-ratio:1/1;border-radius:18px;border-width:6px;"></div>
                    </div>
                </div>
                <div class="event-modal-body">
                    <span class="badge ${event.badge[0]}" style="margin-bottom:12px;">${event.badge[1]}</span>
                    <h2 class="event-modal-title">${event.title}</h2>
                    
                    <div class="event-modal-info">
                        <div class="info-item">
                            <span class="info-icon">📅</span>
                            <span>${event.date}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-icon">📍</span>
                            <span>${event.location}</span>
                        </div>
                    </div>
                    
                    <div class="event-modal-description">
                        <p style="color:var(--muted);line-height:1.7;margin:0;">${event.fullDesc || event.desc}</p>
                    </div>
                    
                    <div class="event-modal-highlights">
                        <div class="highlight-card">
                            <span class="highlight-label">Total Hadiah</span>
                            <span class="highlight-value">${event.prize}</span>
                        </div>
                        <div class="highlight-card">
                            <span class="highlight-label">Kategori Lomba</span>
                            <div class="categories-list">
                                ${event.categories.map(cat => `<span class="category-chip">${cat}</span>`).join('')}
                            </div>
                        </div>
                    </div>
                    
                    <div class="event-modal-terms">
                        <h3 style="font-size:16px;margin:0 0 12px;">Syarat & Ketentuan</h3>
                        <ul style="margin:0;padding-left:20px;color:var(--muted);line-height:1.8;">
                            ${event.terms.map(term => `<li>${term}</li>`).join('')}
                        </ul>
                    </div>
                    
                    <div class="event-modal-actions">
                        <button class="btn btn-secondary" onclick="closeEventModal()">Tutup</button>
                        <a href="{{ route('events.register', ['id' => '']) }}${event.id}" class="btn btn-primary">Daftar Sekarang</a>
                    </div>
                </div>
            `;
            
            modal.classList.add('open');
            backdrop.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        
        function closeEventModal() {
            const modal = document.getElementById('eventModal');
            const backdrop = document.getElementById('eventModalBackdrop');
            modal.classList.remove('open');
            backdrop.classList.remove('open');
            document.body.style.overflow = '';
        }
        
        // Close on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeEventModal();
            }
        });
    </script>
@endsection

