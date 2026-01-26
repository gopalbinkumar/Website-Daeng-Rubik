# Desain UI Admin Panel - Daeng Rubik

## 1. Konsep Desain Umum

### Identitas Visual
- **Tema**: Modern, clean, profesional
- **Warna Utama**: 
  - Background: `#F8F9FA` (light gray)
  - Sidebar: `#1E293B` (dark slate)
  - Primary: `#1976D2` (blue)
  - Accent: `#E53935` (red rubik)
  - Success: `#43A047` (green)
  - Warning: `#FDD835` (yellow)
- **Tipografi**: 
  - Heading: 'Inter' atau 'Poppins' (Bold, 600-700)
  - Body: 'Inter' (Regular, 400)
  - Monospace: Untuk data tabel

### Prinsip Desain
- **Hierarchy**: Informasi penting lebih menonjol
- **Consistency**: Pola desain konsisten di semua halaman
- **Efficiency**: Minimal klik untuk aksi penting
- **Feedback**: Visual feedback untuk setiap interaksi
- **Spacing**: White space yang cukup untuk readability

---

## 2. Struktur Layout

### Layout Utama (Desktop)
```
┌─────────────────────────────────────────────────────────────┐
│  ┌──────────┐ ┌──────────────────────────────────────────┐  │
│  │          │ │  TOPBAR                                   │  │
│  │ SIDEBAR  │ │  [Logo]  [Nama Admin] [🔔] [👤] [Logout] │  │
│  │          │ ├──────────────────────────────────────────┤  │
│  │ [Logo]   │ │                                           │  │
│  │          │ │  MAIN CONTENT AREA                        │  │
│  │ Dashboard│ │                                           │  │
│  │ Produk   │ │  [Konten dinamis sesuai menu]            │  │
│  │ Event    │ │                                           │  │
│  │ Materi   │ │                                           │  │
│  │ Admin    │ │                                           │  │
│  │ Setting  │ │                                           │  │
│  │          │ │                                           │  │
│  └──────────┘ └──────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### Dimensi Layout
- **Sidebar**: Fixed width 260px (desktop)
- **Topbar**: Height 64px
- **Main Content**: Flexible width dengan padding 24px
- **Max Content Width**: 1400px (centered)

---

## 3. Komponen UI

### 3.1 Sidebar

**Deskripsi**: Sidebar tetap di kiri, berisi logo dan menu navigasi.

**Komponen**:
- **Logo Section** (Top):
  - Logo Daeng Rubik (56x56px)
  - Text "Daeng Rubik" (font-weight: 700)
  - Background: Transparent atau subtle gradient
  - Padding: 20px 16px

- **Menu Items**:
  - Setiap item: Height 48px
  - Icon (24x24px) di kiri
  - Label text di kanan icon
  - Active state: Background color + left border indicator
  - Hover state: Background color subtle
  - Spacing: 4px antar item

- **Menu Structure**:
  ```
  ┌─────────────────────┐
  │ 🏠 Dashboard        │ ← Active (blue bg + left border)
  │ 📦 Produk Rubik     │
  │ 🎉 Event Rubik      │
  │ 📚 Materi Pembelajaran│
  │ 👥 Admin            │
  │ ⚙️ Pengaturan Website│
  └─────────────────────┘
  ```

**Visual States**:
- **Default**: Text color `rgba(255,255,255,0.7)`, Icon opacity 0.7
- **Hover**: Background `rgba(255,255,255,0.1)`, Text color `rgba(255,255,255,0.9)`
- **Active**: Background `rgba(25,118,210,0.2)`, Text color `#fff`, Left border 3px solid `#1976D2`

### 3.2 Topbar

**Deskripsi**: Bar di atas konten utama, berisi info admin dan aksi cepat.

**Komponen**:
- **Left Section**: 
  - Breadcrumb navigation (opsional)
  - Page title

- **Right Section**:
  - Notification icon (bell) dengan badge
  - Avatar admin (circular, 40x40px)
  - Dropdown menu: Profile, Settings, Logout
  - Nama admin (font-weight: 600)

**Layout**:
```
┌────────────────────────────────────────────────────────┐
│  Dashboard                    [🔔(3)] [👤 Admin] [Logout] │
└────────────────────────────────────────────────────────┘
```

### 3.3 Card

**Deskripsi**: Container untuk menampilkan informasi dalam bentuk card.

**Variasi**:
- **Stat Card** (Dashboard):
  - Size: ~240px width
  - Icon besar di atas
  - Angka statistik (font-size: 32px, bold)
  - Label deskripsi
  - Background: White dengan shadow
  - Border radius: 12px

