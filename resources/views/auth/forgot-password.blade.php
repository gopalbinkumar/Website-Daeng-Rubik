<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lupa Password - Daeng Rubik</title>
    
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
                
                <h2 class="auth-slogan">Reset Your Password</h2>
                <p class="auth-desc">Kami akan mengirimkan link untuk reset password ke email Anda.</p>
                
                <div class="auth-features">
                    <div class="feature-item">
                        <div class="feature-icon">📧</div>
                        <div style="text-align: left;">
                            <strong>Masukkan email Anda</strong><br>
                            <small style="opacity: 0.9;">Pastikan email masih aktif</small>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔗</div>
                        <div style="text-align: left;">
                            <strong>Cek inbox Anda</strong><br>
                            <small style="opacity: 0.9;">Klik link reset password</small>
                        </div>
                    </div>
                    <div class="feature-item">
                        <div class="feature-icon">🔐</div>
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
                <p class="auth-subtitle">Masukkan email Anda dan kami akan mengirimkan link reset password</p>

                <form id="forgotForm" class="auth-form">
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="email" class="form-input" placeholder="email@example.com" required>
                            <span class="input-icon">✉️</span>
                        </div>
                        <small class="form-helper">Link reset akan dikirim ke email ini</small>
                    </div>

                    <button type="submit" class="auth-btn">Kirim Link Reset</button>
                </form>

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

    <script>
        document.getElementById('forgotForm').addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = e.target.querySelector('button[type="submit"]');
            btn.classList.add('loading');
            btn.disabled = true;
            
            setTimeout(() => {
                alert('Link reset password telah dikirim ke email Anda! (UI Demo)');
                btn.classList.remove('loading');
                btn.disabled = false;
            }, 1500);
        });
    </script>
</body>
</html>
