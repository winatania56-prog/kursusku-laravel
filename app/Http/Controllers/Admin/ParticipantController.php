<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Participant;

class ParticipantController extends Controller
{
    /**
     * Display a listing of participants.
     */
    public function index()
    {
        $participants = Participant::withCount('enrollments')
            ->latest()
            ->paginate(10);

        return view('admin.participants.index', compact('participants'));
    }
}
