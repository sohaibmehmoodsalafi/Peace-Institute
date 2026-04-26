@extends('layouts.dashboard')
@section('title', 'Earnings Management')
@section('page-title', 'Earnings')
@section('page-subtitle', 'Review, approve and manage teacher earnings')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Summary --}}
<div class="grid grid-cols-3 gap-4 mb-8">
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-yellow-400">${{ number_format($summary['total_pending'], 2) }}</div>
        <div class="text-gray-400 text-sm mt-1">Pending Approval</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-green-400">${{ number_format($summary['total_approved'], 2) }}</div>
        <div class="text-gray-400 text-sm mt-1">Approved (Payable)</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-gray-300">${{ number_format($summary['total_paid'], 2) }}</div>
        <div class="text-gray-400 text-sm mt-1">Already Paid Out</div>
    </div>
</div>

{{-- Bulk Approve Form --}}
<form action="{{ route('admin.earnings.approve') }}" method="POST" id="earnings-form">
    @csrf

    <div class="flex items-center justify-between mb-4">
        <div class="flex gap-2">
            <select name="teacher_id" class="input-dark">
                <option value="">All Teachers</option>
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}">{{ $t->user->name }}</option>
                @endforeach
            </select>
            <select name="status" class="input-dark">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="paid">Paid</option>
            </select>
            <button class="btn-outline px-4 py-2 rounded-lg" type="submit" form="filter-form">Filter</button>
        </div>
        <button type="submit" class="btn-gold px-5 py-2 rounded-lg text-sm">
            <i class="fas fa-check-circle mr-1"></i> Approve Selected
        </button>
    </div>

    <div class="card overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                    <th class="px-5 py-4 w-8">
                        <input type="checkbox" id="select-all" onchange="toggleAll(this)">
                    </th>
                    <th class="text-left px-5 py-4">Date</th>
                    <th class="text-left px-5 py-4">Teacher</th>
                    <th class="text-left px-5 py-4">Duration</th>
                    <th class="text-left px-5 py-4">Rate</th>
                    <th class="text-left px-5 py-4">Gross</th>
                    <th class="text-left px-5 py-4">Platform Fee</th>
                    <th class="text-left px-5 py-4">Net</th>
                    <th class="text-left px-5 py-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($earnings as $earning)
                    <tr class="table-row">
                        <td class="px-5 py-4">
                            @if($earning->status === 'pending')
                                <input type="checkbox" name="earning_ids[]" value="{{ $earning->id }}" class="earning-checkbox">
                            @endif
                        </td>
                        <td class="px-5 py-4 text-gray-400 text-xs">{{ $earning->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-4 text-gray-300">{{ $earning->teacher->user->name }}</td>
                        <td class="px-5 py-4 text-gray-400">{{ number_format($earning->session_duration_hours, 2) }}h</td>
                        <td class="px-5 py-4 text-gray-400">${{ number_format($earning->hourly_rate, 2) }}/hr</td>
                        <td class="px-5 py-4 text-gray-300">${{ number_format($earning->amount, 2) }}</td>
                        <td class="px-5 py-4 text-red-400">-${{ number_format($earning->platform_fee, 2) }}</td>
                        <td class="px-5 py-4 text-gold-DEFAULT font-semibold">${{ number_format($earning->net_amount, 2) }}</td>
                        <td class="px-5 py-4"><span class="badge badge-{{ $earning->status === 'approved' ? 'approved' : ($earning->status === 'paid' ? 'completed' : 'pending') }}">{{ $earning->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-5 py-12 text-center text-gray-600">No earnings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</form>
<div class="mt-4">{{ $earnings->links() }}</div>
@endsection

@push('scripts')
<script>
function toggleAll(master) {
    document.querySelectorAll('.earning-checkbox').forEach(cb => cb.checked = master.checked);
}
</script>
@endpush
