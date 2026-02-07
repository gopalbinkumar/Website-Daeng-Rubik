@extends('layouts.app')

@section('title', 'Daftar Event — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
@endpush

@section('content')
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
                    {{-- <h3 style="font-size:18px;margin:0 0 16px;">Ringkasan Event</h3> --}}

                    {{-- COVER IMAGE EVENT --}}
                    @if ($event->cover_image)
                        <div style="margin-bottom:14px;">
                            <img src="{{ asset('storage/' . $event->cover_image) }}" alt="Cover {{ $event->title }}"
                                style="
                                width:100%;
                                height:auto;
                                display:block;
                                /* border-radius:12px; */
                            ">
                        </div>
                    @endif

                    <div class="summary-event-info">
                        {{-- <span class="badge {{ $event->badge_class }}">
                            {{ $event->badge_label }}
                        </span> --}}

                        <h4 style="font-size:20px;margin:12px 0 8px;font-weight:800;">
                            {{ $event->title }}
                        </h4>

                        <div class="summary-info-item">
                            <span class="info-icon">
                                <i class="fa-regular fa-calendar-days"></i>
                            </span>
                            <span>{{ $event->start_datetime->format('d M Y • H:i') }}</span>
                        </div>

                        <div class="summary-info-item">
                            <span class="info-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </span>
                            <span>{{ $event->location }}</span>
                        </div>

                        <div class="summary-info-item">
                            <span class="info-icon">
                                <i class="fa-solid fa-tags"></i>
                            </span>
                            <span>{{ $event->competitionCategories->pluck('name')->implode(', ') }}</span>
                        </div>

                        <span class="summary-info-item">{{ $event->description }}</span>
                    </div>

                    <a href="{{ route('events') }}" class="summary-link">← Lihat detail event</a>
                </aside>


                @if (!$alreadyRegistered)
                    <!-- Registration Form -->
                    <div class="event-register-form-card">
                        <h3 style="font-size:18px;margin:0 0 20px;">Form Registrasi</h3>

                        <form id="eventRegisterForm" class="event-register-form">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap Peserta <span class="required">*</span></label>
                                <input type="text" class="form-input" name="participant_name"
                                    value="{{ $user->name }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email <span class="required">*</span></label>
                                <input type="email" class="form-input" name="participant_email"
                                    value="{{ $user->email }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp <span class="required">*</span></label>
                                <input type="tel" class="form-input" name="participant_whatsapp"
                                    value="{{ $user->whatsapp }}" required>
                                <small class="form-helper">Contoh: +62 812-3456-7890</small>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Kategori Lomba <span class="required">*</span></label>
                                <div class="checkbox-list">
                                    @foreach ($event->competitionCategories as $cat)
                                        <label class="checkbox-item">
                                            <input type="checkbox" class="checkbox-input" name="categories[]"
                                                value="{{ $cat->id }}">
                                            <span>{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <small class="form-helper">Pilih satu atau lebih kategori lomba yang ingin diikuti.</small>
                            </div>

                            <div class="form-actions">
                                <a href="{{ route('events') }}" class="btn btn-secondary">Kembali ke halaman Event</a>
                                <button type="submit" class="btn btn-primary">Daftar Event</button>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- tampilkan ini jika sudah terdaftar --}}
                    <div class="event-register-form-card">
                        <h3 style="font-size:18px;margin:0 0 20px;">Anda sudah mendaftar</h3>

                        <p class="muted" style="line-height:1.6">
                            Anda sudah terdaftar pada event ini.
                            Silakan cek status pendaftaran dan detail kategori lomba
                            di halaman <strong>Event Saya</strong>.
                        </p>

                        <a href="/user/events" class="btn btn-primary">
                            Lihat Event Saya
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Success Toast -->
    <div id="successToast" class="success-toast" role="alert" aria-live="polite">
        <div class="toast-content">
            <span class="toast-icon">✓</span>
            <div>
                <strong>Pendaftaran terkirim!</strong>
                <p style="margin:4px 0 0;font-size:13px;">
                    Kami akan menghubungi Anda via WhatsApp untuk konfirmasi.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('eventRegisterForm').addEventListener('submit', async (e) => {
            e.preventDefault();

            const form = e.target;
            const btn = form.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Mengirim...';

            try {
                const res = await fetch("{{ route('events.register.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: new FormData(form)
                });

                if (!res.ok) {
                    alert('Validasi gagal. Pastikan semua data terisi.');
                    btn.disabled = false;
                    btn.textContent = 'Daftar Event';
                    return;
                }

                // tampilkan toast sukses
                const toast = document.getElementById('successToast');
                toast.classList.add('show');

                // redirect ke halaman event user
                setTimeout(() => {
                    window.location.href = '/user/events';
                }, 1500);

            } catch {
                alert('Terjadi kesalahan jaringan.');
                btn.disabled = false;
                btn.textContent = 'Daftar Event';
            }
        });
    </script>

@endsection
