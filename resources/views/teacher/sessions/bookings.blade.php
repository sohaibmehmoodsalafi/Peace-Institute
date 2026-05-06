@extends('layouts.dashboard')
@section('title', 'Manage Classes')
@section('page-title', 'Class Requests')
@section('page-subtitle', 'Approve, reject and manage student class requests')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.enrollments') }}" class="sidebar-link"><span class="icon"><i class="fas fa-book-open"></i></span> Enrollments</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-calendar-check"></i></span> Classes</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> Class History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('teacher.salary.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary</a>
    <a href="{{ route('teacher.profile') }}" class="sidebar-link"><span class="icon"><i class="fas fa-user-circle"></i></span> Profile</a>
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
                            <img src="{{ $booking->student->user?->avatar_url ?? asset('images/default-avatar.png') }}" class="w-8 h-8 rounded-full" alt="">
                            <span class="text-gray-300">{{ $booking->student->user?->name ?? '—' }}</span>
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
                                {{-- Approve button opens modal --}}
                                <button onclick="openApproveModal({{ $booking->id }}, '{{ addslashes($booking->booking_ref) }}')"
                                    class="text-xs bg-green-900/30 border border-green-500/30 text-green-400 px-3 py-1 rounded-full hover:bg-green-900/50">
                                    <i class="fas fa-check mr-1"></i> Approve
                                </button>
                                <button onclick="openRejectModal({{ $booking->id }})"
                                    class="text-xs bg-red-900/30 border border-red-500/30 text-red-400 px-3 py-1 rounded-full hover:bg-red-900/50">
                                    <i class="fas fa-times mr-1"></i> Reject
                                </button>
                            @endif
                            @if($booking->isApproved())
                                {{-- Update meeting link --}}
                                <button onclick="openLinkModal({{ $booking->id }}, '{{ addslashes($booking->meeting_link ?? '') }}')"
                                    class="text-xs bg-blue-900/30 border border-blue-500/30 text-blue-400 px-3 py-1 rounded-full hover:bg-blue-900/50">
                                    <i class="fas fa-link mr-1"></i> {{ $booking->meeting_link ? 'Edit Link' : 'Add Link' }}
                                </button>
                                @if($booking->meeting_link)
                                    <a href="{{ $booking->meeting_link }}" target="_blank"
                                        class="text-xs bg-purple-900/30 border border-purple-500/30 text-purple-400 px-3 py-1 rounded-full hover:bg-purple-900/50">
                                        <i class="fas fa-video mr-1"></i> Join
                                    </a>
                                @endif
                                @php $session = $booking->classSession; @endphp
                                @if($session && $session->status !== 'completed')
                                    <form action="{{ route('teacher.sessions.complete', $session) }}" method="POST" class="inline">
                                        @csrf
                                        <button class="text-xs bg-purple-900/30 border border-purple-500/30 text-purple-400 px-3 py-1 rounded-full hover:bg-purple-900/50">
                                            <i class="fas fa-check-double mr-1"></i> Done
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="px-5 py-12 text-center text-gray-600">No classes yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-4">{{ $bookings->links() }}</div>

{{-- Approve Modal (with meeting link) --}}
<div id="approve-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-white font-semibold mb-1">Approve Booking</h3>
        <p id="approve-ref" class="text-gray-500 text-xs mb-5 font-mono"></p>
        <form id="approve-form" method="POST">
            @csrf
            <div class="mb-5">
                <label class="block text-gray-400 text-sm mb-2">
                    <i class="fas fa-video mr-1 text-blue-400"></i> Meeting Link
                    <span class="text-gray-600 font-normal">(Zoom / Google Meet)</span>
                </label>
                <input type="url" name="meeting_link" id="approve-link"
                    placeholder="https://zoom.us/j/... or https://meet.google.com/..."
                    class="input-dark w-full">
                <p class="text-gray-600 text-xs mt-1">The student will see this link in their dashboard to join the class.</p>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('approve-modal').classList.add('hidden')"
                    class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-gold flex-1 py-2 rounded-lg">
                    <i class="fas fa-check mr-1"></i> Approve Session
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Meeting Link Update Modal --}}
<div id="link-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-white font-semibold mb-4">
            <i class="fas fa-video mr-2 text-blue-400"></i> Update Meeting Link
        </h3>
        <form id="link-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Zoom / Google Meet Link</label>
                <input type="url" name="meeting_link" id="link-input"
                    placeholder="https://zoom.us/j/... or https://meet.google.com/..."
                    class="input-dark w-full">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('link-modal').classList.add('hidden')"
                    class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-gold flex-1 py-2 rounded-lg">Save Link</button>
            </div>
        </form>
    </div>
</div>

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
                <button type="button" onclick="document.getElementById('reject-modal').classList.add('hidden')"
                    class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-danger flex-1 py-2 rounded-lg">Reject</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openApproveModal(bookingId, ref) {
    document.getElementById('approve-form').action = '/teacher/sessions/' + bookingId + '/approve';
    document.getElementById('approve-ref').textContent  = 'Booking: ' + ref;
    document.getElementById('approve-link').value = '';
    document.getElementById('approve-modal').classList.remove('hidden');
}
function openLinkModal(bookingId, currentLink) {
    document.getElementById('link-form').action = '/teacher/sessions/' + bookingId + '/link';
    document.getElementById('link-input').value  = currentLink;
    document.getElementById('link-modal').classList.remove('hidden');
}
function openRejectModal(bookingId) {
    document.getElementById('reject-form').action = '/teacher/sessions/' + bookingId + '/reject';
    document.getElementById('reject-modal').classList.remove('hidden');
}
</script>
@endpush
