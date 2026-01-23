# Dokumentasi Desain UI - Website Daeng Rubik

## 1. Konsep Desain & Identitas Visual

### Palet Warna
**Warna Primer:**
- **Merah Rubik**: `#E53935` (vibrant red)
- **Biru Rubik**: `#1976D2` (bright blue)
- **Kuning Rubik**: `#FDD835` (energetic yellow)
- **Hijau Rubik**: `#43A047` (fresh green)
- **Oranye Rubik**: `#FB8C00` (warm orange)
- **Putih Rubik**: `#FFFFFF` (clean white)

**Warna Sekunder:**
- **Background Light**: `#F5F5F5` (soft gray)
- **Text Primary**: `#212121` (dark gray)
- **Text Secondary**: `#757575` (medium gray)
- **Accent Gradient**: Linear gradient dari merah → biru → kuning

### Tipografi
- **Heading**: 'Poppins' atau 'Montserrat' (Bold, 700)
- **Body**: 'Inter' atau 'Roboto' (Regular, 400)
- **Accent Text**: 'Rubik' font (jika tersedia) atau 'Nunito' (Semi-bold, 600)

### Elemen Visual
- **Icon Style**: Rounded, colorful, modern
- **Button Style**: Rounded corners (12px), shadow subtle
- **Card Style**: White background, rounded (16px), shadow soft
- **Pattern**: Subtle geometric patterns mengingatkan pada rubik cube

---

## 2. Struktur Navigasi

### Menu Utama (Desktop)
```
[Logo Daeng Rubik]  Beranda  |  Produk  |  Event  |  Belajar  |  Tentang  |  Kontak  [🔍] [🛒]
```

### Menu Mobile (Hamburger)
```
☰ Menu
├── Beranda
├── Produk
├── Event
├── Belajar
├── Tentang
└── Kontak
```

### Footer Navigation
```
Tentang Kami | Produk | Event | Belajar | Kontak
Media Sosial: [Instagram] [Facebook] [WhatsApp] [YouTube]
```

---

## 3. Halaman Landing Page / Beranda

### Layout Struktur:
```
┌─────────────────────────────────────────┐
│         NAVIGATION BAR (Sticky)          │
├─────────────────────────────────────────┤
│                                           │
│         HERO SECTION                     │
│  ┌─────────────────────────────────┐    │
│  │  Logo Daeng Rubik (3D/Animated) │    │
│  │  Slogan: "Solve, Learn, Compete" │    │
│  │  CTA: "Jelajahi Produk"          │    │
│  └─────────────────────────────────┘    │
│                                           │
│    PRODUK UNGGULAN SECTION               │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐   │
│  │ Card │ │ Card │ │ Card │ │ Card │   │
│  │Prod 1│ │Prod 2│ │Prod 3│ │Prod 4│   │
│  └──────┘ └──────┘ └──────┘ └──────┘   │
│                                           │
│    EVENT HIGHLIGHT SECTION               │
│  ┌─────────────────────────────────┐    │
│  │  Event Card (Featured)           │    │
│  │  [Gambar Event] [Info Event]     │    │
│  └─────────────────────────────────┘    │
│                                           │
│    STATISTIK / ACHIEVEMENT               │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐   │
│  │ 100+ │ │ 50+  │ │ 500+ │ │ 4.9⭐│   │
│  │Produk│ │Event │ │Siswa │ │Rating│   │
│  └──────┘ └──────┘ └──────┘ └──────┘   │
│                                           │
│         FOOTER                            │
└─────────────────────────────────────────┘
```

### Komponen Detail:

#### Hero Section
- **Background**: Gradient warna rubik (merah → biru → kuning) dengan pattern geometric subtle
- **Logo**: 3D rubik cube animation atau logo Daeng Rubik dengan efek hover
- **Slogan**: Typography besar, bold, dengan efek gradient text
- **CTA Button**: Primary button besar dengan shadow, hover effect
- **Illustration**: 3D rubik cube floating atau rotating animation

#### Produk Unggulan
- **Layout**: Grid 4 kolom (desktop), 2 kolom (tablet), 1 kolom (mobile)
- **Card Design**:
  - Gambar produk dengan hover zoom effect
  - Nama produk (bold)
  - Harga (warna accent)
  - Badge "Bestseller" atau "New"
  - Quick view button
- **Section Title**: "Produk Terlaris" dengan icon rubik

