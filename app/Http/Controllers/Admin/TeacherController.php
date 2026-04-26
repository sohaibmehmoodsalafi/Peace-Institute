<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function index(Request $request)
    {
        $teachers = Teacher::with('user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) =>
                $u->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->withCount('completedSessions')
            ->paginate(20);

        return view('admin.teachers.index', compact('teachers'));
    }

    public function show(Teacher $teacher)
    {
        $teacher->load(['user', 'earnings', 'reviews.student.user', 'bookings.course']);
        return view('admin.teachers.show', compact('teacher'));
    }

    public function approve(Teacher $teacher)
    {
        $teacher->update(['status' => 'approved']);
        $teacher->user->update(['is_active' => true]);
        return back()->with('success', "Teacher {$teacher->user->name} has been approved.");
    }

    public function suspend(Teacher $teacher)
    {
        $teacher->update(['status' => 'suspended']);
        $teacher->user->update(['is_active' => false]);
        return back()->with('success', "Teacher {$teacher->user->name} has been suspended.");
    }

    public function updateRate(Request $request, Teacher $teacher)
    {
        $request->validate(['hourly_rate' => 'required|numeric|min:0|max:999.99']);
        $teacher->update(['hourly_rate' => $request->hourly_rate]);
        return back()->with('success', 'Hourly rate updated successfully.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete();
        return redirect()->route('admin.teachers.index')
            ->with('success', 'Teacher deleted.');
    }
}