- **Info Card**:
  - Padding: 20px
  - Background: White
  - Border: 1px solid `rgba(0,0,0,0.1)`
  - Shadow: Subtle
  - Border radius: 12px

### 3.4 Tabel

**Deskripsi**: Tabel data dengan fitur sorting, pagination, dan aksi.

**Struktur**:
- **Header**: 
  - Background: `#F8F9FA`
  - Font-weight: 600
  - Border bottom: 2px solid
  - Sortable indicators (↑↓)

- **Rows**:
  - Alternating row colors (zebra striping)
  - Hover state: Background highlight
  - Padding: 12px 16px

- **Actions Column**:
  - Icon buttons: Edit (pencil), Delete (trash)
  - Spacing: 8px antar button
  - Color: Blue untuk edit, Red untuk delete

- **Pagination**:
  - Bottom of table
  - Previous/Next buttons
  - Page numbers
  - Items per page selector

### 3.5 Form

**Deskripsi**: Form input untuk create/edit data.

**Komponen**:
- **Input Fields**:
  - Label di atas input
  - Input height: 44px
  - Border radius: 8px
  - Border: 1px solid `rgba(0,0,0,0.15)`
  - Focus state: Border blue + shadow

- **Textarea**:
  - Min height: 120px
  - Resize: Vertical

- **Select/Dropdown**:
  - Same height as input
  - Custom arrow icon

- **File Upload**:
  - Drag & drop area
  - Preview thumbnail
  - Remove button

- **Buttons**:
  - Primary: Blue background, white text
  - Secondary: White background, blue border
  - Danger: Red background, white text
  - Size: Height 44px, padding horizontal 24px

### 3.6 Button

**Variasi**:
- **Primary Button**: 
  - Background: `#1976D2`
  - Color: White
  - Hover: Darker blue
  - Size: 44px height

- **Secondary Button**:
  - Background: White
  - Border: 1px solid `rgba(0,0,0,0.15)`
  - Color: Text color
  - Hover: Background `#F8F9FA`

- **Danger Button**:
  - Background: `#E53935`
  - Color: White
  - Hover: Darker red

- **Icon Button**:
  - Circular atau square
  - Size: 40x40px
  - Icon only, no text

---

## 4. Deskripsi UI Per Halaman

### 4.1 Dashboard

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Dashboard                                               │
│                                                          │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐ ┌──────────┐ │
│  │ 📦       │ │ 🎉       │ │ 📚       │ │ 👥       │ │
│  │ 100      │ │ 50       │ │ 75       │ │ 5        │ │
│  │ Produk   │ │ Event    │ │ Materi   │ │ Admin    │ │
│  └──────────┘ └──────────┘ └──────────┘ └──────────┘ │
│                                                          │
│  ┌────────────────────────┐ ┌──────────────────────┐  │
│  │ Aktivitas Terkini       │ │ Produk Terbaru        │  │
│  │                         │ │                       │  │
│  │ • Produk baru ditambah  │ │ [Thumb] Rubik 3x3... │  │
│  │ • Event baru dibuat     │ │ [Thumb] Rubik 4x4... │  │
│  │ • Materi baru diupload  │ │ [Thumb] Megaminx...  │  │
│  └────────────────────────┘ └──────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

**Komponen**:
- **4 Stat Cards**: Total Produk, Total Event, Total Materi, Total Admin
- **Activity Feed**: List aktivitas terbaru (timeline style)
- **Recent Items**: Card grid produk/materi terbaru
- **Quick Actions**: Button cepat untuk aksi umum

**Interaksi**:
- Stat cards clickable → redirect ke halaman terkait
- Activity items clickable → detail aktivitas
- Recent items clickable → detail item

