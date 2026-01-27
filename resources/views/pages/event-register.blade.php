@extends('layouts.app')

@section('title', 'Daftar Event — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
@endpush

@section('content')
    @php
        $eventId = request()->get('id', 1);
        $events = [
            1 => [
                'id' => 1,
                'title' => 'Kompetisi Rubik Nasional',
                'date' => '15 Feb 2026 • 08:00 WIB',
                'location' => 'Jakarta Convention Center',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Featured'],
                'categories' => ['3x3', '2x2', '4x4', 'OH', 'BLD'],
            ],
            2 => [
                'id' => 2,
                'title' => 'Workshop Basic 3x3',
                'date' => '20 Feb 2026 • 13:00 WIB',
                'location' => 'Bandung',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'categories' => ['Workshop'],
            ],
            3 => [
                'id' => 3,
                'title' => 'Speedcubing Meetup',
                'date' => '25 Feb 2026 • 16:00 WIB',
                'location' => 'Surabaya',
                'status' => 'Upcoming',
                'badge' => ['muted', 'Meetup'],
                'categories' => ['Meetup'],
            ],
            4 => [
                'id' => 4,
                'title' => 'Rubik Fun Competition',
                'date' => '1 Mar 2026 • 09:00 WIB',
                'location' => 'Yogyakarta',
                'status' => 'Upcoming',
                'badge' => ['warn', 'Competition'],
                'categories' => ['3x3', '2x2'],
            ],
            5 => [
                'id' => 5,
                'title' => 'Advanced F2L Workshop',
                'date' => '5 Mar 2026 • 14:00 WIB',
                'location' => 'Jakarta',
                'status' => 'Upcoming',
                'badge' => ['ok', 'Workshop'],
                'categories' => ['Workshop'],
            ],
            6 => [
                'id' => 6,
                'title' => 'Regional Championship',
                'date' => '12 Mar 2026 • 07:00 WIB',
                'location' => 'Bali',
                'status' => 'Upcoming',
                'badge' => ['hot', 'Championship'],
                'categories' => ['3x3', '2x2', '4x4', '5x5', 'OH', 'BLD', 'Mega'],
            ],
        ];
        $event = $events[$eventId] ?? $events[1];
    @endphp

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda &gt; Event &gt; Daftar</div>
            <h1 class="page-title">Form Pendaftaran Event Rubik</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Isi data dengan benar untuk konfirmasi melalui WhatsApp.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="event-register-layout">
                <!-- Event Summary Card -->
                <aside class="event-summary-card">
                    <h3 style="font-size:18px;margin:0 0 16px;">Ringkasan Event</h3>
                    <div class="summary-event-info">
                        <span class="badge {{ $event['badge'][0] }}">{{ $event['badge'][1] }}</span>
                        <h4 style="font-size:20px;margin:12px 0 8px;font-weight:800;">{{ $event['title'] }}</h4>
                        <div class="summary-info-item">
                            <span class="info-icon">📅</span>
                            <span>{{ $event['date'] }}</span>
                        </div>
                        <div class="summary-info-item">
                            <span class="info-icon">📍</span>
                            <span>{{ $event['location'] }}</span>
                        </div>
                        <div class="summary-info-item">
                            <span class="info-icon">🏷️</span>
                            <span>{{ implode(', ', $event['categories']) }}</span>
                        </div>
                    </div>
                    <a href="{{ route('events') }}" class="summary-link">← Lihat detail event</a>
                    
                    <div class="summary-note" style="margin-top:20px;padding:12px;background:rgba(17,24,39,.04);border-radius:12px;">
                        <p style="margin:0;font-size:13px;color:var(--muted);line-height:1.6;">
                            <strong>Catatan:</strong> Setelah mendaftar, kami akan menghubungi Anda via WhatsApp untuk konfirmasi dan informasi lebih lanjut.
                        </p>
                    </div>
                </aside>

                <!-- Registration Form -->
                <div class="event-register-form-card">
                    <h3 style="font-size:18px;margin:0 0 20px;">Form Registrasi</h3>
                    
                    <form id="eventRegisterForm" class="event-register-form">
                        <div class="form-group">
                            <label class="form-label">Nama Lengkap Peserta <span class="required">*</span></label>
                            <input type="text" class="form-input" placeholder="Masukkan nama lengkap" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Email <span class="required">*</span></label>
                            <input type="email" class="form-input" placeholder="email@example.com" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nomor WhatsApp <span class="required">*</span></label>
                            <input type="tel" class="form-input" placeholder="+62 812-3456-7890" required>
                            <small class="form-helper">Contoh: +62 812-3456-7890</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Kategori Lomba <span class="required">*</span></label>
                            <div class="checkbox-list">
                                @foreach($event['categories'] as $cat)
                                    <label class="checkbox-item">
                                        <input type="checkbox" class="checkbox-input">
                                        <span>{{ $cat }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <small class="form-helper">Pilih satu atau lebih kategori lomba yang ingin diikuti.</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Pengalaman Bermain Rubik <span style="color:var(--muted);font-weight:400;">(opsional)</span></label>
                            <select class="form-input">
                                <option value="">Pilih pengalaman</option>
                                <option value="pemula">Pemula (baru belajar)</option>
                                <option value="menengah">Menengah (sudah bisa solve)</option>
                                <option value="mahir">Mahir (sub-30 detik)</option>
                                <option value="expert">Expert (sub-15 detik)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Catatan Tambahan <span style="color:var(--muted);font-weight:400;">(opsional)</span></label>
                            <textarea class="form-input" rows="4" placeholder="Ada pertanyaan atau catatan khusus?"></textarea>
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('events') }}" class="btn btn-secondary">Kembali ke halaman Event</a>
                            <button type="submit" class="btn btn-primary">Daftar Event</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Success Toast (hidden by default) -->
    <div id="successToast" class="success-toast" role="alert" aria-live="polite">
        <div class="toast-content">
            <span class="toast-icon">✓</span>
            <div>
                <strong>Pendaftaran terkirim!</strong>
                <p style="margin:4px 0 0;font-size:13px;">Kami akan menghubungi Anda via WhatsApp untuk konfirmasi.</p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('eventRegisterForm').addEventListener('submit', (e) => {
            e.preventDefault();
            
            const submitBtn = e.target.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.textContent = 'Mengirim...';
            
            // Simulate API call
            setTimeout(() => {
                const toast = document.getElementById('successToast');
                toast.classList.add('show');
                
                setTimeout(() => {
                    toast.classList.remove('show');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Daftar Event';
                    e.target.reset();
                }, 4000);
            }, 1500);
        });
    </script>
@endsection
