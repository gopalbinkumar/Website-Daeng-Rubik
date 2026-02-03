<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Daeng Rubik</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">

    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>

<body>
    <div class="auth-page">

        <!-- Left Visual Section -->
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-cube"></div>

                <h2 class="auth-slogan">Solve, Learn, Compete</h2>
                <p class="auth-desc">
                    Platform rubik terlengkap di Indonesia untuk belanja, belajar, dan berkompetisi.
                </p>

                <div class="auth-features">
                    <div class="feature-item">
                        <div class="feature-icon">🛒</div>
                        <div>
                            <strong>Katalog Lengkap</strong><br>
                            <small>100+ produk rubik berkualitas</small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">🎉</div>
                        <div>
                            <strong>Event Rutin</strong><br>
                            <small>Kompetisi & workshop berkala</small>
                        </div>
                    </div>

                    <div class="feature-item">
                        <div class="feature-icon">📚</div>
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
                            Email / Username <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input
                                type="email"
                                name="email"
                                class="form-input"
                                placeholder="email@example.com"
                                value="{{ old('email') }}"
                                required
                            >
                            <span class="input-icon"><i class="fa-regular fa-user"></i></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            Password <span class="required">*</span>
                        </label>
                        <div class="input-wrapper">
                            <input
                                type="password"
                                name="password"
                                class="form-input"
                                placeholder="••••••••"
                                required
                                data-password
                            >

                            <span class="input-icon"><i class="fa-solid fa-lock"></i></span>
                            <button type="button" class="toggle-password">👁</button>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="checkbox-input"
                        >
                        <label for="remember" class="checkbox-label">
                            Ingat saya
                        </label>
                    </div>

                    <button type="submit" class="auth-btn">
                        Login
                    </button>
                </form>

                <div class="forgot-link">
                    <a href="{{ route('auth.forgot') }}">Lupa password?</a>
                </div>

                <div class="auth-link">
                    Belum punya akun?
                    <a href="{{ route('auth.register') }}">Daftar</a>
                </div>

                <div class="auth-divider">atau</div>

                <div class="auth-link">
                    <a href="{{ route('home') }}" style="color: var(--muted);">
                        ← Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>

    <!-- JS FIX (WAJIB ADA) -->
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            document.querySelectorAll(".toggle-password").forEach((btn) => {
                btn.addEventListener("click", () => {
                    const wrapper = btn.closest(".input-wrapper");
                    if (!wrapper) return;

                    const input = wrapper.querySelector("input[data-password]");
                    if (!input) return;

                    if (input.type === "password") {
                        input.type = "text";
                        btn.textContent = "👁";
                    } else {
                        input.type = "password";
                        btn.textContent = "👁";
                    }
                });
            });
        });
    </script>

</body>
</html>
