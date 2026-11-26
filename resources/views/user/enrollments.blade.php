@extends('layouts.app')

@section('title', 'Riwayat Pendaftaran Saya — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 180px);">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <span class="hero-tag d-inline-block mb-1">Riwayat Pembelajaran</span>
                <h1 class="h2 fw-bold text-dark mb-1">Riwayat Pendaftaran Kursus</h1>
                <p class="text-muted mb-0">Daftar seluruh kursus yang telah Anda daftarkan di KursusKu.</p>
            </div>
            <div>
                <a href="{{ route('courses.index') }}" class="btn btn-true-pink px-4 py-2 shadow-sm">
                    Daftar Kursus Lainnya
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden; background: #ffffff;">
            <div class="card-body p-0">
                @if ($enrollments->isEmpty())
                    <div class="text-center py-5 text-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor" class="bi bi-inbox mb-3 text-secondary opacity-50" viewBox="0 0 16 16">
                            <path d="M4.98 4a.5.5 0 0 0-.39.188L1.54 8H6a.5.5 0 0 1 .5.5 1.5 1.5 0 1 0 3 0A.5.5 0 0 1 10 8h4.46l-3.05-3.812A.5.5 0 0 0 11.02 4H4.98zm-1.17-.437A1.5 1.5 0 0 1 4.98 3h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a1 1 0 0 1 .11.812l-1.5 6A1 1 0 0 1 13.52 16H2.48a1 1 0 0 1-.98-.8l-1.5-6a1 1 0 0 1 .11-.813l3.7-4.624zM1.125 9l1.25 5h11.25l1.25-5H11.3a2.5 2.5 0 0 1-4.6 0H1.125z"/>
                        </svg>
                        <p class="mb-2 fw-semibold text-dark">Belum ada riwayat pendaftaran</p>
                        <p class="small text-muted mb-3">Anda belum terdaftar pada program pelatihan manapun.</p>
                        <a href="{{ route('courses.index') }}" class="btn btn-sm btn-true-pink">
                            Lihat Katalog Kursus
                        </a>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead style="background-color: var(--color-primary-subtle); color: var(--color-primary-deep);">
                                <tr>
                                    <th class="py-3 px-3 text-center" style="width: 50px;">No.</th>
                                    <th class="py-3 px-3">Kode Kursus</th>
                                    <th class="py-3 px-3">Nama Program Pelatihan</th>
                                    <th class="py-3 px-3 text-center">Tanggal Mulai</th>
                                    <th class="py-3 px-3 text-center">Status Pendaftaran</th>
                                    <th class="py-3 px-3 text-center" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($enrollments as $enrollment)
                                    <tr>
                                        <td class="text-center text-muted fw-semibold py-3 px-3">
                                            {{ $enrollments->firstItem() ? $enrollments->firstItem() + $loop->index : $loop->iteration }}
                                        </td>
                                        <td class="py-3 px-3">
                                            <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary-dark); font-family: monospace;">
                                                {{ $enrollment->course->code ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3">
                                            <div class="fw-semibold text-dark">{{ $enrollment->course->name ?? '-' }}</div>
                                            @if ($enrollment->course && $enrollment->course->description)
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">
                                                    {{ $enrollment->course->description }}
                                                </small>
                                            @endif
                                        </td>
                                        <td class="py-3 px-3 text-center text-muted">
                                            {{ $enrollment->course && $enrollment->course->start_date ? $enrollment->course->start_date->format('d/m/Y') : '-' }}
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            <span class="badge bg-info text-dark px-3 py-1 text-capitalize">
                                                {{ $enrollment->status }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-3 text-center">
                                            @if ($enrollment->course)
                                                <a href="{{ route('courses.show', $enrollment->course) }}" class="btn btn-sm btn-outline-primary px-3">
                                                    Detail
                                                </a>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        @if ($enrollments->hasPages())
            <div class="d-flex justify-content-end mt-4">
                {{ $enrollments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
