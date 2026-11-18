@extends('layouts.app')

@section('title', 'Pendaftaran Kursus — KursusKu')

@section('content')
<div class="form-page-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">

                <div class="text-center mb-4">
                    <span class="hero-tag d-inline-block mb-2">Formulir Pendaftaran</span>
                    <h1 class="h2 fw-bold text-dark">Pendaftaran Kursus</h1>
                    <p class="text-muted">Isi formulir di bawah ini untuk memilih kursus dan memeriksa ketersediaan kursi secara langsung.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4" role="alert">
                        <div class="fw-bold mb-1">Periksa kembali isian formulir:</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-container-card">
                    <form action="{{ route('pendaftaran.preview') }}" method="POST">
                        @csrf

                        {{-- Input Nama --}}
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   name="name"
                                   value="{{ old('name') }}"
                                   placeholder="Masukkan nama lengkap Anda"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Input Email --}}
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="nama@email.com"
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Select Pilih Kursus --}}
                        <div class="mb-3">
                            <label for="course_id" class="form-label">Pilih Kursus</label>
                            <select class="form-select @error('course_id') is-invalid @enderror"
                                    id="course_id"
                                    name="course_id"
                                    required>
                                <option value="">Pilih Kursus</option>
                                @foreach ($courses as $c)
                                    @php
                                        $cId = $c->id ?? $c['id'];
                                        $cName = $c->name ?? $c['nama'] ?? $c['name'];
                                        $cCode = $c->code ?? $c['code'] ?? null;
                                    @endphp
                                    <option value="{{ $cId }}" @selected(old('course_id') == $cId)>
                                        {{ $cName }}{{ $cCode ? ' (' . $cCode . ')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Area Informasi Availability --}}
                        <div class="mb-3">
                            <label class="form-label text-muted small">Status Ketersediaan Kuota</label>
                            <div id="course-availability" class="alert alert-secondary mb-0" role="status" aria-live="polite">
                                Pilih kursus untuk melihat kuota dan status ketersediaan.
                            </div>
                        </div>

                        {{-- Tombol Kirim Pendaftaran --}}
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-true-pink py-2">
                                Kirim Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
                        &larr; Kembali ke Beranda
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
