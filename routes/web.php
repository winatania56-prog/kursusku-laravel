<?php

use App\Http\Controllers\Admin\ParticipantController as AdminParticipantController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserDashboardController;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest, User, Admin)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show')->whereNumber('course');

Route::get('/courses/{course}/availability', function ($course) {
    if (!is_numeric($course)) {
        abort(404, 'Kursus tidak ditemukan.');
    }

    $courseModel = Course::find((int) $course);

    if (!$courseModel) {
        abort(404, 'Kursus tidak ditemukan.');
    }

    return response()->json([
        'id' => $courseModel->id,
        'code' => $courseModel->code,
        'name' => $courseModel->name,
        'quota' => (int) $courseModel->quota,
        'status' => $courseModel->status,
    ]);
})->name('courses.availability');

Route::get('/pendaftaran', function () {
    $courses = Course::orderBy('id')->get();

    return view('pendaftaran.create', compact('courses'));
})->name('pendaftaran.create');

Route::post('/pendaftaran/preview', function (Request $request) {
    $request->validate([
        'name' => 'required|string|min:3|max:100',
        'email' => 'required|email',
        'course_id' => 'required|integer',
    ]);

    return redirect()
        ->route('pendaftaran.create')
        ->with('success', 'Data pendaftaran berhasil divalidasi.')
        ->withInput();
})->name('pendaftaran.preview');

/*
|--------------------------------------------------------------------------
| Guest Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes (All Logged-In Users)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    Route::get('/my-enrollments', [UserDashboardController::class, 'enrollments'])->name('user.enrollments');
});

/*
|--------------------------------------------------------------------------
| User-Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
});

/*
|--------------------------------------------------------------------------
| Admin-Only Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    // Manajemen Kursus (Create, Store, Edit, Update, Destroy)
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');

    // Manajemen Enrollment (Laporan & Tambah Relasi)
    Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store']);

    // Manajemen Pengguna
    Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
    Route::patch('/admin/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('admin.users.role');

    // Manajemen Peserta
    Route::get('/admin/participants', [AdminParticipantController::class, 'index'])->name('admin.participants.index');
});
