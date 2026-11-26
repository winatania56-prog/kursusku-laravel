@extends('layouts.app')

@section('title', $course->name . ' — Detail Kursus')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 200px);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                    <div>
                        <span class="hero-tag d-inline-block mb-1">Informasi Program</span>
                        <h1 class="h2 fw-bold text-dark mb-1">{{ $course->name }}</h1>
                        <p class="text-muted mb-0">Rincian lengkap data kursus dan parameter kelas.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary px-3 py-2">
                            &larr; Kembali
                        </a>
                        @auth
                            @if (auth()->user()->isAdmin())
                                <a href="{{ route('courses.edit', $course) }}" class="btn btn-true-pink px-3 py-2 d-inline-flex align-items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325"/>
                                    </svg>
                                    <span>Edit Kursus</span>
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small">Kode Kursus:</span>
                            <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary-dark); font-family: monospace; font-size: 0.95rem;">
                                {{ $course->code }}
                            </span>
                        </div>
                        <div>
                            @if ($course->status === 'open')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                                    open
                                </span>
                            @elseif ($course->status === 'draft')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill">
                                    draft
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">
                                    closed
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="mb-4">
                            <h5 class="fw-bold text-dark mb-2">Deskripsi Kursus</h5>
                            <p class="text-muted mb-0" style="line-height: 1.7;">
                                {{ $course->description ?: 'Belum ada deskripsi untuk kursus ini.' }}
                            </p>
                        </div>

                        <div class="row g-3 pt-3 border-top">
                            <div class="col-sm-6 col-md-4">
                                <div class="p-3 rounded-3" style="background-color: var(--color-surface-subtle); border: 1px solid var(--color-border-subtle);">
                                    <div class="text-muted small mb-1">Biaya Investasi</div>
                                    <div class="fs-5 fw-bold text-dark">
                                        Rp {{ number_format($course->fee, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="p-3 rounded-3" style="background-color: var(--color-surface-subtle); border: 1px solid var(--color-border-subtle);">
                                    <div class="text-muted small mb-1">Kuota Peserta</div>
                                    <div class="fs-5 fw-bold text-dark">
                                        {{ $course->quota }} Kursi
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-4">
                                <div class="p-3 rounded-3" style="background-color: var(--color-surface-subtle); border: 1px solid var(--color-border-subtle);">
                                    <div class="text-muted small mb-1">Tanggal Mulai</div>
                                    <div class="fs-5 fw-bold text-dark">
                                        {{ $course->start_date ? $course->start_date->format('d/m/Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top text-muted small">
                            <span>Dibuat: {{ $course->created_at ? $course->created_at->format('d/m/Y H:i') : '-' }}</span>
                            <span>Diperbarui: {{ $course->updated_at ? $course->updated_at->format('d/m/Y H:i') : '-' }}</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
