<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService) {}

    public function index(Request $request)
    {
        $bookings = Booking::with(['student.user', 'teacher.user', 'course', 'payment'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->search, fn($q) => $q->where('booking_ref', 'like', '%'.$request->search.'%'))
            ->when($request->date, fn($q) => $q->whereDate('scheduled_at', $request->date))
            ->latest()
            ->paginate(20);

        $statusCounts = Booking::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('admin.bookings.index', compact('bookings', 'statusCounts'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['student.user', 'teacher.user', 'course', 'payment', 'classSession', 'review']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function cancel(Request $request, Booking $booking)
    {
        $request->validate(['reason' => 'required|string']);
        $this->bookingService->cancelBooking($booking, $request->reason, 'Admin');
        return back()->with('success', 'Booking cancelled.');
    }
}
