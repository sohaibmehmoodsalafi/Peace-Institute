<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Teacher;
use Carbon\Carbon;

class EnrollmentController extends Controller
{
    public function index()
    {
        $teacher     = auth()->user()->teacher;
        $teacherIds  = Teacher::idsForUserId(auth()->id());

        $enrollments = Enrollment::with(['student.user', 'course'])
            ->whereIn('teacher_id', $teacherIds)
            ->whereIn('status', ['active', 'pending', 'paused'])
            ->latest()
            ->get();

        // Build monthly schedule for each active enrollment
        $now = Carbon::now();
        $schedules = [];
        foreach ($enrollments->where('status', 'active') as $enr) {
            $schedules[$enr->id] = self::monthDates(
                $enr->selected_days ?? [],
                $now->year,
                $now->month
            );
        }

        return view('teacher.enrollments', compact('teacher', 'enrollments', 'schedules', 'now'));
    }

    /**
     * Return all dates in a given month that fall on the selected days of week.
     */
    public static function monthDates(array $selectedDays, int $year, int $month): array
    {
        $map = [
            'sunday'=>0,'monday'=>1,'tuesday'=>2,'wednesday'=>3,
            'thursday'=>4,'friday'=>5,'saturday'=>6,
        ];
        $nums = array_filter(array_map(fn($d) => $map[$d] ?? null, $selectedDays), fn($v) => $v !== null);

        $dates = [];
        $total = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($d = 1; $d <= $total; $d++) {
            $dow = (int) date('w', mktime(0, 0, 0, $month, $d, $year));
            if (in_array($dow, $nums)) {
                $dates[] = Carbon::createFromDate($year, $month, $d);
            }
        }
        return $dates;
    }
}