#### Event Highlight
- **Layout**: Full-width card dengan split layout (gambar kiri, info kanan)
- **Content**:
  - Gambar event besar
  - Judul event
  - Tanggal & lokasi (icon calendar & location)
  - Deskripsi singkat
  - CTA "Daftar Sekarang" (button accent)
  - Countdown timer (jika event upcoming)

#### Statistik Section
- **Layout**: 4 kolom dengan icon besar
- **Design**: Card dengan icon, angka besar, label kecil
- **Warna**: Setiap card berbeda warna (merah, biru, kuning, hijau)

---

## 4. Halaman Katalog Produk

### Layout Struktur:
```
┌─────────────────────────────────────────┐
│         NAVIGATION BAR                   │
├─────────────────────────────────────────┤
│  Breadcrumb: Beranda > Produk           │
│                                           │
│  ┌──────────────┐ ┌──────────────────┐ │
│  │   SIDEBAR    │ │   PRODUCT GRID   │ │
│  │   FILTER     │ │                  │ │
│  │              │ │  ┌──┐ ┌──┐ ┌──┐ │ │
│  │ Kategori:    │ │  │  │ │  │ │  │ │ │
│  │ ☐ 3x3        │ │  │  │ │  │ │  │ │ │
│  │ ☐ 4x4        │ │  │  │ │  │ │  │ │ │
│  │ ☐ 5x5        │ │  └──┘ └──┘ └──┘ │ │
│  │ ☐ Megaminx   │ │  ┌──┐ ┌──┐ ┌──┐ │ │
│  │ ☐ Pyraminx   │ │  │  │ │  │ │  │ │ │
│  │              │ │  │  │ │  │ │  │ │ │
│  │ Harga:       │ │  └──┘ └──┘ └──┘ │ │
│  │ [Slider]     │ │                  │ │
│  │              │ │  [Pagination]    │ │
│  │ Brand:       │ │                  │ │
│  │ ☐ Moyu       │ │                  │ │
│  │ ☐ GAN        │ │                  │ │
│  │ ☐ QiYi       │ │                  │ │
│  │              │ │                  │ │
│  │ [Reset]      │ │                  │ │
│  └──────────────┘ └──────────────────┘ │
│                                           │
│         FOOTER                            │
└─────────────────────────────────────────┘
```

### Komponen Detail:

#### Sidebar Filter (Desktop)
- **Position**: Sticky saat scroll
- **Background**: White card dengan shadow
- **Sections**:
  - Kategori Rubik (checkbox)
  - Range Harga (slider)
  - Brand (checkbox)
  - Rating (star filter)
  - Stok (radio: Semua / Tersedia)
- **Button**: "Reset Filter" di bawah

#### Filter Mobile
- **Layout**: Drawer/Bottom sheet yang bisa di-toggle
- **Trigger**: Button "Filter" di atas product grid
- **Design**: Full-screen overlay dengan close button

#### Product Grid
- **Layout**: 
  - Desktop: 3-4 kolom
  - Tablet: 2 kolom
  - Mobile: 1 kolom
- **Product Card**:
  - Gambar produk (aspect ratio 1:1)
  - Badge diskon/stok (jika ada)
  - Nama produk (2 baris max, ellipsis)
  - Rating bintang (5 stars)
  - Harga (dengan strikethrough jika diskon)
  - Button "Lihat Detail" / "Tambah ke Cart"
  - Hover effect: Shadow lebih besar, scale sedikit

#### Sort & View Options
- **Position**: Di atas product grid
- **Options**: 
  - Sort by: Terbaru, Harga Terendah, Harga Tertinggi, Rating
  - View: Grid / List toggle
  - Results count: "Menampilkan 24 dari 100 produk"

#### Pagination
- **Style**: Numbered pagination dengan prev/next
- **Design**: Rounded buttons dengan active state highlight

---

## 5. Halaman Event Rubik

### Layout Struktur:
```
┌─────────────────────────────────────────┐
│         NAVIGATION BAR                   │
├─────────────────────────────────────────┤
│  Breadcrumb: Beranda > Event             │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   TAB NAVIGATION                   │  │
│  │  [Semua] [Upcoming] [Berlangsung] │  │
│  │        [Selesai] [Daftar]         │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   EVENT CARD 1 (Featured)          │  │
│  │  ┌──────────┐ ┌─────────────────┐ │  │
│  │  │  Gambar  │ │  Judul Event    │ │  │
│  │  │  Event   │ │  📅 Tanggal     │ │  │
│  │  │          │ │  📍 Lokasi      │ │  │
│  │  │          │ │  👥 Peserta     │ │  │
│  │  │          │ │  [Daftar]       │ │  │
│  │  └──────────┘ └─────────────────┘ │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌──────┐ ┌──────┐ ┌──────┐            │
│  │Event │ │Event │ │Event │            │
│  │Card 2│ │Card 3│ │Card 4│            │
│  └──────┘ └──────┘ └──────┘            │
│                                           │
│         FOOTER                            │
└─────────────────────────────────────────┘
```

