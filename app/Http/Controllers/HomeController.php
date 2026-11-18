<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $siteName = 'KursusKu';
        $tagline = 'Belajar, daftar, dan kelola kursus dalam satu tempat.';
        $year = date('Y');

        $keunggulan = [
            [
                'judul' => 'Kursus Terarah',
                'deskripsi' => 'Kurikulum terstruktur dari konsep dasar hingga praktik nyata untuk membangun keahlian teruji.'
            ],
            [
                'judul' => 'Materi Praktis',
                'deskripsi' => 'Fokus pada studi kasus industri, tugas terpandu, dan portofolio siap kerja di dunia profesional.'
            ],
            [
                'judul' => 'Pendaftaran Mudah',
                'deskripsi' => 'Proses registrasi ringkas dan transparan, memudahkan peserta langsung memulai perjalanan belajar.'
            ]
        ];

        $courses = [
            [
                'nama' => 'Web Development',
                'kategori' => 'Teknologi',
                'harga' => 750000,
                'peserta' => 18,
                'kapasitas' => 20,
                'tanggal' => '2026-10-05',
                'deskripsi' => 'Belajar membuat website interaktif dan responsif dari dasar HTML, CSS, JavaScript hingga backend modern.'
            ],
            [
                'nama' => 'Desain Grafis',
                'kategori' => 'Desain',
                'harga' => 650000,
                'peserta' => 12,
                'kapasitas' => 20,
                'tanggal' => '2026-10-10',
                'deskripsi' => 'Kuasai prinsip dasar desain visual, tipografi, komposisi warna, dan tools grafis standar industri.'
            ],
            [
                'nama' => 'Digital Marketing',
                'kategori' => 'Bisnis',
                'harga' => 800000,
                'peserta' => 20,
                'kapasitas' => 20,
                'tanggal' => '2026-10-15',
                'deskripsi' => 'Strategi pemasaran digital praktis, optimasi SEO, media sosial, dan metrik konversi bisnis.'
            ],
            [
                'nama' => 'Microsoft Office',
                'kategori' => 'Komputer',
                'harga' => 500000,
                'peserta' => 10,
                'kapasitas' => 20,
                'tanggal' => '2026-10-20',
                'deskripsi' => 'Tingkatkan produktivitas kerja melalui penguasaan Word, Excel lanjutan, dan presentasi profesional.'
            ],
            [
                'nama' => 'Data Analysis',
                'kategori' => 'Teknologi',
                'harga' => 900000,
                'peserta' => 15,
                'kapasitas' => 20,
                'tanggal' => '2026-10-25',
                'deskripsi' => 'Pelajari pembersihan data, analisis statistik, visualisasi insight bisnis, dan dashboard interaktif.'
            ],
            [
                'nama' => 'UI/UX Design',
                'kategori' => 'Desain',
                'harga' => 850000,
                'peserta' => 8,
                'kapasitas' => 20,
                'tanggal' => '2026-10-30',
                'deskripsi' => 'Rancang antarmuka aplikasi intuitif dengan riset pengguna, wireframing, dan interactive prototyping.'
            ]
        ];

        $daftarKursus = $courses;

        return view('home', compact('siteName', 'tagline', 'year', 'keunggulan', 'courses', 'daftarKursus'));
    }
}
