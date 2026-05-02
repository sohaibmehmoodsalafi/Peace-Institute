<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Review;
use App\Models\Teacher;

class HomeController extends Controller
{
    public function index()
    {
        $featuredTeachers = Teacher::with('user')
            ->where('status', 'approved')
            ->where('is_featured', true)
            ->whereHas('user')
            ->take(6)
            ->get();

        $courses = Course::where('is_active', true)->get();

        $recentReviews = Review::with(['student.user', 'teacher.user'])
            ->where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'teachers'  => max(10, Teacher::where('status', 'approved')->count()),
            'students'  => max(25, \App\Models\Student::count()),
            'sessions'  => max(500, \App\Models\ClassSession::where('status', 'completed')->count()),
            'countries' => 25,
        ];

        return view('welcome', compact('featuredTeachers', 'courses', 'recentReviews', 'stats'));
    }

    public function teachers()
    {
        $teachers = Teacher::with('user')
            ->where('status', 'approved')
            ->whereHas('user')          // only teachers with valid user
            ->paginate(12);

        $courses = Course::where('is_active', true)->get();

        return view('teachers.index', compact('teachers', 'courses'));
    }

    public function teacherProfile(Teacher $teacher)
    {
        // Guard: if user is missing, redirect back
        if (!$teacher->user) {
            return redirect()->route('teachers')->with('error', 'Teacher not found.');
        }
        $teacher->load(['user', 'reviews.student.user', 'availabilities']);
        $courses = Course::where('is_active', true)->get();
        return view('teachers.show', compact('teacher', 'courses'));
    }

    public function courses()
    {
        $courses     = Course::where('is_active', true)->get();
        $enrolledIds = [];

        if (auth()->check() && auth()->user()->role === 'student') {
            $student     = auth()->user()->student;
            $enrolledIds = $student
                ? $student->courses()->pluck('courses.id')->toArray()
                : [];
        }

        return view('courses.index', compact('courses', 'enrolledIds'));
    }

    public function contact()
    {
        return view('contact');
    }
}
