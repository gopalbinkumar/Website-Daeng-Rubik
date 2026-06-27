<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Daeng Rubik</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body>
    <div class="auth-page">

        <!-- Left Visual Section -->
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-cube" style="display: none"></div>

                <h2 class="auth-slogan">Solve, Learn, Compete</h2>
                <p class="auth-desc">
                    Platform rubik terlengkap di Indonesia untuk belanja, belajar, dan berkompetisi.
                </p>

                <div class="auth-features">
                    <div class="feature-item">
                        <div class="feature-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                        <div>
                            <strong>Katalog Lengkap</strong><br>
                            <small>Berbagai produk rubik berkualitas</small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon"><i class="fa-solid fa-calendar-days"></i></div>
                        <div>
                            <strong>Event Rutin</strong><br>
                            <small>Kompetisi & workshop berkala</small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon"><i class="fa-solid fa-book"></i></div>
                        <div>
                            <strong>Materi Gratis</strong><br>
                            <small>Tutorial dari basic hingga advanced</small>
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
                    <span class="auth-logo-text">
                        Daeng <span class="highlight">Rubik</span>
                    </span>
                </div>

                <h1 class="auth-title">Selamat Datang Kembali</h1>
                <p class="auth-subtitle">Login untuk melanjutkan ke dashboard Anda</p>

                @if ($errors->any())
                    <div class="form-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="form-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.login.post') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">
                            Email <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="email" name="email" class="form-input" placeholder="email@example.com"
                                value="{{ old('email') }}" required>
                            <span class="input-icon"><i class="fa-regular fa-envelope"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input type="password" name="password" class="form-input" placeholder="••••••••" required
                                data-password>

                            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                            <button type="button" class="toggle-password">
                                <i class="fa-regular fa-eye"></i>
                            </button>

                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember" class="checkbox-input">
                        <label for="remember" class="checkbox-label">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="auth-btn">
                        Login
                    </button>
                </form>

                <div class="auth-divider">atau</div>

                <a href="{{ route('auth.google.redirect') }}" class="google-auth-btn">
                    <span class="google-auth-icon" aria-hidden="true">
                        <svg viewBox="0 0 48 48">
                            <path fill="#EA4335"
                                d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z" />
                            <path fill="#4285F4"
                                d="M46.5 24.5c0-1.64-.15-3.22-.42-4.74H24v8.98h12.62c-.54 2.9-2.18 5.36-4.65 7.01l7.18 5.57C43.35 37.45 46.5 31.74 46.5 24.5z" />
                            <path fill="#FBBC05"
                                d="M10.54 28.59A14.48 14.48 0 0 1 9.79 24c0-1.59.27-3.13.75-4.59l-7.98-6.19A23.94 23.94 0 0 0 0 24c0 3.88.93 7.55 2.56 10.78l7.98-6.19z" />
                            <path fill="#34A853"
                                d="M24 48c6.47 0 11.9-2.13 15.87-5.78l-7.18-5.57c-2 1.34-4.55 2.13-8.69 2.13-6.26 0-11.57-4.22-13.46-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z" />
                        </svg>
                    </span>

                    <span>Login dengan Google</span>
                </a>

                <div class="forgot-link">
                    <a href="{{ route('auth.forgot') }}">Lupa password?</a>
                </div>

                <div class="auth-link">
                    Belum punya akun?
                    <a href="{{ route('auth.register') }}">Daftar</a>
                </div>

                {{-- <div class="auth-link">
                    <a href="{{ route('home') }}" style="color: var(--muted);">
                        ← Kembali ke Beranda
                    </a>
                </div> --}}

            </div>
        </div>
    </div>

    <!-- JS FIX (WAJIB ADA) -->
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
                });
            });
        </script>
    @endif


</body>

</html>
