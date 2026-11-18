<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Database\Seeders\CourseSeeder;
use Database\Seeders\ParticipantSeeder;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class RelationalEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        (new CourseSeeder())->run();
        (new ParticipantSeeder())->run();
    }

    public function test_enrollments_index_page_loads_and_displays_empty_state_initially(): void
    {
        $this->assertTrue(Route::has('enrollments.index'));

        $response = $this->get(route('enrollments.index'));

        $response->assertStatus(200);
        $response->assertViewIs('enrollments.index');
        $response->assertSee('Laporan Enrollment KursusKu');
        $response->assertSee('Belum ada enrollment.');
    }

    public function test_enrollments_create_page_loads_and_displays_participants_and_open_courses_only(): void
    {
        $this->assertTrue(Route::has('enrollments.create'));

        // Buat satu kursus berstatus closed untuk memastikan tidak muncul
        Course::create([
            'code' => 'CLS-999',
            'name' => 'Closed Course Secret',
            'price' => 500000,
            'quota' => 10,
            'status' => 'closed',
            'start_date' => '2026-12-01',
        ]);

        $response = $this->get(route('enrollments.create'));

        $response->assertStatus(200);
        $response->assertViewIs('enrollments.create');
        $response->assertSee('Pendaftaran Enrollment Kursus');
        $response->assertSee('Alya Rahma');
        $response->assertSee('alya@example.test');
        $response->assertSee('Bima Putra');
        $response->assertSee('Citra Nabila');
        $response->assertSee('WEB-101');
        $response->assertSee('Web Dasar');
        $response->assertDontSee('CLS-999');
        $response->assertDontSee('Closed Course Secret');
    }

    public function test_enrollments_store_creates_enrollment_successfully(): void
    {
        $participant = Participant::where('email', 'alya@example.test')->firstOrFail();
        $course = Course::where('status', 'open')->firstOrFail();

        $response = $this->post(route('enrollments.store'), [
            'participant_id' => $participant->id,
            'course_id' => $course->id,
        ]);

        $response->assertRedirect(route('enrollments.index'));
        $response->assertSessionHas('success', 'Enrollment berhasil disimpan.');

        $this->assertDatabaseHas('enrollments', [
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        // Verifikasi pada halaman index
        $indexResponse = $this->get(route('enrollments.index'));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee($participant->name);
        $indexResponse->assertSee($course->name);
        $indexResponse->assertSee('registered');
    }

    public function test_enrollments_store_validates_required_fields(): void
    {
        $response = $this->post(route('enrollments.store'), []);

        $response->assertSessionHasErrors(['participant_id', 'course_id']);
    }

    public function test_enrollments_store_validates_existence_of_participant_and_course(): void
    {
        $response = $this->post(route('enrollments.store'), [
            'participant_id' => 99999,
            'course_id' => 88888,
        ]);

        $response->assertSessionHasErrors(['participant_id', 'course_id']);
    }

    public function test_enrollments_store_rejects_duplicate_enrollment_at_validation_layer(): void
    {
        $participant = Participant::firstOrFail();
        $course = Course::where('status', 'open')->firstOrFail();

        Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        // Percobaan pendaftaran kedua untuk kombinasi yang sama
        $response = $this->post(route('enrollments.store'), [
            'participant_id' => $participant->id,
            'course_id' => $course->id,
        ]);

        $response->assertSessionHasErrors([
            'course_id' => 'Participant ini sudah terdaftar pada course tersebut.',
        ]);

        $this->assertCount(1, Enrollment::where('participant_id', $participant->id)->where('course_id', $course->id)->get());
    }

    public function test_database_composite_unique_constraint_rejects_duplicate_at_db_layer(): void
    {
        $participant = Participant::firstOrFail();
        $course = Course::firstOrFail();

        Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        $this->expectException(QueryException::class);

        // Bypass validation by directly inserting via Model/DB
        Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);
    }

    public function test_eloquent_relationships_work_correctly(): void
    {
        $participant = Participant::firstOrFail();
        $course = Course::firstOrFail();

        $enrollment = Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        // BelongsTo
        $this->assertEquals($participant->id, $enrollment->participant->id);
        $this->assertEquals($course->id, $enrollment->course->id);

        // HasMany
        $this->assertTrue($participant->enrollments->contains($enrollment));
        $this->assertTrue($course->enrollments->contains($enrollment));

        // BelongsToMany with pivot
        $this->assertTrue($participant->courses->contains($course));
        $this->assertTrue($course->participants->contains($participant));
        $this->assertEquals('registered', $participant->courses->first()->pivot->status);
    }

    public function test_cascade_delete_when_course_is_deleted(): void
    {
        $participant = Participant::create([
            'name' => 'Peserta Cascade',
            'email' => 'cascade_p@example.test',
        ]);

        $course = Course::create([
            'code' => 'CSD-001',
            'name' => 'Course Cascade Test',
            'price' => 100000,
            'quota' => 10,
            'status' => 'open',
            'start_date' => '2026-11-20',
        ]);

        $enrollment = Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        $enrollmentId = $enrollment->id;

        // Hapus kursus
        $course->delete();

        // Enrollment harus otomatis terhapus karena foreign key cascade
        $this->assertDatabaseMissing('enrollments', ['id' => $enrollmentId]);
        // Participant tetap ada
        $this->assertDatabaseHas('participants', ['id' => $participant->id]);
    }

    public function test_cascade_delete_when_participant_is_deleted(): void
    {
        $participant = Participant::create([
            'name' => 'Peserta Cascade 2',
            'email' => 'cascade_p2@example.test',
        ]);

        $course = Course::create([
            'code' => 'CSD-002',
            'name' => 'Course Cascade Test 2',
            'price' => 100000,
            'quota' => 10,
            'status' => 'open',
            'start_date' => '2026-11-20',
        ]);

        $enrollment = Enrollment::create([
            'participant_id' => $participant->id,
            'course_id' => $course->id,
            'status' => 'registered',
        ]);

        $enrollmentId = $enrollment->id;

        // Hapus peserta
        $participant->delete();

        // Enrollment harus otomatis terhapus karena foreign key cascade
        $this->assertDatabaseMissing('enrollments', ['id' => $enrollmentId]);
        // Course tetap ada
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }
}
