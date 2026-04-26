<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// API: available slots (public)
Route::get('/teachers/{teacher}/slots', function (\App\Models\Teacher $teacher, Request $request) {
    $request->validate([
        'date'     => 'required|date|after_or_equal:today',
        'duration' => 'nullable|integer|in:30,45,60,90',
    ]);
    $service  = app(\App\Services\BookingService::class);
    $date     = \Carbon\Carbon::parse($request->date);
    $duration = $request->duration ?? 60;
    $slots    = $service->getAvailableSlots($teacher, $date, $duration);
    return response()->json(['slots' => $slots]);
});
