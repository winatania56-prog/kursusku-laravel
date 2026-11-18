@extends('layouts.app')

@section('title', 'Tambah Enrollment Baru — KursusKu')

@section('content')
<div class="form-page-section py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 200px);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="text-center mb-4">
                    <span class="hero-tag d-inline-block mb-2">Relasi Database</span>
                    <h1 class="h2 fw-bold text-dark">Pendaftaran Enrollment Kursus</h1>
                    <p class="text-muted">Hubungkan peserta (Participant) dengan program kursus (Course) yang berstatus open.</p>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                        <strong>Berhasil!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4" role="alert">
                        <div class="fw-bold mb-1">Periksa kembali data pendaftaran:</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #fff;">
                    <div class="card-body p-4 p-md-5">
                        <form action="{{ route('enrollments.store') }}" method="POST">
                            @csrf

                            {{-- Dropdown Participant --}}
                            <div class="mb-4">
                                <label for="participant_id" class="form-label fw-semibold text-dark">
                                    Peserta (Participant) <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('participant_id') is-invalid @enderror"
                                        id="participant_id"
                                        name="participant_id"
                                        required>
                                    <option value="">-- Pilih Peserta --</option>
                                    @foreach ($participants as $participant)
                                        <option value="{{ $participant->id }}" {{ old('participant_id') == $participant->id ? 'selected' : '' }}>
                                            {{ $participant->name }} ({{ $participant->email }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('participant_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Pilih nama peserta yang terdaftar pada sistem.</small>
                            </div>

                            {{-- Dropdown Course --}}
                            <div class="mb-4">
                                <label for="course_id" class="form-label fw-semibold text-dark">
                                    Program Kursus (Course) <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('course_id') is-invalid @enderror"
                                        id="course_id"
                                        name="course_id"
                                        required>
                                    <option value="">-- Pilih Kursus (Hanya Status Open) --</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                            {{ $course->code }} — {{ $course->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block mt-1">Hanya kursus dengan status <strong>Open</strong> yang dapat dipilih.</small>
                            </div>

                            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3 pt-3 border-top mt-4">
                                <a href="{{ route('enrollments.index') }}" class="btn btn-outline-secondary px-4 py-2">
                                    Kembali ke Laporan
                                </a>
                                <button type="submit" class="btn btn-true-pink px-4 py-2 shadow-sm">
                                    Simpan Enrollment
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
