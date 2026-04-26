@extends('layouts.dashboard')
@section('title', 'Student Dashboard')
@section('page-title', 'My Dashboard')
@section('page-subtitle', 'Track your learning journey')

@section('sidebar-nav')
    <a href="{{ route('student.dashboard') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teachers') }}" class="sidebar-link"><span class="icon"><i class="fas fa-search"></i></span> Find Teachers</a>
    <a href="{{ route('student.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> My Bookings</a>
@endsection

@section('content')

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-white">{{ $stats['total_sessions'] }}</div>
        <div class="text-gray-300 text-sm">Total Sessions</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-green-400">{{ $stats['completed_sessions'] }}</div>
        <div class="text-gray-300 text-sm">Completed</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-gold-DEFAULT">{{ $stats['enrolled_courses'] }}</div>
        <div class="text-gray-300 text-sm">Courses Enrolled</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-2xl font-bold text-white">${{ number_format($stats['total_spent'], 0) }}</div>
        <div class="text-gray-300 text-sm">Total Invested</div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Upcoming Classes --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-semibold">Upcoming Classes</h3>
            <a href="{{ route('teachers') }}" class="btn-gold px-4 py-2 text-xs rounded-lg">
                <i class="fas fa-plus mr-1"></i> Book Session
            </a>
        </div>
        @forelse($upcomingBookings as $booking)
            <div class="table-row py-4">
                <div class="flex items-start gap-3">
                    <img src="{{ $booking->teacher->user->avatar_url }}" class="w-10 h-10 rounded-full flex-shrink-0" alt="">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm font-medium">{{ $booking->teacher->user->name }}</div>
                        <div class="text-gray-400 text-xs">{{ $booking->course->name ?? 'General Session' }}</div>
                        <div class="text-gold-DEFAULT text-xs mt-1">
                            <i class="fas fa-calendar mr-1"></i>
                            {{ $booking->scheduled_at->format('D, M d Y \a\t h:i A') }}
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="badge badge-{{ $booking->status }}">{{ $booking->status }}</span>
                        @if($booking->isApproved() && $booking->meeting_link)
                            <div class="mt-2">
                                <a href="{{ $booking->meeting_link }}" target="_blank"
                                   class="text-xs bg-blue-900/40 border border-blue-500/40 text-blue-400 px-3 py-1 rounded-full hover:bg-blue-900/60">
                                    <i class="fas fa-video mr-1"></i> Join
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-600 py-8">
                <i class="fas fa-calendar-plus text-4xl mb-3 text-gold-DEFAULT/30"></i>
                <p class="text-gray-500 mb-4">No upcoming classes yet</p>
                <a href="{{ route('teachers') }}" class="btn-gold px-6 py-2 rounded-lg text-sm inline-block">Book Your First Class</a>
            </div>
        @endforelse
    </div>

    {{-- Recent History --}}
    <div class="card p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="text-white font-semibold">Recent History</h3>
            <a href="{{ route('student.bookings.index') }}" class="text-gold-DEFAULT text-xs hover:underline">View all</a>
        </div>
        @forelse($recentHistory as $booking)
            <div class="table-row py-4">
                <div class="flex items-center gap-3">
                    <img src="{{ $booking->teacher->user->avatar_url }}" class="w-9 h-9 rounded-full" alt="">
                    <div class="flex-1 min-w-0">
                        <div class="text-white text-sm">{{ $booking->teacher->user->name }}</div>
                        <div class="text-gray-500 text-xs">{{ $booking->scheduled_at->format('M d, Y') }}</div>
                    </div>
                    <div class="text-right">
                        <div class="text-gray-300 text-sm">${{ number_format($booking->amount, 2) }}</div>
                        @if(!$booking->review)
                            <button onclick="openReviewModal({{ $booking->id }})"
                                class="text-xs text-gold-DEFAULT hover:underline mt-1">Leave Review</button>
                        @else
                            <div class="flex gap-0.5 mt-1 justify-end">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star text-xs {{ $i <= $booking->review->rating ? 'text-yellow-400' : 'text-gray-700' }}"></i>
                                @endfor
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-600 text-center py-8">No completed sessions yet</p>
        @endforelse
    </div>

</div>

{{-- Enrolled Courses --}}
@if($enrolledCourses->count())
<div class="mt-6 card p-6">
    <h3 class="text-white font-semibold mb-5">My Courses</h3>
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach($enrolledCourses as $course)
            <div class="bg-surface-200 rounded-xl p-4 text-center border border-white/5 hover:border-gold-DEFAULT/20 transition-colors">
                <div class="w-10 h-10 gold-gradient rounded-lg flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-book text-black text-sm"></i>
                </div>
                <div class="text-white text-sm font-medium">{{ $course->name }}</div>
                <div class="mt-2">
                    <div class="text-xs text-gray-500 mb-1">{{ $course->pivot->progress_percentage }}%</div>
                    <div class="h-1 bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full gold-gradient rounded-full" style="width: {{ $course->pivot->progress_percentage }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

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
                        <button type="button" onclick="setRating({{ $i }})" data-star="{{ $i }}"
                            class="text-3xl text-gray-600 hover:text-yellow-400 transition-colors">&#9733;</button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating-input">
            </div>
            <div>
                <label class="block text-gray-400 text-sm mb-2">Comment (optional)</label>
                <textarea name="comment" rows="3" class="input-dark w-full" placeholder="Share your experience..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeReviewModal()" class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-gold flex-1 py-2 rounded-lg">Submit Review</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openReviewModal(bookingId) {
    document.getElementById('review-form').action = '/student/bookings/' + bookingId + '/review';
    document.getElementById('review-modal').classList.remove('hidden');
}
function closeReviewModal() {
    document.getElementById('review-modal').classList.add('hidden');
}
function setRating(val) {
    document.getElementById('rating-input').value = val;
    document.querySelectorAll('#star-rating button').forEach((btn, i) => {
        btn.style.color = i < val ? '#facc15' : '';
    });
}
</script>
@endpush
