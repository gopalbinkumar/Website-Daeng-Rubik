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
    <style>
        .category-summary-row {
            align-items: flex-start;
        }

        .summary-category-icon-list {
            display: flex+;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            padding-top: 0px;
        }

        .summary-category-icon-choice {
            width: 30px;
            height: 30px;
            border: none;
            background: transparent;
            padding: 0;
            margin: 0;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            border-radius: 8px;
            color: var(--muted);
            flex-shrink: 0;
        }

        .summary-category-icon-choice .cubing-category-icon {
            width: 22px !important;
            height: 22px !important;
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
    </style>

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

                        <div class="summary-info-item category-summary-row">
                            <span class="info-icon">
                                <i class="fa-solid fa-tags"></i>
                            </span>

                            <div class="summary-category-icon-list">
                                @foreach ($event->competitionCategories as $cat)
                                    <span class="summary-category-icon-choice" title="{{ $cat->name }}"
                                        aria-label="{{ $cat->name }}">
                                        <x-category-icon :code="$cat->code" :name="$cat->name" size="26" />
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <span class="summary-info-item">{{ $event->description }}</span>
                    </div>

                    <a href="{{ route('events') }}" class="summary-link">← Lihat detail event</a>
                </aside>


                @if ($showRegistrationForm)
                    <!-- Registration Form -->
                    <div class="event-register-form-card">
                        <h3 style="font-size:18px;margin:0 0 20px;">Form Registrasi</h3>

                        @if ($registration && $registration->status === 'pending')
                            <p class="muted" style="line-height:1.6">
                                Status pendaftaran Anda masih <strong>pending</strong>, namun anda dapat melakukan
                                pendaftaran ulang jika ingin mengubah data.
                            </p>
                        @endif

                        @if ($registration && $registration->status === 'rejected')
                            <p class="muted" style="line-height:1.6">
                                Status pendaftaran Anda <strong>ditolak</strong>. Silakan daftar ulang.
                            </p>
                        @endif

                        <form method="POST" action="{{ route('events.register.store') }}" class="event-register-form">
                            @csrf
                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap Peserta <span class="required">*</span></label>
                                <input type="text" class="form-input" name="participant_name"
                                    value="{{ $user->name }}">
                            </div>

                            <div class="form-group">
                                <label class="form-label">Tanggal Lahir <span class="required">*</span></label>
                                <input type="date" class="form-input" name="participant_birthdate"
                                    value="{{ old('participant_birthdate', $user->birthdate) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email <span class="required">*</span></label>
                                <input type="email" class="form-input" name="participant_email"
                                    value="{{ old('participant_email', $user->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp <span class="required">*</span></label>
                                <input type="text" class="form-input" name="participant_whatsapp"
                                    value="{{ old('participant_whatsapp', $user->whatsapp) }}" required
                                    inputmode="numeric">
                                <small class="form-helper">Contoh: 081234567890</small>
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
                    <div class="event-register-form-card">
                        @if ($registration && $registration->status === 'accepted')
                            <h3 style="font-size:18px;margin:0 0 20px;">Pendaftaran Diterima</h3>

                            <p class="muted" style="line-height:1.6;margin-bottom:20px;">
                                Pendaftaran Anda pada event ini sudah <strong>diterima</strong>.
                                Hubungi panitia jika ingin mengubah data pendaftaran Anda.
                            </p>

                            <div style="display:grid;gap:14px;">
                                <div class="summary-info-item">
                                    <span class="info-icon">
                                        <i class="fa-regular fa-user"></i>
                                    </span>
                                    <span>
                                        <strong>Nama:</strong>
                                        {{ $registration->participant_name }}
                                    </span>
                                </div>

                                <div class="summary-info-item">
                                    <span class="info-icon">
                                        <i class="fa-regular fa-calendar"></i>
                                    </span>
                                    <span>
                                        <strong>Tanggal Lahir:</strong>
                                        {{ \Carbon\Carbon::parse($registration->participant_birthdate)->format('d M Y') }}
                                    </span>
                                </div>

                                <div class="summary-info-item">
                                    <span class="info-icon">
                                        <i class="fa-regular fa-envelope"></i>
                                    </span>
                                    <span>
                                        <strong>Email:</strong>
                                        {{ $registration->participant_email }}
                                    </span>
                                </div>

                                <div class="summary-info-item">
                                    <span class="info-icon">
                                        <i class="fa-brands fa-whatsapp"></i>
                                    </span>
                                    <span>
                                        <strong>WhatsApp:</strong>
                                        {{ $registration->participant_whatsapp }}
                                    </span>
                                </div>

                                <div class="summary-info-item">
                                    <span class="info-icon">
                                        <i class="fa-solid fa-circle-check"></i>
                                    </span>
                                    <span>
                                        <strong>Status:</strong>
                                        Diterima
                                    </span>
                                </div>

                                <div class="summary-info-item">
                                    <span class="info-icon">
                                        <i class="fa-solid fa-tags"></i>
                                    </span>
                                    <span>
                                        <strong>Kategori:</strong>

                                        @if ($registration->competitionCategories && $registration->competitionCategories->count())
                                            {{ $registration->competitionCategories->pluck('name')->implode(', ') }}
                                        @else
                                            -
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <div class="form-actions" style="margin-top:22px;">
                                <a href="{{ route('events') }}" class="btn btn-secondary">
                                    Kembali ke halaman Event
                                </a>

                                <a href="{{ route('user.competitions') }}" class="btn btn-primary">
                                    Lihat Event Saya
                                </a>
                            </div>
                        @else
                            <h3 style="font-size:18px;margin:0 0 20px;">Anda sudah mendaftar</h3>

                            <p class="muted" style="line-height:1.6">
                                Anda sudah terdaftar pada event ini.
                                Silakan cek status pendaftaran dan detail kategori lomba
                                di halaman <strong>Event Saya</strong>.
                            </p>

                            <a href="{{ route('user.competitions') }}" class="btn btn-primary">
                                Lihat Event Saya
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </section>

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

                const data = await res.json();

                if (!res.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message ?? 'Validasi gagal. Pastikan semua data terisi.'
                    });

                    btn.disabled = false;
                    btn.textContent = 'Daftar Event';
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: 'Pendaftaran berhasil! Silakan cek Event Saya.',
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    window.location.href = '/my-competitions';
                }, 1500);

            } catch {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan jaringan.'
                });

                btn.disabled = false;
                btn.textContent = 'Daftar Event';
            }
        });
    </script>

@endsection
