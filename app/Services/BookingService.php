<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\ClassSession;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class BookingService
{
    public function __construct(private MeetingService $meetingService) {}

    /**
     * Create a new booking with meeting link and session record.
     */
    public function createBooking(array $data, Student $student): Booking
    {
        return DB::transaction(function () use ($data, $student) {
            $teacher = Teacher::findOrFail($data['teacher_id']);

            // Generate meeting link
            $meeting = $this->meetingService->createMeeting([
                'topic'       => 'Peace Institute - Quran Session',
                'start_time'  => $data['scheduled_at'],
                'duration'    => $data['duration'] ?? 60,
            ]);

            $booking = Booking::create([
                'student_id'    => $student->id,
                'teacher_id'    => $teacher->id,
                'course_id'     => $data['course_id'] ?? null,
                'scheduled_at'  => $data['scheduled_at'],
                'duration'      => $data['duration'] ?? 60,
                'booking_type'  => $data['booking_type'] ?? 'single',
                'amount'        => $data['amount'],
                'meeting_link'  => $meeting['link'],
                'meeting_id'    => $meeting['id'],
                'student_notes' => $data['notes'] ?? null,
                'status'        => 'pending',
            ]);

            // Create the corresponding class session record
            ClassSession::create([
                'booking_id'            => $booking->id,
                'teacher_id'            => $teacher->id,
                'student_id'            => $student->id,
                'duration'              => $booking->duration,
                'status'                => 'scheduled',
                'hourly_rate_snapshot'  => $teacher->hourly_rate,
            ]);

            // Update student session count
            $student->increment('total_sessions');

            return $booking;
        });
    }

    /**
     * Teacher approves a booking.
     */
    public function approveBooking(Booking $booking): void
    {
        DB::transaction(function () use ($booking) {
            $booking->update([
                'status'      => 'approved',
                'approved_at' => now(),
            ]);

            $booking->classSession->update(['status' => 'scheduled']);
        });
    }

    /**
     * Cancel a booking.
     */
    public function cancelBooking(Booking $booking, string $reason, string $cancelledBy): void
    {
        DB::transaction(function () use ($booking, $reason, $cancelledBy) {
            $booking->update([
                'status'               => 'cancelled',
                'cancelled_at'         => now(),
                'cancellation_reason'  => "[$cancelledBy] $reason",
            ]);

            if ($booking->classSession) {
                $booking->classSession->update(['status' => 'cancelled']);
            }

            // Refund logic would go here if already paid
        });
    }

    /**
     * Get available time slots for a teacher on a specific date.
     */
    public function getAvailableSlots(Teacher $teacher, Carbon $date, int $durationMinutes = 60): Collection
    {
        $dayOfWeek    = $date->dayOfWeek;
        $availability = $teacher->availabilities()
            ->where('day_of_week', $dayOfWeek)
            ->where('is_available', true)
            ->first();

        if (!$availability) {
            return collect();
        }

        // Get existing bookings for that day
        $bookedSlots = Booking::where('teacher_id', $teacher->id)
            ->whereDate('scheduled_at', $date->format('Y-m-d'))
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->pluck('scheduled_at');

        $slots     = collect();
        $slotStart = Carbon::parse($date->format('Y-m-d').' '.$availability->start_time);
        $slotEnd   = Carbon::parse($date->format('Y-m-d').' '.$availability->end_time);

        while ($slotStart->copy()->addMinutes($durationMinutes)->lte($slotEnd)) {
            $isBooked = $bookedSlots->contains(function ($booked) use ($slotStart, $durationMinutes) {
                $bookedDt = Carbon::parse($booked);
                return $slotStart->between(
                    $bookedDt->copy()->subMinutes($durationMinutes - 1),
                    $bookedDt->copy()->addMinutes($durationMinutes - 1)
                );
            });

            if (!$isBooked && $slotStart->gt(now())) {
                $slots->push([
                    'time'       => $slotStart->format('H:i'),
                    'time_label' => $slotStart->format('h:i A'),
                    'datetime'   => $slotStart->toDateTimeString(),
                ]);
            }

            $slotStart->addMinutes($durationMinutes);
        }

        return $slots;
    }

    /**
     * Check if a specific slot is still available.
     */
    public function isSlotAvailable(Teacher $teacher, Carbon $dateTime, int $durationMinutes = 60): bool
    {
        return !Booking::where('teacher_id', $teacher->id)
            ->where('scheduled_at', $dateTime)
            ->whereNotIn('status', ['cancelled', 'rejected'])
            ->exists();
    }
}
