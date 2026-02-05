@extends('admin.layouts.app')

@section('title', 'Input Hasil Kompetisi')
@section('page-title', 'Input Hasil Kompetisi')
@php
    use Carbon\Carbon;

    /* =========================
     | DUMMY EVENT KOMPETISI
     ========================= */
    $events = collect([
        (object) [
            'id' => 1,
            'title' => 'Daeng Rubik Open 2025',
            'start_datetime' => Carbon::parse('2025-06-15 09:00'),
        ],
        (object) [
            'id' => 2,
            'title' => 'Sulsel Speedcubing Championship',
            'start_datetime' => Carbon::parse('2025-07-03 08:00'),
        ],
        (object) [
            'id' => 3,
            'title' => 'Makassar Cube Fest',
            'start_datetime' => Carbon::parse('2025-08-10 10:00'),
        ],
    ]);

    /* =========================
     | DUMMY KATEGORI RUBIK
     ========================= */
    $competitionCategories = collect([
        (object) ['id' => 1, 'name' => '3x3x3 Cube'],
        (object) ['id' => 2, 'name' => '2x2x2 Cube'],
        (object) ['id' => 3, 'name' => '4x4x4 Cube'],
        (object) ['id' => 4, 'name' => 'Pyraminx'],
        (object) ['id' => 5, 'name' => 'Skewb'],
    ]);
@endphp
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/events-results-create.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h2 class="page-title">Input Hasil Event Kompetisi Rubik</h2>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            Kembali ke Daftar Event
        </a>
    </div>

    <div class="card" style="padding:20px;">
        <form method="POST" action="">
            @csrf

            {{-- INFORMASI EVENT --}}
            <h3 class="form-section-title" style="margin-bottom:14px;">Informasi Event</h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Pilih Event <span class="required">*</span></label>
                    <select name="event_id" class="form-select" required>
                        <option value="">Pilih Event Kompetisi</option>
                        @foreach ($events as $ev)
                            <option value="{{ $ev->id }}" {{ old('event_id') == $ev->id ? 'selected' : '' }}>
                                {{ $ev->title }} — {{ $ev->start_datetime->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Kategori Rubik <span class="required">*</span></label>
                    <select name="competition_category_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($competitionCategories as $cat)
                            <option value="{{ $cat->id }}" {{ old('competition_category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    <small class="form-helper">
                        Contoh: 3x3, 2x2, Pyraminx, dll.
                    </small>
                </div>
            </div>

            {{-- PESERTA & PERINGKAT --}}
            <h3 class="form-section-title" style="margin:18px 0 12px;">Data Peserta</h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Peserta <span class="required">*</span></label>
                    <input type="text" name="participant_name" class="form-input"
                           placeholder="Contoh: Budi Santoso" value="{{ old('participant_name') }}" required>
                </div>

                <div class="form-group" style="max-width:200px;">
                    <label class="form-label">Peringkat <span class="required">*</span></label>
                    <input type="number" name="rank" class="form-input" min="1"
                           placeholder="Contoh: 1" value="{{ old('rank') }}" required>
                </div>
            </div>

            {{-- ATTEMPT SECTION --}}
            <h3 class="form-section-title" style="margin:18px 0 12px;">Attempt Peserta</h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Attempt 1</label>
                    <input type="text" name="attempt1" class="form-input"
                           placeholder="contoh: 12.34" value="{{ old('attempt1') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Attempt 2</label>
                    <input type="text" name="attempt2" class="form-input"
                           placeholder="contoh: 11.98" value="{{ old('attempt2') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Attempt 3</label>
                    <input type="text" name="attempt3" class="form-input"
                           placeholder="contoh: 13.02" value="{{ old('attempt3') }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Attempt 4</label>
                    <input type="text" name="attempt4" class="form-input"
                           placeholder="contoh: 12.10" value="{{ old('attempt4') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Attempt 5</label>
                    <input type="text" name="attempt5" class="form-input"
                           placeholder="contoh: 12.87" value="{{ old('attempt5') }}">
                </div>
            </div>

            <small class="form-helper" style="display:block;margin-top:-6px;margin-bottom:10px;">
                Gunakan format detik desimal, misalnya <b>12.34</b>. Bisa juga isi <b>DNF</b> bila percobaan gagal.
            </small>

            {{-- HASIL OTOMATIS / MANUAL --}}
            <h3 class="form-section-title" style="margin:18px 0 12px;">Hasil (Best & Average)</h3>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Best</label>
                    <input type="text" name="best" class="form-input"
                           placeholder="contoh: 11.98" value="{{ old('best') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Average</label>
                    <input type="text" name="average" class="form-input"
                           placeholder="contoh: 12.33" value="{{ old('average') }}">
                </div>
            </div>

            <small class="form-helper" style="display:block;margin-top:-6px;margin-bottom:14px;">
                <b>Best</b> &amp; <b>Average</b> dapat dihitung otomatis oleh sistem (berdasarkan attempt), atau diisi manual
                oleh admin sesuai hasil resmi yang mengikuti standar kompetisi rubik.
            </small>

            {{-- ACTION BUTTON --}}
            <div class="modal-footer" style="padding:0;margin-top:12px;display:flex;gap:10px;justify-content:flex-end;">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    Simpan Hasil
                </button>
            </div>
        </form>
    </div>
@endsection