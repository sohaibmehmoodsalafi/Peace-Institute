@extends('layouts.dashboard')
@section('title', 'Session History')
@section('page-title', 'Session History')
@section('page-subtitle', 'Completed sessions you have taught')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Sessions</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-history"></i></span> History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 bg-green-900/30 border border-green-500/30 text-green-400 rounded-xl text-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                <th class="text-left px-5 py-4">Ref</th>
                <th class="text-left px-5 py-4">Student</th>
                <th class="text-left px-5 py-4">Course</th>
                <th class="text-left px-5 py-4">Scheduled</th>
                <th class="text-left px-5 py-4">Duration</th>
                <th class="text-right px-5 py-4">Net earned</th>
                <th class="text-left px-5 py-4">Completed</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessions as $session)
                @php $b = $session->booking; @endphp
                <tr class="table-row">
                    <td class="px-5 py-4 font-mono text-xs text-gold-DEFAULT">{{ $b?->booking_ref ?? '—' }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <img src="{{ $b?->student?->user?->avatar_url ?? asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full" alt="">
                            <span class="text-gray-300">{{ $b?->student?->user?->name ?? '—' }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-400">{{ $b?->course?->name ?? '–' }}</td>
                    <td class="px-5 py-4">
                        @if($b?->scheduled_at)
                            <div class="text-gray-300 text-xs">{{ $b->scheduled_at->format('M d, Y') }}</div>
                            <div class="text-gray-500 text-xs">{{ $b->scheduled_at->format('h:i A') }}</div>
                        @else
                            <span class="text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-gray-400">{{ $session->duration }} min</td>
                    <td class="px-5 py-4 text-right text-emerald-400/90">
                        @if($session->earning)
                            ${{ number_format($session->earning->net_amount, 2) }}
                        @else
                            <span class="text-gray-600">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        @if($session->ended_at)
                            <span class="text-gray-400 text-xs">{{ $session->ended_at->format('M d, Y h:i A') }}</span>
                        @elseif($b?->scheduled_at)
                            <span class="text-gray-500 text-xs">{{ $b->scheduled_at->copy()->addMinutes($session->duration)->format('M d h:i A') }}</span>
                        @else
                            <span class="badge badge-{{ $session->status }}">{{ $session->status }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-600">No completed sessions yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $sessions->links() }}</div>
@endsection
