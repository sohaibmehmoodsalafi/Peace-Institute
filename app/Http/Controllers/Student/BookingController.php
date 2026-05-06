<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Course;
use App\Models\Student;
use App\Models\Teacher;
use App\Services\BookingService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index()
    {
        $student = auth()->user()->student;
        $bookings = Booking::query()
            ->whereIn('student_id', Student::idsForUserId(auth()->id()))
            ->with(['teacher.user', 'course', 'payment', 'review'])
            ->latest()
            ->paginate(15);

        return view('student.bookings.index', compact('student', 'bookings'));
    }

    public function create(Request $request)
    {
        $teacher = Teacher::with(['user', 'availabilities'])
            ->where('status', 'approved')
            ->findOrFail($request->teacher_id);

        $courses = Course::where('is_active', true)->get();

        return view('student.bookings.create', compact('teacher', 'courses'));
    }

    public function getSlots(Request $request)
    {
        $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'date'       => 'required|date|after_or_equal:today',
            'duration'   => 'nullable|integer|in:30,45,60,90',
        ]);

        $teacher  = Teacher::findOrFail($request->teacher_id);
        $date     = Carbon::parse($request->date);
        $duration = $request->duration ?? 60;

        $slots = $this->bookingService->getAvailableSlots($teacher, $date, $duration);

        return response()->json(['slots' => $slots]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'teacher_id'   => 'required|exists:teachers,id',
            'course_id'    => 'nullable|exists:courses,id',
            'scheduled_at' => 'required|date|after:now',
            'duration'     => 'required|integer|in:30,45,60,90',
            'booking_type' => 'required|in:single,package',
            'notes'        => 'nullable|string|max:500',
        ]);

        $studentIds = Student::idsForUserId(auth()->id());
        abort_if(count($studentIds) === 0, 403, 'Student profile missing.');
        $student = Student::query()->find($studentIds[0]);
        $teacher = Teacher::findOrFail($request->teacher_id);

        // Verify slot is still available
        $scheduledAt = Carbon::parse($request->scheduled_at);
        if (!$this->bookingService->isSlotAvailable($teacher, $scheduledAt, $request->duration)) {
            return back()->withErrors(['scheduled_at' => 'This slot is no longer available.']);
        }

        // Calculate amount
        $amount = round(($request->duration / 60) * $teacher->hourly_rate, 2);

        $booking = $this->bookingService->createBooking([
            'teacher_id'   => $teacher->id,
            'course_id'    => $request->course_id,
            'scheduled_at' => $scheduledAt,
            'duration'     => $request->duration,
            'booking_type' => $request->booking_type,
            'amount'       => $amount,
            'notes'        => $request->notes,
        ], $student);

        return redirect()->route('student.bookings.index')
            ->with('success', "Booking #{$booking->booking_ref} created! Awaiting teacher approval.");
    }

    public function cancel(Request $request, Booking $booking)
    {
        $myIds = array_map('intval', Student::idsForUserId(auth()->id()));
        abort_unless(in_array((int) $booking->student_id, $myIds), 403);
        abort_if($booking->isCompleted(), 422, 'Cannot cancel a completed session.');

        $request->validate(['reason' => 'required|string']);
        $this->bookingService->cancelBooking($booking, $request->reason, 'Student');

        return back()->with('success', 'Class cancelled.');
    }

    public function review(Request $request, Booking $booking)
    {
        $myIds = array_map('intval', Student::idsForUserId(auth()->id()));
        abort_unless(in_array((int) $booking->student_id, $myIds), 403);
        abort_if(!$booking->isCompleted(), 422, 'Can only review completed sessions.');
        abort_if($booking->review, 422, 'Already reviewed.');

        $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'nullable|string|max:500',
        ]);

        \App\Models\Review::create([
            'teacher_id' => $booking->teacher_id,
            'student_id' => $booking->student_id,
            'booking_id' => $booking->id,
            'rating'     => $request->rating,
            'comment'    => $request->comment,
        ]);

        // Update teacher average rating
        $teacher     = $booking->teacher;
        $avgRating   = $teacher->reviews()->avg('rating');
        $totalReviews = $teacher->reviews()->count();
        $teacher->update(['rating' => $avgRating, 'total_reviews' => $totalReviews]);

        return back()->with('success', 'Review submitted. Thank you!');
    }
}
