@extends('layouts.app')

@section('title', 'Daftar Kursus — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 200px);">
    <div class="container">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <span class="hero-tag d-inline-block mb-1">Manajemen Program</span>
                <h1 class="h2 fw-bold text-dark mb-1">Daftar Kursus</h1>
                <p class="text-muted mb-0">Kelola dan pantau seluruh data program pelatihan aktif di KursusKu.</p>
            </div>
            <div>
                @auth
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('courses.create') }}" class="btn btn-true-pink px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                            </svg>
                            <span>Tambah Kursus</span>
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        {{-- Search & Filter Card (Fallback GET form + AJAX enhancement) --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; background: #ffffff;">
            <div class="card-body p-3 p-md-4">
                <form id="course-filter-form" action="{{ route('courses.index') }}" method="GET" class="row g-3 align-items-center">
                    {{-- Search Input --}}
                    <div class="col-12 col-md-6 col-lg-7">
                        <label for="course-search-input" class="visually-hidden">Pencarian Kursus</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted" id="search-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0 ps-0"
                                   id="course-search-input"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="Cari kode atau nama kursus..."
                                   aria-label="Cari kode atau nama kursus"
                                   aria-describedby="search-addon"
                                   autocomplete="off">
                        </div>
                    </div>

                    {{-- Status Filter --}}
                    <div class="col-12 col-sm-6 col-md-3 col-lg-3">
                        <label for="course-status-filter" class="visually-hidden">Filter Status</label>
                        <select class="form-select" id="course-status-filter" name="status" aria-label="Filter status kursus">
                            <option value="">Semua Status</option>
                            <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    {{-- Actions (Cari button & Reset button) --}}
                    <div class="col-12 col-sm-6 col-md-3 col-lg-2 d-flex gap-2">
                        <button type="submit" class="btn btn-outline-primary w-100 d-inline-flex justify-content-center align-items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                            </svg>
                            <span>Cari</span>
                        </button>
                        @if (request()->filled('search') || request()->filled('status'))
                            <a href="{{ route('courses.index') }}" id="course-reset-btn" class="btn btn-outline-secondary" title="Reset Pencarian & Filter">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        {{-- Dynamic Table Container --}}
        <div id="courses-table-container" style="position: relative; transition: opacity 0.2s ease;">
            @include('courses.partials.table', ['courses' => $courses])
        </div>
    </div>
</div>
@endsection
