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
                    <a href="https://www.instagram.com/daengrubik" aria-label="Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a href="#" aria-label="Facebook">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a href="https://www.youtube.com/@daengrubik" aria-label="YouTube">
                        <i class="fa-brands fa-youtube"></i>
                    </a>

                    <a href="https://www.tiktok.com/@daeng_rubik" aria-label="TikTok">
                        <i class="fa-brands fa-tiktok"></i>
                    </a>

                    <a href="{{ route('contact') }}" aria-label="WhatsApp">
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>
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
                    WhatsApp: +62 812-3456-7890<br>
                    Email: celebescubers@gmail.com<br>
                    Makassar, Indonesia
                </small>
            </div>

        </div>

        <div class="footer-bottom">
            <small>© {{ date('Y') }} Daeng Rubik. All rights reserved.</small>

            <small class="footer-policy">
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
            </small>
        </div>
    </div>
</footer>