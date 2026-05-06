@extends('layouts.dashboard')
@section('title', 'My Classes')
@section('page-title', 'My Classes')

@section('sidebar-nav')
    <a href="{{ route('student.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('student.enrollments.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-book-open"></i></span> My Enrollments</a>
    <a href="{{ route('teachers') }}" class="sidebar-link"><span class="icon"><i class="fas fa-search"></i></span> Find Teachers</a>
    <a href="{{ route('student.bookings.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-calendar-alt"></i></span> My Classes</a>
@endsection

@section('content')

<div class="flex items-center justify-between mb-6">
    <p class="text-gray-500 text-sm">{{ $bookings->total() }} total classes</p>
    <a href="{{ route('teachers') }}" class="btn-gold px-4 py-2 text-sm rounded-lg">
        <i class="fas fa-plus mr-1"></i> Schedule Class
    </a>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                    <th class="text-left px-5 py-4">Class</th>
                    <th class="text-left px-5 py-4">Teacher</th>
                    <th class="text-left px-5 py-4">Course</th>
                    <th class="text-left px-5 py-4">Date & Time</th>
                    <th class="text-left px-5 py-4">Amount</th>
                    <th class="text-left px-5 py-4">Status</th>
                    <th class="text-left px-5 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $booking)
                    <tr class="table-row">
                        <td class="px-5 py-4">
                            <div class="font-mono text-xs text-gold-DEFAULT">{{ $booking->booking_ref }}</div>
                            <div class="text-gray-600 text-xs">{{ $booking->duration }} min</div>
                        </td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <img src="{{ $booking->teacher?->user?->avatar_url ?? asset('images/default-avatar.png') }}" class="w-7 h-7 rounded-full" alt="">
                                <span class="text-gray-300">{{ $booking->teacher?->user?->name ?? '—' }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4 text-gray-400">{{ $booking->course->name ?? '–' }}</td>
                        <td class="px-5 py-4">
                            <div class="text-gray-300 text-xs">{{ $booking->scheduled_at->format('M d, Y') }}</div>
                            <div class="text-gray-500 text-xs">{{ $booking->scheduled_at->format('h:i A') }}</div>
                        </td>
                        <td class="px-5 py-4 text-gray-300">${{ number_format($booking->amount, 2) }}</td>
                        <td class="px-5 py-4"><span class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span></td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                @if($booking->isApproved() && $booking->meeting_link)
                                    <a href="{{ $booking->meeting_link }}" target="_blank"
                                        class="text-xs bg-blue-900/30 border border-blue-500/30 text-blue-400 px-3 py-1 rounded-full">
                                        <i class="fas fa-video mr-1"></i>Join
                                    </a>
                                @endif
                                @if(!$booking->isCompleted() && !$booking->isCancelled())
                                    <button onclick="cancelBooking({{ $booking->id }})"
                                        class="text-xs text-red-400 hover:underline">Cancel</button>
                                @endif
                                @if($booking->isCompleted() && !$booking->review)
                                    <button onclick="openReviewModal({{ $booking->id }})"
                                        class="text-xs text-gold-DEFAULT hover:underline">Review</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center text-gray-600">
                            <i class="fas fa-calendar-times text-4xl mb-3 block text-gold-DEFAULT/20"></i>
                            No classes yet.
                            <a href="{{ route('teachers') }}" class="text-gold-DEFAULT hover:underline ml-1">Schedule your first class</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">{{ $bookings->links() }}</div>

{{-- Cancel Modal --}}
<div id="cancel-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-white font-semibold mb-4">Cancel Class</h3>
        <form id="cancel-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-400 text-sm mb-2">Reason for cancellation</label>
                <textarea name="reason" rows="3" required class="input-dark w-full" placeholder="Please provide a reason..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')" class="btn-outline flex-1 py-2 rounded-lg">Keep Class</button>
                <button type="submit" class="btn-danger flex-1 py-2 rounded-lg">Confirm Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- Review Modal --}}
<div id="review-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-md mx-4">
        <h3 class="text-white font-semibold mb-4">Leave a Review</h3>
        <form id="review-form" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-400 text-sm mb-2">Rating</label>
                <div class="flex gap-2" id="star-rating">
                    @for($i=1;$i<=5;$i++)
                        <button type="button" onclick="setRating({{ $i }})" class="text-3xl text-gray-600 hover:text-yellow-400 transition-colors">&#9733;</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input">
            </div>
            <div>
                <textarea name="comment" rows="3" class="input-dark w-full" placeholder="Share your experience..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('review-modal').classList.add('hidden')" class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-gold flex-1 py-2 rounded-lg">Submit</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function cancelBooking(id) {
    document.getElementById('cancel-form').action = '/student/bookings/' + id + '/cancel';
    document.getElementById('cancel-modal').classList.remove('hidden');
}
function openReviewModal(id) {
    document.getElementById('review-form').action = '/student/bookings/' + id + '/review';
    document.getElementById('review-modal').classList.remove('hidden');
}
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('#star-rating button').forEach((btn, i) => {
        btn.style.color = i < val ? '#facc15' : '';
    });
}
</script>
@endpush
