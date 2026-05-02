<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $student   = auth()->user()->student;
        $studentIds = Student::idsForUserId(auth()->id());

        $upcomingBookings = Booking::query()
            ->whereIn('student_id', $studentIds)
            ->with(['teacher.user', 'course'])
            ->whereIn('status', ['pending', 'approved'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $recentHistory = Booking::query()
            ->whereIn('student_id', $studentIds)
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
