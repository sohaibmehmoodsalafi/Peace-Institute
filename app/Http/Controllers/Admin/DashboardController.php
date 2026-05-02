<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ClassSession;
use App\Models\Earning;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_teachers'         => Teacher::where('status', 'approved')->count(),
            'pending_teachers'       => Teacher::where('status', 'pending')->count(),
            'total_students'         => Student::count(),
            'total_bookings'         => Booking::count(),
            'pending_bookings'       => Booking::where('status', 'pending')->count(),
            'completed_sessions'     => ClassSession::where('status', 'completed')->count(),
            'total_revenue'          => Payment::where('status', 'completed')->sum('amount'),
            'total_earnings_pending' => Earning::where('status', 'pending')->sum('net_amount'),
            'monthly_revenue'        => Payment::where('status', 'completed')
                ->whereMonth('paid_at', now()->month)
                ->whereYear('paid_at', now()->year)
                ->sum('amount'),
        ];

        // Recent bookings (only with valid relations)
        $recentBookings = Booking::with(['student.user', 'teacher.user', 'course'])
            ->whereHas('student.user')
            ->whereHas('teacher.user')
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue chart data (last 12 months)
        $revenueChart = Payment::where('status', 'completed')
            ->whereDate('paid_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw('YEAR(paid_at) as year, MONTH(paid_at) as month, SUM(amount) as total')
            ->groupByRaw('YEAR(paid_at), MONTH(paid_at)')
            ->orderByRaw('YEAR(paid_at), MONTH(paid_at)')
            ->get();

        // Top teachers by earnings (only with valid user)
        $topTeachers = Teacher::with('user')
            ->whereHas('user')
            ->orderByDesc('total_earnings')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'revenueChart', 'topTeachers'));
    }
}
