@extends('layouts.app')

@section('title', 'Laporan Enrollment — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 200px);">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <span class="hero-tag d-inline-block mb-1">Relasi Data</span>
                <h1 class="h2 fw-bold text-dark mb-1">Laporan Enrollment KursusKu</h1>
                <p class="text-muted mb-0">Daftar relasi pendaftaran antara peserta (Participant) dan kursus (Course).</p>
            </div>
            <div>
                <a href="{{ route('enrollments.create') }}" class="btn btn-true-pink px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    <span>Tambah Enrollment</span>
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden; background: #fff;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: var(--color-primary-subtle); color: var(--color-primary-deep);">
                            <tr>
                                <th scope="col" class="py-3 px-3 text-center" style="width: 60px;">No.</th>
                                <th scope="col" class="py-3 px-3">Nama Participant</th>
                                <th scope="col" class="py-3 px-3">Nama Course</th>
                                <th scope="col" class="py-3 px-3 text-center">Tanggal Mulai Course</th>
                                <th scope="col" class="py-3 px-3 text-center" style="width: 160px;">Status Enrollment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr>
                                    <td class="text-center text-muted fw-semibold py-3 px-3">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-3">
                                        <div class="fw-bold text-dark">{{ $enrollment->participant->name ?? '-' }}</div>
                                        <small class="text-muted">{{ $enrollment->participant->email ?? '-' }}</small>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="fw-semibold text-dark">{{ $enrollment->course->name ?? '-' }}</div>
                                        <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary-dark); font-family: monospace; font-size: 0.75rem;">
                                            {{ $enrollment->course->code ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if ($enrollment->course && $enrollment->course->start_date)
                                            <span class="text-secondary">{{ $enrollment->course->start_date->format('d/m/Y') }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @php
                                            $status = strtolower($enrollment->status ?? 'registered');
                                            $badgeClass = match($status) {
                                                'registered' => 'bg-info text-dark',
                                                'active' => 'bg-success',
                                                'completed' => 'bg-primary',
                                                'cancelled' => 'bg-secondary',
                                                default => 'bg-secondary',
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }} px-3 py-2 text-capitalize">
                                            {{ $enrollment->status }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-inbox mb-3 text-secondary opacity-50" viewBox="0 0 16 16">
                                                <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a1 1 0 0 1 .11.812l-1.5 6A1 1 0 0 1 13.52 16H2.48a1 1 0 0 1-.98-.8l-1.5-6a1 1 0 0 1 .11-.813l3.7-4.624zM1.125 9l1.25 5h11.25l1.25-5H11.3a2.5 2.5 0 0 1-4.6 0H1.125z"/>
                                            </svg>
                                            <p class="mb-2 fw-semibold">Belum ada enrollment.</p>
                                            <p class="small mb-3">Peserta belum didaftarkan pada kursus manapun.</p>
                                            <a href="{{ route('enrollments.create') }}" class="btn btn-sm btn-true-pink">
                                                Tambah Enrollment Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
