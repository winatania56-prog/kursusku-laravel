<header class="site-header">
    <nav class="navbar navbar-expand-lg site-navbar py-3" aria-label="Navigasi Utama">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
                <span>KursusKu</span>
                <span class="nav-brand-dot" aria-hidden="true"></span>
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#kursuskuNavbar" aria-controls="kursuskuNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="kursuskuNavbar">
                <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('home') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('home') }}">Beranda</a>
                    </li>

                    @if (request()->routeIs('home'))
                        <li class="nav-item"><a class="nav-link px-2" href="#keunggulan">Keunggulan</a></li>
                        <li class="nav-item"><a class="nav-link px-2" href="#katalog">Katalog Kursus</a></li>
                        <li class="nav-item"><a class="nav-link px-2" href="#alur">Alur Pendaftaran</a></li>
                        <li class="nav-item"><a class="nav-link px-2" href="#kontak">Kontak</a></li>
                    @endif

                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('courses.*') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('courses.index') }}">Daftar Kursus</a>
                    </li>

                    {{-- Dynamic Role-Based Menus --}}
                    @guest
                        <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                            <a class="btn btn-sm btn-outline-primary px-3 rounded-pill fw-semibold" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                            </a>
                        </li>
                    @else
                        @if (auth()->user()->isAdmin())
                            {{-- Admin Menu --}}
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ request()->routeIs('admin.dashboard') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('admin.dashboard') }}">
                                    Dashboard Admin
                                </a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle px-2 {{ request()->routeIs('enrollments.*') || request()->routeIs('admin.participants.*') || request()->routeIs('admin.users.*') ? 'active text-primary fw-semibold' : '' }}" href="#" id="adminManageDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Manajemen
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="adminManageDropdown">
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('courses.create') }}">
                                            <i class="bi bi-plus-circle me-2 text-primary"></i>Tambah Kursus Baru
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('enrollments.index') }}">
                                            <i class="bi bi-card-checklist me-2 text-primary"></i>Laporan Enrollment
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.participants.index') }}">
                                            <i class="bi bi-people me-2 text-primary"></i>Manajemen Peserta
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <a class="dropdown-item py-2" href="{{ route('admin.users.index') }}">
                                            <i class="bi bi-person-gear me-2 text-primary"></i>Manajemen Pengguna
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @else
                            {{-- User Menu --}}
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ request()->routeIs('dashboard') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('dashboard') }}">
                                    Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link px-2 {{ request()->routeIs('user.enrollments') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('user.enrollments') }}">
                                    Riwayat Pendaftaran
                                </a>
                            </li>
                        @endif

                        {{-- User / Admin Profile & Logout Dropdown --}}
                        <li class="nav-item dropdown ms-lg-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2 px-2" href="#" id="userAccountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.85rem; font-weight: 600;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="d-none d-lg-inline small fw-semibold text-dark">{{ auth()->user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm" aria-labelledby="userAccountDropdown">
                                <li class="px-3 py-2 border-bottom">
                                    <div class="fw-bold text-dark small">{{ auth()->user()->name }}</div>
                                    <div class="text-muted small text-truncate" style="max-width: 180px;">{{ auth()->user()->email }}</div>
                                    <div class="mt-1">
                                        @if (auth()->user()->isAdmin())
                                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle small">Admin</span>
                                        @else
                                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle small">User</span>
                                        @endif
                                    </div>
                                </li>
                                <li>
                                    <a class="dropdown-item py-2 {{ request()->routeIs('profile.edit') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person me-2"></i>Profil Saya
                                    </a>
                                </li>
                                @if (auth()->user()->isAdmin())
                                    <li>
                                        <a class="dropdown-item py-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item py-2 {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2 me-2"></i>Dashboard Saya
                                        </a>
                                    </li>
                                @endif
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item py-2 text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>
</header>
