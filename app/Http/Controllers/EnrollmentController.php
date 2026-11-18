<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EnrollmentController extends Controller
{
    /**
     * Display a listing of the enrollments.
     */
    public function index()
    {
        $enrollments = Enrollment::with(['participant', 'course'])->latest()->get();

        return view('enrollments.index', compact('enrollments'));
    }

    /**
     * Show the form for creating a new enrollment.
     */
    public function create()
    {
        $participants = Participant::orderBy('name')->get();
        $courses = Course::where('status', 'open')->orderBy('name')->get();

        return view('enrollments.create', compact('participants', 'courses'));
    }

    /**
     * Store a newly created enrollment in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'participant_id' => ['required', 'exists:participants,id'],
            'course_id' => [
                'required',
                'exists:courses,id',
                Rule::unique('enrollments', 'course_id')->where(function ($query) use ($request) {
                    return $query->where('participant_id', $request->participant_id);
                }),
            ],
        ], [
            'course_id.unique' => 'Participant ini sudah terdaftar pada course tersebut.',
        ]);

        Enrollment::create([
            'participant_id' => $request->participant_id,
            'course_id' => $request->course_id,
            'status' => 'registered',
        ]);

        return to_route('enrollments.index')->with('success', 'Enrollment berhasil disimpan.');
    }
}
