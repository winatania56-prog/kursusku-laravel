<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Matriks Hak Akses:
     * - Melihat Course: Guest (✓), User (✓), Admin (✓)
     * - Pencarian Course: Guest (✓), User (✓), Admin (✓)
     * - Filter Course: Guest (✓), User (✓), Admin (✓)
     * - Pagination: Guest (✓), User (✓), Admin (✓)
     * - Tambah Course: Guest (✗), User (✗), Admin (✓)
     * - Edit Course: Guest (✗), User (✗), Admin (✓)
     * - Hapus Course: Guest (✗), User (✗), Admin (✓)
     * - Dashboard User: Guest (✗), User (✓), Admin (✗)
     * - Dashboard Admin: Guest (✗), User (✗), Admin (✓)
     * - Edit Profil Sendiri: Guest (✗), User (✓), Admin (✓)
     * - Lihat Enrollment Sendiri: Guest (✗), User (✓), Admin (✓)
     * - Kelola Seluruh Enrollment: Guest (✗), User (✗), Admin (✓)
     * - Kelola Pengguna: Guest (✗), User (✗), Admin (✓)
     */

    public function test_guest_can_only_view_search_filter_and_paginate_courses(): void
    {
        $course = Course::create([
            'code' => 'WEB-001',
            'name' => 'Web Development Dasar',
            'fee' => 150000,
            'quota' => 30,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Melihat index course
        $this->get(route('courses.index'))->assertStatus(200);

        // Melihat detail course
        $this->get(route('courses.show', $course))->assertStatus(200);

        // Pencarian course
        $this->get(route('courses.index', ['search' => 'Web']))->assertStatus(200);

        // Filter status course
        $this->get(route('courses.index', ['status' => 'open']))->assertStatus(200);

        // Pagination
        $this->get(route('courses.index', ['page' => 1]))->assertStatus(200);
    }

    public function test_guest_is_forbidden_from_admin_and_user_features_and_redirects_to_login(): void
    {
        $course = Course::create([
            'code' => 'WEB-002',
            'name' => 'Web Development Lanjutan',
            'fee' => 250000,
            'quota' => 20,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Tambah Course
        $this->get(route('courses.create'))->assertRedirect(route('login'));
        $this->post(route('courses.store'), [
            'code' => 'NEW-001',
            'name' => 'Test Course',
            'fee' => 100000,
            'quota' => 10,
            'status' => 'open',
        ])->assertRedirect(route('login'));

        // Edit Course
        $this->get(route('courses.edit', $course))->assertRedirect(route('login'));
        $this->put(route('courses.update', $course), [
            'code' => 'WEB-002',
            'name' => 'Updated Web Course',
            'fee' => 250000,
            'quota' => 20,
            'status' => 'open',
        ])->assertRedirect(route('login'));

        // Hapus Course
        $this->delete(route('courses.destroy', $course))->assertRedirect(route('login'));

        // Dashboard
        $this->get(route('dashboard'))->assertRedirect(route('login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));

        // Profil Sendiri
        $this->get(route('profile.edit'))->assertRedirect(route('login'));

        // Enrollment Sendiri
        $this->get(route('user.enrollments'))->assertRedirect(route('login'));

        // Kelola Seluruh Enrollment
        $this->get(route('enrollments.index'))->assertRedirect(route('login'));
        $this->get(route('enrollments.create'))->assertRedirect(route('login'));

        // Kelola Pengguna
        $this->get(route('admin.users.index'))->assertRedirect(route('login'));
        $this->get(route('admin.participants.index'))->assertRedirect(route('login'));
    }

    public function test_user_can_access_user_features_and_view_courses(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $course = Course::create([
            'code' => 'WEB-003',
            'name' => 'Web Desain UI/UX',
            'fee' => 175000,
            'quota' => 25,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Melihat, mencari, memfilter Course
        $this->actingAs($user)->get(route('courses.index'))->assertStatus(200);
        $this->actingAs($user)->get(route('courses.show', $course))->assertStatus(200);
        $this->actingAs($user)->get(route('courses.index', ['search' => 'Desain']))->assertStatus(200);
        $this->actingAs($user)->get(route('courses.index', ['status' => 'open']))->assertStatus(200);

        // Dashboard User
        $this->actingAs($user)->get(route('dashboard'))->assertStatus(200);

        // Edit Profil Sendiri
        $this->actingAs($user)->get(route('profile.edit'))->assertStatus(200);

        // Lihat Enrollment Sendiri
        $this->actingAs($user)->get(route('user.enrollments'))->assertStatus(200);
    }

    public function test_user_is_strictly_forbidden_from_admin_management_with_403(): void
    {
        $user = User::factory()->create(['role' => 'user']);
        $course = Course::create([
            'code' => 'WEB-004',
            'name' => 'Backend Development',
            'fee' => 300000,
            'quota' => 15,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Dilarang Tambah Course
        $this->actingAs($user)->get(route('courses.create'))->assertForbidden();
        $this->actingAs($user)->post(route('courses.store'), [
            'code' => 'NEW-999',
            'name' => 'Illegal Course',
            'fee' => 100000,
            'quota' => 10,
            'status' => 'open',
        ])->assertForbidden();

        // Dilarang Edit Course
        $this->actingAs($user)->get(route('courses.edit', $course))->assertForbidden();
        $this->actingAs($user)->put(route('courses.update', $course), [
            'code' => 'WEB-004',
            'name' => 'Updated Name',
            'fee' => 300000,
            'quota' => 15,
            'status' => 'open',
        ])->assertForbidden();

        // Dilarang Hapus Course
        $this->actingAs($user)->delete(route('courses.destroy', $course))->assertForbidden();

        // Dilarang Dashboard Admin
        $this->actingAs($user)->get(route('admin.dashboard'))->assertForbidden();

        // Dilarang Kelola Seluruh Enrollment
        $this->actingAs($user)->get(route('enrollments.index'))->assertForbidden();
        $this->actingAs($user)->get(route('enrollments.create'))->assertForbidden();

        // Dilarang Kelola Pengguna & Peserta
        $this->actingAs($user)->get(route('admin.users.index'))->assertForbidden();
        $this->actingAs($user)->get(route('admin.participants.index'))->assertForbidden();
    }

    public function test_admin_has_full_management_access(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $course = Course::create([
            'code' => 'WEB-005',
            'name' => 'DevOps Cloud',
            'fee' => 400000,
            'quota' => 12,
            'status' => 'open',
            'start_date' => '2026-12-01',
        ]);

        // Melihat, mencari, filter Course
        $this->actingAs($admin)->get(route('courses.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('courses.show', $course))->assertStatus(200);

        // Tambah Course
        $this->actingAs($admin)->get(route('courses.create'))->assertStatus(200);

        // Edit Course
        $this->actingAs($admin)->get(route('courses.edit', $course))->assertStatus(200);

        // Dashboard Admin
        $this->actingAs($admin)->get(route('admin.dashboard'))->assertStatus(200);

        // Profil Sendiri
        $this->actingAs($admin)->get(route('profile.edit'))->assertStatus(200);

        // Lihat Enrollment Sendiri
        $this->actingAs($admin)->get(route('user.enrollments'))->assertStatus(200);

        // Kelola Seluruh Enrollment
        $this->actingAs($admin)->get(route('enrollments.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('enrollments.create'))->assertStatus(200);

        // Kelola Pengguna & Peserta
        $this->actingAs($admin)->get(route('admin.users.index'))->assertStatus(200);
        $this->actingAs($admin)->get(route('admin.participants.index'))->assertStatus(200);

        // Admin dilarang membuka Dashboard User reguler (Dashboard User khusus role:user)
        $this->actingAs($admin)->get(route('dashboard'))->assertForbidden();
    }
}
