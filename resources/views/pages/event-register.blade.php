@extends('layouts.app')

@section('title', 'Daftar Event — Daeng Rubik')

@push('styles')
    <link rel="stylesheet" href="{{ assetVersion('assets/css/events.css') }}">
    <link rel="stylesheet" href="{{ assetVersion('assets/css/competition-detail.css') }}">
@endpush

@section('content')
    @php
        $resultsTabActive = request()->has('category') || request()->has('round');
        $registrationTabActive = !$resultsTabActive && ($errors->any() || old('event_id') == $event->id);
        $selectedCategoryIds = collect(
            old('categories', $registration?->competitionCategories?->pluck('id')->all() ?? []),
        )
            ->map(fn($id) => (int) $id)
            ->all();
    @endphp

    <section class="page-head">
        <div class="container">
            <div class="breadcrumb">Beranda > Event > Detail</div>

            <h1 class="page-title">{{ $event->title }}</h1>

            <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7">
                Informasi, pendaftaran, peserta, dan hasil kompetisi.
            </p>
        </div>
    </section>

    <section class="section event-register-section">
        <div class="container">
            <div class="event-register-tabs-card">
                <div class="event-register-tabs">
                    <input type="radio" class="event-register-tab-input" name="event-register-tab"
                        id="eventRegisterTabInfo" {{ !$resultsTabActive && !$registrationTabActive ? 'checked' : '' }}>
                    <label class="event-register-tab-link" for="eventRegisterTabInfo">
                        Info
                    </label>

                    <div class="event-register-tab-body">
                        <div class="event-info-grid">
                            <div class="event-info-media">
                                @if ($event->cover_image)
                                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                                        alt="Poster {{ $event->title }}">
                                @else
                                    <div class="event-info-media-empty">
                                        <i class="fa-solid fa-calendar-days"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="event-info-content">
                                <h2>{{ $event->title }}</h2>

                                <div class="event-info-meta">
                                    <div class="summary-info-item">
                                        <span class="info-icon">
                                            <i class="fa-regular fa-calendar-days"></i>
                                        </span>
                                        <span>{{ $event->start_datetime->format('d M Y • H:i') }}</span>
                                    </div>

                                    <div class="summary-info-item">
                                        <span class="info-icon">
                                            <i class="fa-solid fa-location-dot"></i>
                                        </span>
                                        <span>{{ $event->location }}</span>
                                    </div>

                                    <div class="summary-info-item category-summary-row">
                                        <span class="info-icon">
                                            <i class="fa-solid fa-tags"></i>
                                        </span>

                                        <div class="summary-category-icon-list">
                                            @foreach ($event->competitionCategories as $cat)
                                                <span class="summary-category-icon-choice" title="{{ $cat->name }}"
                                                    aria-label="{{ $cat->name }}">
                                                    <x-category-icon :code="$cat->code" :name="$cat->name" size="26" />
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="event-info-description">
                                    {!! nl2br(e($event->description)) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="radio" class="event-register-tab-input" name="event-register-tab"
                        id="eventRegisterTabRegistration" {{ $registrationTabActive ? 'checked' : '' }}>
                    <label class="event-register-tab-link" for="eventRegisterTabRegistration">
                        Daftar
                    </label>

                    <div class="event-register-tab-body">
                        <div class="event-register-action-panel event-register-action-panel--standalone">
                            @if (!auth()->check())
                                <h3>Silakan Login untuk Mendaftar</h3>

                                <p class="muted">
                                    Silakan login terlebih dahulu untuk mendaftar dan mengikuti kompetisi ini.
                                </p>

                                <div class="form-actions">
                                    <a href="{{ route('auth.login') }}" class="btn btn-primary">
                                        Login untuk Mendaftar
                                    </a>

                                    <a href="{{ route('events') }}" class="btn btn-secondary">
                                        Kembali ke halaman Event
                                    </a>
                                </div>
                            @elseif ($showRegistrationForm)
                                @if ($registration && $registration->status === 'pending')
                                    <p class="muted event-register-status-note">
                                        Status pendaftaran Anda masih <strong>pending</strong>, namun Anda dapat
                                        melakukan pendaftaran ulang jika ingin mengubah data.
                                    </p>
                                @endif

                                @if ($registration && $registration->status === 'rejected')
                                    <p class="muted event-register-status-note">
                                        Status pendaftaran Anda <strong>ditolak</strong>. Silakan daftar ulang.
                                    </p>
                                @endif

                                <form method="POST" action="{{ route('events.register.store') }}"
                                    class="event-register-form">
                                    @csrf

                                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                                    <div class="form-group">
                                        <label class="form-label">
                                            Nama Lengkap Peserta <span class="required">*</span>
                                        </label>

                                        <input type="text" class="form-input" name="participant_name"
                                            value="{{ old('participant_name', $user->name) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Tanggal Lahir <span class="required">*</span>
                                        </label>

                                        <input type="date" class="form-input" name="participant_birthdate"
                                            value="{{ old('participant_birthdate', $user->birthdate) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Email <span class="required">*</span>
                                        </label>

                                        <input type="email" class="form-input" name="participant_email"
                                            value="{{ old('participant_email', $user->email) }}" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Nomor WhatsApp <span class="required">*</span>
                                        </label>

                                        <input type="text" class="form-input" name="participant_whatsapp"
                                            value="{{ old('participant_whatsapp', $user->whatsapp) }}" required
                                            inputmode="numeric">

                                        <small class="form-helper">
                                            Contoh: 081234567890
                                        </small>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label">
                                            Kategori Lomba <span class="required">*</span>
                                        </label>

                                        <div class="checkbox-list">
                                            @foreach ($event->competitionCategories as $cat)
                                                <label class="checkbox-item">
                                                    <input type="checkbox" class="checkbox-input" name="categories[]"
                                                        value="{{ $cat->id }}"
                                                        {{ in_array($cat->id, $selectedCategoryIds, true) ? 'checked' : '' }}>

                                                    <span>{{ $cat->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>

                                        <small class="form-helper">
                                            Pilih satu atau lebih kategori lomba yang ingin diikuti.
                                        </small>
                                    </div>

                                    <div class="form-actions">
                                        <button type="submit" class="btn btn-primary">
                                            Daftar Event
                                        </button>

                                        <a href="{{ route('events') }}" class="btn btn-secondary">
                                            Kembali ke halaman Event
                                        </a>
                                    </div>
                                </form>
                            @else
                                @if ($registration && $registration->status === 'accepted')
                                    <h3>Pendaftaran Diterima</h3>

                                    <p class="muted">
                                        Pendaftaran Anda pada event ini sudah <strong>diterima</strong>. Hubungi
                                        panitia jika ingin mengubah data pendaftaran Anda.
                                    </p>

                                    <div class="registered-detail-list">
                                        <div class="summary-info-item">
                                            <span class="info-icon">
                                                <i class="fa-regular fa-user"></i>
                                            </span>
                                            <span><strong>Nama:</strong> {{ $registration->participant_name }}</span>
                                        </div>

                                        <div class="summary-info-item">
                                            <span class="info-icon">
                                                <i class="fa-regular fa-calendar"></i>
                                            </span>
                                            <span>
                                                <strong>Tanggal Lahir:</strong>
                                                {{ \Carbon\Carbon::parse($registration->participant_birthdate)->format('d M Y') }}
                                            </span>
                                        </div>

                                        <div class="summary-info-item">
                                            <span class="info-icon">
                                                <i class="fa-regular fa-envelope"></i>
                                            </span>
                                            <span><strong>Email:</strong> {{ $registration->participant_email }}</span>
                                        </div>

                                        <div class="summary-info-item">
                                            <span class="info-icon">
                                                <i class="fa-brands fa-whatsapp"></i>
                                            </span>
                                            <span><strong>WhatsApp:</strong>
                                                {{ $registration->participant_whatsapp }}</span>
                                        </div>

                                        <div class="summary-info-item">
                                            <span class="info-icon">
                                                <i class="fa-solid fa-tags"></i>
                                            </span>
                                            <span>
                                                <strong>Kategori:</strong>
                                                @if ($registration->competitionCategories && $registration->competitionCategories->count())
                                                    {{ $registration->competitionCategories->pluck('name')->implode(', ') }}
                                                @else
                                                    -
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-actions">
                                        <a href="{{ route('user.competitions') }}" class="btn btn-primary">
                                            Lihat Event Saya
                                        </a>
                                        <a href="{{ route('events') }}" class="btn btn-secondary">
                                            Kembali ke halaman Event
                                        </a>
                                    </div>
                                @else
                                    <h3>Anda sudah mendaftar</h3>

                                    <p class="muted">
                                        Anda sudah terdaftar pada event ini. Silakan cek status pendaftaran dan detail
                                        kategori lomba di halaman <strong>Event Saya</strong>.
                                    </p>

                                    <a href="{{ route('user.competitions') }}" class="btn btn-primary">
                                        Lihat Event Saya
                                    </a>
                                @endif
                            @endif
                        </div>
                    </div>

                    <input type="radio" class="event-register-tab-input" name="event-register-tab"
                        id="eventRegisterTabParticipants">
                    <label class="event-register-tab-link" for="eventRegisterTabParticipants">
                        Peserta
                    </label>

                    <div class="event-register-tab-body">
                        <div class="accepted-participants-head">
                            <h2>Peserta</h2>
                        </div>

                        @forelse ($acceptedParticipants as $participant)
                            <div class="accepted-participant-row">
                                <div>
                                    <strong>{{ ucwords(strtolower($participant->participant_name)) }}</strong>
                                    {{-- <span>{{ $participant->participant_email }}</span> --}}
                                </div>

                                <p>
                                    @if ($participant->competitionCategories && $participant->competitionCategories->count())
                                        {{ $participant->competitionCategories->pluck('name')->implode(', ') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        @empty
                            <p class="muted accepted-participants-empty">
                                Belum ada peserta yang diterima.
                            </p>
                        @endforelse
                    </div>

                    <input type="radio" class="event-register-tab-input" name="event-register-tab"
                        id="eventRegisterTabResults" {{ $resultsTabActive ? 'checked' : '' }}>
                    <label class="event-register-tab-link" for="eventRegisterTabResults">
                        Hasil
                    </label>

                    <div class="event-register-tab-body event-register-results-body">
                        <div class="competition-detail-page event-register-results">
                            @include('pages.partials.competition-results')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
