<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    /**
     * Display the User Dashboard.
     */
    public function index()
    {
        $user = Auth::user();
        $participant = Participant::where('email', $user->email)->first();

        $enrollments = $participant
            ? Enrollment::where('participant_id', $participant->id)->with('course')->latest()->take(5)->get()
            : collect();

        $totalEnrollments = $participant
            ? Enrollment::where('participant_id', $participant->id)->count()
            : 0;

        return view('user.dashboard', compact('user', 'participant', 'enrollments', 'totalEnrollments'));
    }

    /**
     * Display user's enrollment history.
     */
    public function enrollments()
    {
        $user = Auth::user();
        $participant = Participant::where('email', $user->email)->first();

        $enrollments = $participant
            ? Enrollment::where('participant_id', $participant->id)->with('course')->latest()->paginate(10)
            : new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10);

        return view('user.enrollments', compact('user', 'participant', 'enrollments'));
    }
}