### Komponen Detail:

#### Tab Navigation
- **Style**: Underline tab dengan active indicator
- **Tabs**: Semua | Upcoming | Berlangsung | Selesai | Daftar Saya
- **Color**: Active tab menggunakan warna accent

#### Event Card (Featured)
- **Layout**: Horizontal split (gambar besar kiri, info kanan)
- **Size**: Lebih besar dari card biasa
- **Content**:
  - Gambar event (cover image)
  - Badge "Featured" atau "Hot Event"
  - Judul event (bold, besar)
  - Tanggal & waktu (icon calendar)
  - Lokasi (icon location)
  - Jumlah peserta terdaftar (icon users)
  - Kategori event (badge: Speedcubing, Competition, Workshop)
  - Deskripsi singkat (2-3 baris)
  - CTA: "Daftar Sekarang" (primary button)
  - Countdown timer (jika upcoming)

#### Event Card (Regular)
- **Layout**: Vertical card (gambar atas, info bawah)
- **Size**: Standard card size
- **Content**:
  - Gambar event (thumbnail)
  - Status badge (Upcoming / Ongoing / Finished)
  - Judul event
  - Tanggal & lokasi (compact)
  - Harga tiket (jika berbayar)
  - Button "Lihat Detail"

#### Filter & Search
- **Position**: Di atas event list
- **Options**: 
  - Search bar
  - Filter: Kategori, Tanggal, Lokasi
  - Sort: Terbaru, Terdekat, Popular

---

## 6. Halaman Belajar Rubik

### Layout Struktur:
```
┌─────────────────────────────────────────┐
│         NAVIGATION BAR                   │
├─────────────────────────────────────────┤
│  Breadcrumb: Beranda > Belajar           │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   HERO SECTION                     │  │
│  │   "Mulai Perjalanan Belajar Anda"  │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   LEVEL NAVIGATION                 │  │
│  │  [Basic] [Intermediate] [Advanced] │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌──────┐ ┌──────┐ ┌──────┐            │
│  │Tutor │ │Tutor │ │Tutor │            │
│  │Card 1│ │Card 2│ │Card 3│            │
│  │Basic │ │Basic │ │Basic │            │
│  └──────┘ └──────┘ └──────┘            │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   KATEGORI TUTORIAL                │  │
│  │  [3x3] [4x4] [5x5] [Megaminx]     │  │
│  └───────────────────────────────────┘  │
│                                           │
│         FOOTER                            │
└─────────────────────────────────────────┘
```

### Komponen Detail:

#### Hero Section
- **Background**: Gradient dengan ilustrasi rubik cube
- **Content**: 
  - Judul: "Belajar Rubik dari Nol hingga Pro"
  - Subtitle: Deskripsi singkat program pembelajaran
  - CTA: "Mulai Belajar Sekarang"

#### Level Navigation
- **Style**: Pill-shaped buttons dengan icon
- **Levels**:
  - **Basic**: Icon pemula, warna hijau
  - **Intermediate**: Icon menengah, warna kuning
  - **Advanced**: Icon expert, warna merah
- **Active State**: Highlight dengan background gradient

#### Tutorial Card
- **Layout**: Grid card dengan thumbnail
- **Content**:
  - Thumbnail video/gambar tutorial
  - Badge level (Basic/Intermediate/Advanced)
  - Judul tutorial
  - Durasi video (icon clock)
  - Jumlah viewer (icon eye)
  - Rating bintang
  - Progress bar (jika sudah mulai belajar)
  - Button "Mulai Belajar" / "Lanjutkan"

#### Kategori Tutorial
- **Style**: Horizontal scroll atau grid
- **Items**: 
  - Icon rubik cube (3x3, 4x4, 5x5, Megaminx, Pyraminx, dll)
  - Nama kategori
  - Jumlah tutorial
- **Design**: Card dengan hover effect

#### Sidebar (jika ada)
- **Progress Learning**: 
  - Progress bar overall
  - Tutorial selesai / total
  - Badge achievement
- **Quick Links**: 
  - Tutorial favorit
  - Terakhir dipelajari
  - Rekomendasi

