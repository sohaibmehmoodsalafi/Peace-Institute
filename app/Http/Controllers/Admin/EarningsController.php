<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\Payout;
use App\Models\Teacher;
use App\Services\EarningsService;
use Illuminate\Http\Request;

class EarningsController extends Controller
{
    public function __construct(private EarningsService $earningsService) {}

    public function index(Request $request)
    {
        $earnings = Earning::with(['teacher.user', 'classSession', 'booking'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->teacher_id, fn($q) => $q->where('teacher_id', $request->teacher_id))
            ->when($request->month, fn($q) => $q->whereMonth('created_at', $request->month))
            ->when($request->year, fn($q) => $q->whereYear('created_at', $request->year))
            ->latest()
            ->paginate(20);

        $summary = [
            'total_pending'  => Earning::where('status', 'pending')->sum('net_amount'),
            'total_approved' => Earning::where('status', 'approved')->sum('net_amount'),
            'total_paid'     => Earning::where('status', 'paid')->sum('net_amount'),
        ];

        $teachers = Teacher::with('user')->where('status', 'approved')->get();

        return view('admin.earnings.index', compact('earnings', 'summary', 'teachers'));
    }

    public function approve(Request $request)
    {
        $request->validate(['earning_ids' => 'required|array']);
        $count = $this->earningsService->approveEarnings($request->earning_ids);
        return back()->with('success', "$count earnings approved for payout.");
    }

    public function payouts(Request $request)
    {
        $payouts = Payout::with('teacher.user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return view('admin.earnings.payouts', compact('payouts'));
    }

    public function processPayout(Request $request, Teacher $teacher)
    {
        $request->validate([
            'earning_ids'    => 'required|array',
            'payment_method' => 'required|string',
        ]);

        $this->earningsService->processPayout($teacher, $request->earning_ids);

        return back()->with('success', 'Payout processed successfully.');
    }
}
