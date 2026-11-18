<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'code' => 'WEB-101',
                'name' => 'Web Dasar',
                'description' => 'Pelatihan dasar pembuatan struktur halaman web dengan HTML5 dan styling CSS3 modern.',
                'fee' => 300000,
                'quota' => 25,
                'start_date' => '2026-11-25',
                'status' => 'open',
            ],
            [
                'code' => 'PHP-101',
                'name' => 'PHP Fundamental',
                'description' => 'Memahami sintaks dasar PHP, kontrol alur, manipulasi array, dan penanganan formulir web.',
                'fee' => 400000,
                'quota' => 25,
                'start_date' => '2026-12-01',
                'status' => 'open',
            ],
            [
                'code' => 'LRV-101',
                'name' => 'Laravel Dasar',
                'description' => 'Belajar arsitektur MVC, Blade templating, routing, controller, dan Eloquent ORM di Laravel.',
                'fee' => 500000,
                'quota' => 20,
                'start_date' => '2026-12-05',
                'status' => 'open',
            ],
            [
                'code' => 'SQL-101',
                'name' => 'MySQL Dasar',
                'description' => 'Pengenalan basis data relasional, desain skema tabel, operasi DDL, DML, dan query SQL.',
                'fee' => 350000,
                'quota' => 30,
                'start_date' => '2026-12-10',
                'status' => 'open',
            ],
            [
                'code' => 'UI-101',
                'name' => 'Bootstrap UI',
                'description' => 'Membangun antarmuka web responsif dengan sistem grid, komponen navigasi, dan utilitas Bootstrap 5.',
                'fee' => 300000,
                'quota' => 25,
                'start_date' => '2026-12-15',
                'status' => 'draft',
            ],
            [
                'code' => 'JS-101',
                'name' => 'JavaScript Dasar',
                'description' => 'Konsep dasar pemrograman JavaScript, manipulasi DOM, penanganan event, dan fungsi asinkron.',
                'fee' => 400000,
                'quota' => 20,
                'start_date' => '2026-12-20',
                'status' => 'open',
            ],
            [
                'code' => 'AJAX-101',
                'name' => 'jQuery dan Ajax',
                'description' => 'Integrasi permintaan HTTP asinkron di sisi browser menggunakan library jQuery tanpa reload halaman.',
                'fee' => 450000,
                'quota' => 20,
                'start_date' => '2027-01-05',
                'status' => 'draft',
            ],
            [
                'code' => 'API-101',
                'name' => 'REST API Dasar',
                'description' => 'Perancangan dan pembuatan layanan RESTful web service dengan pertukaran format data JSON.',
                'fee' => 550000,
                'quota' => 18,
                'start_date' => '2027-01-12',
                'status' => 'closed',
            ],
        ];

        foreach ($courses as $course) {
            Course::firstOrCreate(
                ['code' => $course['code']],
                $course
            );
        }
    }
}
