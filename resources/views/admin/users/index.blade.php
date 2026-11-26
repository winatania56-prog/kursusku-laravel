@extends('layouts.app')

@section('title', 'Manajemen Pengguna — KursusKu')

@section('content')
<div class="container py-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Dashboard Admin</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Manajemen Pengguna</li>
                </ol>
            </nav>
            <h1 class="h3 fw-bold mb-0">Manajemen Pengguna</h1>
            <p class="text-muted small mb-0">Daftar pengguna terdaftar serta pengelolaan hak akses sistem KursusKu.</p>
        </div>
        <div>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold mb-0">Daftar Akun Pengguna</h5>
                <span class="badge bg-secondary">{{ $users->total() }} Total Pengguna</span>
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
                            <th scope="col">Role Saat Ini</th>
                            <th scope="col">Terdaftar Pada</th>
                            <th scope="col" class="text-center" style="width: 220px;">Ubah Hak Akses</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $index => $u)
                            <tr>
                                <td class="ps-3 text-muted">{{ $users->firstItem() + $index }}</td>
                                <td class="fw-semibold text-dark">
                                    {{ $u->name }}
                                    @if ($u->id === auth()->id())
                                        <span class="badge bg-info-subtle text-info border border-info-subtle ms-1">Anda</span>
                                    @endif
                                </td>
                                <td>{{ $u->email }}</td>
                                <td>
                                    @if ($u->isAdmin())
                                        <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                            <i class="bi bi-shield-lock-fill me-1"></i>Administrator
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                            <i class="bi bi-person-fill me-1"></i>User
                                        </span>
                                    @endif
                                </td>
                                <td class="text-muted small">
                                    {{ $u->created_at ? $u->created_at->translatedFormat('d M Y, H:i') : '-' }}
                                </td>
                                <td class="text-center">
                                    @if ($u->id === auth()->id())
                                        <span class="text-muted small fst-italic">Akun Sedang Digunakan</span>
                                    @else
                                        <form action="{{ route('admin.users.update-role', $u) }}" method="POST" class="d-inline-flex align-items-center gap-1">
                                            @csrf
                                            @method('PATCH')
                                            <select name="role" class="form-select form-select-sm" style="width: 110px;">
                                                <option value="user" {{ $u->role === 'user' ? 'selected' : '' }}>User</option>
                                                <option value="admin" {{ $u->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>
                                            <button type="submit" class="btn btn-sm btn-outline-primary" title="Simpan Perubahan Role">
                                                Simpan
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    Belum ada data pengguna yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($users->hasPages())
            <div class="card-footer bg-white border-top py-3">
                <div class="d-flex justify-content-center">
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
