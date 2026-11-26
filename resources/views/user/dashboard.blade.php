@extends('layouts.app')

@section('title', 'Dashboard Peserta — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 180px);">
    <div class="container">
        {{-- Welcome Banner --}}
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; background: linear-gradient(135deg, #fff1f2 0%, #ffffff 100%);">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-1 rounded-pill mb-2">
                            Dashboard Siswa
                        </span>
                        <h1 class="h2 fw-bold text-dark mb-1">Halo, {{ $user->name }}!</h1>
                        <p class="text-muted mb-0">Selamat datang di portal pembelajaran KursusKu. Pantau kemajuan dan pendaftaran kursus Anda di sini.</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('courses.index') }}" class="btn btn-true-pink px-3 py-2 shadow-sm">
                            Jelajahi Kursus
                        </a>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-secondary px-3 py-2">
                            Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Account Information Card --}}
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-dark mb-3">Informasi Akun</h5>
                        <ul class="list-unstyled mb-4">
                            <li class="mb-2">
                                <small class="text-muted d-block">Nama Lengkap</small>
                                <span class="fw-semibold text-dark">{{ $user->name }}</span>
                            </li>
                            <li class="mb-2">
                                <small class="text-muted d-block">Alamat Email</small>
                                <span class="fw-semibold text-dark">{{ $user->email }}</span>
                            </li>
                            <li class="mb-2">
                                <small class="text-muted d-block">Role Akun</small>
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1 text-uppercase">
                                    {{ $user->role }}
                                </span>
                            </li>
                            <li class="mb-0">
                                <small class="text-muted d-block">Bergabung Sejak</small>
                                <span class="text-muted">{{ $user->created_at ? $user->created_at->format('d F Y') : '-' }}</span>
                            </li>
                        </ul>

                        <div class="p-3 rounded" style="background-color: var(--color-surface-subtle); border: 1px solid var(--color-border);">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="text-muted small">Total Pendaftaran</div>
                                    <div class="h4 fw-bold text-dark mb-0">{{ $totalEnrollments }}</div>
                                </div>
                                <a href="{{ route('user.enrollments') }}" class="btn btn-sm btn-outline-primary">
                                    Lihat Semua
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Enrollment Status Summary Card --}}
            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Pendaftaran Kursus Terbaru</h5>
                        <a href="{{ route('user.enrollments') }}" class="small text-decoration-none" style="color: var(--color-primary);">
                            Lihat Riwayat Lengkap &rarr;
                        </a>
                    </div>
                    <div class="card-body p-0">
                        @if ($enrollments->isEmpty())
                            <div class="text-center py-5 text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-journal-bookmark text-muted opacity-50 mb-3" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M6 8V1h1v6.117L8.743 6.07a.5.5 0 0 1 .514 0L11 7.117V1h1v7a.5.5 0 0 1-.757.429L9 7.083 6.757 8.43A.5.5 0 0 1 6 8"/>
                                    <path d="M3 0h10a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2v-1h1v1a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H3a1 1 0 0 0-1 1v1H2V2a2 2 0 0 1 2-2"/>
                                    <path d="M1 5v-.5a.5.5 0 0 1 1 0V5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0V8h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1zm0 3v-.5a.5.5 0 0 1 1 0v.5h.5a.5.5 0 0 1 0 1h-2a.5.5 0 0 1 0-1z"/>
                                </svg>
                                <p class="mb-2 fw-semibold text-dark">Belum ada kursus yang diikuti</p>
                                <p class="small text-muted mb-3">Pilih dan daftarkan diri Anda pada kursus pelatihan yang tersedia.</p>
                                <a href="{{ route('courses.index') }}" class="btn btn-sm btn-true-pink">
                                    Pilih Kursus Sekarang
                                </a>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead style="background-color: var(--color-primary-subtle); color: var(--color-primary-deep);">
                                        <tr>
                                            <th class="py-3 px-3">Kode</th>
                                            <th class="py-3 px-3">Nama Kursus</th>
                                            <th class="py-3 px-3">Tanggal Mulai</th>
                                            <th class="py-3 px-3 text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enrollments as $enrollment)
                                            <tr>
                                                <td class="py-3 px-3">
                                                    <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary-dark); font-family: monospace;">
                                                        {{ $enrollment->course->code ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-3 fw-semibold text-dark">
                                                    {{ $enrollment->course->name ?? '-' }}
                                                </td>
                                                <td class="py-3 px-3 text-muted">
                                                    {{ $enrollment->course && $enrollment->course->start_date ? $enrollment->course->start_date->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="py-3 px-3 text-center">
                                                    <span class="badge bg-info text-dark px-3 py-1 text-capitalize">
                                                        {{ $enrollment->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
