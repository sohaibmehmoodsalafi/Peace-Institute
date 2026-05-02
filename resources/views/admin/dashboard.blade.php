@extends('layouts.dashboard')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'Platform overview and management')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Stat Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['icon' => 'fa-chalkboard-teacher', 'val' => $stats['total_teachers'],     'label' => 'Active Teachers',    'sub' => $stats['pending_teachers'].' pending', 'color' => 'text-gold-DEFAULT'],
        ['icon' => 'fa-users',               'val' => $stats['total_students'],      'label' => 'Total Students',     'sub' => 'All time',                           'color' => 'text-blue-400'],
        ['icon' => 'fa-video',               'val' => $stats['completed_sessions'],  'label' => 'Sessions Done',      'sub' => $stats['pending_bookings'].' pending', 'color' => 'text-green-400'],
        ['icon' => 'fa-dollar-sign',         'val' => '$'.number_format($stats['total_revenue'],0), 'label' => 'Total Revenue', 'sub' => '$'.number_format($stats['monthly_revenue'],0).' this month', 'color' => 'text-purple-400'],
    ] as $s)
        <div class="stat-card p-5">
            <div class="flex items-start justify-between mb-3">
                <div class="text-2xl font-bold text-white">{{ $s['val'] }}</div>
                <i class="fas {{ $s['icon'] }} {{ $s['color'] }} text-xl opacity-70"></i>
            </div>
            <div class="text-gray-300 text-sm font-medium">{{ $s['label'] }}</div>
            <div class="text-gray-600 text-xs mt-1">{{ $s['sub'] }}</div>
        </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Recent Bookings --}}
    <div class="lg:col-span-2 card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-semibold">Recent Bookings</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-gold-DEFAULT text-xs hover:underline">View all</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase">
                        <th class="text-left pb-3">Ref</th>
                        <th class="text-left pb-3">Student</th>
                        <th class="text-left pb-3">Teacher</th>
                        <th class="text-left pb-3">Date</th>
                        <th class="text-left pb-3">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentBookings as $booking)
                        <tr class="table-row">
                            <td class="py-3 text-gold-DEFAULT font-mono text-xs">{{ $booking->booking_ref }}</td>
                            <td class="py-3 text-gray-300">{{ $booking->student->user->name }}</td>
                            <td class="py-3 text-gray-300">{{ $booking->teacher?->user?->name ?? '—' }}</td>
                            <td class="py-3 text-gray-500 text-xs">{{ $booking->scheduled_at->format('M d, Y') }}</td>
                            <td class="py-3"><span class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top Teachers --}}
    <div class="card p-6">
        <h3 class="text-white font-semibold mb-5">Top Earning Teachers</h3>
        <div class="space-y-4">
            @foreach($topTeachers as $i => $teacher)
                <div class="flex items-center gap-3">
                    <span class="text-gray-600 text-sm w-5">{{ $i+1 }}</span>
                    <img src="{{ $teacher->user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm truncate">{{ $teacher->user->name }}</div>
                        <div class="text-gray-500 text-xs">{{ $teacher->total_sessions }} sessions</div>
                    </div>
                    <span class="text-gold-DEFAULT text-sm font-semibold">${{ number_format($teacher->total_earnings, 0) }}</span>
                </div>
            @endforeach
        </div>
    </div>

</div>

{{-- Pending Teacher Approvals --}}
@if($stats['pending_teachers'] > 0)
<div class="mt-6 card p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-white font-semibold">
            <span class="text-yellow-400"><i class="fas fa-exclamation-circle mr-2"></i></span>
            Pending Teacher Approvals ({{ $stats['pending_teachers'] }})
        </h3>
        <a href="{{ route('admin.teachers.index', ['status' => 'pending']) }}" class="btn-gold px-4 py-2 text-sm rounded-lg">Review Now</a>
    </div>
    <p class="text-gray-500 text-sm">There are teachers waiting for account approval.</p>
</div>
@endif

@endsection
