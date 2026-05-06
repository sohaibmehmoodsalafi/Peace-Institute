@extends('layouts.dashboard')
@section('title', 'Teacher Dashboard')
@section('page-title', 'Teacher Dashboard')
@section('page-subtitle', 'Manage your classes and earnings')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.enrollments') }}" class="sidebar-link"><span class="icon"><i class="fas fa-book-open"></i></span> Enrollments</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link">
        <span class="icon"><i class="fas fa-calendar-check"></i></span> Classes
        @if(isset($pendingApproval) && $pendingApproval > 0)
            <span class="ml-auto bg-yellow-500 text-black text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center">{{ $pendingApproval }}</span>
        @endif
    </a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> Class History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('teacher.salary.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary</a>
    <a href="{{ route('teacher.profile') }}" class="sidebar-link"><span class="icon"><i class="fas fa-user-circle"></i></span> Profile</a>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-white">{{ number_format($teacher->totalHoursWorked(), 1) }}</div>
        <div class="text-gray-300 text-sm font-medium">Total Hours</div>
        <div class="text-gray-600 text-xs mt-1">All time</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-white">${{ number_format($teacher->total_earnings, 2) }}</div>
        <div class="text-gray-300 text-sm font-medium">Total Earned</div>
        <div class="text-gray-600 text-xs mt-1">Net earnings</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-gold-DEFAULT">${{ number_format($teacher->pending_payout, 2) }}</div>
        <div class="text-gray-300 text-sm font-medium">Pending Payout</div>
        <div class="text-gray-600 text-xs mt-1">Awaiting approval</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-white">${{ number_format($monthlySummary['net_earnings'], 2) }}</div>
        <div class="text-gray-300 text-sm font-medium">This Month</div>
        <div class="text-gray-600 text-xs mt-1">{{ $monthlySummary['total_sessions'] }} sessions</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Upcoming Sessions --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-semibold">Upcoming Classes</h3>
            <a href="{{ route('teacher.sessions') }}" class="text-gold-DEFAULT text-xs hover:underline">View all</a>
        </div>
        @forelse($upcoming as $booking)
            <div class="table-row py-4">
                <div class="flex items-start gap-3">
                    <img src="{{ $booking->student->user->avatar_url }}" class="w-10 h-10 rounded-full flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium">{{ $booking->student->user->name }}</div>
                        <div class="text-gray-400 text-xs">{{ $booking->course->name ?? 'General Class' }}</div>
                        <div class="text-gold-DEFAULT text-xs mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $booking->scheduled_at->format('D, M d Y \a\t h:i A') }}
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-2">
                        <span class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span>
                        @if($booking->isPending())
                            <div class="flex gap-1">
                                <form action="{{ route('teacher.sessions.approve', $booking) }}" method="POST">
                                    @csrf
                                    <button class="text-xs bg-green-900/40 border border-green-500/40 text-green-400 px-2 py-1 rounded hover:bg-green-900/60">Approve</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
                @if($booking->meeting_link)
                    <div class="mt-2 ml-13">
                        <a href="{{ $booking->meeting_link }}" target="_blank" class="text-xs text-blue-400 hover:underline">
                            <i class="fas fa-video mr-1"></i> Join Meeting
                        </a>
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-600 py-8">
                <i class="fas fa-calendar-times text-3xl mb-3"></i>
                <p>No upcoming classes</p>
            </div>
        @endforelse
    </div>

    {{-- Monthly Summary --}}
    <div class="card p-6">
        <h3 class="text-white font-semibold mb-5">Monthly Summary – {{ now()->format('F Y') }}</h3>
        <div class="space-y-4">
            @foreach([
                ['Classes Completed', $monthlySummary['total_sessions'], 'fa-check-circle', 'text-green-400'],
                ['Hours Worked',       number_format($monthlySummary['total_hours'], 1).'h', 'fa-clock', 'text-blue-400'],
                ['Gross Earnings',     '$'.number_format($monthlySummary['gross_earnings'], 2), 'fa-dollar-sign', 'text-yellow-400'],
                ['Platform Fee (15%)', '$'.number_format($monthlySummary['platform_fee_total'], 2), 'fa-percent', 'text-red-400'],
                ['Net Earnings',       '$'.number_format($monthlySummary['net_earnings'], 2), 'fa-wallet', 'text-gold-DEFAULT'],
            ] as $item)
                <div class="flex items-center justify-between py-2 border-b border-white/5">
                    <div class="flex items-center gap-2 text-gray-400 text-sm">
                        <i class="fas {{ $item[2] }} {{ $item[3] }} w-4 text-center"></i>
                        {{ $item[0] }}
                    </div>
                    <span class="{{ $item[3] }} font-semibold">{{ $item[1] }}</span>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <a href="{{ route('teacher.earnings') }}" class="btn-gold w-full py-2 rounded-lg text-sm text-center block">
                View Full Earnings Report
            </a>
        </div>
    </div>

</div>

{{-- Hourly Rate Info --}}
<div class="mt-6 card p-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gold-DEFAULT/10 rounded-lg flex items-center justify-center">
            <i class="fas fa-tag text-gold-DEFAULT"></i>
        </div>
        <div>
            <div class="text-white text-sm font-medium">Your Hourly Rate</div>
            <div class="text-gray-500 text-xs">Set by admin · affects session earnings</div>
        </div>
    </div>
    <div class="text-2xl font-bold text-gold-DEFAULT">${{ number_format($teacher->hourly_rate, 2) }}/hr</div>
</div>

@endsection
