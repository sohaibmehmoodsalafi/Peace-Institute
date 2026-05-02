@extends('layouts.dashboard')
@section('title', 'My Salary Slips')
@section('page-title', 'Salary Slips')
@section('page-subtitle', 'View your approved monthly salary slips')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Sessions</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('teacher.salary.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary Slips</a>
@endsection

@section('content')

{{-- Teacher Salary Info --}}
@if($teacher->monthly_salary > 0)
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="stat-card p-5">
        <div class="text-xs text-gray-500 uppercase mb-2">Monthly Salary</div>
        <div class="text-2xl font-bold text-gold-DEFAULT">${{ number_format($teacher->monthly_salary, 2) }}</div>
        <div class="text-xs text-gray-600 mt-1">Fixed by institute</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-xs text-gray-500 uppercase mb-2">Target Classes</div>
        <div class="text-2xl font-bold text-white">{{ $teacher->monthly_target_classes }}</div>
        <div class="text-xs text-gray-600 mt-1">Per month</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-xs text-gray-500 uppercase mb-2">Per Class Value</div>
        <div class="text-2xl font-bold text-green-400">
            ${{ $teacher->monthly_target_classes > 0 ? number_format($teacher->monthly_salary / $teacher->monthly_target_classes, 2) : '0.00' }}
        </div>
        <div class="text-xs text-gray-600 mt-1">Deducted if missed</div>
    </div>
</div>
@endif

{{-- Slips List --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                    <th class="text-left px-5 py-4">Period</th>
                    <th class="text-left px-5 py-4">Fixed Salary</th>
                    <th class="text-left px-5 py-4">Classes</th>
                    <th class="text-left px-5 py-4">Deduction</th>
                    <th class="text-left px-5 py-4">Net Salary</th>
                    <th class="text-left px-5 py-4">Status</th>
                    <th class="text-left px-5 py-4"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($slips as $slip)
                <tr class="table-row">
                    <td class="px-5 py-4 text-white font-medium">{{ $slip->period }}</td>
                    <td class="px-5 py-4 text-gray-300">${{ number_format($slip->fixed_salary, 2) }}</td>
                    <td class="px-5 py-4">
                        <span class="text-green-400">{{ $slip->conducted_classes }}</span>
                        <span class="text-gray-600">/{{ $slip->target_classes }}</span>
                        @if($slip->missed_classes > 0)
                            <span class="text-red-400 text-xs block">{{ $slip->missed_classes }} missed</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-red-400 text-sm">
                        {{ $slip->total_deduction > 0 ? '-$'.number_format($slip->total_deduction, 2) : '—' }}
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-gold-DEFAULT font-bold text-base">${{ number_format($slip->net_salary, 2) }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="badge badge-{{ $slip->status_color }}">{{ ucfirst($slip->status) }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <a href="{{ route('teacher.salary.show', $slip) }}" class="btn-outline px-3 py-1 text-xs rounded-lg">
                            <i class="fas fa-eye mr-1"></i>View Slip
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center text-gray-600">
                        <i class="fas fa-file-invoice-dollar text-4xl mb-3 block opacity-20"></i>
                        <p class="text-gray-500 mb-2">No salary slips yet</p>
                        <p class="text-xs">Slips will appear here once admin generates and approves them</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $slips->links() }}</div>

@endsection
