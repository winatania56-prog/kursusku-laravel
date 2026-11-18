# Test Matrix Week 09

1. Home layout: PASS
2. Navbar mobile: PASS
3. Form page: PASS
4. Select default: PASS
5. Ajax course 1: PASS
6. Ajax course 2: PASS
7. Ajax course 3: PASS
8. Valid POST: PASS
9. Invalid email: PASS
10. Network JSON: PASS

## Detail Hasil Pengujian

| No | Skenario Pengujian | Hasil Pengujian | Keterangan Verifikasi |
|---|---|---|---|
| 1 | Home layout | PASS | Master layout `layouts/app.blade.php`, navbar reusable, stylesheet True Pink, dan Bootstrap dimuat via `@vite`. |
| 2 | Navbar mobile | PASS | Tombol hamburger (`navbar-toggler`) terpasang dengan target collapse `#kursuskuNavbar` dan event toggle Bootstrap JS aktif. |
| 3 | Form page | PASS | Halaman `/pendaftaran` (`enrollments.create`) berhasil dibuka (HTTP 200), form card, CSRF token, input lengkap. |
| 4 | Select default | PASS | Pilihan kursus bernilai kosong (`""`) menampilkan instruksi `"Pilih kursus untuk melihat sisa kursi."` dalam `alert-secondary`. |
| 5 | Ajax course 1 | PASS | Pemilihan Web Dasar (ID 1) menghasilkan request GET `/courses/1/availability`, response `{"course":"Web Dasar","remaining":12}`, teks `"12 kursi tersisa untuk Web Dasar."` dalam `alert-success`. |
| 6 | Ajax course 2 | PASS | Pemilihan PHP Dasar (ID 2) menghasilkan request GET `/courses/2/availability`, response `{"course":"PHP Dasar","remaining":8}`, teks `"8 kursi tersisa untuk PHP Dasar."` dalam `alert-success`. |
| 7 | Ajax course 3 | PASS | Pemilihan Laravel Dasar (ID 3) menghasilkan request GET `/courses/3/availability`, response `{"course":"Laravel Dasar","remaining":4}`, teks `"4 kursi tersisa untuk Laravel Dasar."` dalam `alert-success`. |
| 8 | Valid POST | PASS | Pengiriman formulir valid ke `/pendaftaran/preview` divalidasi berhasil, dialihkan kembali ke `/pendaftaran` dengan pesan sukses flash dan input lama (`withInput()`). |
| 9 | Invalid email | PASS | Pengiriman email tidak valid memicu error validasi Laravel (`$errors`), pesan kesalahan muncul pada alert merah (`alert-danger`), nilai input nama tetap dipertahankan. |
| 10 | Network JSON | PASS | Endpoint ketersediaan mengembalikan header `Content-Type: application/json` dan payload JSON murni dengan status HTTP 200 (serta HTTP 404 untuk ID tidak terdaftar). |

## Console Check
- Bootstrap JavaScript 5.3.9 dimuat tanpa error melalui bundling Vite.
- jQuery 4.1.0 diinisialisasi dan diekspos ke `window.$` serta `window.jQuery`.
- Build Vite (`npm run build`) berjalan bersih (0 error, 0 warning).
- Tidak ada error uncaught JavaScript pada browser runtime console.
