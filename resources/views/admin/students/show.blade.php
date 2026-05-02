@extends('layouts.dashboard')
@section('title', 'Student — ' . ($student->user->name ?? 'N/A'))
@section('page-title', 'Student Profile')
@section('page-subtitle', 'View student details and booking history')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Back + Actions --}}
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.students.index') }}" class="text-gray-400 hover:text-white flex items-center gap-2 text-sm">
        <i class="fas fa-arrow-left"></i> Back to Students
    </a>
    <form action="{{ route('admin.students.toggle', $student) }}" method="POST">
        @csrf
        <button class="{{ $student->user?->is_active ? 'btn-danger' : 'btn-outline' }}" style="font-size:.8rem;padding:6px 16px">
            <i class="fas fa-{{ $student->user?->is_active ? 'ban' : 'check' }} mr-1"></i>
            {{ $student->user?->is_active ? 'Deactivate Account' : 'Activate Account' }}
        </button>
    </form>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Profile Card --}}
    <div class="lg:col-span-1 flex flex-col gap-6">

        <div class="card p-6 text-center">
            <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#1A6B3C,#22874D);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.6rem;color:#fff;margin:0 auto 12px">
                {{ strtoupper(substr($student->user->name ?? 'S', 0, 1)) }}
            </div>
            <div class="text-white font-semibold text-lg">{{ $student->user->name ?? 'N/A' }}</div>
            <div class="text-gray-400 text-sm mt-1">{{ $student->user->email ?? '' }}</div>
            <div class="text-gray-500 text-xs mt-1">{{ $student->user->phone ?? '—' }}</div>
            <div class="mt-3">
                @if($student->user?->is_active)
                    <span class="badge badge-approved">Active</span>
                @else
                    <span class="badge badge-cancelled">Inactive</span>
                @endif
            </div>
        </div>

        {{-- Stats --}}
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Summary</h3>
            <div class="flex flex-col gap-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Total Bookings</span>
                    <span class="text-white font-semibold">{{ $student->bookings->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Completed</span>
                    <span class="text-green-400 font-semibold">{{ $student->bookings->where('status','completed')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Pending</span>
                    <span class="text-yellow-400 font-semibold">{{ $student->bookings->where('status','pending')->count() }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Cancelled</span>
                    <span class="text-red-400 font-semibold">{{ $student->bookings->whereIn('status',['cancelled','rejected'])->count() }}</span>
                </div>
                <div class="flex justify-between items-center border-t border-white/5 pt-3">
                    <span class="text-gray-400 text-sm">Total Paid</span>
                    <span class="text-gold-DEFAULT font-semibold">${{ number_format($student->payments->where('status','completed')->sum('amount'), 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400 text-sm">Member Since</span>
                    <span class="text-gray-300 text-sm">{{ $student->created_at?->format('M d, Y') ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Enrolled Courses --}}
        @if($student->courses->count())
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Enrolled Courses</h3>
            <div class="flex flex-col gap-2">
                @foreach($student->courses as $course)
                    <div class="flex items-center gap-2 text-sm text-gray-300">
                        <i class="fas fa-book-open text-gold-DEFAULT text-xs"></i>
                        {{ $course->name }}
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>

    {{-- Right: Bookings History --}}
    <div class="lg:col-span-2 flex flex-col gap-6">

        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5">
                <h3 class="text-white font-semibold">Booking History</h3>
            </div>
            @if($student->bookings->isEmpty())
                <div class="p-10 text-center text-gray-600">
                    <i class="fas fa-calendar-times text-3xl mb-3 block"></i>
                    No bookings yet.
                </div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                            <th class="text-left px-5 py-3">Ref</th>
                            <th class="text-left px-5 py-3">Teacher</th>
                            <th class="text-left px-5 py-3">Course</th>
                            <th class="text-left px-5 py-3">Date</th>
                            <th class="text-left px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($student->bookings->sortByDesc('created_at') as $booking)
                        <tr class="table-row">
                            <td class="px-5 py-3 font-mono text-xs text-gold-DEFAULT">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="hover:underline">
                                    {{ $booking->booking_ref ?? '#'.$booking->id }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-gray-300">{{ $booking->teacher->user->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $booking->course->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-400 text-xs">
                                {{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y') : ($booking->created_at?->format('M d, Y') ?? '—') }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="badge badge-{{ $booking->status === 'approved' ? 'approved' : ($booking->status === 'completed' ? 'approved' : ($booking->status === 'pending' ? 'pending' : 'cancelled')) }}">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        {{-- Payments --}}
        @if($student->payments->count())
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5">
                <h3 class="text-white font-semibold">Payment History</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                        <th class="text-left px-5 py-3">Ref</th>
                        <th class="text-left px-5 py-3">Amount</th>
                        <th class="text-left px-5 py-3">Method</th>
                        <th class="text-left px-5 py-3">Status</th>
                        <th class="text-left px-5 py-3">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student->payments->sortByDesc('created_at') as $payment)
                    <tr class="table-row">
                        <td class="px-5 py-3 font-mono text-xs text-gray-400">{{ $payment->transaction_ref }}</td>
                        <td class="px-5 py-3 text-white font-semibold">${{ number_format($payment->amount, 2) }}</td>
                        <td class="px-5 py-3 text-gray-400 text-xs">{{ ucfirst(str_replace('_',' ',$payment->payment_method)) }}</td>
                        <td class="px-5 py-3">
                            <span class="badge badge-{{ $payment->status === 'completed' ? 'approved' : ($payment->status === 'pending' ? 'pending' : 'cancelled') }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-gray-500 text-xs">{{ $payment->created_at?->format('M d, Y') ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

    </div>
</div>

@endsection
