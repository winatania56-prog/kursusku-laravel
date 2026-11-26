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
                            <td class="text-center text-muted fw-semibold py-3 px-3">
                                {{ $courses->firstItem() ? $courses->firstItem() + $loop->index : $loop->iteration }}
                            </td>
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
                                    @auth
                                        @if (auth()->user()->isAdmin())
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
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <div class="py-3">
                                    @if (request()->filled('search') || request()->filled('status'))
                                        <div class="mb-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="currentColor" class="bi bi-search text-muted opacity-50" viewBox="0 0 16 16">
                                                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                            </svg>
                                        </div>
                                        <p class="mb-1 fw-semibold text-dark">Kursus tidak ditemukan</p>
                                        <p class="text-muted small mb-3">
                                            @if (request()->filled('search') && request()->filled('status'))
                                                Tidak ada kursus berstatus <strong>{{ request('status') }}</strong> yang cocok dengan kata kunci &ldquo;<strong>{{ request('search') }}</strong>&rdquo;.
                                            @elseif (request()->filled('search'))
                                                Tidak ada kursus yang cocok dengan kata kunci &ldquo;<strong>{{ request('search') }}</strong>&rdquo;.
                                            @else
                                                Tidak ada kursus dengan status <strong>{{ request('status') }}</strong>.
                                            @endif
                                        </p>
                                        <a href="{{ route('courses.index') }}" class="btn btn-sm btn-outline-secondary">
                                            Reset Filter &amp; Pencarian
                                        </a>
                                    @else
                                        <p class="mb-2 fs-6">Belum ada data kursus.</p>
                                        <a href="{{ route('courses.create') }}" class="btn btn-sm btn-true-pink">
                                            Tambah Kursus Pertama
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if ($courses->hasPages() || $courses->total() > 0)
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3 mt-4">
        <div class="text-muted small">
            Menampilkan {{ $courses->firstItem() ?? 0 }} sampai {{ $courses->lastItem() ?? 0 }} dari total {{ $courses->total() }} kursus
        </div>
        <div class="pagination-wrapper">
            {{ $courses->links() }}
        </div>
    </div>
@endif
