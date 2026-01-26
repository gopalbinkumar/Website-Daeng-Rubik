<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar - Daeng Rubik</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
</head>
<body>
    <div class="auth-page">
        <!-- Left Visual Section -->
        <div class="auth-visual">
            <div class="auth-visual-content">
                <div class="auth-cube"></div>
                
                <h2 class="auth-slogan">Join the Community</h2>
                <p class="auth-desc">Bergabung dengan komunitas pencinta rubik di Makassar</p>
                
                <div class="auth-features">
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div style="text-align: left;">
                            <strong>Akses katalog lengkap</strong><br>
                            <small style="opacity: 0.9;">Belanja rubik dengan mudah</small>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div style="text-align: left;">
                            <strong>Daftar event rubik</strong><br>
                            <small style="opacity: 0.9;">Ikuti kompetisi & workshop</small>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div style="text-align: left;">
                            <strong>Materi belajar gratis</strong><br>
                            <small style="opacity: 0.9;">Dari basic hingga advanced</small>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">✓</div>
                        <div style="text-align: left;">
                            <strong>Komunitas aktif</strong><br>
                            <small style="opacity: 0.9;">Diskusi & sharing pengalaman</small>
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

                <h1 class="auth-title">Daftar Akun Baru</h1>
                <p class="auth-subtitle">Mulai perjalanan rubik Anda bersama kami</p>

                <form id="registerForm" class="auth-form">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" class="form-input" placeholder="Masukkan nama lengkap Anda" required>
                            <span class="input-icon">👤</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="email" class="form-input" placeholder="email@example.com" required>
                            <span class="input-icon">✉️</span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="tel" class="form-input" placeholder="+62 812-3456-7890" required>
                            <span class="input-icon">💬</span>
                        </div>
                        <small class="form-helper">Untuk konfirmasi pesanan & event</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Password <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password" class="form-input" placeholder="Minimal 8 karakter" required>
                            <span class="input-icon">🔒</span>
                            <button type="button" class="toggle-password">👁</button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill"></div>
                            </div>
                            <span class="strength-text"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="confirmPassword" class="form-input" placeholder="Ulangi password" required>
                            <span class="input-icon">🔒</span>
                            <button type="button" class="toggle-password">👁</button>
                        </div>
                        <small class="form-error" style="display: none;">
                            ⚠️ Password tidak cocok
                        </small>
                    </div>

                    <button type="submit" class="auth-btn">Daftar</button>
                </form>

                <div class="auth-link">
                    Sudah punya akun? <a href="{{ route('auth.login') }}">Login</a>
                </div>

                <div class="auth-divider">atau</div>

                <div class="auth-link">
                    <a href="{{ route('home') }}" style="color: var(--muted);">← Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/auth.js') }}" defer></script>
</body>
</html>
