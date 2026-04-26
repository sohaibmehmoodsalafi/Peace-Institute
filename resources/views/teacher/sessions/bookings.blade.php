@extends('layouts.dashboard')
@section('title', 'Manage Sessions')
@section('page-title', 'Booking Requests')
@section('page-subtitle', 'Approve, reject and manage student bookings')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-calendar-check"></i></span> Sessions</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
@endsection

@section('content')

<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                <th class="text-left px-5 py-4">Ref</th>
                <th class="text-left px-5 py-4">Student</th>
                <th class="text-left px-5 py-4">Course</th>
                <th class="text-left px-5 py-4">Date & Time</th>
                <th class="text-left px-5 py-4">Duration</th>
                <th class="text-left px-5 py-4">Status</th>
                <th class="text-left px-5 py-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
                <tr class="table-row">
                    <td class="px-5 py-4 font-mono text-xs text-gold-DEFAULT">{{ $booking->booking_ref }}</td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2">
                            <img src="{{ $booking->student->user->avatar_url }}" class="w-8 h-8 rounded-full" alt="">
                            <span class="text-gray-300">{{ $booking->student->user->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-400">{{ $booking->course->name ?? '–' }}</td>
                    <td class="px-5 py-4">
                        <div class="text-gray-300 text-xs">{{ $booking->scheduled_at->format('M d, Y') }}</div>
                        <div class="text-gray-500 text-xs">{{ $booking->scheduled_at->format('h:i A') }}</div>
                    </td>
                    <td class="px-5 py-4 text-gray-400">{{ $booking->duration }} min</td>
                    <td class="px-5 py-4"><span class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span></td>
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-2 flex-wrap">
                            @if($booking->isPending())
                                <form action="{{ route('teacher.sessions.approve', $booking) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="text-xs bg-green-900/30 border border-green-500/30 text-green-400 px-3 py-1 rounded-full hover:bg-green-900/50">
                                        <i class="fas fa-check mr-1"></i> Approve
                                    </button>
                                </form>
                                <button onclick="openRejectModal({{ $booking->id }})"
                                    class="text-xs bg-red-900/30 border border-red-500/30 text-red-400 px-3 py-1 rounded-full hover:bg-red-900/50">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            @endif
                            @if($booking->isApproved())
                                @php $session = $booking->classSession; @endphp
                                @if($session && $session->status !== 'completed')
                                    <form action="{{ route('teacher.sessions.complete', $session) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-xs bg-purple-900/30 border border-purple-500/30 text-purple-400 px-3 py-1 rounded-full hover:bg-purple-900/50">
                                            <i class="fas fa-check-double mr-1"></i> Mark Complete
                                        </button>
                                    </form>
                                @endif
                                @if($booking->meeting_link)
                                    <a href="{{ $booking->meeting_link }}" target="_blank"
                                        class="text-xs text-blue-400 hover:underline">
                                        <i class="fas fa-video mr-1"></i> Join
                                    </a>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-600">No bookings yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>

{{-- Reject Modal --}}
<div id="reject-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-white font-semibold mb-4">Reject Booking</h3>
        <form id="reject-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Reason</label>
                <textarea name="reason" rows="3" required class="input-dark w-full" placeholder="Why are you rejecting this booking?"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')" class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-danger flex-1 py-2 rounded-lg">Reject</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openRejectModal(bookingId) {
    document.getElementById('reject-form').action = '/teacher/sessions/' + bookingId + '/reject';
    document.getElementById('reject-modal').classList.remove('hidden');
}
</script>
@endpush
