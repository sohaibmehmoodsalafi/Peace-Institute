@extends('layouts.dashboard')
@section('title', 'My Enrollments – Teacher')
@section('page-title', 'Student Enrollments')
@section('page-subtitle', 'Students enrolled with you this month')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('teacher.enrollments') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-book-open"></i></span> Enrollments</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Sessions</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('teacher.salary.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary</a>
    <a href="{{ route('teacher.profile') }}" class="sidebar-link"><span class="icon"><i class="fas fa-user"></i></span> Profile</a>
@endsection

@push('styles')
<style>
.enr-wrap { display: flex; flex-direction: column; gap: 1.5rem; }

.enr-block {
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 16px;
    overflow: hidden;
}
.enr-block-head {
    padding: 1.1rem 1.5rem;
    border-bottom: 1px solid rgba(255,255,255,.07);
    display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: .75rem;
}
.enr-block-body { padding: 1.25rem 1.5rem; }

/* Day pills */
.day-pill {
    display: inline-block; padding: .2rem .65rem; border-radius: 99px;
    font-size: .72rem; font-weight: 700;
    background: rgba(52,211,153,.1); color: #34d399;
    border: 1px solid rgba(52,211,153,.2); margin-right: .3rem; margin-bottom: .3rem;
}

/* Monthly calendar grid */
.month-cal { margin-top: 1rem; }
.month-cal-title {
    font-size: .72rem; color: #6b7280; text-transform: uppercase;
    letter-spacing: .1em; font-weight: 700; margin-bottom: .75rem;
}
.cal-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: .35rem;
}
.cal-head {
    text-align: center; font-size: .65rem; font-weight: 700;
    color: #6b7280; text-transform: uppercase; padding: .25rem 0;
}
.cal-cell {
    aspect-ratio: 1; display: flex; align-items: center; justify-content: center;
    border-radius: 8px; font-size: .78rem; font-weight: 600;
    color: #4b5563;
}
.cal-cell.class-day {
    background: rgba(26,107,60,.2);
    border: 1.5px solid rgba(52,211,153,.35);
    color: #34d399;
    font-weight: 800;
}
.cal-cell.today {
    background: rgba(212,175,55,.15);
    border: 1.5px solid rgba(212,175,55,.4);
    color: #D4AF37;
}
.cal-cell.class-day.today {
    background: rgba(26,107,60,.35);
    border: 1.5px solid #34d399;
    color: #fff;
    box-shadow: 0 0 8px rgba(52,211,153,.3);
}
.cal-cell.empty { background: transparent; }

