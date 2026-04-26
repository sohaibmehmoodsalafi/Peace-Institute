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
            ->take(6)
            ->get();

        $courses = Course::where('is_active', true)->get();

        $recentReviews = Review::with(['student.user', 'teacher.user'])
            ->where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        $stats = [
            'teachers'  => Teacher::where('status', 'approved')->count(),
            'students'  => \App\Models\Student::count(),
            'sessions'  => \App\Models\ClassSession::where('status', 'completed')->count(),
            'countries' => 25,
        ];

        return view('welcome', compact('featuredTeachers', 'courses', 'recentReviews', 'stats'));
    }

    public function teachers()
    {
        $teachers = Teacher::with('user')
            ->where('status', 'approved')
            ->paginate(12);

        $courses = Course::where('is_active', true)->get();

        return view('teachers.index', compact('teachers', 'courses'));
    }

    public function teacherProfile(Teacher $teacher)
    {
        $teacher->load(['user', 'reviews.student.user', 'availabilities']);
        $courses = Course::where('is_active', true)->get();
        return view('teachers.show', compact('teacher', 'courses'));
    }

    public function courses()
    {
        $courses = Course::where('is_active', true)->get();
        return view('courses.index', compact('courses'));
    }

    public function contact()
    {
        return view('contact');
    }
}
