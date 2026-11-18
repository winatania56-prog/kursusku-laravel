# Test Matrix Week 11 — Course CRUD & Validation

Dokumentasi hasil pengujian menyeluruh (15 Test Case Wajib dan pengujian pendukung) pada implementasi Show, Form Request, Edit, Update, dan Delete untuk modul Course di KursusKu Laravel.

| No | Kasus Pengujian | Input yang Diuji | Expected | Actual | Status | Bukti |
|:---:|---|---|---|---|:---:|---|
| 1 | Duplicate code saat create | `code: 'WEB-101'` (sudah ada di database) | Validasi gagal, pesan error *"The code has already been taken."*, data ditolak | HTTP 302 redirect back dengan error session `code`, tidak ada record baru | **PASS** | `04-validation-duplicate-code.png`, `CourseTest::test_case_01` |
| 2 | Nama kosong saat create | `name: ''` | Validasi gagal, pesan error *"The name field is required."* | HTTP 302 redirect back dengan error session `name` | **PASS** | `CourseTest::test_case_02` |
| 3 | Nama terlalu pendek saat create | `name: 'AI'` (kurang dari 3 karakter) | Validasi gagal, pesan error *"The name field must be at least 3 characters."* | HTTP 302 redirect back dengan error session `name` | **PASS** | `CourseTest::test_case_03` |
| 4 | Fee negatif saat create | `fee: -1000` | Validasi gagal, pesan error *"The fee field must be at least 0."* | HTTP 302 redirect back dengan error session `fee` | **PASS** | `CourseTest::test_case_04` |
| 5 | Fee bukan integer saat create | `fee: 'abc'` | Validasi gagal, pesan error *"The fee field must be an integer."* | HTTP 302 redirect back dengan error session `fee` | **PASS** | `CourseTest::test_case_05` |
| 6 | Quota nol saat create | `quota: 0` | Validasi gagal, pesan error *"The quota field must be between 1 and 500."* | HTTP 302 redirect back dengan error session `quota` | **PASS** | `CourseTest::test_case_06` |
| 7 | Quota 501 saat create | `quota: 501` | Validasi gagal, pesan error *"The quota field must be between 1 and 500."* | HTTP 302 redirect back dengan error session `quota` | **PASS** | `05-validation-quota-501.png`, `CourseTest::test_case_07` |
| 8 | Boundary quota minimum saat create | `quota: 1` | Validasi berhasil, record tersimpan dengan quota 1 | HTTP 302 redirect ke `courses.index`, flash *"Kursus berhasil ditambahkan."*, data tersimpan | **PASS** | `CourseTest::test_case_08` |
| 9 | Boundary quota maksimum saat create | `quota: 500` | Validasi berhasil, record tersimpan dengan quota 500 | HTTP 302 redirect ke `courses.index`, flash *"Kursus berhasil ditambahkan."*, data tersimpan | **PASS** | `CourseTest::test_case_09` |
| 10 | Tanggal invalid saat create | `start_date: 'bukan-tanggal-valid'` | Validasi gagal, pesan error *"The start date field must be a valid date."* | HTTP 302 redirect back dengan error session `start_date` | **PASS** | `CourseTest::test_case_10` |
| 11 | Status invalid saat create | `status: 'active'` | Validasi gagal, pesan error *"The selected status is invalid."* | HTTP 302 redirect back dengan error session `status` | **PASS** | `CourseTest::test_case_11` |
| 12 | Update dengan code sendiri | PUT `/courses/{id}` tanpa mengubah kode kursus | Validasi lolos karena ignore ID sendiri, perubahan tersimpan | HTTP 302 redirect ke `courses.show`, flash *"Kursus berhasil diperbarui."*, perubahan tersimpan | **PASS** | `CourseTest::test_case_12` |
| 13 | Update ke code Course lain | PUT `/courses/{id2}` dengan kode milik kursus 1 | Validasi gagal, pesan error *"The code has already been taken."* | HTTP 302 redirect back dengan error session `code`, data tidak tertimpa | **PASS** | `CourseTest::test_case_13` |
| 14 | Update normal data kursus | PUT `/courses/{id}` mengubah nama, biaya, kuota, status | Data berhasil diperbarui, redirect ke halaman show | HTTP 302 redirect ke detail `courses.show`, flash message sukses, data terupdate di database | **PASS** | `06-update-success.png`, `CourseTest::test_case_14` |
| 15 | Delete Course dummy | DELETE `/courses/{dummyId}` dengan konfirmasi dialog | Record dummy terhapus permanen dari MySQL | HTTP 302 redirect ke `courses.index`, flash *"Kursus berhasil dihapus."*, 8 kursus utama tetap aman | **PASS** | `07-delete-success.png`, `CourseTest::test_case_15` |

## Pengujian Tambahan Fitur & Keamanan
- **Resource Routing Lengkap:** 8 rute aktif (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`, `availability`) terverifikasi &bull; Bukti: `01-route-list.png`.
- **Halaman Detail (Show):** Menampilkan seluruh atribut kursus, badge status, tombol kembali dan edit &bull; Bukti: `02-course-show.png`.
- **Halaman Edit (Edit):** Menampilkan form dengan isian lama `old('field', $course->field)` dan format tanggal `Y-m-d` &bull; Bukti: `03-course-edit.png`.
- **Route Model Binding 404:** Akses ID tidak terdaftar (contoh: `/courses/99999`) menghasilkan respon HTTP 404 Not Found &bull; **PASS**.
- **Konfirmasi Hapus JavaScript:** Form DELETE dilengkapi `onsubmit="return confirm('Hapus kursus ini?');"`. Jika memilih Cancel, proses submit batal dan data tidak terhapus &bull; **PASS**.
- **CSRF & Method Spoofing:** Form edit menyertakan `@csrf` dan `@method('PUT')`, form delete menyertakan `@csrf` dan `@method('DELETE')` &bull; **PASS**.
- **Keamanan Data Utama:** 8 data kursus awal dari Pertemuan 10 tidak tersentuh proses penghapusan &bull; **PASS**.
