<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    // ── Show enrollment form ──────────────────────────────────────
    public function create(Request $request)
    {
        $teachers = Teacher::with('user')->where('status', 'approved')->get();
        $courses  = Course::where('is_active', true)->orderBy('name')->get();

        // Pre-select teacher if coming from teacher profile
        $teacher = null;
        if ($request->teacher_id) {
            $teacher = Teacher::with(['user', 'availabilities'])
                ->where('status', 'approved')
                ->find($request->teacher_id);
        }

        return view('student.enroll', compact('teachers', 'courses', 'teacher'));
    }

    // ── Submit enrollment request ─────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'teacher_id'      => 'required|exists:teachers,id',
            'course_id'       => 'required|exists:courses,id',
            'selected_days'   => 'required|array|min:1|max:7',
            'selected_days.*' => 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'preferred_time'  => 'nullable|string|max:10',
            'notes'           => 'nullable|string|max:500',
        ]);

        $studentIds = Student::idsForUserId(auth()->id());
        abort_if(count($studentIds) === 0, 403, 'Student profile missing.');

        // Prevent duplicate active enrollment for same teacher+course (any profile row).
        $existing = Enrollment::query()
            ->whereIn('student_id', $studentIds)
            ->where('teacher_id', $request->teacher_id)
            ->where('course_id', $request->course_id)
            ->whereIn('status', ['pending', 'active'])
            ->first();

        if ($existing) {
            return back()->with('error', 'You already have a pending or active enrollment for this teacher and course.');
        }

        $course = Course::find($request->course_id);

        Enrollment::create([
            'student_id'       => $studentIds[0],
            'teacher_id'       => $request->teacher_id,
            'course_id'        => $request->course_id,
            'selected_days'    => $request->selected_days,
            'classes_per_week' => count($request->selected_days),
            'preferred_time'   => $request->preferred_time,
            'monthly_fee'      => $course->monthly_price ?? 30,
            'status'           => 'pending',
            'notes'            => $request->notes,
        ]);

        return redirect()->route('student.enrollments.index')
            ->with('success', 'Enrollment request submitted! Our team will contact you within 24 hours.');
    }

    // ── Student's enrollment list ─────────────────────────────────
    public function index()
    {
        $enrollments = Enrollment::with(['teacher.user', 'course'])
            ->whereIn('student_id', Student::idsForUserId(auth()->id()))
            ->latest()
            ->get();

        return view('student.my-enrollments', compact('enrollments'));
    }

    // ── Cancel enrollment (pending only) ──────────────────────────
    public function cancel(Enrollment $enrollment)
    {
        // Ownership check — cast to int to avoid strict type mismatch
        $myIds = array_map('intval', Student::idsForUserId(auth()->id()));
        abort_unless(in_array((int) $enrollment->student_id, $myIds), 403);
        abort_if($enrollment->status !== 'pending', 403, 'This enrollment cannot be cancelled.');

        $enrollment->update(['status' => 'cancelled']);

        return back()->with('success', 'Enrollment request cancelled.');
    }
}
