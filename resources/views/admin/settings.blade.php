@extends('admin.layouts.app')

@section('title', 'Pengaturan Website')
@section('page-title', 'Pengaturan Website')

@section('content')
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Profil Usaha</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Logo Website</label>
                    <div style="display: flex; gap: 20px; align-items: flex-start;">
                        <div class="upload-area" onclick="document.getElementById('logoUpload').click()" style="flex: 1;">
                            <p>Drag & Drop atau Klik untuk Upload</p>
                            <p style="font-size: 12px; color: var(--admin-text-muted);">Format: PNG, JPG (Max 2MB)</p>
                        </div>
                        <div class="upload-preview">
                            <img src="{{ asset('assets/logo-daeng-rubik.png') }}" alt="Logo Preview" style="max-width: 200px; border-radius: 8px; border: 1px solid var(--admin-border);">
                        </div>
                    </div>
                    <input type="file" id="logoUpload" accept="image/*" style="display: none;">
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Usaha <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Daeng Rubik">
                </div>

                <div class="form-group">
                    <label class="form-label">Deskripsi <span class="required">*</span></label>
                    <textarea class="form-textarea" rows="4">Platform terpadu untuk belanja rubik, mengikuti event rubik, dan belajar rubik dari basic sampai advanced.</textarea>
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 style="margin: 0; font-size: 18px; font-weight: 600;">Kontak & Media Sosial</h3>
        </div>
        <div class="card-body">
            <form>
                <div class="form-group">
                    <label class="form-label">Alamat <span class="required">*</span></label>
                    <input type="text" class="form-input" value="Jakarta, Indonesia">
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Telepon <span class="required">*</span></label>
                        <input type="tel" class="form-input" value="+62 812-3456-7890">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" class="form-input" value="info@daengrubik.com">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">WhatsApp <span class="required">*</span></label>
                    <input type="tel" class="form-input" value="+62 812-3456-7890">
                </div>

                <div style="margin: 24px 0; height: 1px; background: var(--admin-border);"></div>

                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                        <span>📷</span> Instagram
                    </label>
                    <input type="url" class="form-input" placeholder="https://instagram.com/daengrubik">
                </div>

                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                        <span>📘</span> Facebook
                    </label>
                    <input type="url" class="form-input" placeholder="https://facebook.com/daengrubik">
                </div>

                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                        <span>📺</span> YouTube
                    </label>
                    <input type="url" class="form-input" placeholder="https://youtube.com/@daengrubik">
                </div>

                <div class="form-group">
                    <label class="form-label" style="display: flex; align-items: center; gap: 8px;">
                        <span>🎵</span> TikTok
                    </label>
                    <input type="url" class="form-input" placeholder="https://tiktok.com/@daengrubik">
                </div>

                <div style="display: flex; justify-content: flex-end;">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