### 4.2 Produk Rubik

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Produk Rubik                    [+ Tambah Produk]      │
│                                                          │
│  [🔍 Search] [Filter ▼] [Sort ▼]                        │
│                                                          │
│  ┌───────────────────────────────────────────────────┐  │
│  │ [Img] │ Nama      │ Harga    │ Stok │ Aksi      │  │
│  ├───────┼───────────┼──────────┼──────┼───────────┤  │
│  │ [📷]  │ Rubik 3x3 │ Rp 50K   │ 25   │ [✏️] [🗑️] │  │
│  │ [📷]  │ Rubik 4x4 │ Rp 80K   │ 15   │ [✏️] [🗑️] │  │
│  │ [📷]  │ Rubik 5x5 │ Rp 120K  │ 8    │ [✏️] [🗑️] │  │
│  └───────────────────────────────────────────────────┘  │
│                                                          │
│  [<] 1 2 3 4 5 [>]  Menampilkan 1-10 dari 100 produk    │
└─────────────────────────────────────────────────────────┘
```

**Komponen**:
- **Header Section**:
  - Title "Produk Rubik"
  - Button "+ Tambah Produk" (primary, di kanan)

- **Toolbar**:
  - Search input (width: 300px)
  - Filter dropdown (Kategori, Brand, Status)
  - Sort dropdown (Terbaru, Harga, Nama)

- **Table**:
  - Columns: Thumbnail, Nama, Harga, Stok, Aksi
  - Thumbnail: 60x60px, rounded
  - Actions: Edit (blue), Delete (red) dengan konfirmasi modal

- **Pagination**: Bottom of table

**Modal: Tambah/Edit Produk**:
- Form fields: Nama, Deskripsi, Harga, Stok, Kategori, Brand
- Image upload: Drag & drop atau file picker
- Preview gambar
- Buttons: Simpan (primary), Batal (secondary)

### 4.3 Event Rubik

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Event Rubik                       [+ Tambah Event]      │
│                                                          │
│  [🔍 Search] [Status: All ▼] [Sort ▼]                   │
│                                                          │
│  ┌───────────────────────────────────────────────────┐  │
│  │ Judul        │ Tanggal    │ Lokasi    │ Status │Aksi│ │
│  ├──────────────┼────────────┼───────────┼────────┼────┤ │
│  │ Kompetisi... │ 15 Feb 26  │ Jakarta   │ [Up]   │✏️🗑️│ │
│  │ Workshop...  │ 20 Feb 26  │ Bandung   │ [Up]   │✏️🗑️│ │
│  │ Meetup...    │ 25 Feb 26  │ Surabaya  │ [Up]   │✏️🗑️│ │
│  └───────────────────────────────────────────────────┘  │
│                                                          │
│  [<] 1 2 3 [>]  Menampilkan 1-10 dari 50 event          │
└─────────────────────────────────────────────────────────┘
```

**Komponen**:
- **Header**: Title + "+ Tambah Event" button
- **Toolbar**: Search, Status filter, Sort
- **Table Columns**: 
  - Judul Event
  - Tanggal & Waktu
  - Lokasi
  - Status Badge (Upcoming, Ongoing, Finished)
  - Aksi (Edit, Delete)

**Status Badge Colors**:
- Upcoming: Yellow/Orange (`#FDD835`)
- Ongoing: Green (`#43A047`)
- Finished: Gray (`#9E9E9E`)

**Modal: Tambah/Edit Event**:
- Form: Judul, Deskripsi, Tanggal mulai, Tanggal akhir, Lokasi, Kategori, Harga tiket, Max peserta
- Image upload untuk cover event
- Preview

### 4.4 Materi Pembelajaran

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Materi Pembelajaran              [+ Tambah Materi]     │
│                                                          │
│  [Level: All ▼] [Kategori: All ▼] [🔍 Search]          │
│                                                          │
│  ┌───────────────────────────────────────────────────┐  │
│  │ [Thumb] │ Judul        │ Level      │ Durasi │Aksi│ │
│  ├─────────┼──────────────┼────────────┼────────┼────┤ │
│  │ [📹]    │ Pengenalan...│ Basic      │ 5:30   │✏️🗑️│ │
│  │ [📹]    │ F2L Inter... │ Intermediate│ 18:20  │✏️🗑️│ │
│  │ [📹]    │ Full OLL...  │ Advanced   │ 24:10  │✏️🗑️│ │
│  └───────────────────────────────────────────────────┘  │
│                                                          │
│  [<] 1 2 3 [>]  Menampilkan 1-10 dari 75 materi         │
└─────────────────────────────────────────────────────────┘
```

**Komponen**:
- **Header**: Title + "+ Tambah Materi" button
- **Filter Bar**: 
  - Level dropdown (All, Basic, Intermediate, Advanced)
  - Kategori dropdown (3x3, 4x4, 5x5, etc.)
  - Search input

- **Table**:
  - Thumbnail video (80x60px)
  - Judul materi
  - Level badge (color-coded)
  - Durasi video
  - Views count
  - Aksi (Edit, Delete)

**Level Badge Colors**:
- Basic: Green (`#43A047`)
- Intermediate: Yellow (`#FDD835`)
- Advanced: Red (`#E53935`)