---

## 7. Halaman Tentang Kami

### Layout Struktur:
```
┌─────────────────────────────────────────┐
│         NAVIGATION BAR                   │
├─────────────────────────────────────────┤
│  Breadcrumb: Beranda > Tentang Kami      │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   HERO SECTION                     │  │
│  │   "Tentang Daeng Rubik"            │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   SEJARAH & VISI MISI              │  │
│  │  ┌──────────┐ ┌─────────────────┐ │  │
│  │  │  Gambar  │ │  Teks Sejarah   │ │  │
│  │  │  Founder │ │  Visi & Misi    │ │  │
│  │  └──────────┘ └─────────────────┘ │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   LAYANAN KAMI                     │  │
│  │  ┌──────┐ ┌──────┐ ┌──────┐      │  │
│  │  │Icon  │ │Icon  │ │Icon  │      │  │
│  │  │Jual  │ │Event │ │Tutor │      │  │
│  │  └──────┘ └──────┘ └──────┘      │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   TIM / TEAM                       │  │
│  │  ┌──────┐ ┌──────┐ ┌──────┐      │  │
│  │  │Foto  │ │Foto  │ │Foto  │      │  │
│  │  │Nama  │ │Nama  │ │Nama  │      │  │
│  │  │Role  │ │Role  │ │Role  │      │  │
│  │  └──────┘ └──────┘ └──────┘      │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   PENCAPAIAN / STATISTIK           │  │
│  │  ┌──────┐ ┌──────┐ ┌──────┐      │  │
│  │  │ 100+ │ │ 50+  │ │ 500+ │      │  │
│  │  │Produk│ │Event │ │Siswa │      │  │
│  │  └──────┘ └──────┘ └──────┘      │  │
│  └───────────────────────────────────┘  │
│                                           │
│         FOOTER                            │
└─────────────────────────────────────────┘
```

### Komponen Detail:

#### Hero Section
- **Background**: Gradient atau pattern rubik
- **Content**: 
  - Judul besar: "Tentang Daeng Rubik"
  - Subtitle: Tagline atau quote inspiratif

#### Sejarah & Visi Misi
- **Layout**: Split layout (gambar kiri, teks kanan) atau stacked
- **Content**:
  - Foto founder/team
  - Cerita perjalanan UMKM
  - Visi: Tujuan jangka panjang
  - Misi: Langkah-langkah yang dilakukan

#### Layanan Kami
- **Layout**: 3 kolom dengan icon besar
- **Cards**:
  - Icon ilustrasi
  - Judul layanan
  - Deskripsi singkat
  - Link ke halaman terkait

#### Tim
- **Layout**: Grid foto tim
- **Card Design**:
  - Foto profil (circular)
  - Nama
  - Role/Position
  - Social media links (optional)

#### Pencapaian
- **Style**: Statistik dengan icon dan angka besar
- **Design**: Card dengan gradient background berbeda per statistik

---

## 8. Halaman Kontak

### Layout Struktur:
```
┌─────────────────────────────────────────┐
│         NAVIGATION BAR                   │
├─────────────────────────────────────────┤
│  Breadcrumb: Beranda > Kontak            │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   HERO SECTION                     │  │
│  │   "Hubungi Kami"                   │  │
│  └───────────────────────────────────┘  │
│                                           │
│  ┌──────────────┐ ┌──────────────────┐ │
│  │   FORM       │ │   INFO KONTAK    │ │
│  │   KONTAK     │ │                  │ │
│  │              │ │  📍 Alamat        │ │
│  │  Nama: [___] │ │  📞 Telepon       │ │
│  │  Email:[___] │ │  ✉️ Email         │ │
│  │  Subjek:[__] │ │  💬 WhatsApp      │ │
│  │  Pesan:[___] │ │                  │ │
│  │              │ │  MEDIA SOSIAL:   │ │
│  │  [Kirim]     │ │  [Instagram]     │ │
│  │              │ │  [Facebook]      │ │
│  │              │ │  [YouTube]       │ │
│  │              │ │  [TikTok]        │ │
│  └──────────────┘ └──────────────────┘ │
│                                           │
│  ┌───────────────────────────────────┐  │
│  │   MAP / LOKASI (jika ada)          │  │
│  └───────────────────────────────────┘  │
│                                           │
│         FOOTER                            │
└─────────────────────────────────────────┘
```

### Komponen Detail:

#### Hero Section
- **Content**: 
  - Judul: "Hubungi Daeng Rubik"
  - Subtitle: "Kami siap membantu Anda"

