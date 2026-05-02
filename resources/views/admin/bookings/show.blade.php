@extends('layouts.dashboard')
@section('title', 'Booking — ' . ($booking->booking_ref ?? '#'.$booking->id))
@section('page-title', 'Booking Details')
@section('page-subtitle', 'Full booking information and actions')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Back + Actions --}}
<div class="flex items-center justify-between mb-6 flex-wrap gap-3">
    <a href="{{ route('admin.bookings.index') }}" class="text-gray-400 hover:text-white flex items-center gap-2 text-sm">
        <i class="fas fa-arrow-left"></i> Back to Bookings
    </a>
    <div class="flex gap-2 flex-wrap">
        @if(!in_array($booking->status, ['completed','cancelled','rejected']))
            <button onclick="document.getElementById('cancel-modal').classList.remove('hidden')"
                class="btn-danger" style="font-size:.8rem;padding:6px 16px">
                <i class="fas fa-times mr-1"></i> Cancel Booking
            </button>
        @endif
        @if(!$booking->payment || $booking->payment->status !== 'completed')
            <form action="{{ route('admin.bookings.payment.manual', $booking) }}" method="POST"
                  onsubmit="return confirm('Mark this booking as manually paid?')">
                @csrf
                <button class="btn-outline" style="font-size:.8rem;padding:6px 16px;color:#22c55e;border-color:#22c55e">
                    <i class="fas fa-check-circle mr-1"></i> Mark as Paid
                </button>
            </form>
        @endif
    </div>
