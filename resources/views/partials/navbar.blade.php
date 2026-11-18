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
                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('enrollments.index') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('enrollments.index') }}">Laporan Enrollment</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link px-2 {{ request()->routeIs('enrollments.create') ? 'active text-primary fw-semibold' : '' }}" href="{{ route('enrollments.create') }}">Pendaftaran</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="nav-cta text-decoration-none" href="{{ route('enrollments.create') }}">Daftar Sekarang</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</header>
