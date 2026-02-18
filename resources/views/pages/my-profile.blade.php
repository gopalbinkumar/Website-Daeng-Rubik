@extends('layouts.app')

@section('title', 'Profil Saya — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/events.css') }}">
@endpush

@section('content')

    <style>
        .password-field {
            position: relative;
        }

        .password-field .form-input {
            padding-right: 44px;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 14px;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 15px;
            color: rgba(17, 24, 39, 0.6);
        }

        .toggle-password:hover {
            color: var(--red);
        }
    </style>

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">
                Beranda &gt; Profil Saya
            </div>
            <h1 class="page-title">Profil Saya</h1>
            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Kelola informasi akun dan keamanan Anda.
            </p>
        </div>
    </section>

    <section class="section" style="padding-top:22px;">
        <div class="container">
            <div class="event-register-layout">

                {{-- ================= LEFT CARD (SUMMARY) ================= --}}
                <aside class="event-summary-card">

                    <h4 style="font-size:20px;margin:0 0 12px;font-weight:700;">
                        {{ auth()->user()->name }}
                    </h4>

                    <div class="summary-info-item">
                        <span class="info-icon">
                            <i class="fa-regular fa-envelope"></i>
                        </span>
                        <span>{{ auth()->user()->email }}</span>
                    </div>

                    <div class="summary-info-item">
                        <span class="info-icon">
                            <i class="fa-brands fa-whatsapp"></i>
                        </span>
                        <span>{{ auth()->user()->whatsapp ?? '-' }}</span>
                    </div>

                    {{-- <div class="summary-info-item">
                    <span class="info-icon">
                        <i class="fa-solid fa-user-shield"></i>
                    </span>
                    <span>Role: {{ ucfirst(auth()->user()->role) }}</span>
                </div> --}}

                    <a href="{{ route('home') }}" class="summary-link">
                        ← Kembali ke Beranda
                    </a>
                </aside>


                {{-- ================= RIGHT CARD (FORM) ================= --}}
                <div style="display:flex;flex-direction:column;gap:24px;">

                    {{-- UPDATE PROFIL --}}
                    <div class="event-register-form-card">
                        <h3 style="font-size:18px;margin:0 0 20px;">Informasi Akun</h3>

                        <form method="POST" action="{{ route('profile.update') }}" class="event-register-form">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-input"
                                    value="{{ old('name', auth()->user()->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-input"
                                    value="{{ old('email', auth()->user()->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp</label>
                                <input type="number" name="whatsapp" class="form-input"
                                    value="{{ old('whatsapp', auth()->user()->whatsapp) }}">
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>


                    {{-- UBAH PASSWORD --}}
                    <div class="event-register-form-card">
                        <h3 style="font-size:18px;margin:0 0 20px;">Ubah Password</h3>

                        <form method="POST" action="{{ route('profile.password') }}" class="event-register-form">
                            @csrf
                            @method('PUT')

                            {{-- PASSWORD LAMA --}}
                            <div class="form-group">
                                <label class="form-label">Password Lama</label>
                                <div class="password-field">
                                    <input type="password" name="current_password" class="form-input password-input"
                                        required>
                                    <button type="button" class="toggle-password">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- PASSWORD BARU --}}
                            <div class="form-group">
                                <label class="form-label">Password Baru</label>
                                <div class="password-field">
                                    <input type="password" name="password" class="form-input password-input" required>
                                    <button type="button" class="toggle-password">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- KONFIRMASI PASSWORD --}}
                            <div class="form-group">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <div class="password-field">
                                    <input type="password" name="password_confirmation" class="form-input password-input"
                                        required>
                                    <button type="button" class="toggle-password">
                                        <i class="fa-regular fa-eye"></i>
                                    </button>
                                </div>
                            </div>


                            <div class="form-actions">
                                <button type="submit" class="btn btn-outline">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.toggle-password').forEach(button => {

            button.addEventListener('click', function() {

                const input = this.parentElement.querySelector('.password-input');
                const icon = this.querySelector('i');

                if (input.type === 'password') {
                    input.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    input.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }

            });

        });
    </script>


@endsection
