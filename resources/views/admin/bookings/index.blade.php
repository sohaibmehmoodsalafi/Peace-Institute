@extends('layouts.dashboard')
@section('title', 'All Bookings')
@section('page-title', 'Bookings')
@section('page-subtitle', 'All platform booking activity')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Status filter tabs --}}
<div class="flex gap-2 mb-6 flex-wrap">
    @foreach(['', 'pending', 'approved', 'completed', 'cancelled'] as $s)
        <a href="{{ request()->fullUrlWithQuery(['status' => $s]) }}"
           class="px-4 py-2 rounded-lg text-sm border transition-colors
           {{ request('status', '') === $s ? 'border-gold-DEFAULT bg-gold-DEFAULT/10 text-gold-DEFAULT' : 'border-white/10 text-gray-500 hover:border-white/30' }}">
            {{ $s ? ucfirst($s) : 'All' }}
            @if($s && isset($statusCounts[$s]))
                <span class="ml-1 opacity-60">({{ $statusCounts[$s] }})</span>
            @endif
        </a>
    @endforeach
</div>

{{-- Search / Filter --}}
<div class="flex gap-3 mb-5">
    <form action="" method="GET" class="flex gap-2">
        <input type="hidden" name="status" value="{{ request('status') }}">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by booking ref..." class="input-dark">
        <input type="date" name="date" value="{{ request('date') }}" class="input-dark">
        <button class="btn-gold px-4 py-2 rounded-lg">Search</button>
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                    <th class="text-left px-5 py-4">Ref</th>
                    <th class="text-left px-5 py-4">Student</th>
                    <th class="text-left px-5 py-4">Teacher</th>
                    <th class="text-left px-5 py-4">Course</th>
                    <th class="text-left px-5 py-4">Scheduled</th>
                    <th class="text-left px-5 py-4">Amount</th>
                    <th class="text-left px-5 py-4">Payment</th>
                    <th class="text-left px-5 py-4">Status</th>
                    <th class="text-left px-5 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="table-row">
                        <td class="px-5 py-4 font-mono text-xs text-gold-DEFAULT">{{ $booking->booking_ref }}</td>
                        <td class="px-5 py-4 text-gray-300">{{ $booking->student?->user?->name ?? '—' }}</td>
                        <td class="px-5 py-4 text-gray-300">{{ $booking->teacher?->user?->name ?? '—' }}</td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ $booking->course->name ?? '–' }}</td>
                        <td class="px-5 py-4">
                            <div class="text-gray-300 text-xs">{{ $booking->scheduled_at->format('M d, Y') }}</div>
                            <div class="text-gray-600 text-xs">{{ $booking->scheduled_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-5 py-4 text-gray-300">${{ number_format($booking->amount, 2) }}</td>
                        <td class="px-5 py-4">
                            @if($booking->payment)
                                <span class="badge badge-{{ $booking->payment->isCompleted() ? 'approved' : 'pending' }}">
                                    {{ $booking->payment->status }}
                                </span>
                            @else
                                <span class="text-gray-600 text-xs">No payment</span>
                            @endif
                        </td>
                        <td class="px-5 py-4"><span class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span></td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-xs text-blue-400 hover:underline">Details</a>
                                @if(!$booking->isCompleted() && !$booking->isCancelled())
                                    <button onclick="cancelBooking({{ $booking->id }})" class="text-xs text-red-400 hover:underline">Cancel</button>
                                @endif
                                @if(!$booking->payment || !$booking->payment->isCompleted())
                                    <form action="{{ route('admin.bookings.payment.manual', $booking) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Mark as manually paid?')">
                                        @csrf
                                        <button class="text-xs text-green-400 hover:underline">Mark Paid</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="px-5 py-12 text-center text-gray-600">No bookings found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>

{{-- Cancel Modal --}}
<div id="cancel-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-white font-semibold mb-4">Cancel Booking</h3>
        <form id="cancel-form" method="POST">
            @csrf
            <textarea name="reason" rows="3" required class="input-dark w-full mb-4" placeholder="Reason..."></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')" class="btn-outline flex-1 py-2 rounded-lg">Close</button>
                <button type="submit" class="btn-danger flex-1 py-2 rounded-lg">Confirm</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cancelBooking(id) {
    document.getElementById('cancel-form').action = '/admin/bookings/' + id + '/cancel';
    document.getElementById('cancel-modal').classList.remove('hidden');
}
</script>
@endpush
