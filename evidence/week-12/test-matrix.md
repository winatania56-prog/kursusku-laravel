# Matriks Pengujian & Verifikasi — Pertemuan 12: Relational Database

Dokumentasi hasil eksekusi pengujian fungsional, integritas referensial database, cascading delete, dan automated feature testing untuk entitas `Course`, `Participant`, dan `Enrollment` pada proyek **KursusKu**.

---

## 1. Lingkungan Pengujian
- **Framework:** Laravel 13
- **PHP:** 8.3.33
- **Database Utama:** MySQL (`kursus_nia` di Laragon)
- **Database Testing Otomatis:** SQLite In-Memory (`:memory:`)
- **Tanggal Eksekusi:** 18 November 2026

---

## 2. Matriks Pengujian End-to-End (8 Skenario Wajib)

| No | Skenario Pengujian | Input / Aksi | Ekspektasi | Hasil Aktual | Status |
|---|---|---|---|---|---|
| **1** | Pendaftaran pertama Alya Rahma ke kursus 'Laravel Dasar' | `participant_id`: 1 (Alya), `course_id`: 3 (LRV-101) | Data tersimpan ke tabel `enrollments` dengan status `registered`, redirect ke laporan dengan pesan sukses | Enrollment ID: 1 tersimpan; Participant: Alya Rahma; Course: Laravel Dasar; Status: `registered` | **PASS** |
| **2** | Pendaftaran kedua Alya Rahma ke kursus 'Laravel Dasar' (Duplikasi) | `participant_id`: 1 (Alya), `course_id`: 3 (LRV-101) | Validasi menolak pendaftaran; pesan error: "Participant ini sudah terdaftar pada course tersebut." | Validasi layer controller memblokir request sebelum menyentuh database; error ditampilkan pada field `course_id` | **PASS** |
| **3** | Pendaftaran Alya Rahma ke kursus 'PHP Fundamental' | `participant_id`: 1 (Alya), `course_id`: 2 (PHP-101) | Data tersimpan ke tabel `enrollments` karena kursus berbeda meskipun participant sama | Enrollment ID: 2 tersimpan; Participant: Alya Rahma; Course: PHP Fundamental; Status: `registered` | **PASS** |
| **4** | Pendaftaran Bima Putra ke kursus 'Laravel Dasar' | `participant_id`: 2 (Bima), `course_id`: 3 (LRV-101) | Data tersimpan ke tabel `enrollments` karena participant berbeda meskipun kursus sama | Enrollment ID: 3 tersimpan; Participant: Bima Putra; Course: Laravel Dasar; Status: `registered` | **PASS** |
| **5** | Form pendaftaran kosong (Tanpa Participant & Course) | `participant_id`: null, `course_id`: null | Validasi required memblokir request dan mengembalikan pesan error validasi | HTTP 302 redirect back dengan session errors pada `participant_id` dan `course_id` | **PASS** |
| **6** | Form pendaftaran dengan ID yang tidak valid | `participant_id`: 9999, `course_id`: 8888 | Validasi `exists` memblokir request karena referensi data tidak ditemukan di database | HTTP 302 redirect back dengan session errors `exists:participants` dan `exists:courses` | **PASS** |
| **7** | Filter Course pada dropdown pendaftaran | GET `/enrollments/create` | Hanya kursus dengan status `open` yang muncul di opsi dropdown (kursus `draft` atau `closed` disembunyikan) | Dari total 8 kursus, hanya 5 kursus berstatus `open` yang dimuat: WEB-101, PHP-101, LRV-101, SQL-101, JS-101 | **PASS** |
| **8** | Laporan Enrollment dengan Eager Loading | GET `/enrollments` | Menampilkan tabel lengkap relasi: Nama Peserta, Nama Kursus, Tanggal Mulai Kursus, Status Enrollment; terhindar dari N+1 query problem | Eager loading `with(['participant', 'course'])` berhasil memuat seluruh 3 record enrollment dengan data relasional lengkap | **PASS** |

---

## 3. Matriks Pengujian Integritas Referensial & Cascade Delete (2 Skenario)

