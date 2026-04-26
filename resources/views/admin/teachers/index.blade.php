@extends('layouts.dashboard')
@section('title', 'Manage Teachers')
@section('page-title', 'Teachers')
@section('page-subtitle', 'Approve, manage and monitor all teachers')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Filters --}}
<div class="flex flex-wrap gap-3 mb-6">
    <form action="" method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search teachers..." class="input-dark">
        <select name="status" class="input-dark">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
        </select>
        <button class="btn-gold px-4 py-2 rounded-lg">Filter</button>
    </form>
</div>

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                <th class="text-left px-5 py-4">Teacher</th>
                <th class="text-left px-5 py-4">Specialization</th>
                <th class="text-left px-5 py-4">Rate</th>
                <th class="text-left px-5 py-4">Sessions</th>
                <th class="text-left px-5 py-4">Total Earned</th>
                <th class="text-left px-5 py-4">Status</th>
                <th class="text-left px-5 py-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($teachers as $teacher)
                <tr class="table-row">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ $teacher->user->avatar_url }}" class="w-9 h-9 rounded-full" alt="">
                            <div>
                                <div class="text-white font-medium">{{ $teacher->user->name }}</div>
                                <div class="text-gray-500 text-xs">{{ $teacher->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-400">{{ $teacher->specialization ?? '–' }}</td>
                    <td class="px-5 py-4">
                        <span class="text-gold-DEFAULT font-semibold">${{ number_format($teacher->hourly_rate, 2) }}</span>
                        <button onclick="openRateModal({{ $teacher->id }}, {{ $teacher->hourly_rate }})"
                            class="ml-1 text-gray-600 hover:text-gold-DEFAULT"><i class="fas fa-edit text-xs"></i></button>
                    </td>
                    <td class="px-5 py-4 text-gray-300">{{ $teacher->completed_sessions_count }}</td>
                    <td class="px-5 py-4 text-gray-300">${{ number_format($teacher->total_earnings, 2) }}</td>
                    <td class="px-5 py-4"><span class="badge badge-{{ $teacher->status === 'approved' ? 'approved' : ($teacher->status === 'pending' ? 'pending' : 'cancelled') }}">{{ $teacher->status }}</span></td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.teachers.show', $teacher) }}" class="text-xs text-blue-400 hover:underline">View</a>
                            @if($teacher->status === 'pending')
                                <form action="{{ route('admin.teachers.approve', $teacher) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-xs text-green-400 hover:underline">Approve</button>
                                </form>
                            @elseif($teacher->status === 'approved')
                                <form action="{{ route('admin.teachers.suspend', $teacher) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-xs text-red-400 hover:underline">Suspend</button>
                                </form>
                            @elseif($teacher->status === 'suspended')
                                <form action="{{ route('admin.teachers.approve', $teacher) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-xs text-green-400 hover:underline">Reinstate</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-600">No teachers found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $teachers->links() }}</div>

{{-- Rate Edit Modal --}}
<div id="rate-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-white font-semibold mb-4">Update Hourly Rate</h3>
        <form id="rate-form" method="POST">
            @csrf
            <input type="number" name="hourly_rate" id="rate-input" step="0.50" min="0" class="input-dark w-full mb-4">
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('rate-modal').classList.add('hidden')" class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-gold flex-1 py-2 rounded-lg">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRateModal(teacherId, currentRate) {
    document.getElementById('rate-form').action = '/admin/teachers/' + teacherId + '/rate';
    document.getElementById('rate-input').value = currentRate;
    document.getElementById('rate-modal').classList.remove('hidden');
}
</script>
@endpush