**Modal: Tambah/Edit Materi**:
- Form: Judul, Deskripsi, Level, Kategori, Durasi
- Video upload atau URL YouTube
- Thumbnail upload
- Preview

### 4.5 Admin

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Admin                              [+ Tambah Admin]     │
│                                                          │
│  [🔍 Search]                                            │
│                                                          │
│  ┌───────────────────────────────────────────────────┐  │
│  │ Avatar │ Nama      │ Email          │ Role   │Aksi│ │
│  ├────────┼───────────┼───────────────┼────────┼────┤ │
│  │ [👤]   │ Admin 1   │ admin1@...    │ Super  │✏️🗑️│ │
│  │ [👤]   │ Admin 2   │ admin2@...    │ Admin  │✏️🗑️│ │
│  │ [👤]   │ Admin 3   │ admin3@...    │ Editor │✏️🗑️│ │
│  └───────────────────────────────────────────────────┘  │
│                                                          │
│  [<] 1 [>]  Menampilkan 1-10 dari 5 admin               │
└─────────────────────────────────────────────────────────┘
```

**Komponen**:
- **Header**: Title + "+ Tambah Admin" button
- **Table Columns**:
  - Avatar (circular, 40x40px)
  - Nama
  - Email
  - Role (Badge: Super Admin, Admin, Editor)
  - Aksi (Edit, Delete - disabled untuk super admin)

**Role Badge Colors**:
- Super Admin: Red (`#E53935`)
- Admin: Blue (`#1976D2`)
- Editor: Green (`#43A047`)

**Modal: Tambah/Edit Admin**:
- Form: Nama, Email, Password (untuk baru), Role dropdown, Avatar upload
- Preview avatar

### 4.6 Pengaturan Website

**Layout**:
```
┌─────────────────────────────────────────────────────────┐
│  Pengaturan Website                                     │
│                                                          │
│  ┌───────────────────────────────────────────────────┐  │
│  │ Profil Usaha                                       │  │
│  │                                                    │  │
│  │ Logo Website:                                     │  │
│  │ [Upload Area] [Preview]                           │  │
│  │                                                    │  │
│  │ Nama Usaha: [________________]                    │  │
│  │ Deskripsi:  [________________]                    │  │
│  │            [________________]                    │  │
│  │                                                    │  │
│  │ [Simpan Perubahan]                                │  │
│  └───────────────────────────────────────────────────┘  │
│                                                          │
│  ┌───────────────────────────────────────────────────┐  │
│  │ Kontak & Media Sosial                              │  │
│  │                                                    │  │
│  │ Alamat:     [________________]                    │  │
│  │ Telepon:    [________________]                    │  │
│  │ Email:      [________________]                    │  │
│  │ WhatsApp:   [________________]                    │  │
│  │                                                    │  │
│  │ Instagram:  [________________]                    │  │
│  │ Facebook:   [________________]                    │  │
│  │ YouTube:    [________________]                    │  │
│  │ TikTok:     [________________]                    │  │
│  │                                                    │  │
│  │ [Simpan Perubahan]                                │  │
│  └───────────────────────────────────────────────────┘  │
└─────────────────────────────────────────────────────────┘
```

**Komponen**:
- **Profil Usaha Section**:
  - Logo upload dengan drag & drop
  - Preview logo (200x200px max)
  - Nama usaha input
  - Deskripsi textarea

- **Kontak & Media Sosial Section**:
  - Input fields untuk kontak (Alamat, Telepon, Email, WhatsApp)
  - Input fields untuk social media (Instagram, Facebook, YouTube, TikTok)
  - Icon untuk setiap social media di kiri input

- **Buttons**: 
  - "Simpan Perubahan" (primary) di setiap section
  - Toast notification setelah save

---

## 5. Skema Navigasi

### 5.1 Flow Navigasi

