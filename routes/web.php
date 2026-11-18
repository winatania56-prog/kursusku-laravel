<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

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

Route::resource('courses', CourseController::class);

Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store']);

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
