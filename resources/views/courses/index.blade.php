@extends('layouts.app')

@section('title', 'Daftar Kursus — KursusKu')

@section('content')
<div class="py-5" style="background-color: var(--color-bg); min-height: calc(100vh - 200px);">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
            <div>
                <span class="hero-tag d-inline-block mb-1">Manajemen Program</span>
                <h1 class="h2 fw-bold text-dark mb-1">Daftar Kursus</h1>
                <p class="text-muted mb-0">Kelola dan pantau seluruh data program pelatihan aktif di KursusKu.</p>
            </div>
            <div>
                <a href="{{ route('courses.create') }}" class="btn btn-true-pink px-4 py-2 shadow-sm d-inline-flex align-items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                    </svg>
                    <span>Tambah Kursus</span>
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead style="background-color: var(--color-primary-subtle); color: var(--color-primary-deep);">
                            <tr>
                                <th scope="col" class="py-3 px-3 text-center" style="width: 50px;">No.</th>
                                <th scope="col" class="py-3 px-3">Kode</th>
                                <th scope="col" class="py-3 px-3">Nama</th>
                                <th scope="col" class="py-3 px-3">Biaya</th>
                                <th scope="col" class="py-3 px-3 text-center">Kuota</th>
                                <th scope="col" class="py-3 px-3">Tanggal Mulai</th>
                                <th scope="col" class="py-3 px-3 text-center">Status</th>
                                <th scope="col" class="py-3 px-3 text-center" style="width: 200px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($courses as $course)
                                <tr>
                                    <td class="text-center text-muted fw-semibold py-3 px-3">{{ $loop->iteration }}</td>
                                    <td class="py-3 px-3">
                                        <span class="badge" style="background-color: var(--color-primary-light); color: var(--color-primary-dark); font-family: monospace; font-size: 0.85rem;">
                                            {{ $course->code }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3">
                                        <div class="fw-bold text-dark">{{ $course->name }}</div>
                                        @if ($course->description)
                                            <small class="text-muted text-truncate d-inline-block" style="max-width: 260px;">
                                                {{ $course->description }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 fw-semibold text-dark">
                                        Rp {{ number_format($course->fee, 0, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <span class="badge bg-light text-dark border">{{ $course->quota }}</span>
                                    </td>
                                    <td class="py-3 px-3 text-muted">
                                        {{ $course->start_date ? $course->start_date->format('d/m/Y') : '-' }}
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        @if ($course->status === 'open')
                                            <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-1 rounded-pill">
                                                open
                                            </span>
                                        @elseif ($course->status === 'draft')
                                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-3 py-1 rounded-pill">
                                                draft
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-3 py-1 rounded-pill">
                                                closed
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="d-inline-flex gap-1 align-items-center">
                                            <a href="{{ route('courses.show', $course) }}" class="btn btn-sm btn-outline-primary px-2 py-1">
                                                Detail
                                            </a>
                                            <a href="{{ route('courses.edit', $course) }}" class="btn btn-sm btn-outline-secondary px-2 py-1">
                                                Edit
                                            </a>
                                            <form action="{{ route('courses.destroy', $course) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kursus ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger px-2 py-1">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5 text-muted">
                                        <div class="py-3">
                                            <p class="mb-0 fs-6">Belum ada data kursus.</p>
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
