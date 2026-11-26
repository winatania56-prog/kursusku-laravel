@extends('layouts.app')

@section('title', 'Profil Saya — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 180px);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <span class="hero-tag d-inline-block mb-1">Pengaturan Akun</span>
                        <h1 class="h2 fw-bold text-dark mb-1">Profil Pengguna</h1>
                        <p class="text-muted mb-0">Kelola informasi pribadi dan keamanan kata sandi akun Anda.</p>
                    </div>
                    <div>
                        <span class="badge {{ $user->isAdmin() ? 'bg-danger' : 'bg-primary' }} text-uppercase px-3 py-2 fs-6">
                            Role: {{ $user->role }}
                        </span>
                    </div>
                </div>

                {{-- Alert Sukses Update Profil --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Alert Sukses Update Password --}}
                @if (session('password_success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        {{ session('password_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Card 1: Informasi Profil --}}
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">Informasi Pribadi</h5>
                        <small class="text-muted">Perbarui nama lengkap dan alamat email akun Anda.</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold text-dark">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $user->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold text-dark">Alamat Email <span class="text-danger">*</span></label>
                                <input type="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-true-pink px-4 py-2 shadow-sm">
                                    Simpan Perubahan Profil
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Card 2: Ubah Password --}}
                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
                    <div class="card-header bg-white border-bottom py-3 px-4">
                        <h5 class="fw-bold mb-0 text-dark">Ubah Password</h5>
                        <small class="text-muted">Pastikan akun Anda menggunakan kombinasi password yang kuat dan aman.</small>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('profile.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="current_password" class="form-label fw-semibold text-dark">Password Saat Ini <span class="text-danger">*</span></label>
                                <input type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       id="current_password"
                                       name="current_password"
                                       placeholder="Masukkan password yang aktif saat ini"
                                       required>
                                @error('current_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label fw-semibold text-dark">Password Baru <span class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Minimal 8 karakter"
                                           required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label fw-semibold text-dark">Konfirmasi Password Baru <span class="text-danger">*</span></label>
                                    <input type="password"
                                           class="form-control"
                                           id="password_confirmation"
                                           name="password_confirmation"
                                           placeholder="Ulangi password baru"
                                           required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-outline-danger px-4 py-2">
                                    Perbarui Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
