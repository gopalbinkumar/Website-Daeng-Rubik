<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="brand" style="margin-bottom:10px;">
                    <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Daeng Rubik Logo" style="width:38px;height:38px;object-fit:contain;display:block;" />
                    <span>Daeng <span style="color:var(--yellow)">Rubik</span></span>
                </div>
                <small class="muted" style="color:rgba(255,255,255,.70);display:block;line-height:1.7">
                    Platform terpadu untuk belanja rubik, mengikuti event rubik, dan belajar rubik dari basic sampai advanced.
                </small>
                <div class="social" style="margin-top:14px;">
                    <a href="#" aria-label="Instagram">IG</a>
                    <a href="#" aria-label="Facebook">FB</a>
                    <a href="#" aria-label="YouTube">YT</a>
                    <a href="#" aria-label="TikTok">TT</a>
                    <a href="{{ route('contact') }}" aria-label="WhatsApp">WA</a>
                </div>
            </div>

            <div>
                <h4>Navigasi</h4>
                <div style="display:grid;gap:8px;">
                    <a href="{{ url('/') }}">Beranda</a>
                    <a href="{{ route('products') }}">Produk</a>
                    <a href="{{ route('events') }}">Event</a>
                    <a href="{{ route('learn') }}">Belajar</a>
                </div>
            </div>

            <div>
                <h4>Layanan</h4>
                <div style="display:grid;gap:8px;">
                    <a href="{{ route('products') }}">Katalog Rubik</a>
                    <a href="{{ route('events') }}">Kompetisi & Workshop</a>
                    <a href="{{ route('learn') }}">Tutorial Rubik</a>
                    <a href="{{ route('contact') }}">Kerjasama</a>
                </div>
            </div>

            <div>
                <h4>Kontak</h4>
                <small style="display:block;color:rgba(255,255,255,.70);line-height:1.7">
                    WhatsApp: +62 812-3456-7890<br>
                    Email: info@daengrubik.com<br>
                    Jakarta, Indonesia
                </small>
                <div style="margin-top:12px;">
                    <a class="btn btn-primary" href="{{ route('contact') }}">Hubungi Kami</a>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <small>© {{ date('Y') }} Daeng Rubik. All rights reserved.</small>
            <small>
                <a href="#" style="margin-right:10px;">Kebijakan Privasi</a>
                <a href="#">Syarat & Ketentuan</a>
            </small>
        </div>
    </div>
</footer>

