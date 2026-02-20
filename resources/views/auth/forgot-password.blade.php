<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Daeng Rubik</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body>
    <div class="auth-page">
        <!-- Left Visual Section -->
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-cube" style="display: none"></div>
                
                <h2 class="auth-slogan">Reset Your Password</h2>
                <p class="auth-desc">Kami akan mengirimkan link untuk reset password ke email Anda.</p>

                <div class="auth-features">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <div style="text-align: left;">
                            <strong>Masukkan email Anda</strong><br>
                            <small style="opacity: 0.9;">Pastikan email masih aktif</small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-inbox"></i>
                        </div>
                        <div style="text-align: left;">
                            <strong>Cek inbox Anda</strong><br>
                            <small style="opacity: 0.9;">Periksa kode reset password</small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <div style="text-align: left;">
                            <strong>Buat password baru</strong><br>
                            <small style="opacity: 0.9;">Password minimal 8 karakter</small>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Right Form Section -->
        <div class="auth-form-container">
            <div class="auth-form-card">
                <div class="auth-logo">
                    <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik">
                    <span class="auth-logo-text">Daeng <span class="highlight">Rubik</span></span>
                </div>

                <h1 class="auth-title">Lupa Password?</h1>
                <p class="auth-subtitle">Masukkan email Anda dan kami akan mengirimkan kode reset password</p>

                @php
                    $step = session('step');
                @endphp

                {{-- STEP 1 --}}
                @if (!$step)
                    <form method="POST" action="{{ route('auth.forgot.send.code') }}" class="auth-form">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <div class="input-wrapper">
                                <input type="email" name="email" class="form-input" placeholder="example@gmail.com"
                                    required>
                                <span class="input-icon">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>
                            </div>
                        </div>
                        <button type="submit" class="auth-btn">Kirim Kode OTP</button>
                    </form>
                @endif

                {{-- STEP 2 --}}
                @if ($step === 'otp')
                    <form method="POST" action="{{ route('auth.forgot.verify.code') }}" class="auth-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">

                        <div class="form-group">
                            <label class="form-label">Masukkan Kode OTP</label>
                            <div class="input-wrapper">
                                <input type="number" name="code" class="form-input" placeholder="123456" required>
                                <span class="input-icon">
                                    <i class="fa-solid fa-key"></i>
                                </span>
                            </div>
                        </div>

                        <button type="submit" class="auth-btn">Verifikasi Kode</button>
                    </form>
                @endif

                {{-- STEP 3 --}}
                @if ($step === 'password')
                    <form method="POST" action="{{ route('auth.forgot.update.password') }}" class="auth-form">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">

                        {{-- Password Baru --}}
                        <div class="form-group">
                            <label class="form-label">
                                Password Baru <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="password" name="password" class="form-input" placeholder="••••••••"
                                    required data-password>

                                <span class="input-icon">
                                    <i class="fa-solid fa-lock"></i>
                                </span>

                                <button type="button" class="toggle-password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="form-group">
                            <label class="form-label">
                                Konfirmasi Password <span class="required">*</span>
                            </label>
                            <div class="input-wrapper">
                                <input type="password" name="password_confirmation" class="form-input"
                                    placeholder="••••••••" required data-password>

                                <span class="input-icon">
                                    <i class="fa-solid fa-lock"></i>
                                </span>

                                <button type="button" class="toggle-password">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="auth-btn">
                            Simpan Password Baru
                        </button>
                    </form>
                @endif



                <div class="forgot-link">
                    <a href="{{ route('auth.login') }}">← Kembali ke Login</a>
                </div>

                <div class="auth-divider">atau</div>

                <div class="auth-link">
                    Belum punya akun? <a href="{{ route('auth.register') }}">Daftar</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/auth.js') }}" defer></script>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1500,
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#E53935',
                    customClass: {
                        confirmButton: 'auth-btn'
                    }
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#E53935',
                    customClass: {
                        confirmButton: 'auth-btn'
                    }
                });
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.toggle-password').forEach(function(toggle) {
            toggle.addEventListener('click', function() {

                const input = this.closest('.input-wrapper').querySelector('.password-field');
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


</body>

</html>
