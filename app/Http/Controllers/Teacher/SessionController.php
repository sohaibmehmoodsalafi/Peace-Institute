<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClassSession;
use App\Services\BookingService;
use App\Services\EarningsService;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function __construct(
        private BookingService $bookingService,
        private EarningsService $earningsService
    ) {}

    public function bookings()
    {
        $teacher  = auth()->user()->teacher;
        $bookings = $teacher->bookings()
            ->with(['student.user', 'course'])
            ->latest()
            ->paginate(20);

        return view('teacher.sessions.bookings', compact('teacher', 'bookings'));
    }

    public function approve(Booking $booking)
    {
        abort_if($booking->teacher_id !== auth()->user()->teacher->id, 403);
        $this->bookingService->approveBooking($booking);
        return back()->with('success', 'Session approved. Student has been notified.');
    }

    public function reject(Request $request, Booking $booking)
    {
        abort_if($booking->teacher_id !== auth()->user()->teacher->id, 403);
        $request->validate(['reason' => 'required|string']);
        $this->bookingService->cancelBooking($booking, $request->reason, 'Teacher');
        return back()->with('success', 'Session rejected.');
    }

    public function complete(Request $request, ClassSession $session)
    {
        abort_if($session->teacher_id !== auth()->user()->teacher->id, 403);
        abort_if($session->status === 'completed', 422, 'Session already completed.');

        // Update actual duration if provided
        if ($request->actual_duration) {
            $session->update(['duration' => (int) $request->actual_duration]);
        }

        $earning = $this->earningsService->processCompletedSession($session);

        return back()->with('success', sprintf(
            'Session marked complete. Earned: $%.2f for %.2f hours.',
            $earning->net_amount,
            $earning->session_duration_hours
        ));
    }

    public function history()
    {
        $teacher  = auth()->user()->teacher;
        $sessions = $teacher->classSessions()
            ->with(['booking.student.user', 'booking.course', 'earning'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        return view('teacher.sessions.history', compact('teacher', 'sessions'));
    }
}
