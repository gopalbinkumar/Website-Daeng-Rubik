<footer class="footer">
    <div class="container">
        <div class="footer-grid">

            <div class="footer-about">
                <a href="{{ url('/') }}" class="footer-brand">
                    <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik Logo">
                    <span>Daeng <span style="color:var(--red)">Rubik</span></span>
                </a>

                <small class="footer-desc">
                    Platform terpadu untuk belanja rubik, mengikuti event rubik, dan belajar rubik dari basic sampai
                    advanced.
                </small>

                <div class="social">
                    @if ($siteContact->instagram_url)
                        <a href="{{ $siteContact->instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    @endif

                    @if ($siteContact->facebook_url)
                        <a href="{{ $siteContact->facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    @endif

                    @if ($siteContact->youtube_url)
                        <a href="{{ $siteContact->youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    @endif

                    @if ($siteContact->tiktok_url)
                        <a href="{{ $siteContact->tiktok_url }}" target="_blank" rel="noopener" aria-label="TikTok">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    @endif

                    @if ($siteContact->whatsapp_url)
                        <a href="{{ $siteContact->whatsapp_url }}" target="_blank" rel="noopener" aria-label="WhatsApp">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    @endif
                </div>
            </div>

            <div class="footer-col">
                <h4>Navigasi</h4>
                <div class="footer-links">
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="{{ route('products') }}">Produk</a>
                    <a href="{{ route('events') }}">Event</a>
                    <a href="{{ route('learn.index') }}">Belajar</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Layanan</h4>
                <div class="footer-links">
                    <a href="{{ route('products') }}">Katalog Rubik</a>
                    <a href="{{ route('events') }}">Kompetisi & Workshop</a>
                    <a href="{{ route('learn.index') }}">Tutorial Rubik</a>
                    <a href="{{ route('contact') }}">Kerjasama</a>
                </div>
            </div>

            <div class="footer-col">
                <h4>Kontak</h4>
                <small class="footer-contact">
                    WhatsApp: {{ $siteContact->whatsapp_number ?: '-' }}<br>
                    Email: {{ $siteContact->email ?: '-' }}<br>
                    {{ $siteContact->address ?: '-' }}
                </small>
            </div>

        </div>

        <div class="footer-bottom" style="display: none">
            <small>© {{ date('Y') }} Daeng Rubik. All rights reserved.</small>

            <small class="footer-policy">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
            </small>
        </div>
    </div>
</footer>
