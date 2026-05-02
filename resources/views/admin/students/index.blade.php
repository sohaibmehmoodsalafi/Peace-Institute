@extends('layouts.dashboard')
@section('title', 'Students')
@section('page-title', 'Students')
@section('page-subtitle', 'Manage all registered students')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Search --}}
<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search by name or email..."
        class="input-dark flex-1 max-w-xs">
    <button type="submit" class="btn-gold">Search</button>
    @if(request('search'))
        <a href="{{ route('admin.students.index') }}" class="btn-outline">Clear</a>
    @endif
</form>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full">
        <thead>
            <tr style="border-bottom:1px solid rgba(212,175,55,.1)">
                <th class="text-left p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Student</th>
                <th class="text-left p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Contact</th>
                <th class="text-left p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Bookings</th>
                <th class="text-left p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                <th class="text-left p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                <th class="text-left p-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($students as $student)
            <tr class="table-row">
                <td class="p-4">
                    <div class="flex items-center gap-3">
                        <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#1A6B3C,#22874D);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem;color:#fff;flex-shrink:0">
                            {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
                        </div>
                        <div>
                            <div class="text-sm font-semibold text-white">{{ $student->user->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">ID #{{ $student->id }}</div>
                        </div>
                    </div>
                </td>
                <td class="p-4">
                    <div class="text-sm text-gray-300">{{ $student->user->email ?? '' }}</div>
                    <div class="text-xs text-gray-500">{{ $student->user->phone ?? '—' }}</div>
                </td>
                <td class="p-4">
                    <span class="text-white font-semibold">{{ $student->bookings_count }}</span>
                </td>
                <td class="p-4 text-sm text-gray-400">
                    {{ $student->created_at?->format('M d, Y') ?? '—' }}
                </td>
                <td class="p-4">
                    @if($student->user?->is_active)
                        <span class="badge badge-approved">Active</span>
                    @else
                        <span class="badge badge-cancelled">Inactive</span>
                    @endif
                </td>
                <td class="p-4">
                    <form action="{{ route('admin.students.toggle', $student) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="{{ $student->user?->is_active ? 'btn-danger' : 'btn-outline' }}" style="font-size:.75rem;padding:5px 12px">
                            {{ $student->user?->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="p-8 text-center text-gray-500">No students found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $students->links() }}</div>
</div>
@endsection
