@extends('layouts.app')

@section('title', 'Edit Kursus — ' . $course->name)

@section('content')
<div class="form-page-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">

                <div class="text-center mb-4">
                    <span class="hero-tag d-inline-block mb-2">Edit Data Program</span>
                    <h1 class="h2 fw-bold text-dark">Edit Kursus</h1>
                    <p class="text-muted">Perbarui data atau parameter pelaksanaan untuk kursus <strong>{{ $course->code }}</strong>.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger shadow-sm mb-4" role="alert">
                        <div class="fw-bold mb-1">Periksa kembali data yang dimasukkan:</div>
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-container-card">
                    <form action="{{ route('courses.update', $course) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            {{-- Kode Kursus --}}
                            <div class="col-md-6 mb-3">
                                <label for="code" class="form-label">Kode Kursus <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('code') is-invalid @enderror"
                                       id="code"
                                       name="code"
                                       maxlength="20"
                                       value="{{ old('code', $course->code) }}"
                                       placeholder="Contoh: WEB-101"
                                       required>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Maksimal 20 karakter, unik.</small>
                            </div>

                            {{-- Nama Kursus --}}
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nama Kursus <span class="text-danger">*</span></label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       maxlength="120"
                                       value="{{ old('name', $course->name) }}"
                                       placeholder="Contoh: Web Dasar"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Antara 3 hingga 120 karakter.</small>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi Kursus</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      name="description"
                                      rows="3"
                                      placeholder="Ringkasan materi atau silabus kursus (opsional)">{{ old('description', $course->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Maksimal 2000 karakter.</small>
                        </div>

                        <div class="row">
                            {{-- Biaya Kursus --}}
                            <div class="col-md-6 mb-3">
                                <label for="fee" class="form-label">Biaya (Rp) <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control @error('fee') is-invalid @enderror"
                                       id="fee"
                                       name="fee"
                                       min="0"
                                       value="{{ old('fee', $course->fee) }}"
                                       required>
                                @error('fee')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Minimal 0 (tidak boleh negatif).</small>
                            </div>

                            {{-- Kuota Kursus --}}
                            <div class="col-md-6 mb-3">
                                <label for="quota" class="form-label">Kuota Peserta <span class="text-danger">*</span></label>
                                <input type="number"
                                       class="form-control @error('quota') is-invalid @enderror"
                                       id="quota"
                                       name="quota"
                                       min="1"
                                       max="500"
                                       value="{{ old('quota', $course->quota) }}"
                                       required>
                                @error('quota')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Antara 1 hingga 500 kursi.</small>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Tanggal Mulai --}}
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                <input type="date"
                                       class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date"
                                       name="start_date"
                                       value="{{ old('start_date', $course->start_date ? $course->start_date->format('Y-m-d') : '') }}"
                                       required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Status Kursus --}}
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror"
                                        id="status"
                                        name="status"
                                        required>
                                    <option value="draft" @selected(old('status', $course->status) === 'draft')>Draft</option>
                                    <option value="open" @selected(old('status', $course->status) === 'open')>Open</option>
                                    <option value="closed" @selected(old('status', $course->status) === 'closed')>Closed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                            <a href="{{ route('courses.show', $course) }}" class="btn btn-outline-secondary px-4 py-2">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-true-pink px-4 py-2">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('courses.show', $course) }}" class="text-decoration-none text-muted small">
                        &larr; Kembali ke Detail Kursus
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
