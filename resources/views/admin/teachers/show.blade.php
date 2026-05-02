@extends('layouts.dashboard')
@section('title', 'Teacher — ' . ($teacher->user->name ?? 'N/A'))
@section('page-title', 'Teacher Profile')
@section('page-subtitle', 'View teacher details, bookings and earnings')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Back + Actions --}}
<div class="flex items-center justify-between mb-6">
    <a href="{{ route('admin.teachers.index') }}" class="text-gray-400 hover:text-white flex items-center gap-2 text-sm">
        <i class="fas fa-arrow-left"></i> Back to Teachers
    </a>
    <div class="flex gap-2">
        @if($teacher->status === 'pending' || $teacher->status === 'suspended')
            <form action="{{ route('admin.teachers.approve', $teacher) }}" method="POST">
                @csrf
                <button class="btn-outline" style="font-size:.8rem;padding:6px 16px;color:#22c55e;border-color:#22c55e">
                    <i class="fas fa-check mr-1"></i> Approve
                </button>
            </form>
        @endif
        @if($teacher->status === 'approved')
            <form action="{{ route('admin.teachers.suspend', $teacher) }}" method="POST">
                @csrf
                <button class="btn-danger" style="font-size:.8rem;padding:6px 16px">
                    <i class="fas fa-ban mr-1"></i> Suspend
                </button>
            </form>
        @endif
        <form action="{{ route('admin.teachers.destroy', $teacher) }}" method="POST"
              onsubmit="return confirm('Delete this teacher? This cannot be undone.')">
            @csrf @method('DELETE')
            <button class="btn-danger" style="font-size:.8rem;padding:6px 16px;background:transparent;color:#ef4444">
                <i class="fas fa-trash mr-1"></i> Delete
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Left: Profile --}}
    <div class="lg:col-span-1 flex flex-col gap-6">

        <div class="card p-6 text-center">
            @php
                $avatarUrl = $teacher->user->avatar_url ?? null;
            @endphp
            @if($avatarUrl)
                <img src="{{ $avatarUrl }}" alt="{{ $teacher->user->name }}"
                     style="width:72px;height:72px;border-radius:50%;object-fit:cover;margin:0 auto 12px;border:2px solid rgba(212,175,55,.3)">
            @else
                <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#1A6B3C,#22874D);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:1.6rem;color:#fff;margin:0 auto 12px">
                    {{ strtoupper(substr($teacher->user->name ?? 'T', 0, 1)) }}
                </div>
            @endif
            <div class="text-white font-semibold text-lg">{{ $teacher->user->name ?? 'N/A' }}</div>
            <div class="text-gray-400 text-sm mt-1">{{ $teacher->user->email ?? '' }}</div>
            <div class="text-gray-500 text-xs mt-1">{{ $teacher->user->phone ?? '—' }}</div>
            <div class="mt-3">
                <span class="badge badge-{{ $teacher->status === 'approved' ? 'approved' : ($teacher->status === 'pending' ? 'pending' : 'cancelled') }}">
                    {{ ucfirst($teacher->status) }}
                </span>
            </div>
            @if($teacher->is_featured)
                <div class="mt-2"><span class="text-xs text-gold-DEFAULT"><i class="fas fa-star mr-1"></i>Featured Teacher</span></div>
            @endif
        </div>

        {{-- Info --}}
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Teacher Info</h3>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Specialization</span>
                    <span class="text-gray-200">{{ $teacher->specialization ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Hourly Rate</span>
                    <span class="text-gold-DEFAULT font-semibold">${{ number_format($teacher->hourly_rate ?? 0, 2) }}/hr</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Gender</span>
                    <span class="text-gray-200">{{ $teacher->gender ? ucfirst($teacher->gender) : '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Zoom Email</span>
                    <span class="text-gray-200 text-xs">{{ $teacher->zoom_email ?? '—' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Joined</span>
                    <span class="text-gray-200">{{ $teacher->created_at?->format('M d, Y') ?? '—' }}</span>
                </div>
            </div>
            {{-- Update Rate --}}
            <form action="{{ route('admin.teachers.rate', $teacher) }}" method="POST" class="mt-4 pt-4 border-t border-white/5 flex gap-2">
                @csrf
                <input type="number" name="hourly_rate" value="{{ $teacher->hourly_rate }}" step="0.5" min="0"
                       class="input-dark flex-1" placeholder="Hourly rate">
                <button class="btn-gold px-3 py-2 rounded-lg text-xs">Update</button>
            </form>
        </div>

        {{-- Stats --}}
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-4">Summary</h3>
            <div class="flex flex-col gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Bookings</span>
                    <span class="text-white font-semibold">{{ $teacher->bookings->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Sessions</span>
                    <span class="text-white font-semibold">{{ $teacher->earnings->count() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Total Earned</span>
                    <span class="text-gold-DEFAULT font-semibold">${{ number_format($teacher->earnings->sum('amount'), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Pending Payout</span>
                    <span class="text-yellow-400 font-semibold">${{ number_format($teacher->earnings->where('status','pending')->sum('net_amount'), 2) }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Reviews</span>
                    <span class="text-white font-semibold">{{ $teacher->reviews->count() }}</span>
                </div>
                @if($teacher->reviews->count())
                <div class="flex justify-between">
                    <span class="text-gray-500">Avg Rating</span>
                    <span class="text-gold-DEFAULT font-semibold">
                        {{ number_format($teacher->reviews->avg('rating'), 1) }}
                        <i class="fas fa-star text-xs ml-1"></i>
                    </span>
                </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Right: Details --}}
    <div class="lg:col-span-2 flex flex-col gap-6">

        {{-- Bio --}}
        @if($teacher->bio)
        <div class="card p-5">
            <h3 class="text-xs text-gray-500 uppercase tracking-wider font-semibold mb-3">Bio</h3>
            <p class="text-gray-300 text-sm leading-relaxed">{{ $teacher->bio }}</p>
        </div>
        @endif

        {{-- Recent Bookings --}}
        <div class="card overflow-hidden">
            <div class="px-5 py-4 border-b border-white/5 flex items-center justify-between">
                <h3 class="text-white font-semibold">Recent Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs text-blue-400 hover:underline">View all</a>
            </div>
            @if($teacher->bookings->isEmpty())
                <div class="p-8 text-center text-gray-600">No bookings yet.</div>
            @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                            <th class="text-left px-5 py-3">Ref</th>
                            <th class="text-left px-5 py-3">Student</th>
                            <th class="text-left px-5 py-3">Course</th>
                            <th class="text-left px-5 py-3">Date</th>
                            <th class="text-left px-5 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($teacher->bookings->sortByDesc('created_at')->take(10) as $booking)
                        <tr class="table-row">
                            <td class="px-5 py-3 font-mono text-xs text-gold-DEFAULT">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="hover:underline">
                                    {{ $booking->booking_ref ?? '#'.$booking->id }}
                                </a>
                            </td>
                            <td class="px-5 py-3 text-gray-300">{{ $booking->student->user->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs">{{ $booking->course->name ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-400 text-xs">
                                {{ $booking->scheduled_at ? $booking->scheduled_at->format('M d, Y') : ($booking->created_at?->format('M d, Y') ?? '—') }}
                            </td>
                            <td class="px-5 py-3">
                                <span class="badge badge-{{ in_array($booking->status,['approved','completed']) ? 'approved' : ($booking->status === 'pending' ? 'pending' : 'cancelled') }}">
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

        {{-- Reviews --}}
        @if($teacher->reviews->count())
        <div class="card p-5">
            <h3 class="text-white font-semibold mb-4">Recent Reviews</h3>
            <div class="flex flex-col gap-4">
                @foreach($teacher->reviews->sortByDesc('created_at')->take(5) as $review)
                <div class="border border-white/5 rounded-xl p-4">
                    <div class="flex items-center justify-between mb-2">
                        <div class="text-sm text-gray-300 font-medium">{{ $review->student->user->name ?? 'Anonymous' }}</div>
                        <div class="flex items-center gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star text-xs {{ $i <= $review->rating ? 'text-gold-DEFAULT' : 'text-gray-700' }}"></i>
                            @endfor
                        </div>
                    </div>
                    @if($review->comment)
                        <p class="text-gray-400 text-xs leading-relaxed">{{ $review->comment }}</p>
                    @endif
                    <div class="text-gray-600 text-xs mt-2">{{ $review->created_at?->format('M d, Y') }}</div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>

@endsection
