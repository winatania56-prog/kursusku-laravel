<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseUiRoleVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_does_not_see_add_edit_or_delete_buttons_in_course_views(): void
    {
        $course = Course::create([
            'code' => 'UI-001',
            'name' => 'UI/UX Fundamentals',
            'fee' => 200000,
            'quota' => 20,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Halaman Index
        $responseIndex = $this->get(route('courses.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Daftar Kursus');
        $responseIndex->assertSee('Detail');
        $responseIndex->assertDontSee('Tambah Kursus');
        $responseIndex->assertDontSee('Edit</a>', false);
        $responseIndex->assertDontSee('Hapus</button>', false);

        // Halaman Show
        $responseShow = $this->get(route('courses.show', $course));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('UI/UX Fundamentals');
        $responseShow->assertDontSee('Edit Kursus');
    }

    public function test_user_does_not_see_add_edit_or_delete_buttons_in_course_views(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $course = Course::create([
            'code' => 'UI-002',
            'name' => 'React Native Mobile',
            'fee' => 350000,
            'quota' => 15,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Halaman Index
        $responseIndex = $this->actingAs($user)->get(route('courses.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Detail');
        $responseIndex->assertDontSee('Tambah Kursus');
        $responseIndex->assertDontSee('Edit</a>', false);
        $responseIndex->assertDontSee('Hapus</button>', false);

        // Halaman Show
        $responseShow = $this->actingAs($user)->get(route('courses.show', $course));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('React Native Mobile');
        $responseShow->assertDontSee('Edit Kursus');
    }

    public function test_admin_sees_all_add_edit_and_delete_buttons_in_course_views(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::create([
            'code' => 'UI-003',
            'name' => 'Golang Backend Architecture',
            'fee' => 450000,
            'quota' => 10,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Halaman Index
        $responseIndex = $this->actingAs($admin)->get(route('courses.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('Detail');
        $responseIndex->assertSee('Tambah Kursus');
        $responseIndex->assertSee('Edit');
        $responseIndex->assertSee('Hapus');

        // Halaman Show
        $responseShow = $this->actingAs($admin)->get(route('courses.show', $course));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Edit Kursus');
    }
}
