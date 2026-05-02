<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\SalarySlip;

class SalaryController extends Controller
{
    public function index()
    {
        $teacher = auth()->user()->teacher;

        $slips = SalarySlip::where('teacher_id', $teacher->id)
            ->whereIn('status', ['approved', 'paid'])
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->paginate(12);

        return view('teacher.salary.index', compact('slips', 'teacher'));
    }

    public function show(SalarySlip $slip)
    {
        $teacher = auth()->user()->teacher;

        abort_if($slip->teacher_id !== $teacher->id, 403);
        abort_if($slip->status === 'draft', 403, 'This salary slip has not been approved yet.');

        $slip->load('approvedBy');

        return view('teacher.salary.show', compact('slip', 'teacher'));
    }
}
