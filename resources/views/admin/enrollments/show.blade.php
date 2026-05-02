@extends('layouts.dashboard')
@section('title', 'Enrollment – '.$enrollment->student->user->name)
@section('page-title', 'Enrollment Detail')
@section('page-subtitle', $enrollment->student->user->name.' → '.$enrollment->teacher->user->name)

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.enrollments.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-book-open"></i></span> Enrollments</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.salary.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary Slips</a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Back --}}
    <div class="flex justify-between items-center mb-5 no-print">
        <a href="{{ route('admin.enrollments.index') }}" class="btn-outline px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back to Enrollments
        </a>
        <span style="padding:.3rem .9rem;border-radius:99px;font-size:.75rem;font-weight:700;
                     letter-spacing:.06em;text-transform:uppercase;{{ $enrollment->status_badge }}">
            {{ ucfirst($enrollment->status) }}
        </span>
    </div>

    {{-- Detail card --}}
    <div class="card p-6 mb-5">

        {{-- Header --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,.07);">
            <div>
                <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Student</div>
                <div style="font-size:1.1rem;font-weight:700;color:#e5e7eb;">{{ $enrollment->student->user->name }}</div>
                <div style="font-size:.8rem;color:#9ca3af;">{{ $enrollment->student->user->email }}</div>
                @if($enrollment->student->user->phone)
                    <div style="font-size:.8rem;color:#9ca3af;"><i class="fas fa-phone mr-1"></i>{{ $enrollment->student->user->phone }}</div>
                @endif
            </div>
            <div>
                <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Teacher</div>
                <div style="font-size:1.1rem;font-weight:700;color:#e5e7eb;">{{ $enrollment->teacher->user->name }}</div>
                <div style="font-size:.8rem;color:#4ade80;">{{ $enrollment->teacher->specialization ?? 'Teacher' }}</div>
                <div style="font-size:.8rem;color:#9ca3af;">{{ $enrollment->teacher->user->email }}</div>
            </div>
        </div>

        {{-- Course + Schedule --}}
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,.07);">
            <div>
                <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.75rem;">Course</div>
                <div style="font-size:1rem;font-weight:700;color:#D4AF37;">{{ $enrollment->course->name ?? '—' }}</div>
                <div style="font-size:.8rem;color:#9ca3af;margin-top:.25rem;">Monthly Fee: <strong style="color:#D4AF37">${{ number_format($enrollment->monthly_fee, 2) }}</strong></div>
            </div>
            <div>
                <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.75rem;">Schedule</div>
                <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:.5rem;">
                    @foreach($enrollment->selected_days ?? [] as $day)
                        <span style="padding:.25rem .65rem;border-radius:99px;font-size:.72rem;font-weight:700;
                                     background:rgba(52,211,153,.1);color:#34d399;border:1px solid rgba(52,211,153,.2);">
                            {{ ucfirst($day) }}
                        </span>
                    @endforeach
                </div>
                <div style="font-size:.8rem;color:#9ca3af;">
                    {{ $enrollment->classes_per_week }} classes/week · ~{{ $enrollment->classes_per_month }} classes/month
                </div>
                @if($enrollment->preferred_time)
                    <div style="font-size:.8rem;color:#9ca3af;margin-top:.25rem;">
                        <i class="fas fa-clock mr-1"></i>{{ date('h:i A', strtotime($enrollment->preferred_time)) }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Monthly Calendar --}}
        @php
            use Carbon\Carbon;
            use App\Http\Controllers\Teacher\EnrollmentController as EC;
            $now       = Carbon::now();
            $classDates = EC::monthDates($enrollment->selected_days ?? [], $now->year, $now->month);
            $dayNums   = collect($classDates)->map(fn($d) => $d->day)->toArray();
            $firstDow  = (int) Carbon::createFromDate($now->year, $now->month, 1)->dayOfWeek;
            $totalDays = cal_days_in_month(CAL_GREGORIAN, $now->month, $now->year);
        @endphp
        <div style="margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,.07);">
            <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.875rem;">
                {{ $now->format('F Y') }} — Monthly Schedule ({{ count($classDates) }} classes)
            </div>
            <div style="display:grid;grid-template-columns:auto 1fr;gap:1.5rem;align-items:start;">
                {{-- Calendar grid --}}
                <div style="min-width:240px;">
                    <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:.3rem;">
                        @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $h)
                            <div style="text-align:center;font-size:.6rem;font-weight:700;color:#4b5563;padding:.2rem 0;">{{ $h }}</div>
                        @endforeach
                        @for($e = 0; $e < $firstDow; $e++)
                            <div></div>
                        @endfor
                        @for($d = 1; $d <= $totalDays; $d++)
                            @if(in_array($d, $dayNums))
                                <div style="aspect-ratio:1;display:flex;align-items:center;justify-content:center;
                                            border-radius:7px;font-size:.75rem;font-weight:800;
                                            background:rgba(26,107,60,.25);border:1.5px solid rgba(52,211,153,.4);
                                            color:#34d399;">{{ $d }}</div>
                            @elseif($d === $now->day)
                                <div style="aspect-ratio:1;display:flex;align-items:center;justify-content:center;
                                            border-radius:7px;font-size:.75rem;font-weight:600;
                                            background:rgba(212,175,55,.15);border:1px solid rgba(212,175,55,.3);
                                            color:#D4AF37;">{{ $d }}</div>
                            @else
                                <div style="aspect-ratio:1;display:flex;align-items:center;justify-content:center;
                                            border-radius:7px;font-size:.75rem;color:#374151;">{{ $d }}</div>
                            @endif
                        @endfor
                    </div>
                </div>
                {{-- Date list --}}
                <div>
                    <div style="display:flex;flex-wrap:wrap;gap:.4rem;">
                        @foreach($classDates as $dt)
                            <span style="padding:.25rem .65rem;border-radius:8px;font-size:.75rem;font-weight:600;
                                         {{ $dt->day < $now->day ? 'background:rgba(52,211,153,.05);color:#4b5563;border:1px solid rgba(52,211,153,.1);' : 'background:rgba(52,211,153,.12);color:#34d399;border:1px solid rgba(52,211,153,.25);' }}">
                                {{ $dt->format('d M') }} ({{ $dt->format('D') }})
                            </span>
                        @endforeach
                    </div>
                    <div style="margin-top:.875rem;font-size:.78rem;color:#6b7280;">
                        <i class="fas fa-info-circle mr-1"></i>
                        {{ count($classDates) }} classes × ${{ number_format($enrollment->monthly_fee / max(count($classDates),1), 2) }}/class
                        = <strong style="color:#D4AF37">${{ number_format($enrollment->monthly_fee, 2) }}</strong>/month
                    </div>
                </div>
            </div>
        </div>

        {{-- Notes --}}
        @if($enrollment->notes)
        <div style="margin-bottom:1.5rem;padding-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,.07);">
            <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">Student Notes</div>
            <p style="font-size:.875rem;color:#d1d5db;line-height:1.6;">{{ $enrollment->notes }}</p>
        </div>
        @endif

        {{-- Submitted --}}
        <div style="font-size:.75rem;color:#6b7280;">
            Submitted: {{ $enrollment->created_at->format('d M Y, h:i A') }}
            @if($enrollment->approved_at)
                · Approved: {{ $enrollment->approved_at->format('d M Y') }} by {{ $enrollment->approvedBy->name ?? 'Admin' }}
            @endif
            @if($enrollment->start_date && $enrollment->status === 'active')
                · Start Date: <strong style="color:#34d399">{{ $enrollment->start_date->format('d M Y') }}</strong>
            @endif
        </div>

    </div>

    {{-- Admin note (existing) --}}
    @if($enrollment->admin_note)
    <div class="card p-4 mb-5" style="border-left:3px solid #D4AF37;">
        <div style="font-size:.72rem;color:#9ca3af;margin-bottom:.4rem;">Admin Note</div>
        <p style="font-size:.875rem;color:#d1d5db;">{{ $enrollment->admin_note }}</p>
    </div>
    @endif

    {{-- Actions --}}
    @if($enrollment->status === 'pending')
    <div class="card p-6 mb-4">
        <h3 class="text-white font-semibold mb-4"><i class="fas fa-check-circle text-green-400 mr-2"></i>Approve Enrollment</h3>
        <form action="{{ route('admin.enrollments.approve', $enrollment) }}" method="POST" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Start Date</label>
                    <input type="date" name="start_date"
                           value="{{ now()->addDay()->format('Y-m-d') }}"
                           class="input-dark w-full">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Note to Student (optional)</label>
                    <input type="text" name="admin_note"
                           placeholder="e.g. Classes confirmed for Sat & Wed at 10 AM"
                           class="input-dark w-full">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-gold px-6 py-2 rounded-lg">
                    <i class="fas fa-check mr-2"></i>Approve & Activate
                </button>
            </div>
        </form>

        <hr style="border-color:rgba(255,255,255,.07);margin:1.5rem 0;">

        <h3 class="text-white font-semibold mb-4"><i class="fas fa-times-circle text-red-400 mr-2"></i>Reject Enrollment</h3>
        <form action="{{ route('admin.enrollments.reject', $enrollment) }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-400 text-sm mb-2">Reason (required)</label>
                <input type="text" name="admin_note" required
                       placeholder="e.g. Teacher not available for selected days"
                       class="input-dark w-full">
            </div>
            <button type="submit" class="px-6 py-2 rounded-lg font-semibold text-sm"
                    style="background:rgba(239,68,68,.15);color:#f87171;border:1px solid rgba(239,68,68,.3);"
                    onclick="return confirm('Reject this enrollment?')">
                <i class="fas fa-times mr-2"></i>Reject
            </button>
        </form>
    </div>

    @elseif($enrollment->status === 'active')
    <div class="flex gap-3">
        <form action="{{ route('admin.enrollments.pause', $enrollment) }}" method="POST">
            @csrf
            <button class="btn-outline px-5 py-2 rounded-lg text-sm">
                <i class="fas fa-pause mr-2"></i>Pause Enrollment
            </button>
        </form>
        <form action="{{ route('admin.enrollments.cancel', $enrollment) }}" method="POST"
              onsubmit="return confirm('Cancel this active enrollment?')">
            @csrf
            <button class="px-5 py-2 rounded-lg text-sm"
                    style="background:rgba(239,68,68,.1);color:#f87171;border:1px solid rgba(239,68,68,.2);">
                <i class="fas fa-ban mr-2"></i>Cancel
            </button>
        </form>
    </div>

    @elseif($enrollment->status === 'paused')
    <form action="{{ route('admin.enrollments.resume', $enrollment) }}" method="POST">
        @csrf
        <button class="btn-gold px-5 py-2 rounded-lg text-sm">
            <i class="fas fa-play mr-2"></i>Resume Enrollment
        </button>
    </form>
    @endif

</div>
@endsection
