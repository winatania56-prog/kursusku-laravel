@extends('layouts.app')

@section('title', 'Dashboard Administrator — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 180px);">
    <div class="container">
        {{-- Header Section --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill mb-2">
                    Administrator Area
                </span>
                <h1 class="h2 fw-bold text-dark mb-1">Pusat Kendali Administrator</h1>
                <p class="text-muted mb-0">Kelola operasional program pelatihan, data peserta, pendaftaran, dan hak akses pengguna.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('courses.create') }}" class="btn btn-true-pink px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    <span>Tambah Kursus</span>
                </a>
            </div>
        </div>

        {{-- Statistics Row --}}
        <div class="row g-3 mb-4">
            {{-- Total Kursus --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">Total Kursus</span>
                            <span class="badge bg-primary-subtle text-primary p-2 rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/>
                                    <path d="M4 4h8v1H4zm0 2h8v1H4zm0 2h8v1H4zm0 2h5v1H4z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="h3 fw-bold text-dark mb-1">{{ $totalCourses }}</div>
                        <small class="text-muted">Katalog aktif di sistem</small>
                    </div>
                </div>
            </div>

            {{-- Total Peserta --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">Total Peserta</span>
                            <span class="badge bg-success-subtle text-success p-2 rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4 1 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/>
                                </svg>
                            </span>
                        </div>
                        <div class="h3 fw-bold text-dark mb-1">{{ $totalParticipants }}</div>
                        <small class="text-muted">Peserta terdaftar</small>
                    </div>
                </div>
            </div>

            {{-- Total Enrollment --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">Total Enrollment</span>
                            <span class="badge bg-info-subtle text-info p-2 rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0"/>
                                    <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1z"/>
                                    <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="h3 fw-bold text-dark mb-1">{{ $totalEnrollments }}</div>
                        <small class="text-muted">Relasi pendaftaran aktif</small>
                    </div>
                </div>
            </div>

            {{-- Total Users --}}
            <div class="col-12 col-sm-6 col-lg-3">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted small fw-semibold text-uppercase">Total Pengguna</span>
                            <span class="badge bg-warning-subtle text-warning p-2 rounded-circle">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                </svg>
                            </span>
                        </div>
                        <div class="h3 fw-bold text-dark mb-1">{{ $totalUsers }}</div>
                        <small class="text-muted">Akun terdaftar di sistem</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Status Kursus & Quick Actions --}}
        <div class="row g-4 mb-4">
            {{-- Status Breakdown --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">Ringkasan Status Kursus</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3 text-center">
                            <div class="col-4">
                                <div class="p-3 rounded border border-success-subtle bg-success-subtle">
                                    <div class="h3 fw-bold text-success mb-0">{{ $statusCounts['open'] }}</div>
                                    <small class="text-success fw-semibold text-uppercase">Open</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded border border-danger-subtle bg-danger-subtle">
                                    <div class="h3 fw-bold text-danger mb-0">{{ $statusCounts['closed'] }}</div>
                                    <small class="text-danger fw-semibold text-uppercase">Closed</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 rounded border border-secondary-subtle bg-secondary-subtle">
                                    <div class="h3 fw-bold text-secondary mb-0">{{ $statusCounts['draft'] }}</div>
                                    <small class="text-secondary fw-semibold text-uppercase">Draft</small>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="d-flex justify-content-between text-muted small mb-1">
                                <span>Distribusi Kursus Open</span>
                                <span>{{ $totalCourses > 0 ? round(($statusCounts['open'] / $totalCourses) * 100) : 0 }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $totalCourses > 0 ? ($statusCounts['open'] / $totalCourses) * 100 : 0 }}%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Navigasi Pengelolaan --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">Navigasi Pengelolaan Utama</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <a href="{{ route('courses.index') }}" class="btn btn-outline-secondary w-100 text-start p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-bold text-dark">Manajemen Kursus</div>
                                        <small class="text-muted">Katalog, edit, hapus kursus</small>
                                    </div>
                                    <span class="fs-5">&rarr;</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary w-100 text-start p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-bold text-dark">Manajemen Enrollment</div>
                                        <small class="text-muted">Laporan pendaftaran kursus</small>
                                    </div>
                                    <span class="fs-5">&rarr;</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('admin.participants.index') }}" class="btn btn-outline-secondary w-100 text-start p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-bold text-dark">Manajemen Peserta</div>
                                        <small class="text-muted">Daftar kontak peserta</small>
                                    </div>
                                    <span class="fs-5">&rarr;</span>
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary w-100 text-start p-3 d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="fw-bold text-dark">Manajemen Pengguna</div>
                                        <small class="text-muted">Kelola akun &amp; role</small>
                                    </div>
                                    <span class="fs-5">&rarr;</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tables Preview: Kursus Terbaru & Enrollment Terbaru --}}
        <div class="row g-4">
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden; background: #ffffff;">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">Kursus Terbaru</h6>
                        <a href="{{ route('courses.index') }}" class="small text-decoration-none" style="color: var(--color-primary);">Lihat Semua &rarr;</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    @foreach ($recentCourses as $course)
                                        <tr>
                                            <td class="py-3 px-3">
                                                <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary-dark); font-family: monospace;">
                                                    {{ $course->code }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-3">
                                                <div class="fw-semibold text-dark">{{ $course->name }}</div>
                                                <small class="text-muted">Rp {{ number_format($course->fee, 0, ',', '.') }}</small>
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="badge {{ $course->status === 'open' ? 'bg-success-subtle text-success' : ($course->status === 'draft' ? 'bg-secondary-subtle text-secondary' : 'bg-danger-subtle text-danger') }} rounded-pill px-2">
                                                    {{ $course->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden; background: #ffffff;">
                    <div class="card-header bg-white border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold mb-0 text-dark">Enrollment Terbaru</h6>
                        <a href="{{ route('enrollments.index') }}" class="small text-decoration-none" style="color: var(--color-primary);">Lihat Semua &rarr;</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <tbody>
                                    @foreach ($recentEnrollments as $enr)
                                        <tr>
                                            <td class="py-3 px-3">
                                                <div class="fw-semibold text-dark">{{ $enr->participant->name ?? '-' }}</div>
                                                <small class="text-muted">{{ $enr->course->name ?? '-' }}</small>
                                            </td>
                                            <td class="py-3 px-3 text-center">
                                                <span class="badge bg-info text-dark px-2 py-1 text-capitalize">
                                                    {{ $enr->status }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
