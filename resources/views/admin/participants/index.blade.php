@extends('layouts.app')

@section('title', 'Manajemen Peserta — KursusKu')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Peserta</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0">Manajemen Peserta</h1>
            <p class="text-muted small mb-0">Daftar seluruh peserta kursus yang terdaftar pada sistem KursusKu.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('enrollments.index') }}" class="btn btn-outline-primary btn-sm">
                <i class="bi bi-card-checklist me-1"></i> Laporan Enrollment
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">Daftar Peserta Terdaftar</h5>
                <span class="badge bg-secondary">{{ $participants->total() }} Total Peserta</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="ps-3" style="width: 50px;">No</th>
                            <th scope="col">Nama Lengkap</th>
                            <th scope="col">Alamat Email</th>
                            <th scope="col">Nomor Telepon</th>
                            <th scope="col" class="text-center">Kursus Diikuti</th>
                            <th scope="col">Terdaftar Pada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($participants as $index => $participant)
                            <tr>
                                <td class="ps-3 text-muted">{{ $participants->firstItem() + $index }}</td>
                                <td class="fw-semibold text-dark">{{ $participant->name }}</td>
                                <td>{{ $participant->email }}</td>
                                <td>{{ $participant->phone ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">
                                        {{ $participant->enrollments_count ?? $participant->enrollments->count() }} Kursus
                                    </span>
                                </td>
                                <td class="text-muted small">
                                    {{ $participant->created_at ? $participant->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data peserta yang tersimpan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($participants->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-center">
                    {{ $participants->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
