<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    protected User $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->adminUser = User::factory()->create([
            'role' => 'admin',
        ]);
        $this->actingAs($this->adminUser);
    }

    public function test_courses_all_resource_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('courses.index'));
        $this->assertTrue(Route::has('courses.create'));
        $this->assertTrue(Route::has('courses.store'));
        $this->assertTrue(Route::has('courses.show'));
        $this->assertTrue(Route::has('courses.edit'));
        $this->assertTrue(Route::has('courses.update'));
        $this->assertTrue(Route::has('courses.destroy'));
    }

    public function test_courses_index_returns_ok_and_shows_empty_message_when_no_data(): void
    {
        $response = $this->get(route('courses.index'));

        $response->assertStatus(200);
        $response->assertViewIs('courses.index');
        $response->assertViewHas('courses');
        $response->assertSee('Belum ada data kursus.');
    }

    public function test_courses_create_form_displays_properly(): void
    {
        $response = $this->get(route('courses.create'));

        $response->assertStatus(200);
        $response->assertViewIs('courses.create');
        $response->assertSee('Tambah Kursus Baru');
        $response->assertSee('name="code"', false);
        $response->assertSee('name="name"', false);
        $response->assertSee('name="description"', false);
        $response->assertSee('name="fee"', false);
        $response->assertSee('name="quota"', false);
        $response->assertSee('name="start_date"', false);
        $response->assertSee('name="status"', false);
        $response->assertSee('Simpan Kursus');
        $response->assertSee('Batal');
    }

    public function test_courses_show_displays_course_details(): void
    {
        $course = Course::create([
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'description' => 'Pelatihan dasar web interaktif.',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $response = $this->get(route('courses.show', $course));

        $response->assertStatus(200);
        $response->assertViewIs('courses.show');
        $response->assertSee('WEB-101');
        $response->assertSee('Web Dasar');
        $response->assertSee('Pelatihan dasar web interaktif.');
        $response->assertSee('Rp 300.000');
        $response->assertSee('25 Kursi');
        $response->assertSee('open');
        $response->assertSee('Edit Kursus');
        $response->assertSee('Kembali');
    }

    public function test_courses_show_non_existent_id_returns_404(): void
    {
        $response = $this->get('/courses/99999');

        $response->assertStatus(404);
    }

    public function test_courses_edit_form_displays_properly_with_saved_values(): void
    {
        $course = Course::create([
            'code' => 'PHP-101',
            'name' => 'PHP Fundamental',
            'description' => 'Materi dasar pemrograman PHP.',
            'fee' => 400000,
            'quota' => 25,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $response = $this->get(route('courses.edit', $course));

        $response->assertStatus(200);
        $response->assertViewIs('courses.edit');
        $response->assertSee('value="PHP-101"', false);
        $response->assertSee('value="PHP Fundamental"', false);
        $response->assertSee('Materi dasar pemrograman PHP.');
        $response->assertSee('value="400000"', false);
        $response->assertSee('value="25"', false);
        $response->assertSee('value="2026-12-01"', false);
        $response->assertSee('Simpan Perubahan');
        $response->assertSee('Batal');
    }

    public function test_courses_edit_non_existent_id_returns_404(): void
    {
        $response = $this->get('/courses/99999/edit');

        $response->assertStatus(404);
    }

    // --- BAGIAN A: VALIDASI CREATE (1 - 8) ---

    public function test_case_01_create_duplicate_code_is_rejected(): void
    {
        Course::create([
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'description' => 'Original course',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $payload = [
            'code' => 'WEB-101',
            'name' => 'Web Dasar Baru',
            'description' => 'Deskripsi lain',
            'fee' => 350000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['code']);
        $this->assertEquals(1, Course::count());
    }

    public function test_case_02_create_empty_name_is_rejected(): void
    {
        $payload = [
            'code' => 'PHP-101',
            'name' => '',
            'description' => 'Deskripsi valid',
            'fee' => 400000,
            'quota' => 25,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['name']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_03_create_name_too_short_is_rejected(): void
    {
        $payload = [
            'code' => 'AI-101',
            'name' => 'AI',
            'description' => 'Deskripsi valid',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-12-05',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['name']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_04_create_fee_negative_is_rejected(): void
    {
        $payload = [
            'code' => 'SQL-101',
            'name' => 'MySQL Dasar',
            'description' => 'Deskripsi valid',
            'fee' => -1000,
            'quota' => 30,
            'start_date' => '2026-12-10',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['fee']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_05_create_fee_non_integer_is_rejected(): void
    {
        $payload = [
            'code' => 'SQL-102',
            'name' => 'MySQL Dasar',
            'description' => 'Deskripsi valid',
            'fee' => 'abc',
            'quota' => 30,
            'start_date' => '2026-12-10',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['fee']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_06_create_quota_zero_is_rejected(): void
    {
        $payload = [
            'code' => 'UI-101',
            'name' => 'Bootstrap UI',
            'description' => 'Deskripsi valid',
            'fee' => 300000,
            'quota' => 0,
            'start_date' => '2026-12-15',
            'status' => 'draft',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['quota']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_07_create_quota_501_is_rejected(): void
    {
        $payload = [
            'code' => 'UI-102',
            'name' => 'Bootstrap UI',
            'description' => 'Deskripsi valid',
            'fee' => 300000,
            'quota' => 501,
            'start_date' => '2026-12-15',
            'status' => 'draft',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['quota']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_08_create_boundary_quota_minimum_is_accepted(): void
    {
        $payload = [
            'code' => 'MIN-001',
            'name' => 'Kelas Privat Minimum',
            'description' => 'Satu peserta',
            'fee' => 1000000,
            'quota' => 1,
            'start_date' => '2026-12-20',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success', 'Kursus berhasil ditambahkan.');
        $this->assertDatabaseHas('courses', ['code' => 'MIN-001', 'quota' => 1]);
    }

    // --- BAGIAN B: VALIDASI, UPDATE, DAN DELETE (9 - 15) ---

    public function test_case_09_create_boundary_quota_maximum_is_accepted(): void
    {
        $payload = [
            'code' => 'MAX-500',
            'name' => 'Webinar Akbar Maksimum',
            'description' => 'Kapasitas maksimal 500 peserta',
            'fee' => 50000,
            'quota' => 500,
            'start_date' => '2026-12-25',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success', 'Kursus berhasil ditambahkan.');
        $this->assertDatabaseHas('courses', ['code' => 'MAX-500', 'quota' => 500]);
    }

    public function test_case_10_create_invalid_date_is_rejected(): void
    {
        $payload = [
            'code' => 'DATE-01',
            'name' => 'Kursus Tanggal Salah',
            'description' => 'Deskripsi valid',
            'fee' => 250000,
            'quota' => 20,
            'start_date' => 'bukan-tanggal-valid',
            'status' => 'open',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['start_date']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_11_create_invalid_status_is_rejected(): void
    {
        $payload = [
            'code' => 'JS-101',
            'name' => 'JavaScript Dasar',
            'description' => 'Deskripsi valid',
            'fee' => 400000,
            'quota' => 20,
            'start_date' => '2026-12-20',
            'status' => 'active',
        ];

        $response = $this->post(route('courses.store'), $payload);

        $response->assertSessionHasErrors(['status']);
        $this->assertEquals(0, Course::count());
    }

    public function test_case_12_update_with_own_code_is_accepted(): void
    {
        $course = Course::create([
            'code' => 'OWN-101',
            'name' => 'Nama Awal',
            'description' => 'Deskripsi Awal',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $payload = [
            'code' => 'OWN-101',
            'name' => 'Nama Telah Diperbarui',
            'description' => 'Deskripsi Baru',
            'fee' => 350000,
            'quota' => 30,
            'start_date' => '2026-11-30',
            'status' => 'open',
        ];

        $response = $this->put(route('courses.update', $course), $payload);

        $response->assertRedirect(route('courses.show', $course));
        $response->assertSessionHas('success', 'Kursus berhasil diperbarui.');

        $this->assertDatabaseHas('courses', [
            'id' => $course->id,
            'code' => 'OWN-101',
            'name' => 'Nama Telah Diperbarui',
            'fee' => 350000,
            'quota' => 30,
        ]);
    }

    public function test_case_13_update_to_another_course_code_is_rejected(): void
    {
        Course::create([
            'code' => 'OTHER-01',
            'name' => 'Kursus Lain',
            'fee' => 300000,
            'quota' => 25,
            'start_date' => '2026-11-25',
            'status' => 'open',
        ]);

        $course2 = Course::create([
            'code' => 'COURSE-02',
            'name' => 'Kursus Kedua',
            'fee' => 400000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ]);

        $payload = [
            'code' => 'OTHER-01',
            'name' => 'Mencoba Pakai Kode Lain',
            'fee' => 450000,
            'quota' => 20,
            'start_date' => '2026-12-01',
            'status' => 'open',
        ];

        $response = $this->put(route('courses.update', $course2), $payload);

        $response->assertSessionHasErrors(['code']);
        $this->assertDatabaseHas('courses', [
            'id' => $course2->id,
            'code' => 'COURSE-02',
        ]);
    }

    public function test_case_14_update_normal_updates_data_and_redirects_to_show(): void
    {
        $course = Course::create([
            'code' => 'LRV-101',
            'name' => 'Laravel Dasar',
            'description' => 'Materi dasar Laravel.',
            'fee' => 500000,
            'quota' => 20,
            'start_date' => '2026-12-05',
            'status' => 'open',
        ]);

        $payload = [
            'code' => 'LRV-101',
            'name' => 'Laravel Advanced & Architecture',
            'description' => 'Materi lanjutan arsitektur Laravel.',
            'fee' => 750000,
            'quota' => 15,
            'start_date' => '2026-12-10',
            'status' => 'closed',
        ];

        $response = $this->put(route('courses.update', $course), $payload);

        $response->assertRedirect(route('courses.show', $course));
        $response->assertSessionHas('success', 'Kursus berhasil diperbarui.');

        $freshCourse = $course->fresh();
        $this->assertEquals('Laravel Advanced & Architecture', $freshCourse->name);
        $this->assertEquals(750000, $freshCourse->fee);
        $this->assertEquals(15, $freshCourse->quota);
        $this->assertEquals('closed', $freshCourse->status);
    }

    public function test_case_15_delete_course_removes_record_and_redirects_to_index(): void
    {
        $dummy = Course::create([
            'code' => 'DUMMY-DEL',
            'name' => 'Kursus Dummy Khusus Hapus',
            'description' => 'Data sementara untuk pengujian delete.',
            'fee' => 100000,
            'quota' => 10,
            'start_date' => '2026-12-31',
            'status' => 'draft',
        ]);

        $this->assertEquals(1, Course::where('code', 'DUMMY-DEL')->count());

        $response = $this->delete(route('courses.destroy', $dummy));

        $response->assertRedirect(route('courses.index'));
        $response->assertSessionHas('success', 'Kursus berhasil dihapus.');

        $this->assertEquals(0, Course::where('code', 'DUMMY-DEL')->count());
        $this->assertDatabaseMissing('courses', ['code' => 'DUMMY-DEL']);
    }
}