/* Status badge */
.s-badge {
    padding: .2rem .65rem; border-radius: 99px;
    font-size: .7rem; font-weight: 700; letter-spacing: .04em; text-transform: uppercase;
}
.s-pending  { background: rgba(251,191,36,.15); color: #fbbf24; border: 1px solid rgba(251,191,36,.3); }
.s-active   { background: rgba(52,211,153,.15);  color: #34d399; border: 1px solid rgba(52,211,153,.3); }
.s-paused   { background: rgba(156,163,175,.15); color: #9ca3af; border: 1px solid rgba(156,163,175,.3); }

.class-list { display: flex; flex-wrap: wrap; gap: .4rem; margin-top: .5rem; }
.class-date {
    padding: .25rem .6rem; border-radius: 8px; font-size: .75rem; font-weight: 600;
    background: rgba(26,107,60,.15); color: #4ade80;
    border: 1px solid rgba(52,211,153,.2);
}
.class-date.past { opacity: .4; }
</style>
@endpush

@section('content')

<div style="margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.75rem;">
    <div>
        <span style="font-size:.875rem;color:#9ca3af;">
            Showing enrollments for <strong style="color:#D4AF37;">{{ $now->format('F Y') }}</strong>
        </span>
    </div>
    <div style="display:flex;gap:.75rem;align-items:center;">
        <span style="padding:.3rem .8rem;border-radius:99px;background:rgba(52,211,153,.1);color:#34d399;font-size:.78rem;font-weight:700;border:1px solid rgba(52,211,153,.2);">
            {{ $enrollments->where('status','active')->count() }} Active
        </span>
        <span style="padding:.3rem .8rem;border-radius:99px;background:rgba(251,191,36,.1);color:#fbbf24;font-size:.78rem;font-weight:700;border:1px solid rgba(251,191,36,.2);">
            {{ $enrollments->where('status','pending')->count() }} Pending
        </span>
    </div>
</div>

@if($enrollments->isEmpty())
    <div class="card p-10 text-center">
        <i class="fas fa-book-open" style="font-size:2rem;color:#374151;display:block;margin-bottom:1rem;"></i>
        <p style="color:#6b7280;">No enrollments yet. Students will appear here once they enroll.</p>
    </div>
@else

<div class="enr-wrap">
@foreach($enrollments as $enr)
@php
    $classDates  = $schedules[$enr->id] ?? [];
    $classDayNums = collect($classDates)->map(fn($d) => $d->day)->toArray();
    // Build full calendar
    $firstDow = (int) \Carbon\Carbon::createFromDate($now->year, $now->month, 1)->dayOfWeek; // 0=Sun
    $totalDays = cal_days_in_month(CAL_GREGORIAN, $now->month, $now->year);
    $todayNum  = $now->day;
@endphp
<div class="enr-block">

    {{-- Header --}}
    <div class="enr-block-head">
        <div style="display:flex;align-items:center;gap:.875rem;">
            <img src="{{ $enr->student->user->avatar_url }}"
                 style="width:42px;height:42px;border-radius:50%;object-fit:cover;border:2px solid rgba(255,255,255,.1);"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($enr->student->user->name) }}&background=1A6B3C&color=fff&size=80&bold=true'"
                 alt="">
            <div>
                <div style="font-weight:700;color:#e5e7eb;font-size:.95rem;">{{ $enr->student->user->name }}</div>
                <div style="font-size:.78rem;color:#9ca3af;">{{ $enr->student->user->email }}</div>
            </div>
        </div>
        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;">
            <span style="font-size:.82rem;color:#D4AF37;font-weight:700;">
                {{ $enr->course->name ?? '—' }}
            </span>
            <span class="s-badge s-{{ $enr->status }}">{{ ucfirst($enr->status) }}</span>
        </div>
    </div>

    <div class="enr-block-body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">

            {{-- Left: Info --}}
            <div>
                <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.6rem;">Class Days</div>
                <div>
                    @foreach($enr->selected_days ?? [] as $day)
                        <span class="day-pill">{{ ucfirst($day) }}</span>
                    @endforeach
                </div>
                <div style="margin-top:.875rem;font-size:.82rem;color:#9ca3af;display:flex;flex-direction:column;gap:.35rem;">
                    <span><i class="fas fa-redo mr-2" style="color:#6b7280;width:14px;"></i>
                        {{ $enr->classes_per_week }}× per week &nbsp;·&nbsp; ~{{ $enr->classes_per_month }}× per month
                    </span>
                    @if($enr->preferred_time)
                    <span><i class="fas fa-clock mr-2" style="color:#6b7280;width:14px;"></i>
                        {{ date('h:i A', strtotime($enr->preferred_time)) }}
                    </span>
                    @endif
                    <span><i class="fas fa-dollar-sign mr-2" style="color:#6b7280;width:14px;"></i>
                        ${{ number_format($enr->monthly_fee, 0) }} / month
                    </span>
                    @if($enr->start_date)
                    <span><i class="fas fa-calendar-check mr-2" style="color:#6b7280;width:14px;"></i>
                        Since {{ $enr->start_date->format('d M Y') }}
                    </span>
                    @endif
                </div>
                @if($enr->notes)
                <div style="margin-top:.875rem;font-size:.78rem;color:#9ca3af;
                            background:rgba(255,255,255,.03);padding:.6rem .875rem;border-radius:8px;
                            border:1px solid rgba(255,255,255,.06);">
                    <strong style="color:#6b7280;">Student Note:</strong> {{ $enr->notes }}
                </div>
                @endif

                {{-- Class dates list --}}
                @if($enr->status === 'active' && count($classDates))
                <div style="margin-top:1rem;">
                    <div style="font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.1em;margin-bottom:.5rem;">
                        {{ $now->format('F') }} Dates ({{ count($classDates) }} classes)
                    </div>
                    <div class="class-list">
                        @foreach($classDates as $dt)
                            <span class="class-date {{ $dt->day < $todayNum ? 'past' : '' }}">
                                {{ $dt->format('d') }} {{ $dt->format('D') }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Right: Mini calendar --}}
            @if($enr->status === 'active')
            <div>
                <div class="month-cal-title">{{ $now->format('F Y') }} — Class Calendar</div>
                <div class="cal-grid">
                    {{-- Day headers --}}
                    @foreach(['Su','Mo','Tu','We','Th','Fr','Sa'] as $h)
                        <div class="cal-head">{{ $h }}</div>
                    @endforeach
                    {{-- Empty cells before first day --}}
                    @for($e = 0; $e < $firstDow; $e++)
                        <div class="cal-cell empty"></div>
                    @endfor
                    {{-- Day cells --}}
                    @for($d = 1; $d <= $totalDays; $d++)
                        @php
                            $isClass = in_array($d, $classDayNums);
                            $isToday = ($d === $todayNum);
                        @endphp
                        <div class="cal-cell {{ $isClass ? 'class-day' : '' }} {{ $isToday ? 'today' : '' }}">
                            {{ $d }}
                        </div>
                    @endfor
                </div>
                <div style="margin-top:.75rem;display:flex;gap:1rem;font-size:.7rem;color:#6b7280;">
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:rgba(26,107,60,.3);border:1px solid rgba(52,211,153,.4);margin-right:4px;"></span>Class day</span>
                    <span><span style="display:inline-block;width:10px;height:10px;border-radius:3px;background:rgba(212,175,55,.2);border:1px solid rgba(212,175,55,.4);margin-right:4px;"></span>Today</span>
                </div>
            </div>
            @else
            <div style="display:flex;align-items:center;justify-content:center;min-height:120px;">
                <div style="text-align:center;color:#4b5563;">
                    <i class="fas fa-hourglass-half" style="font-size:1.5rem;margin-bottom:.5rem;display:block;"></i>
                    <span style="font-size:.82rem;">Waiting for approval</span>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endforeach
</div>

@endif

@endsection