#### Form Kontak
- **Fields**:
  - Nama (required)
  - Email (required, validation)
  - Subjek (required)
  - Pesan (required, textarea)
- **Design**: 
  - Input dengan border rounded
  - Focus state dengan warna accent
  - Error state dengan pesan validasi
  - Submit button dengan loading state

#### Info Kontak
- **Layout**: Card dengan icon
- **Items**:
  - Alamat lengkap (icon location)
  - Nomor telepon (clickable untuk call)
  - Email (clickable untuk email)
  - WhatsApp (button dengan link direct chat)
- **Design**: Setiap item dengan icon berwarna, hover effect

#### Media Sosial
- **Layout**: Grid icon social media
- **Items**: 
  - Instagram (gradient icon)
  - Facebook (blue icon)
  - YouTube (red icon)
  - TikTok (black icon)
- **Design**: Rounded icon buttons dengan hover scale effect

#### Map
- **Integration**: Google Maps embed atau OpenStreetMap
- **Marker**: Lokasi toko/office Daeng Rubik

---

## 9. Komponen Global

### Navigation Bar
- **Style**: Sticky top dengan shadow saat scroll
- **Background**: White dengan blur effect (glassmorphism)
- **Logo**: Kiri atas, clickable ke home
- **Menu**: Center atau right-aligned
- **Icons**: Search, Cart (dengan badge count), User/Menu (mobile)
- **Mobile**: Hamburger menu dengan slide-out drawer

### Footer
- **Layout**: 4 kolom (desktop), stacked (mobile)
- **Sections**:
  - Tentang: Deskripsi singkat, logo
  - Quick Links: Navigasi utama
  - Layanan: Produk, Event, Belajar
  - Kontak: Alamat, telepon, email, social media
- **Bottom Bar**: Copyright, Terms, Privacy Policy

### Loading States
- **Skeleton Loader**: Untuk product cards, event cards
- **Spinner**: Untuk button loading, page loading
- **Design**: Menggunakan warna accent rubik

### Empty States
- **Illustration**: Rubik cube illustration
- **Message**: Pesan informatif
- **CTA**: Button untuk action (jika applicable)

### Modal/Dialog
- **Product Quick View**: Modal dengan gambar, info, add to cart
- **Event Detail**: Modal dengan detail lengkap event
- **Design**: Overlay dengan blur, rounded corners, close button

---

## 10. Responsive Breakpoints

### Desktop
- **Width**: 1200px+
- **Layout**: Full width dengan max-width container
- **Grid**: 3-4 kolom untuk produk/event

### Tablet
- **Width**: 768px - 1199px
- **Layout**: 2 kolom untuk produk/event
- **Navigation**: Hamburger menu

### Mobile
- **Width**: < 768px
- **Layout**: 1 kolom, stacked
- **Navigation**: Bottom navigation atau hamburger
- **Cards**: Full width dengan padding

---

## 11. Interaksi & Animasi

### Hover Effects
- **Cards**: Scale (1.02), shadow increase
- **Buttons**: Background color change, scale
- **Images**: Zoom effect (1.1x)

### Transitions
- **Duration**: 0.3s ease-in-out
- **Properties**: Transform, opacity, background-color

### Scroll Animations
- **Fade In**: Elemen muncul saat scroll
- **Slide Up**: Konten slide dari bawah
- **Stagger**: Animasi berurutan untuk grid items

### Micro-interactions
- **Button Click**: Ripple effect
- **Form Input**: Label animation, focus ring
- **Cart Icon**: Bounce animation saat add item

---

## 12. Accessibility Considerations

- **Color Contrast**: Minimal 4.5:1 untuk text
- **Focus States**: Visible outline untuk keyboard navigation
- **Alt Text**: Semua gambar memiliki alt text
- **ARIA Labels**: Untuk icon buttons
- **Keyboard Navigation**: Semua interaksi accessible via keyboard

---

## Catatan Implementasi

1. **Framework Suggestion**: React/Next.js atau Vue/Nuxt untuk SPA
2. **UI Library**: Tailwind CSS atau Material-UI untuk styling
3. **Icons**: Heroicons, Font Awesome, atau custom SVG
4. **Animations**: Framer Motion atau CSS animations
5. **Images**: Optimized dengan lazy loading
6. **Performance**: Code splitting, image optimization, caching

---

**Dokumen ini dapat digunakan sebagai referensi untuk implementasi frontend website Daeng Rubik.**