```
                    ┌─────────────┐
                    │   LOGIN     │
                    └──────┬──────┘
                           │
                    ┌──────▼──────┐
                    │  DASHBOARD  │
                    └──────┬──────┘
                           │
        ┌──────────────────┼──────────────────┐
        │                  │                  │
    ┌───▼───┐        ┌─────▼─────┐    ┌──────▼──────┐
    │PRODUK │        │   EVENT   │    │   MATERI   │
    └───┬───┘        └─────┬─────┘    └──────┬─────┘
        │                  │                  │
    ┌───▼───┐        ┌─────▼─────┐    ┌──────▼──────┐
    │Tambah │        │  Tambah   │    │   Tambah    │
    │Produk │        │   Event   │    │   Materi    │
    └───┬───┘        └─────┬─────┘    └──────┬─────┘
        │                  │                  │
    ┌───▼───┐        ┌─────▼─────┐    ┌──────▼──────┐
    │ Edit  │        │   Edit    │    │    Edit     │
    │Produk │        │   Event   │    │   Materi    │
    └───────┘        └───────────┘    └─────────────┘
        │
    ┌───▼───┐
    │ Hapus │
    │Produk │
    └───────┘
        │
    ┌───▼──────────┐
    │  Konfirmasi  │
    │    Modal     │
    └──────────────┘
```

### 5.2 Menu Hierarchy

```
Sidebar Menu
│
├── 🏠 Dashboard
│   └── (Landing page setelah login)
│
├── 📦 Produk Rubik
│   ├── Daftar Produk
│   ├── Tambah Produk
│   └── Edit Produk (via table action)
│
├── 🎉 Event Rubik
│   ├── Daftar Event
│   ├── Tambah Event
│   └── Edit Event (via table action)
│
├── 📚 Materi Pembelajaran
│   ├── Daftar Materi
│   ├── Tambah Materi
│   └── Edit Materi (via table action)
│
├── 👥 Admin
│   ├── Daftar Admin
│   ├── Tambah Admin
│   └── Edit Admin (via table action)
│
└── ⚙️ Pengaturan Website
    ├── Profil Usaha
    └── Kontak & Media Sosial
```

### 5.3 Breadcrumb Navigation

**Format**: `Dashboard > [Current Page]`

**Contoh**:
- `Dashboard > Produk Rubik`
- `Dashboard > Produk Rubik > Tambah Produk`
- `Dashboard > Event Rubik > Edit Event`

---

## 6. Interaksi & Feedback

### 6.1 Loading States
- **Page Load**: Skeleton loader untuk tabel/card
- **Button Loading**: Spinner di dalam button saat proses
- **Form Submit**: Disable button + loading indicator

### 6.2 Success Feedback
- **Toast Notification**: 
  - Position: Top right
  - Auto dismiss: 3 seconds
  - Color: Green background
  - Icon: Checkmark

### 6.3 Error Feedback
- **Form Validation**: 
  - Red border pada input error
  - Error message di bawah input
- **Error Toast**: 
  - Position: Top right
  - Color: Red background
  - Icon: X or warning

### 6.4 Confirmation Dialogs
- **Delete Action**: 
  - Modal dialog
  - Title: "Hapus [Item]?"
  - Message: "Apakah Anda yakin ingin menghapus [item name]? Tindakan ini tidak dapat dibatalkan."
  - Buttons: "Batal" (secondary), "Hapus" (danger)

---

## 7. Responsive Design (Desktop Focus)

### Breakpoints
- **Desktop**: 1280px+ (Full layout dengan sidebar)
- **Tablet**: 768px - 1279px (Sidebar collapse menjadi icon-only)
- **Mobile**: < 768px (Sidebar menjadi drawer/hamburger menu)

### Desktop Optimizations
- Sidebar selalu visible
- Tabel dengan semua kolom
- Multi-column layouts untuk dashboard
- Hover states untuk semua interaktif elements

---

## 8. Accessibility Considerations

- **Keyboard Navigation**: 
  - Tab order logical
  - Enter untuk submit form
  - Escape untuk close modal

- **Focus States**: 
  - Visible outline untuk semua focusable elements
  - High contrast

- **ARIA Labels**: 
  - Semua icon buttons memiliki aria-label
  - Form inputs memiliki label yang jelas

- **Color Contrast**: 
  - Minimal 4.5:1 untuk text
  - Status badges memiliki text alternatif

---

## 9. Design Tokens

### Spacing Scale
- 4px, 8px, 12px, 16px, 20px, 24px, 32px, 40px, 48px

### Border Radius
- Small: 4px (badges, small buttons)
- Medium: 8px (inputs, cards)
- Large: 12px (cards, modals)

### Shadows
- Small: `0 2px 4px rgba(0,0,0,0.1)`
- Medium: `0 4px 8px rgba(0,0,0,0.12)`
- Large: `0 8px 16px rgba(0,0,0,0.15)`

### Transitions
- Duration: 0.2s - 0.3s
- Easing: `ease-in-out`

---

**Dokumen ini dapat digunakan sebagai referensi untuk implementasi frontend Admin Panel Daeng Rubik.**
