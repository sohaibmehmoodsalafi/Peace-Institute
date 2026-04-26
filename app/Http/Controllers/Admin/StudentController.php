<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('user')
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) =>
                $u->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%')
            ))
            ->withCount('bookings')
            ->paginate(20);

        return view('admin.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load(['user', 'bookings.teacher.user', 'payments', 'courses']);
        return view('admin.students.show', compact('student'));
    }

    public function toggleStatus(Student $student)
    {
        $student->user->update(['is_active' => !$student->user->is_active]);
        $status = $student->user->is_active ? 'activated' : 'suspended';
        return back()->with('success', "Student {$status} successfully.");
    }
}
