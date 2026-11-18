@extends('layouts.app')

@section('title', ($siteName ?? 'KursusKu') . ' — ' . ($tagline ?? 'Belajar, daftar, dan kelola kursus dalam satu tempat.'))

@section('content')
    <!-- Hero Section -->
    <section id="hero" class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <span class="hero-tag">Platform Kursus Terpercaya</span>
                    <h1 class="hero-title">
                        Bangun Keahlian Masa Depan Bersama <span class="text-primary">{{ $siteName ?? 'KursusKu' }}</span>
                    </h1>
                    <p class="hero-desc">
                        {{ $tagline ?? 'Belajar, daftar, dan kelola kursus dalam satu tempat.' }} Dapatkan bimbingan intensif dari praktisi berpengalaman dan bangun portofolio profesional untuk karier impian Anda.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ route('enrollments.create') }}" class="btn btn-primary">Daftar Sekarang</a>
                        <a href="#katalog" class="btn btn-secondary">Lihat Katalog Kursus</a>
                    </div>
                    <div class="hero-stats">
                        <div class="stat-item">
                            <span class="stat-number">6+</span>
                            <span class="stat-label">Kursus Pilihan</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">100%</span>
                            <span class="stat-label">Praktik Terarah</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">Resmi</span>
                            <span class="stat-label">Sertifikat Kelulusan</span>
                        </div>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-media-card">
                        <img src="{{ asset('images/hero-kursus.jpg') }}" alt="Suasana pembelajaran interaktif di KursusKu">
                        <div class="hero-media-badge">
                            Belajar Fleksibel & Terjadwal
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Keunggulan Section -->
    <section id="keunggulan" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Nilai Tambah</span>
                <h2 class="section-title">Keunggulan KursusKu</h2>
                <p class="section-subtitle">
                    Kami merancang ekosistem pembelajaran yang berfokus pada penguasaan kompetensi aplikatif untuk kebutuhan industri.
                </p>
            </div>

            <div class="features-grid">
                @if (isset($keunggulan) && count($keunggulan) > 0)
                    @foreach ($keunggulan as $index => $item)
                        <article class="feature-card">
                            <span class="feature-number">0{{ $index + 1 }}</span>
                            <h3 class="feature-title">{{ $item['judul'] }}</h3>
                            <p class="feature-desc">{{ $item['deskripsi'] }}</p>
                        </article>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <!-- Katalog Kursus Section -->
    <section id="katalog" class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Program Unggulan</span>
                <h2 class="section-title">Katalog Kursus Pilihan</h2>
                <p class="section-subtitle">
                    Pilih program pelatihan intensif yang sesuai dengan minat dan target pengembangan karier Anda. Kuota kelas dibatasi untuk menjaga efektivitas belajar.
                </p>
            </div>

            <div class="courses-grid">
                @foreach ($courses as $item)
                    <article class="course-card">
                        <div class="course-header">
                            <span class="course-category">{{ $item['kategori'] }}</span>
                            <span class="course-status {{ $item['peserta'] >= $item['kapasitas'] ? 'status-penuh' : 'status-tersedia' }}">
                                {{ $item['peserta'] >= $item['kapasitas'] ? 'Penuh' : 'Tersedia' }}
                            </span>
                        </div>

                        <h3 class="course-title">{{ $item['nama'] }}</h3>
                        <p class="course-desc">{{ $item['deskripsi'] ?? 'Materi pembelajaran komprehensif dirancang langsung oleh mentor ahli di bidangnya.' }}</p>

                        <div class="course-meta">
                            <div class="meta-row">
                                <span class="meta-label">Peserta Terdaftar</span>
                                <span class="meta-value">{{ $item['peserta'] }} / {{ $item['kapasitas'] }}</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-label">Sisa Kuota</span>
                                <span class="meta-value">{{ $item['kapasitas'] - $item['peserta'] }} Kursi</span>
                            </div>
                            <div class="meta-row">
                                <span class="meta-label">Tanggal Mulai</span>
                                <span class="meta-value">{{ date('d-m-Y', strtotime($item['tanggal'])) }}</span>
                            </div>
                        </div>

                        <div class="course-pricing">
                            <span class="price-label">Biaya Investasi</span>
                            <span class="price-value">Rp {{ number_format($item['harga'], 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('enrollments.create') }}" class="btn btn-primary btn-block">Daftar Kursus</a>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Alur Pendaftaran Section -->
    <section id="alur" class="section section-alt">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Tahapan Mudah</span>
                <h2 class="section-title">Cara Mendaftar</h2>
                <p class="section-subtitle">
                    Empat langkah sederhana untuk memulai petualangan belajar baru Anda di KursusKu.
                </p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <span class="step-badge">1</span>
                    <h3 class="step-title">Pilih Kursus</h3>
                    <p class="step-desc">Tentukan program pelatihan yang sesuai dengan minat dan target keterampilan Anda.</p>
                </div>

                <div class="step-card">
                    <span class="step-badge">2</span>
                    <h3 class="step-title">Cek Kuota</h3>
                    <p class="step-desc">Pastikan ketersediaan kursi pada jadwal dan gelombang kelas yang Anda minati.</p>
                </div>

                <div class="step-card">
                    <span class="step-badge">3</span>
                    <h3 class="step-title">Isi Formulir</h3>
                    <p class="step-desc">Lengkapi identitas diri dan preferensi belajar melalui formulir pendaftaran.</p>
                </div>

                <div class="step-card">
                    <span class="step-badge">4</span>
                    <h3 class="step-title">Mulai Belajar</h3>
                    <p class="step-desc">Terima konfirmasi registrasi dan akses jadwal orientasi bersama mentor pembimbing.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Media Pembelajaran Section -->
    <section id="media" class="section">
        <div class="container">
            <div class="section-header">
                <span class="section-tag">Fasilitas & Dokumentasi</span>
                <h2 class="section-title">Media Pembelajaran</h2>
                <p class="section-subtitle">
                    Kenali suasana ruang belajar interaktif dan standar materi yang kami sajikan di KursusKu.
                </p>
            </div>

            <div class="media-container">
                <img src="{{ asset('images/hero-kursus.jpg') }}" alt="Ilustrasi kegiatan belajar interaktif di KursusKu">
                <div class="media-caption">
                    <div>
                        <h4>Ruang Kolaborasi & Praktik Terpadu</h4>
                        <p>Sesi interaktif yang memadukan teori fundamental dengan praktik studi kasus industri.</p>
                    </div>
                    <a href="{{ route('enrollments.create') }}" class="btn btn-secondary">Daftar Sekarang</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Kontak Section -->
    <section id="kontak" class="section section-alt">
        <div class="container">
            <div class="contact-card">
                <span class="section-tag">Layanan Informasi</span>
                <h2 class="contact-title">Hubungi Kami</h2>
                <p class="contact-desc">
                    Punya pertanyaan seputar kurikulum, jadwal, atau alur pendaftaran? Tim KursusKu siap membantu Anda.
                </p>
                <div class="contact-channels">
                    <div class="contact-channel">
                        <span>Email:</span>
                        <a href="mailto:info@kursusku.test">info@kursusku.test</a>
                    </div>
                    <div class="contact-channel">
                        <span>Jam Layanan:</span>
                        <span>Senin — Jumat (08.00 - 17.00 WIB)</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
