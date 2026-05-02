<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = Enrollment::with(['student.user', 'teacher.user', 'course'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->teacher_id, fn($q) => $q->where('teacher_id', $request->teacher_id))
            ->latest()
            ->paginate(25);

        $counts = [
            'pending'   => Enrollment::where('status', 'pending')->count(),
            'active'    => Enrollment::where('status', 'active')->count(),
            'paused'    => Enrollment::where('status', 'paused')->count(),
            'cancelled' => Enrollment::where('status', 'cancelled')->count(),
        ];

        return view('admin.enrollments.index', compact('enrollments', 'counts'));
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student.user', 'teacher.user', 'course', 'approvedBy']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function approve(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'admin_note' => 'nullable|string|max:500',
            'start_date' => 'nullable|date',
        ]);

        $enrollment->update([
            'status'      => 'active',
            'admin_note'  => $request->admin_note,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'start_date'  => $request->start_date ?? now()->addDay()->toDateString(),
        ]);

        return back()->with('success', 'Enrollment approved. Student is now active.');
    }

    public function reject(Request $request, Enrollment $enrollment)
    {
        $request->validate(['admin_note' => 'required|string|max:500']);

        $enrollment->update([
            'status'     => 'cancelled',
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Enrollment rejected.');
    }

    public function pause(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'paused']);
        return back()->with('success', 'Enrollment paused.');
    }

    public function resume(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'active']);
        return back()->with('success', 'Enrollment resumed.');
    }

    public function cancel(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'cancelled']);
        return back()->with('success', 'Enrollment cancelled.');
    }
}
