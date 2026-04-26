<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\TeacherAvailability;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function index()
    {
        $teacher       = auth()->user()->teacher;
        $availabilities = $teacher->availabilities()->orderBy('day_of_week')->get()
            ->keyBy('day_of_week');

        return view('teacher.availability.index', compact('teacher', 'availabilities'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'slots'                  => 'required|array',
            'slots.*.day_of_week'    => 'required|integer|between:0,6',
            'slots.*.start_time'     => 'required|date_format:H:i',
            'slots.*.end_time'       => 'required|date_format:H:i|after:slots.*.start_time',
            'slots.*.is_available'   => 'boolean',
        ]);

        $teacher = auth()->user()->teacher;

        // Replace all availability slots
        $teacher->availabilities()->delete();

        foreach ($request->slots as $slot) {
            if ($slot['is_available'] ?? false) {
                TeacherAvailability::create([
                    'teacher_id'   => $teacher->id,
                    'day_of_week'  => $slot['day_of_week'],
                    'start_time'   => $slot['start_time'],
                    'end_time'     => $slot['end_time'],
                    'is_available' => true,
                ]);
            }
        }

        return back()->with('success', 'Availability updated successfully.');
    }
}
