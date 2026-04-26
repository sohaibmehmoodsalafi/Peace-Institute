<?php

namespace App\Services;

use App\Models\ClassSession;
use App\Models\Earning;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EarningsService
{
    // Platform commission percentage (admin cut)
    private const PLATFORM_FEE_PERCENT = 15;

    /**
     * Core salary formula: earned = (duration_minutes / 60) * hourly_rate
     */
    public function calculateSessionEarning(ClassSession $session): array
    {
        $durationHours = round($session->duration / 60, 4);
        $grossAmount   = round($durationHours * $session->hourly_rate_snapshot, 2);
        $platformFee   = round($grossAmount * (self::PLATFORM_FEE_PERCENT / 100), 2);
        $netAmount     = round($grossAmount - $platformFee, 2);

        return [
            'session_duration_hours' => $durationHours,
            'hourly_rate'            => $session->hourly_rate_snapshot,
            'amount'                 => $grossAmount,
            'platform_fee'           => $platformFee,
            'net_amount'             => $netAmount,
        ];
    }

    /**
     * Mark session complete, calculate & record the earning.
     */
    public function processCompletedSession(ClassSession $session): Earning
    {
        if ($session->earning_processed) {
            return $session->earning;
        }

        DB::transaction(function () use ($session) {
            // Calculate earning amounts
            $calc = $this->calculateSessionEarning($session);

            // Store session-level earned amount
            $session->update([
                'status'            => 'completed',
                'earned_amount'     => $calc['amount'],
                'earning_processed' => true,
            ]);

            // Create earning record
            $earning = Earning::create([
                'teacher_id'             => $session->teacher_id,
                'class_session_id'       => $session->id,
                'booking_id'             => $session->booking_id,
                'session_duration_hours' => $calc['session_duration_hours'],
                'hourly_rate'            => $calc['hourly_rate'],
                'amount'                 => $calc['amount'],
                'platform_fee'           => $calc['platform_fee'],
                'net_amount'             => $calc['net_amount'],
                'status'                 => 'pending',
            ]);

            // Update teacher aggregate totals
            $session->teacher->increment('total_earnings', $calc['net_amount']);
            $session->teacher->increment('pending_payout', $calc['net_amount']);
            $session->teacher->increment('total_sessions');

            // Update booking status
            $session->booking->update(['status' => 'completed']);

            // Update student completed session count
            $session->student->increment('completed_sessions');

            Log::info("Earning processed", [
                'teacher_id' => $session->teacher_id,
                'session_id' => $session->id,
                'amount'     => $calc['net_amount'],
            ]);

            return $earning;
        });

        return $session->fresh()->earning;
    }

    /**
     * Monthly earnings summary for a teacher.
     */
    public function getMonthlySummary(Teacher $teacher, int $year, int $month): array
    {
        $earnings = $teacher->earnings()
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->get();

        return [
            'total_sessions'       => $earnings->count(),
            'total_hours'          => $earnings->sum('session_duration_hours'),
            'gross_earnings'       => $earnings->sum('amount'),
            'platform_fee_total'   => $earnings->sum('platform_fee'),
            'net_earnings'         => $earnings->sum('net_amount'),
            'pending_payout'       => $earnings->where('status', 'pending')->sum('net_amount'),
            'approved_payout'      => $earnings->where('status', 'approved')->sum('net_amount'),
        ];
    }

    /**
     * Yearly earnings breakdown by month for a teacher.
     */
    public function getYearlyBreakdown(Teacher $teacher, int $year): array
    {
        $monthly = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthly[$month] = $teacher->earnings()
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->sum('net_amount');
        }
        return $monthly;
    }

    /**
     * Admin: approve earnings and move to payable status.
     */
    public function approveEarnings(array $earningIds): int
    {
        return Earning::whereIn('id', $earningIds)
            ->where('status', 'pending')
            ->update([
                'status'      => 'approved',
                'approved_at' => now(),
            ]);
    }

    /**
     * Admin: process payout and mark earnings as paid.
     */
    public function processPayout(Teacher $teacher, array $earningIds): void
    {
        DB::transaction(function () use ($teacher, $earningIds) {
            $total = Earning::whereIn('id', $earningIds)
                ->where('teacher_id', $teacher->id)
                ->where('status', 'approved')
                ->sum('net_amount');

            Earning::whereIn('id', $earningIds)->update([
                'status'  => 'paid',
                'paid_at' => now(),
            ]);

            $teacher->decrement('pending_payout', $total);

            Log::info("Payout processed", [
                'teacher_id' => $teacher->id,
                'amount'     => $total,
            ]);
        });
    }
}
