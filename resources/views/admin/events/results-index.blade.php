@extends('admin.layouts.app')

@section('title', 'Hasil Kompetisi')
@section('page-title', 'Hasil Kompetisi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/admin/events-results-index.css') }}">
@endpush

@section('content')
    <div class="page-header">
        <h2 class="page-title">Hasil Event Kompetisi Rubik</h2>
        {{-- <a href="{{ route('admin.events.competition.create') }}" class="btn btn-primary">
            + Input Hasil Baru
        </a> --}}
    </div>

    <div class="table-wrapper">
        <form method="GET" action="{{ route('admin.events.competition.index') }}">
            <div class="table-toolbar">
                <input type="text" name="search" class="search-input" placeholder="Search event..." value="">

                <select name="competition_category_id" class="filter-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                </select>
            </div>
        </form>

        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Kompetisi</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($results as $event)
                    <tr>
                        <td>
                            <strong>{{ $event->title }}</strong>
                        </td>

                        <td>
                            {{ $event->start_datetime->format('d M Y') }}
                        </td>

                        <td>
                            @if ($event->status === 'finished')
                                <span class="badge badge-success">Selesai</span>
                            @elseif ($event->status === 'ongoing')
                                <span class="badge badge-warning">Berlangsung</span>
                            @else
                                <span class="badge badge-gray">Upcoming</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <div class="table-actions">
                                <a href="{{ route('admin.events.competition.create', ['event_id' => $event->id]) }}"
                                    class="btn btn-icon btn-primary" title="Input / Edit Hasil">
                                    <i class="fa-solid fa-edit"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align:center; color:var(--admin-text-muted);">
                            Belum ada event kompetisi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- PAGINATION (STYLE SAMA DENGAN YANG KAMU PAKAI) --}}
        <div class="pagination">
            <div class="pagination-info">
                Menampilkan
                {{ $results->firstItem() ?? 0 }}–{{ $results->lastItem() ?? 0 }}
                dari {{ $results->total() }} event
            </div>

            <div class="pagination-controls">
                {{-- PREV --}}
                @if ($results->onFirstPage())
                    <button class="page-btn" disabled>‹</button>
                @else
                    <a href="{{ $results->previousPageUrl() }}">
                        <button class="page-btn">‹</button>
                    </a>
                @endif

                {{-- PAGE --}}
                @for ($i = 1; $i <= $results->lastPage(); $i++)
                    <a href="{{ $results->url($i) }}">
                        <button class="page-btn {{ $results->currentPage() == $i ? 'active' : '' }}">
                            {{ $i }}
                        </button>
                    </a>
                @endfor

                {{-- NEXT --}}
                @if ($results->hasMorePages())
                    <a href="{{ $results->nextPageUrl() }}">
                        <button class="page-btn">›</button>
                    </a>
                @else
                    <button class="page-btn" disabled>›</button>
                @endif
            </div>
        </div>
    </div>
@endsection