| Kode | Skenario | Langkah Pengujian | Verifikasi Integritas | Status |
|---|---|---|---|---|
| **CSD-A** | **Course Cascade Delete** (`ON DELETE CASCADE`) | 1. Buat kursus dummy: `DUMMY-CSD` (ID: 11)<br>2. Daftarkan Citra Nabila ke kursus dummy (Enrollment ID: 4)<br>3. Hapus kursus dummy via Eloquent `$dummyCourse->delete()` | - Record `enrollments` ID 4 **otomatis terhapus** oleh MySQL foreign key engine.<br>- Record Participant Citra Nabila (ID: 3) **tetap aman & utuh**.<br>- 8 Data Course utama **tetap utuh tanpa perubahan**. | **PASS** |
| **CSD-B** | **Participant Cascade Delete** (`ON DELETE CASCADE`) | 1. Buat peserta dummy: `Dummy Participant` (ID: 4)<br>2. Daftarkan peserta dummy ke PHP Fundamental (Enrollment ID: 5)<br>3. Hapus peserta dummy via Eloquent `$dummyParticipant->delete()` | - Record `enrollments` ID 5 **otomatis terhapus** oleh MySQL foreign key engine.<br>- Record Course PHP Fundamental (ID: 2) **tetap aman & utuh**.<br>- 8 Data Course utama **tetap utuh tanpa perubahan**. | **PASS** |

---

## 4. Hasil Automated Feature Testing (`php artisan test`)

Eksekusi pengujian otomatis dijalankan pada database in-memory SQLite:

```
PASS  Tests\Feature\CourseCrudTest
✓ course index page loads successfully and displays courses
✓ course create page loads successfully
✓ course store creates course and redirects
✓ course store validates required fields
✓ course store validates unique code
✓ course store validates numeric fee and quota
✓ course store validates enum status
✓ course show page displays course details
✓ course show returns 404 for non-existent course
✓ course edit page loads with existing data
✓ course update modifies existing course
✓ course update validates unique code ignoring current course
✓ course delete removes course from database
✓ course store request validates start date format

PASS  Tests\Feature\CourseTest
✓ database connection is working
✓ courses table exists
✓ courses table has initial data
✓ course model retrieves data
✓ course model validates fillable attributes
✓ course model has proper casts
✓ courses index view displays course list
✓ home page displays active courses from database

PASS  Tests\Feature\EnrollmentTest
✓ enrollment create route exists and returns ok
✓ enrollment form renders required elements and csrf
✓ enrollment preview succeeds with valid data
✓ enrollment preview fails with invalid email
✓ enrollment preview fails with short name
✓ enrollment preview fails with missing course id
✓ courses availability returns json for valid courses
✓ courses availability returns 404 for unknown or invalid id

PASS  Tests\Feature\HomeTest
✓ home route returns successful response
✓ home page displays brand name
✓ home page displays hero section
✓ home page displays catalog section
✓ home page displays all courses from controller
✓ home page contains true pink color palette

PASS  Tests\Feature\RelationalEnrollmentTest
✓ enrollments index page loads and displays empty state initially
✓ enrollments create page loads and displays participants and open courses only
✓ enrollments store creates enrollment successfully
✓ enrollments store validates required fields
✓ enrollments store validates existence of participant and course
✓ enrollments store rejects duplicate enrollment at validation layer
✓ database composite unique constraint rejects duplicate at db layer
✓ eloquent relationships work correctly
✓ cascade delete when course is deleted
✓ cascade delete when participant is deleted

Tests:    49 passed (222 assertions)
Duration: 1.99s
```

---

## 5. Ringkasan Status Akhir Basis Data `kursus_nia`

- **Tabel `courses`:** 8 data kursus utama (utuh dan tidak terhapus).
- **Tabel `participants`:** 3 data peserta (`Alya Rahma`, `Bima Putra`, `Citra Nabila`).
- **Tabel `enrollments`:** 3 data pendaftaran aktif (`Alya` &rarr; `LRV-101`, `Alya` &rarr; `PHP-101`, `Bima` &rarr; `LRV-101`).
- **Foreign Key Constraints:** Aktif dengan `ON DELETE CASCADE` untuk `participant_id` dan `course_id`.
- **Composite Unique Index:** Aktif untuk pasangan kolom `(participant_id, course_id)`.
