# Desain UI Login & Register - Daeng Rubik

## 1. Konsep Desain Umum

### Identitas Visual
- **Tema**: Modern, clean, welcoming
- **Warna Utama**: 
  - Background: Gradient dengan warna rubik
  - Card: White dengan shadow
  - Primary Button: Gradient rubik (merah → biru → kuning)
  - Input Border: `rgba(17,24,39,0.15)`
- **Tipografi**: 
  - Heading: 'Inter' atau 'Poppins' (Bold, 700)
  - Body: 'Inter' (Regular, 400)

### Layout Pattern
- **Split Screen** (Desktop):
  - Kiri: Visual/Ilustrasi branding Daeng Rubik
  - Kanan: Form login/register
- **Stacked** (Mobile):
  - Logo di atas
  - Form di bawah

### Prinsip Desain
- **Simplicity**: Form yang tidak overwhelming
- **Trust**: Visual yang profesional untuk keamanan
- **Guidance**: Clear error messages dan validation
- **Branding**: Konsisten dengan identitas Daeng Rubik

---

## 2. Halaman Login

### Layout Desktop
```
┌─────────────────────────────────────────────────────────────────┐
│                                                                   │
│  ┌──────────────────┐ ┌──────────────────────────────────────┐  │
│  │                  │ │                                        │  │
│  │                  │ │  ┌──────────────────────────────────┐ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  [Logo Daeng Rubik]               │ │  │
│  │  ILUSTRASI       │ │  │  Daeng Rubik                      │ │  │
│  │  RUBIK CUBE      │ │  │                                   │ │  │
│  │  (Animated)      │ │  │  Selamat Datang Kembali           │ │  │
│  │                  │ │  │  Login untuk melanjutkan          │ │  │
│  │  "Solve, Learn,  │ │  │                                   │ │  │
│  │   Compete"       │ │  │  Email / Username                 │ │  │
│  │                  │ │  │  [_____________________]          │ │  │
│  │  Background:     │ │  │                                   │ │  │
│  │  Gradient Rubik  │ │  │  Password                         │ │  │
│  │  Colors          │ │  │  [_____________________] [👁]    │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  ☐ Ingat saya                     │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  [     Login     ]                │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  Lupa password?                   │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  Belum punya akun? Daftar         │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  └──────────────────────────────────┘ │  │
│  │                  │ │                                        │  │
│  └──────────────────┘ └──────────────────────────────────────┘  │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Layout Mobile
```
┌─────────────────────────┐
│                         │
│  [Logo Daeng Rubik]     │
│  Daeng Rubik            │
│                         │
│  Selamat Datang Kembali │
│  Login untuk melanjutkan│
│                         │
│  Email / Username       │
│  [___________________]  │
│                         │
│  Password               │
│  [___________________]  │
│                         │
│  ☐ Ingat saya           │
│                         │
│  [     Login     ]      │
│                         │
│  Lupa password?         │
│                         │
│  Belum punya akun?      │
│  Daftar                 │
│                         │
└─────────────────────────┘
```

### Komponen Detail

#### Visual/Ilustrasi Kiri (Desktop)
- **Background**: 
  - Gradient: `linear-gradient(135deg, #E53935, #1976D2, #FDD835)`
  - Pattern: Subtle geometric rubik pattern
  - Opacity overlay untuk readability

- **Content**:
  - Rubik cube 3D illustration (animated, rotating)
  - Slogan: "Solve, Learn, Compete"
  - Subtext: "Platform rubik terlengkap di Indonesia"
  - Decorative elements: Floating cubes, pattern

#### Form Card Kanan
- **Card**:
  - Width: 480px (desktop)
  - Padding: 40px
  - Background: White
  - Border radius: 16px
  - Shadow: `0 10px 40px rgba(0,0,0,0.1)`

- **Logo & Title**:
  - Logo Daeng Rubik (56x56px)
  - Title: "Daeng Rubik" (font-size: 24px, bold)
  - Subtitle: "Selamat Datang Kembali" (font-size: 28px, bold)
  - Description: "Login untuk melanjutkan" (font-size: 14px, muted)

- **Form Fields**:
  - **Email/Username Input**:
    - Label: "Email / Username"
    - Placeholder: "email@example.com"
    - Icon: Envelope icon (left)
    - Height: 48px
    - Border: 1px solid rgba(0,0,0,0.15)
    - Border radius: 8px
    - Focus state: Blue border + shadow

  - **Password Input**:
    - Label: "Password"
    - Placeholder: "••••••••"
    - Icon: Lock icon (left)
    - Toggle visibility icon (right): Eye icon
    - Height: 48px
    - Same styling as email input

  - **Remember Me**:
    - Checkbox + label "Ingat saya"
    - Aligned to left
    - Font-size: 14px

- **Login Button**:
  - Full width
  - Height: 48px
  - Background: Gradient rubik colors
  - Text: White, bold
  - Border radius: 8px
  - Hover: Shadow increase + slight scale
  - Active: Scale down

- **Links**:
  - **Lupa Password**: 
    - Text align: center
    - Color: Primary blue
    - Font-size: 14px
    - Underline on hover

  - **Register Link**: 
    - Text: "Belum punya akun? **Daftar**"
    - Center aligned
    - "Daftar" bold dan primary color
    - Font-size: 14px

---

## 3. Halaman Register

### Layout Desktop
```
┌─────────────────────────────────────────────────────────────────┐
│                                                                   │
│  ┌──────────────────┐ ┌──────────────────────────────────────┐  │
│  │                  │ │                                        │  │
│  │                  │ │  ┌──────────────────────────────────┐ │  │
│  │                  │ │  │                                   │ │  │
│  │  ILUSTRASI       │ │  │  [Logo Daeng Rubik]               │ │  │
│  │  RUBIK CUBE      │ │  │  Daeng Rubik                      │ │  │
│  │  (Animated)      │ │  │                                   │ │  │
│  │                  │ │  │  Daftar Akun Baru                 │ │  │
│  │  "Join the       │ │  │  Mulai perjalanan rubik Anda      │ │  │
│  │   Community"     │ │  │                                   │ │  │
│  │                  │ │  │  Nama Lengkap                     │ │  │
│  │  Background:     │ │  │  [_____________________]          │ │  │
│  │  Gradient Rubik  │ │  │                                   │ │  │
│  │  Colors          │ │  │  Email                            │ │  │
│  │                  │ │  │  [_____________________]          │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  Nomor WhatsApp                   │ │  │
│  │                  │ │  │  [_____________________]          │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  Password                         │ │  │
│  │                  │ │  │  [_____________________] [👁]    │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  Konfirmasi Password              │ │  │
│  │                  │ │  │  [_____________________] [👁]    │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  ☑ Saya setuju dengan Syarat &   │ │  │
│  │                  │ │  │      Ketentuan                    │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  [     Daftar     ]               │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  │  Sudah punya akun? Login          │ │  │
│  │                  │ │  │                                   │ │  │
│  │                  │ │  └──────────────────────────────────┘ │  │
│  │                  │ │                                        │  │
│  └──────────────────┘ └──────────────────────────────────────┘  │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Layout Mobile
```
┌─────────────────────────┐
│                         │
│  [Logo Daeng Rubik]     │
│  Daeng Rubik            │
│                         │
│  Daftar Akun Baru       │
│  Mulai perjalanan       │
│  rubik Anda             │
│                         │
│  Nama Lengkap           │
│  [___________________]  │
│                         │
│  Email                  │
│  [___________________]  │
│                         │
│  Nomor WhatsApp         │
│  [___________________]  │
│                         │
│  Password               │
│  [___________________]  │
│                         │
│  Konfirmasi Password    │
│  [___________________]  │
│                         │
│  ☑ Saya setuju dengan   │
│    Syarat & Ketentuan   │
│                         │
│  [     Daftar     ]     │
│                         │
│  Sudah punya akun?      │
│  Login                  │
│                         │
└─────────────────────────┘
```

### Komponen Detail

#### Visual/Ilustrasi Kiri (Desktop)
- **Background**: 
  - Gradient: `linear-gradient(135deg, #E53935, #1976D2, #FDD835)`
  - Pattern: Subtle geometric rubik pattern
  - Animated rubik cubes floating

- **Content**:
  - Rubik cube 3D illustration (animated)
  - Slogan: "Join the Community"
  - Subtext: "Belanja, belajar, dan berkompetisi bersama komunitas rubik"
  - Benefits list:
    - "✓ Akses katalog lengkap"
    - "✓ Daftar event rubik"
    - "✓ Materi belajar gratis"

#### Form Card Kanan
- **Card**:
  - Width: 480px (desktop)
  - Padding: 40px
  - Background: White
  - Border radius: 16px
  - Shadow: `0 10px 40px rgba(0,0,0,0.1)`

- **Logo & Title**:
  - Logo Daeng Rubik (56x56px)
  - Title: "Daeng Rubik" (font-size: 24px, bold)
  - Subtitle: "Daftar Akun Baru" (font-size: 28px, bold)
  - Description: "Mulai perjalanan rubik Anda" (font-size: 14px, muted)

- **Form Fields**:
  - **Nama Lengkap Input**:
    - Label: "Nama Lengkap"
    - Placeholder: "Masukkan nama lengkap Anda"
    - Icon: User icon (left)
    - Height: 48px
    - Border: 1px solid rgba(0,0,0,0.15)
    - Border radius: 8px

  - **Email Input**:
    - Label: "Email"
    - Placeholder: "email@example.com"
    - Icon: Envelope icon (left)
    - Validation: Email format
    - Error message: "Email tidak valid" (red, below input)

  - **Nomor WhatsApp Input**:
    - Label: "Nomor WhatsApp"
    - Placeholder: "+62 812-3456-7890"
    - Icon: WhatsApp icon (left)
    - Helper text: "Untuk konfirmasi pesanan & event"

  - **Password Input**:
    - Label: "Password"
    - Placeholder: "Minimal 8 karakter"
    - Icon: Lock icon (left)
    - Toggle visibility icon (right): Eye icon
    - Password strength indicator (progress bar):
      - Weak: Red
      - Medium: Yellow
      - Strong: Green

  - **Konfirmasi Password Input**:
    - Label: "Konfirmasi Password"
    - Placeholder: "Ulangi password"
    - Icon: Lock icon (left)
    - Toggle visibility icon (right)
    - Real-time validation: Must match password
    - Error message: "Password tidak cocok"

  - **Terms & Conditions Checkbox**:
    - Checkbox + label "Saya setuju dengan **Syarat & Ketentuan**"
    - "Syarat & Ketentuan" adalah link (blue, underline on hover)
    - Font-size: 14px
    - Required: Yes

- **Register Button**:
  - Full width
  - Height: 48px
  - Background: Gradient rubik colors
  - Text: "Daftar" (white, bold)
  - Border radius: 8px
  - Disabled state: Gray + not clickable (jika checkbox tidak dicentang)
  - Loading state: Spinner + "Mendaftar..."

- **Login Link**: 
  - Text: "Sudah punya akun? **Login**"
  - Center aligned
  - "Login" bold dan primary color
  - Font-size: 14px

---

## 4. Halaman Lupa Password

### Layout
```
┌─────────────────────────────────────────────────────────────────┐
│                                                                   │
│  ┌──────────────────┐ ┌──────────────────────────────────────┐  │
│  │                  │ │                                        │  │
│  │  ILUSTRASI       │ │  [Logo Daeng Rubik]                   │  │
│  │  RUBIK CUBE      │ │  Daeng Rubik                          │  │
│  │                  │ │                                        │  │
│  │  "Reset Your     │ │  Lupa Password?                       │  │
│  │   Password"      │ │  Masukkan email untuk reset password  │  │
│  │                  │ │                                        │  │
│  │                  │ │  Email                                │  │
│  │                  │ │  [_____________________]              │  │
│  │                  │ │                                        │  │
│  │                  │ │  [  Kirim Link Reset  ]               │  │
│  │                  │ │                                        │  │
│  │                  │ │  Kembali ke Login                     │  │
│  │                  │ │                                        │  │
│  └──────────────────┘ └──────────────────────────────────────┘  │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

### Komponen Detail
- **Title**: "Lupa Password?" (font-size: 28px, bold)
- **Description**: "Masukkan email Anda dan kami akan mengirimkan link reset password"
- **Email Input**: Same styling as login page
- **Submit Button**: "Kirim Link Reset" (primary button)
- **Back Link**: "Kembali ke Login" (center aligned)

---

## 5. Komponen UI Detail

### 5.1 Input Field

**Default State**:
- Height: 48px
- Padding: 12px 16px (jika ada icon: padding-left: 44px)
- Border: 1px solid `rgba(17,24,39,0.15)`
- Border radius: 8px
- Font-size: 14px
- Placeholder color: `rgba(17,24,39,0.4)`

**Focus State**:
- Border: 2px solid `#1976D2`
- Box-shadow: `0 0 0 4px rgba(25,118,210,0.1)`
- Outline: none

**Error State**:
- Border: 2px solid `#E53935`
- Box-shadow: `0 0 0 4px rgba(229,57,53,0.1)`
- Error message: Red text, 12px, below input

**Success State**:
- Border: 2px solid `#43A047`
- Checkmark icon (right)

**Icon (Left)**:
- Position: Absolute, left 12px
- Size: 20x20px
- Color: `rgba(17,24,39,0.5)`
- Changes to primary blue on focus

### 5.2 Button

**Primary Button** (Login/Register):
- Width: 100%
- Height: 48px
- Background: `linear-gradient(90deg, #E53935, #1976D2, #FDD835)`
- Color: White
- Font-weight: 700
- Font-size: 16px
- Border: none
- Border radius: 8px
- Cursor: pointer
- Transition: all 0.3s ease

**Hover State**:
- Transform: translateY(-2px)
- Box-shadow: `0 12px 30px rgba(25,118,210,0.3)`

**Active State**:
- Transform: translateY(0)

**Disabled State**:
- Background: `#E0E0E0`
- Color: `rgba(0,0,0,0.3)`
- Cursor: not-allowed

**Loading State**:
- Spinner icon (rotating)
- Text: "Memproses..."
- Disabled

### 5.3 Checkbox

- Size: 18x18px
- Border: 2px solid `rgba(17,24,39,0.3)`
- Border radius: 4px
- Checked state: Background blue, white checkmark
- Label: Clickable, font-size 14px

### 5.4 Link

- Color: `#1976D2` (primary blue)
- Font-weight: 600
- Text-decoration: none
- Hover: Underline
- Transition: all 0.2s

---

## 6. Validasi & Error Messages

### Login
- **Email kosong**: "Email harus diisi"
- **Password kosong**: "Password harus diisi"
- **Kredensial salah**: "Email atau password salah"
- **Akun belum diaktivasi**: "Akun belum diaktivasi. Cek email Anda."

### Register
- **Nama kosong**: "Nama lengkap harus diisi"
- **Email tidak valid**: "Format email tidak valid"
- **Email sudah terdaftar**: "Email sudah digunakan"
- **WhatsApp tidak valid**: "Nomor WhatsApp tidak valid"
- **Password terlalu pendek**: "Password minimal 8 karakter"
- **Password tidak cocok**: "Konfirmasi password tidak cocok"
- **Checkbox tidak dicentang**: "Anda harus menyetujui Syarat & Ketentuan"

### Success Messages
- **Register berhasil**: "Akun berhasil dibuat! Cek email untuk aktivasi."
- **Login berhasil**: "Login berhasil! Mengalihkan..."
- **Reset password**: "Link reset password telah dikirim ke email Anda."

---

## 7. Alur Navigasi

### Login Flow
```
[Landing Page]
      │
      ▼
  [Login Page]
      │
      ├──► [Lupa Password] ──► [Email Sent] ──► [Reset Password]
      │
      ├──► [Register Page] ──► [Email Verification] ──► [Login Page]
      │
      ▼
  [Dashboard / Home]
```

### Register Flow
```
[Landing Page / Login Page]
      │
      ▼
[Register Page]
      │
      ├──► Input Form
      │
      ├──► Validation (Client-side)
      │
      ▼
[Submit Form]
      │
      ├──► Success ──► [Email Verification Page]
      │                      │
      │                      ▼
      │                [Verify Email via Link]
      │                      │
      │                      ▼
      │                [Login Page]
      │
      └──► Error ──► [Show Error Message] ──► [Back to Form]
```

### Page Transitions
- **Login → Register**: Click "Daftar" link
- **Register → Login**: Click "Login" link
- **Login → Forgot Password**: Click "Lupa password?" link
- **Forgot Password → Login**: Click "Kembali ke Login" link

---

## 8. Responsive Behavior

### Desktop (1024px+)
- Split screen layout (50-50 atau 40-60)
- Form card centered di kanan
- Ilustrasi penuh di kiri

### Tablet (768px - 1023px)
- Split screen tetap, tapi ilustrasi lebih kecil (30-70)
- Form tetap comfortable

### Mobile (<768px)
- Stacked layout (ilustrasi di atas, form di bawah)
- Ilustrasi simplified (smaller, less decorative)
- Form full width dengan padding 20px
- Inputs tetap 48px height untuk touch-friendly

---

## 9. Animation & Micro-interactions

### Page Load
- Form card: Fade in + slide up from bottom
- Ilustrasi: Fade in

### Input Interactions
- Focus: Smooth border color transition + shadow
- Error: Shake animation (horizontal)
- Success: Checkmark appears with bounce

### Button Interactions
- Hover: Lift effect (translateY)
- Click: Press effect (scale down slightly)
- Loading: Spinner rotation

### Password Visibility Toggle
- Click: Icon changes (eye → eye-slash)
- Smooth transition

### Checkbox
- Click: Checkmark appears with scale animation

---

## 10. Security & UX Features

### Login
- **Remember Me**: Persistent session (30 days)
- **Rate Limiting**: Max 5 attempts, then cooldown
- **CSRF Protection**: Token in form

### Register
- **Email Verification**: Required before login
- **Password Strength Indicator**:
  - Visual bar below password input
  - Real-time feedback (weak/medium/strong)
  - Criteria: Length, uppercase, lowercase, numbers, symbols

- **WhatsApp Verification** (Optional):
  - OTP via WhatsApp after register

### General
- **Auto-focus**: First input on page load
- **Enter Key**: Submit form
- **Tab Navigation**: Logical order
- **Loading States**: Prevent double submission

---

## 11. Additional Features (Optional)

### Social Login (Future)
```
┌─────────────────────────────────────┐
│  [  Login dengan Google  ]          │
│  [  Login dengan Facebook  ]        │
│                                      │
│  ────────── atau ──────────         │
│                                      │
│  [Email & Password form...]          │
└─────────────────────────────────────┘
```

### Two-Factor Authentication (Future)
- After login: OTP via WhatsApp
- Backup codes for lost phone

---

## 12. Accessibility

### Keyboard Navigation
- Tab order: Logo → Inputs (top to bottom) → Checkbox → Button → Links
- Enter: Submit form
- Escape: Clear form (if has content)

### Screen Reader
- ARIA labels untuk semua inputs
- Error messages announced
- Success messages announced

### Color Contrast
- Text: Minimum 4.5:1 contrast ratio
- Links: Minimum 3:1 contrast ratio
- Focus indicators: High visibility

---

## 13. Catatan Implementasi

### Form Validation
- **Client-side**: Real-time validation (on blur)
- **Server-side**: Backend validation (security)
- **Display**: Error messages below each field

### Password Requirements
- Minimal 8 karakter
- Kombinasi huruf & angka (recommended)
- Special characters (optional but recommended)

### Email Verification
- Link dikirim setelah register
- Expire dalam 24 jam
- Resend link option

### Admin Access
- Admin **TIDAK** bisa register dari halaman publik
- Admin dibuat melalui panel admin
- Admin login menggunakan halaman login yang sama (email + password)
- Redirect logic: 
  - User → `/` (homepage)
  - Admin → `/admin` (dashboard)

---

**Dokumen ini dapat digunakan sebagai referensi untuk implementasi halaman Login & Register.**
