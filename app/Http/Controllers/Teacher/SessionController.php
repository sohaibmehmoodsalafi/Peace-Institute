<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClassSession;
use App\Models\Teacher;
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
        $teacher = auth()->user()->teacher;
        $teacherIds = Teacher::idsForUserId(auth()->id());

        $bookings = Booking::query()
            ->whereIn('teacher_id', $teacherIds)
            ->with(['student.user', 'course'])
            ->latest()
            ->paginate(20);

        return view('teacher.sessions.bookings', compact('teacher', 'bookings'));
    }

    private function bookingBelongsToAuthTeacher(Booking $booking): bool
    {
        return in_array($booking->teacher_id, Teacher::idsForUserId(auth()->id()), true);
    }

    private function sessionBelongsToAuthTeacher(ClassSession $session): bool
    {
        return in_array($session->teacher_id, Teacher::idsForUserId(auth()->id()), true);
    }

    public function approve(Request $request, Booking $booking)
    {
        abort_unless($this->bookingBelongsToAuthTeacher($booking), 403);

        // Save meeting link if teacher provided one
        if ($request->filled('meeting_link')) {
            $booking->update(['meeting_link' => $request->meeting_link]);
        }

        $this->bookingService->approveBooking($booking);
        return back()->with('success', 'Session approved! Student has been notified.');
    }

    public function reject(Request $request, Booking $booking)
    {
        abort_unless($this->bookingBelongsToAuthTeacher($booking), 403);
        $request->validate(['reason' => 'required|string']);
        $this->bookingService->cancelBooking($booking, $request->reason, 'Teacher');
        return back()->with('success', 'Session rejected.');
    }

    public function complete(Request $request, ClassSession $session)
    {
        abort_unless($this->sessionBelongsToAuthTeacher($session), 403);
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

    public function updateLink(Request $request, Booking $booking)
    {
        abort_unless($this->bookingBelongsToAuthTeacher($booking), 403);

        $request->validate(['meeting_link' => 'required|url|max:500']);
        $booking->update(['meeting_link' => $request->meeting_link]);

        return back()->with('success', 'Meeting link updated. Student can now see it in their dashboard.');
    }

    public function history()
    {
        $teacher = auth()->user()->teacher;
        $teacherIds = Teacher::idsForUserId(auth()->id());

        $sessions = ClassSession::query()
            ->whereIn('teacher_id', $teacherIds)
            ->with(['booking.student.user', 'booking.course', 'earning'])
            ->where('status', 'completed')
            ->latest()
            ->paginate(20);

        return view('teacher.sessions.history', compact('teacher', 'sessions'));
    }
}
