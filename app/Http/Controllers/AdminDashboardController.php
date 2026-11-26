<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Participant;
use App\Models\User;

class AdminDashboardController extends Controller
{
    /**
     * Display the Admin Dashboard.
     */
    public function index()
    {
        $totalCourses = Course::count();
        $totalParticipants = Participant::count();
        $totalEnrollments = Enrollment::count();
        $totalUsers = User::count();

        $statusCounts = [
            'open' => Course::where('status', 'open')->count(),
            'closed' => Course::where('status', 'closed')->count(),
            'draft' => Course::where('status', 'draft')->count(),
        ];

        $recentEnrollments = Enrollment::with(['participant', 'course'])->latest()->take(5)->get();
        $recentCourses = Course::latest('id')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalCourses',
            'totalParticipants',
            'totalEnrollments',
            'totalUsers',
            'statusCounts',
            'recentEnrollments',
            'recentCourses'
        ));
    }
}
