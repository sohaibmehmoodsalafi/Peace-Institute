<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Services\EarningsService;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function __construct(private EarningsService $earningsService) {}

    public function index(Request $request)
    {
        $teacher = auth()->user()->teacher;

        $year  = $request->year ?? now()->year;
        $month = $request->month ?? now()->month;

        $earnings = $teacher->earnings()
            ->with(['classSession', 'booking.student.user'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest()
            ->paginate(20);

        $monthlySummary  = $this->earningsService->getMonthlySummary($teacher, $year, $month);
        $yearlyBreakdown = $this->earningsService->getYearlyBreakdown($teacher, $year);

        $pendingPayouts = $teacher->payouts()
            ->where('status', 'requested')
            ->latest()
            ->get();

        return view('teacher.earnings.index', compact(
            'teacher', 'earnings', 'monthlySummary', 'yearlyBreakdown', 'pendingPayouts', 'year', 'month'
        ));
    }

    public function requestPayout(Request $request)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:10',
            'payment_method' => 'required|string',
            'payment_details'=> 'required|string',
        ]);

        $teacher = auth()->user()->teacher;

        if ($request->amount > $teacher->pending_payout) {
            return back()->withErrors(['amount' => 'Requested amount exceeds available balance.']);
        }

        Payout::create([
            'teacher_id'     => $teacher->id,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_details'=> ['details' => $request->payment_details],
            'status'         => 'requested',
        ]);

        return back()->with('success', 'Payout request submitted. Admin will process it shortly.');
    }
}
