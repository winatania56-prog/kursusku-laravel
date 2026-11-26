<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class CourseCatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'code' => 'REACT-101',
                'name' => 'React Development',
                'description' => 'Membangun antarmuka web modern berbasis komponen menggunakan React, Hooks, dan State Management.',
                'fee' => 550000,
                'quota' => 20,
                'start_date' => '2027-01-20',
                'status' => 'open',
            ],
            [
                'code' => 'PY-101',
                'name' => 'Python Programming',
                'description' => 'Dasar pemrograman Python, struktur data, fungsi, OOP, dan otomatisasi skrip backend.',
                'fee' => 450000,
                'quota' => 25,
                'start_date' => '2027-01-25',
                'status' => 'open',
            ],
            [
                'code' => 'GIT-101',
                'name' => 'Git Fundamental',
                'description' => 'Manajemen versi kode, branching, merging, pull request, dan kolaborasi tim via GitHub.',
                'fee' => 250000,
                'quota' => 30,
                'start_date' => '2027-02-01',
                'status' => 'open',
            ],
            [
                'code' => 'NODE-101',
                'name' => 'Node.js Development',
                'description' => 'Pengembangan runtime server-side JavaScript dengan Express, middleware, dan asynchronous IO.',
                'fee' => 500000,
                'quota' => 20,
                'start_date' => '2027-02-05',
                'status' => 'open',
            ],
            [
                'code' => 'MOB-101',
                'name' => 'Mobile App Development Flutter',
                'description' => 'Pembuatan aplikasi mobile multi-platform Android dan iOS menggunakan framework Flutter dan Dart.',
                'fee' => 600000,
                'quota' => 18,
                'start_date' => '2027-02-10',
                'status' => 'open',
            ],
            [
                'code' => 'UIUX-101',
                'name' => 'UI/UX Design Figma',
                'description' => 'Prinsip desain pengalaman pengguna, wireframing, prototyping interaktif, dan design system di Figma.',
                'fee' => 400000,
                'quota' => 22,
                'start_date' => '2027-02-15',
                'status' => 'open',
            ],
            [
                'code' => 'HTML-101',
                'name' => 'HTML dan CSS Modern',
                'description' => 'Semantik HTML5, CSS Flexbox, CSS Grid, media query responsif, dan aksesibilitas web.',
                'fee' => 300000,
                'quota' => 30,
                'start_date' => '2027-02-20',
                'status' => 'open',
            ],
            [
                'code' => 'FS-101',
                'name' => 'Full Stack Development',
                'description' => 'Pengembangan aplikasi web end-to-end dari perancangan database, API backend, hingga frontend.',
                'fee' => 750000,
                'quota' => 15,
                'start_date' => '2027-02-25',
                'status' => 'open',
            ],
            [
                'code' => 'TS-101',
                'name' => 'TypeScript Dasar',
                'description' => 'Sistem tipe statis, interface, generics, dan integrasi TypeScript pada ekosistem JavaScript.',
                'fee' => 450000,
                'quota' => 20,
                'start_date' => '2027-03-01',
                'status' => 'closed',
            ],
            [
                'code' => 'POSTG-101',
                'name' => 'Database PostgreSQL',
                'description' => 'Pengelolaan basis data relasional enterprise, indexing, query optimization, dan JSONB.',
                'fee' => 400000,
                'quota' => 25,
                'start_date' => '2027-03-05',
                'status' => 'closed',
            ],
            [
                'code' => 'NEXT-101',
                'name' => 'Next.js Development',
                'description' => 'Server-side rendering, App Router, static site generation, dan deployment aplikasi React.',
                'fee' => 600000,
                'quota' => 20,
                'start_date' => '2027-03-10',
                'status' => 'closed',
            ],
            [
                'code' => 'DOCK-101',
                'name' => 'Docker untuk Developer',
                'description' => 'Kontainerisasi aplikasi, Dockerfile, docker-compose multi-service, dan pipeline container.',
                'fee' => 500000,
                'quota' => 20,
                'start_date' => '2027-03-15',
                'status' => 'closed',
            ],
            [
                'code' => 'BOOT-101',
                'name' => 'Bootstrap 5 Pro',
                'description' => 'Kustomisasi SCSS Bootstrap, komponen tingkat lanjut, utilities API, dan tema responsif.',
                'fee' => 350000,
                'quota' => 25,
                'start_date' => '2027-03-20',
                'status' => 'draft',
            ],
            [
                'code' => 'VUE-101',
                'name' => 'Vue.js Fundamental',
                'description' => 'Reactivity system, Composition API, Pinia state management, dan Vue Router.',
                'fee' => 480000,
                'quota' => 20,
                'start_date' => '2027-03-25',
                'status' => 'draft',
            ],
            [
                'code' => 'CYBER-101',
                'name' => 'Cyber Security Dasar',
                'description' => 'Konsep keamanan informasi, identifikasi kerentanan OWASP Top 10, dan praktik secure coding.',
                'fee' => 650000,
                'quota' => 15,
                'start_date' => '2027-04-01',
                'status' => 'draft',
            ],
            [
                'code' => 'DATA-101',
                'name' => 'Data Analysis dengan Python',
                'description' => 'Analisis data eksploratif menggunakan library Pandas, NumPy, dan visualisasi data Matplotlib.',
                'fee' => 550000,
                'quota' => 20,
                'start_date' => '2027-04-05',
                'status' => 'draft',
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
