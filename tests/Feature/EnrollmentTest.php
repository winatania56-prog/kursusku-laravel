<?php

namespace Tests\Feature;

use App\Models\Course;
use Database\Seeders\CourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class EnrollmentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        (new CourseSeeder())->run();
    }

    public function test_enrollment_create_route_exists_and_returns_ok(): void
    {
        $this->assertTrue(Route::has('pendaftaran.create'));

        $response = $this->get(route('pendaftaran.create'));

        $response->assertStatus(200);
        $response->assertViewIs('pendaftaran.create');
        $response->assertViewHas('courses');

        $courses = $response->viewData('courses');
        $this->assertGreaterThanOrEqual(3, $courses->count());
        $this->assertEquals('WEB-101', $courses[0]->code);
        $this->assertEquals('Web Dasar', $courses[0]->name);
    }

    public function test_enrollment_form_renders_required_elements_and_csrf(): void
    {
        $response = $this->get(route('pendaftaran.create'));

        $response->assertSee('name="name"', false);
        $response->assertSee('name="email"', false);
        $response->assertSee('name="course_id"', false);
        $response->assertSee('id="course-availability"', false);
        $response->assertSee('Pilih kursus untuk melihat kuota dan status ketersediaan.');
        $response->assertSee('Kirim Pendaftaran');
        $response->assertSee(route('pendaftaran.preview'));
    }

    public function test_enrollment_preview_succeeds_with_valid_data(): void
    {
        $response = $this->post(route('pendaftaran.preview'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'course_id' => 1,
        ]);

        $response->assertRedirect(route('pendaftaran.create'));
        $response->assertSessionHas('success');
        $response->assertSessionHasInput('name', 'Budi Santoso');
        $response->assertSessionHasInput('email', 'budi@example.com');
        $response->assertSessionHasInput('course_id', 1);

        $followResponse = $this->get(route('pendaftaran.create'));
        $followResponse->assertStatus(200);
    }

    public function test_enrollment_preview_fails_with_invalid_email(): void
    {
        $response = $this->post(route('pendaftaran.preview'), [
            'name' => 'Budi Santoso',
            'email' => 'bukan-email-valid',
            'course_id' => 1,
        ]);

        $response->assertSessionHasErrors(['email']);
        $response->assertSessionHasInput('name', 'Budi Santoso');
    }

    public function test_enrollment_preview_fails_with_short_name(): void
    {
        $response = $this->post(route('pendaftaran.preview'), [
            'name' => 'Al',
            'email' => 'al@example.com',
            'course_id' => 1,
        ]);

        $response->assertSessionHasErrors(['name']);
    }

    public function test_enrollment_preview_fails_with_missing_course_id(): void
    {
        $response = $this->post(route('pendaftaran.preview'), [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
        ]);

        $response->assertSessionHasErrors(['course_id']);
    }

    public function test_courses_availability_returns_json_for_valid_courses(): void
    {
        $this->assertTrue(Route::has('courses.availability'));

        $course1 = Course::where('code', 'WEB-101')->firstOrFail();
        $res1 = $this->get(route('courses.availability', ['course' => $course1->id]));
        $res1->assertStatus(200);
        $res1->assertHeader('Content-Type', 'application/json');
        $res1->assertExactJson([
            'id' => $course1->id,
            'code' => 'WEB-101',
            'name' => 'Web Dasar',
            'quota' => 25,
            'status' => 'open',
        ]);

        $course2 = Course::where('code', 'PHP-101')->firstOrFail();
        $res2 = $this->get(route('courses.availability', ['course' => $course2->id]));
        $res2->assertStatus(200);
        $res2->assertExactJson([
            'id' => $course2->id,
            'code' => 'PHP-101',
            'name' => 'PHP Fundamental',
            'quota' => 25,
            'status' => 'open',
        ]);
    }

    public function test_courses_availability_returns_404_for_unknown_or_invalid_id(): void
    {
        $resNotFound = $this->get(route('courses.availability', ['course' => 999]));
        $resNotFound->assertStatus(404);

        $resInvalid = $this->get('/courses/abc/availability');
        $resInvalid->assertStatus(404);
    }
}
