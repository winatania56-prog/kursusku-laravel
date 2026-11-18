# Eight Test Case Wajib — Pertemuan 10

Dokumentasi hasil pengujian validasi, penyimpanan (Create), dan pembacaan (Read) data kursus pada tabel `courses` di database MySQL `kursus_nia`.

| No | Nama Pengujian | Input yang Diuji | Hasil Aktual | Status | Catatan / Perbaikan |
|---|---|---|---|:---:|---|
| 1 | Simpan data valid | `code: 'GIT-101'`, `name: 'Git dan GitHub'`, `fee: 300000`, `quota: 25`, `start_date: '2026-11-25'`, `status: 'open'` | Data berhasil disimpan ke tabel `courses`, dialihkan ke `courses.index` (`to_route`), dan flash success message *"Kursus berhasil ditambahkan."* tampil. | **PASS** | Validasi controller dan model mass assignment berfungsi dengan baik. |
| 2 | Kode kosong | `code: ''`, `name: 'Web Dasar'`, `fee: 300000`, `quota: 25`, `start_date: '2026-11-25'`, `status: 'open'` | Permintaan ditolak dengan pesan kesalahan validasi *"The code field is required."*, tidak ada record baru yang ditambahkan ke database. | **PASS** | Aturan `required` pada field `code` aktif. |
| 3 | Kode duplikat | `code: 'WEB-101'` (sudah tersimpan), `name: 'Web Dasar Baru'`, `fee: 350000`, `quota: 20`, `start_date: '2026-12-01'`, `status: 'open'` | Permintaan ditolak dengan pesan validasi *"The code has already been taken."*, proses insert dibatalkan. | **PASS** | Aturan `unique:courses,code` berhasil mencegah duplikasi data. |
| 4 | Nama kosong | `code: 'PHP-102'`, `name: ''`, `fee: 400000`, `quota: 25`, `start_date: '2026-12-01'`, `status: 'open'` | Validasi gagal dengan pesan *"The name field is required."*, data tidak tersimpan ke database. | **PASS** | Aturan `required` pada field `name` aktif. |
| 5 | Biaya negatif | `code: 'SQL-102'`, `name: 'MySQL Lanjutan'`, `fee: -1000`, `quota: 30`, `start_date: '2026-12-10'`, `status: 'open'` | Validasi gagal dengan pesan *"The fee field must be at least 0."*, nilai biaya negatif ditolak. | **PASS** | Aturan `min:0` pada field `fee` aktif. |
| 6 | Kuota 0 | `code: 'UI-102'`, `name: 'Tailwind CSS'`, `fee: 300000`, `quota: 0`, `start_date: '2026-12-15'`, `status: 'draft'` | Validasi gagal dengan pesan *"The quota field must be at least 1."*, kuota bernilai nol ditolak. | **PASS** | Aturan `min:1` pada field `quota` aktif. |
| 7 | Status tidak valid | `code: 'JS-102'`, `name: 'TypeScript Dasar'`, `fee: 400000`, `quota: 20`, `start_date: '2026-12-20'`, `status: 'active'` | Validasi gagal dengan pesan *"The selected status is invalid."*, input status di luar enum ditolak. | **PASS** | Aturan `in:draft,open,closed` aktif. |
| 8 | Daftar tampil ulang | Refresh / akses langsung URL `/courses` (`courses.index`) | Seluruh 8 data kursus berhasil dibaca ulang dari MySQL `kursus_nia` melalui Eloquent `Course::latest()->get()` dan dirender pada tabel HTML. | **PASS** | Data persisten di database MySQL dan pembacaan Read berhasil. |

## Ringkasan Verifikasi
- Pengujian otomatis dijalankan melalui PHPUnit / Pest (`tests/Feature/CourseTest.php`).
- Seluruh 11 skenario pengujian `CourseTest` lulus dengan status **PASS**.
- Operasi invalid tidak menghasilkan data `INSERT` ke database.
