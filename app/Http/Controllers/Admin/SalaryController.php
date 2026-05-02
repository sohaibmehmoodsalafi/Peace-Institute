<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SalarySlip;
use App\Models\Teacher;
use App\Models\Booking;
use Illuminate\Http\Request;

class SalaryController extends Controller
{
    // ── List all salary slips ─────────────────────────────────────────────────
    public function index(Request $request)
    {
        $slips = SalarySlip::with('teacher.user')
            ->when($request->month,  fn($q) => $q->where('month',  $request->month))
            ->when($request->year,   fn($q) => $q->where('year',   $request->year))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->teacher_id, fn($q) => $q->where('teacher_id', $request->teacher_id))
            ->orderBy('year', 'desc')->orderBy('month', 'desc')
            ->paginate(20);

        $teachers = Teacher::with('user')->where('status', 'approved')->get();

        return view('admin.salary.index', compact('slips', 'teachers'));
    }

    // ── Generate salary slips for a month ────────────────────────────────────
    public function generate(Request $request)
    {
        $request->validate([
            'month'      => 'required|integer|min:1|max:12',
            'year'       => 'required|integer|min:2020|max:2035',
            'teacher_id' => 'nullable|exists:teachers,id',
        ]);

        $month = (int) $request->month;
        $year  = (int) $request->year;

        $teachers = Teacher::with('user')
            ->where('status', 'approved')
            ->when($request->teacher_id, fn($q) => $q->where('id', $request->teacher_id))
            ->get();

        $generated = 0;
        $skipped   = 0;

        foreach ($teachers as $teacher) {
            // Skip if already generated
            if (SalarySlip::where('teacher_id', $teacher->id)
                ->where('month', $month)->where('year', $year)->exists()) {
                $skipped++;
                continue;
            }

            $fixedSalary   = $teacher->monthly_salary ?? 0;
            $targetClasses = $teacher->monthly_target_classes ?? 0;

            // Count completed bookings this month
            $conducted = Booking::where('teacher_id', $teacher->id)
                ->where('status', 'completed')
                ->whereMonth('scheduled_at', $month)
                ->whereYear('scheduled_at', $year)
                ->count();

            $missed    = max(0, $targetClasses - $conducted);
            $perClass  = $targetClasses > 0 ? $fixedSalary / $targetClasses : 0;
            $deduction = $missed * $perClass;
            $net       = max(0, $fixedSalary - $deduction);

            SalarySlip::create([
                'teacher_id'          => $teacher->id,
                'month'               => $month,
                'year'                => $year,
                'fixed_salary'        => $fixedSalary,
                'target_classes'      => $targetClasses,
                'conducted_classes'   => $conducted,
                'missed_classes'      => $missed,
                'deduction_per_class' => $perClass,
                'total_deduction'     => $deduction,
                'admin_adjustment'    => 0,
                'adjustment_note'     => null,
                'net_salary'          => $net,
                'status'              => 'draft',
            ]);
            $generated++;
        }

        $period = date('F Y', mktime(0, 0, 0, $month, 1, $year));
        $msg = "$generated slip(s) generated for $period.";
        if ($skipped) $msg .= " $skipped already existed (skipped).";

        return back()->with('success', $msg);
    }

    // ── View a slip ───────────────────────────────────────────────────────────
    public function show(SalarySlip $slip)
    {
        $slip->load('teacher.user', 'approvedBy');

        // Load individual completed bookings for that month
        $bookings = Booking::with('student.user', 'course')
            ->where('teacher_id', $slip->teacher_id)
            ->where('status', 'completed')
            ->whereMonth('scheduled_at', $slip->month)
            ->whereYear('scheduled_at', $slip->year)
            ->orderBy('scheduled_at')
            ->get();

        return view('admin.salary.show', compact('slip', 'bookings'));
    }

    // ── Admin adjust & update ─────────────────────────────────────────────────
    public function update(Request $request, SalarySlip $slip)
    {
        $request->validate([
            'conducted_classes' => 'required|integer|min:0',
            'missed_classes'    => 'required|integer|min:0',
            'admin_adjustment'  => 'required|numeric',
            'adjustment_note'   => 'nullable|string|max:500',
        ]);

        $slip->update([
            'conducted_classes' => $request->conducted_classes,
            'missed_classes'    => $request->missed_classes,
            'admin_adjustment'  => $request->admin_adjustment,
            'adjustment_note'   => $request->adjustment_note,
        ]);

        $slip->recalculate();

        return back()->with('success', 'Salary slip updated successfully.');
    }

    // ── Approve ───────────────────────────────────────────────────────────────
    public function approve(SalarySlip $slip)
    {
        if ($slip->status !== 'draft') {
            return back()->with('error', 'Only draft slips can be approved.');
        }

        $slip->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return back()->with('success', 'Salary slip approved. Teacher can now view it.');
    }

    // ── Mark paid ─────────────────────────────────────────────────────────────
    public function markPaid(SalarySlip $slip)
    {
        $slip->update(['status' => 'paid', 'paid_at' => now()]);
        return back()->with('success', 'Salary marked as paid.');
    }

    // ── Update teacher salary settings ────────────────────────────────────────
    public function updateTeacherSalary(Request $request, Teacher $teacher)
    {
        $request->validate([
            'monthly_salary'         => 'required|numeric|min:0',
            'monthly_target_classes' => 'required|integer|min:0',
        ]);

        $teacher->update([
            'monthly_salary'         => $request->monthly_salary,
            'monthly_target_classes' => $request->monthly_target_classes,
        ]);

        return back()->with('success', 'Teacher salary settings updated.');
    }
}