</div>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="mb-5 px-4 py-3 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Summary --}}
    <div class="lg:col-span-1 flex flex-col gap-6">

        {{-- Booking Status Card --}}
        <div class="card p-5">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold">Booking</h3>
                <span class="badge badge-{{ in_array($booking->status,['approved','completed']) ? 'approved' : ($booking->status === 'pending' ? 'pending' : 'cancelled') }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
            <div class="flex flex-col gap-3 text-sm">
                <div>
                    <div class="text-gray-500 text-xs mb-1">Reference</div>
                    <div class="font-mono text-gold-DEFAULT font-semibold">{{ $booking->booking_ref ?? 'BK-'.$booking->id }}</div>
                </div>
                <div>
                    <div class="text-gray-500 text-xs mb-1">Course</div>
                    <div class="text-white">{{ $booking->course->name ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-gray-500 text-xs mb-1">Scheduled</div>
                    <div class="text-gray-200">
                        @if($booking->scheduled_at)
                            {{ $booking->scheduled_at->format('D, M d Y') }}<br>
                            <span class="text-gray-500">{{ $booking->scheduled_at->format('h:i A') }}</span>
                        @else
                            —
                        @endif
                    </div>
                </div>
                <div>
                    <div class="text-gray-500 text-xs mb-1">Duration</div>
                    <div class="text-gray-200">{{ $booking->duration_minutes ?? 60 }} minutes</div>
                </div>
                <div>
                    <div class="text-gray-500 text-xs mb-1">Booked On</div>
                    <div class="text-gray-400">{{ $booking->created_at?->format('M d, Y h:i A') }}</div>
                </div>
            </div>
        </div>

        {{-- Payment Card --}}
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Payment</h3>
            @if($booking->payment)
                <div class="flex flex-col gap-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Amount</span>
                        <span class="text-white font-semibold">${{ number_format($booking->payment->amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Method</span>
                        <span class="text-gray-300">{{ ucfirst(str_replace('_',' ',$booking->payment->payment_method)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="badge badge-{{ $booking->payment->status === 'completed' ? 'approved' : ($booking->payment->status === 'pending' ? 'pending' : 'cancelled') }}">
                            {{ ucfirst($booking->payment->status) }}
                        </span>
                    </div>
                    @if($booking->payment->paid_at)
                    <div class="flex justify-between">
                        <span class="text-gray-500">Paid At</span>
                        <span class="text-gray-400 text-xs">{{ $booking->payment->paid_at->format('M d, Y') }}</span>
                    </div>
                    @endif
                    @if($booking->payment->transaction_ref)
                    <div>
                        <div class="text-gray-500 text-xs mb-1">Transaction Ref</div>
                        <div class="font-mono text-xs text-gray-400 break-all">{{ $booking->payment->transaction_ref }}</div>
                    </div>
                    @endif
                </div>
            @else
                <div class="text-center text-gray-600 py-4">
                    <i class="fas fa-credit-card text-2xl mb-2 block"></i>
                    No payment record
                </div>
            @endif
        </div>

        {{-- Class Session --}}
        @if($booking->classSession)
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Class Session</h3>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Status</span>
                    <span class="badge badge-{{ in_array($booking->classSession->status,['completed']) ? 'approved' : 'pending' }}">
                        {{ ucfirst(str_replace('_',' ',$booking->classSession->status)) }}
                    </span>
                </div>
                @if($booking->classSession->started_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">Started</span>
                    <span class="text-gray-300 text-xs">{{ $booking->classSession->started_at->format('M d, h:i A') }}</span>
                </div>
                @endif
                @if($booking->classSession->ended_at)
                <div class="flex justify-between">
                    <span class="text-gray-500">Ended</span>
                    <span class="text-gray-300 text-xs">{{ $booking->classSession->ended_at->format('M d, h:i A') }}</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-gray-500">Duration</span>
                    <span class="text-gray-300">{{ $booking->classSession->duration ?? 60 }} min</span>
                </div>
                @if($booking->classSession->earned_amount > 0)
                <div class="flex justify-between">
                    <span class="text-gray-500">Earned</span>
                    <span class="text-gold-DEFAULT font-semibold">${{ number_format($booking->classSession->earned_amount, 2) }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif

    </div>

    {{-- Right: People + Notes --}}
    <div class="lg:col-span-2 flex flex-col gap-6">

        {{-- People --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Student --}}
            <div class="card p-5">
                <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Student</h3>
                <div class="flex items-center gap-3 mb-4">
                    <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#1A6B3C,#22874D);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;color:#fff;flex-shrink:0">
                        {{ strtoupper(substr(($booking->student?->user?->name ?? 'S'), 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-white font-semibold">{{ $booking->student?->user?->name ?? 'N/A' }}</div>
                        <div class="text-gray-500 text-xs">{{ $booking->student?->user?->email ?? '' }}</div>
                    </div>
                </div>
                @if($booking->student)
                <a href="{{ route('admin.students.show', $booking->student) }}" class="text-xs text-blue-400 hover:underline">
                    <i class="fas fa-external-link-alt mr-1"></i>View Profile
                </a>
                @endif
            </div>

            {{-- Teacher --}}
            <div class="card p-5">
                <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Teacher</h3>
                <div class="flex items-center gap-3 mb-4">
                    @php $tAvatar = $booking->teacher?->user?->avatar_url ?? null; @endphp
                    @if($tAvatar)
                        <img src="{{ $tAvatar }}" style="width:44px;height:44px;border-radius:50%;object-fit:cover;flex-shrink:0" alt="">
                    @else
                        <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#92400E,#B45309);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.1rem;color:#fff;flex-shrink:0">
                            {{ strtoupper(substr(($booking->teacher?->user?->name ?? 'T'), 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <div class="text-white font-semibold">{{ $booking->teacher?->user?->name ?? 'N/A' }}</div>
                        <div class="text-gray-500 text-xs">{{ $booking->teacher?->user?->email ?? '' }}</div>
                    </div>
                </div>
                @if($booking->teacher)
                <a href="{{ route('admin.teachers.show', $booking->teacher) }}" class="text-xs text-blue-400 hover:underline">
                    <i class="fas fa-external-link-alt mr-1"></i>View Profile
                </a>
                @endif
            </div>
        </div>

        {{-- Notes --}}
        @if($booking->notes)
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-3">Student Notes</h3>
            <p class="text-gray-300 text-sm leading-relaxed">{{ $booking->notes }}</p>
        </div>
        @endif

        {{-- Review --}}
        @if($booking->review)
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Review</h3>
            <div class="flex items-center gap-3 mb-3">
                <div class="flex gap-1">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star text-sm {{ $i <= $booking->review->rating ? 'text-gold-DEFAULT' : 'text-gray-700' }}"></i>
                    @endfor
                </div>
                <span class="text-gold-DEFAULT font-semibold">{{ $booking->review->rating }}/5</span>
            </div>
            @if($booking->review->comment)
                <p class="text-gray-300 text-sm leading-relaxed">{{ $booking->review->comment }}</p>
            @endif
            <div class="text-gray-500 text-xs mt-3">By {{ $booking->student?->user?->name ?? 'Student' }} · {{ $booking->review->created_at?->format('M d, Y') }}</div>
        </div>
        @endif

        {{-- Timeline --}}
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Activity Timeline</h3>
            <div class="flex flex-col gap-3">
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-blue-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-plus text-blue-400" style="font-size:.6rem"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-300">Booking Created</div>
                        <div class="text-xs text-gray-500">{{ $booking->created_at?->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @if(in_array($booking->status, ['approved','completed']))
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-check text-green-400" style="font-size:.6rem"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-300">Booking Approved</div>
                        <div class="text-xs text-gray-500">{{ $booking->updated_at?->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @endif
                @if($booking->payment && $booking->payment->status === 'completed')
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-gold-DEFAULT/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-dollar-sign text-gold-DEFAULT" style="font-size:.6rem"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-300">Payment Received — ${{ number_format($booking->payment->amount, 2) }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->payment->paid_at?->format('M d, Y h:i A') ?? 'Date unknown' }}</div>
                    </div>
                </div>
                @endif
                @if($booking->status === 'completed')
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-green-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-graduation-cap text-green-400" style="font-size:.6rem"></i>
                    </div>
                    <div>
                        <div class="text-sm text-gray-300">Session Completed</div>
                        <div class="text-xs text-gray-500">{{ $booking->updated_at?->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @endif
                @if(in_array($booking->status, ['cancelled','rejected']))
                <div class="flex items-start gap-3">
                    <div class="w-6 h-6 rounded-full bg-red-500/20 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <i class="fas fa-times text-red-400" style="font-size:.6rem"></i>
                    </div>
                    <div>
                        <div class="text-sm text-red-300">Booking {{ ucfirst($booking->status) }}</div>
                        <div class="text-xs text-gray-500">{{ $booking->updated_at?->format('M d, Y h:i A') }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

{{-- Cancel Modal --}}
<div id="cancel-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-white font-semibold mb-4">Cancel Booking</h3>
        <form action="{{ route('admin.bookings.cancel', $booking) }}" method="POST">
            @csrf
            <textarea name="reason" rows="3" required class="input-dark w-full mb-4" placeholder="Reason for cancellation..."></textarea>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('cancel-modal').classList.add('hidden')"
                    class="btn-outline flex-1 py-2 rounded-lg">Close</button>
                <button type="submit" class="btn-danger flex-1 py-2 rounded-lg">Confirm Cancel</button>
            </div>
        </form>
    </div>
</div>

@endsection
