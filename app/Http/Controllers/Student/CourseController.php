<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;

class CourseController extends Controller
{
    public function enroll(Course $course)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('login')->with('error', 'Please log in as a student to enroll.');
        }

        if ($student->courses()->where('course_id', $course->id)->exists()) {
            return back()->with('info', 'You are already enrolled in ' . $course->name . '.');
        }

        $student->courses()->attach($course->id, [
            'status'              => 'active',
            'progress_percentage' => 0,
            'enrolled_at'         => now(),
        ]);

        $course->increment('enrolled_count');

        return back()->with('success', 'You have successfully enrolled in ' . $course->name . '! Book a session to start learning.');
    }

    public function unenroll(Course $course)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return back()->with('error', 'Not authorised.');
        }

        $student->courses()->detach($course->id);
        $course->decrement('enrolled_count');

        return back()->with('success', 'You have been unenrolled from ' . $course->name . '.');
    }
}
