<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $student = auth()->user()->student;

        $upcomingBookings = $student->upcomingBookings()
            ->with(['teacher.user', 'course'])
            ->take(5)
            ->get();

        $recentHistory = $student->bookings()
            ->with(['teacher.user', 'course'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        $enrolledCourses = $student->courses()
            ->wherePivot('status', 'active')
            ->get();

        $stats = [
            'total_sessions'     => $student->total_sessions,
            'completed_sessions' => $student->completed_sessions,
            'total_spent'        => $student->total_spent,
            'enrolled_courses'   => $enrolledCourses->count(),
        ];

        return view('student.dashboard', compact(
            'student', 'upcomingBookings', 'recentHistory', 'enrolledCourses', 'stats'
        ));
    }
}
