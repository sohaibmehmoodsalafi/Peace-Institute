<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\Teacher;
use App\Services\EarningsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private EarningsService $earningsService) {}

    public function index()
    {
        $teacher     = auth()->user()->teacher;
        $teacherIds  = Teacher::idsForUserId(auth()->id());

        $upcoming = Booking::query()
            ->whereIn('teacher_id', $teacherIds)
            ->with(['student.user', 'course'])
            ->whereIn('status', ['pending', 'approved'])
            ->where('scheduled_at', '>=', now())
            ->orderBy('scheduled_at')
            ->take(5)
            ->get();

        $pendingApproval = Booking::query()
            ->whereIn('teacher_id', $teacherIds)
            ->where('status', 'pending')
            ->count();

        $monthlySummary = $this->earningsService->getMonthlySummary(
            $teacher, now()->year, now()->month
        );

        $recentSessions = ClassSession::query()
            ->whereIn('teacher_id', $teacherIds)
            ->with(['booking.student.user'])
            ->where('status', 'completed')
            ->latest()
            ->take(5)
            ->get();

        $activeEnrollments = Enrollment::query()
            ->whereIn('teacher_id', $teacherIds)
            ->where('status', 'active')
            ->count();

        $pendingEnrollments = Enrollment::query()
            ->whereIn('teacher_id', $teacherIds)
            ->where('status', 'pending')
            ->count();

        return view('teacher.dashboard', compact(
            'teacher', 'upcoming', 'pendingApproval', 'monthlySummary', 'recentSessions',
            'activeEnrollments', 'pendingEnrollments'
        ));
    }

    public function pending()
    {
        return view('teacher.pending');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'bio'              => 'nullable|string|max:2000',
            'specialization'   => 'nullable|string|max:255',
            'experience_years' => 'nullable|integer|min:0',
            'education'        => 'nullable|string|max:255',
            'certification'    => 'nullable|string|max:255',
            'subjects'         => 'nullable|array',
            'language'         => 'nullable|string',
        ]);

        $teacher = auth()->user()->teacher;
        $teacher->update($request->only([
            'bio', 'specialization', 'experience_years',
            'education', 'certification', 'subjects', 'language',
        ]));

        auth()->user()->update($request->only(['name', 'phone', 'timezone']));

        return back()->with('success', 'Profile updated successfully.');
    }
}
