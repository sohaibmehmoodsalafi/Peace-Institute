@extends('layouts.dashboard')
@section('title', 'Enrollments – Admin')
@section('page-title', 'Enrollments')
@section('page-subtitle', 'Manage student enrollment requests')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.enrollments.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-book-open"></i></span> Enrollments</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.salary.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary Slips</a>
@endsection

@section('content')

{{-- Status count cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:1.5rem;">
    @foreach(['pending'=>['#fbbf24','Pending'],'active'=>['#34d399','Active'],'paused'=>['#9ca3af','Paused'],'cancelled'=>['#f87171','Cancelled']] as $s=>[$col,$lbl])
    <a href="{{ route('admin.enrollments.index', ['status'=>$s]) }}"
       style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);
              border-radius:14px;padding:1rem 1.25rem;text-decoration:none;display:block;
              {{ request('status')===$s ? 'border-color:'.$col.';box-shadow:0 0 0 1px '.$col.';' : '' }}">
        <div style="font-size:1.75rem;font-weight:800;color:{{ $col }}">{{ $counts[$s] }}</div>
        <div style="font-size:.75rem;color:#6b7280;margin-top:3px">{{ $lbl }}</div>
    </a>
    @endforeach
</div>

{{-- Filter bar --}}
<div class="card p-4 mb-4 flex gap-3 items-center flex-wrap">
    <form method="GET" class="flex gap-2 flex-wrap">
        <select name="status" onchange="this.form.submit()"
                style="padding:.5rem .75rem;border-radius:8px;background:rgba(255,255,255,.07);
                       border:1px solid rgba(255,255,255,.1);color:#e5e7eb;font-size:.85rem;">
            <option value="">All Status</option>
            <option value="pending"   {{ request('status')==='pending'   ? 'selected' : '' }}>Pending</option>
            <option value="active"    {{ request('status')==='active'    ? 'selected' : '' }}>Active</option>
            <option value="paused"    {{ request('status')==='paused'    ? 'selected' : '' }}>Paused</option>
            <option value="cancelled" {{ request('status')==='cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        @if(request()->anyFilled(['status','teacher_id']))
            <a href="{{ route('admin.enrollments.index') }}"
               style="padding:.5rem .75rem;border-radius:8px;border:1px solid rgba(255,255,255,.1);
                      color:#9ca3af;font-size:.85rem;text-decoration:none;">
                <i class="fas fa-times mr-1"></i>Clear
            </a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <table style="width:100%;border-collapse:collapse;">
        <thead>
            <tr style="border-bottom:1px solid rgba(255,255,255,.07);">
                <th style="padding:1rem 1.25rem;text-align:left;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Student</th>
                <th style="padding:1rem 1.25rem;text-align:left;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Teacher</th>
                <th style="padding:1rem 1.25rem;text-align:left;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Course</th>
                <th style="padding:1rem 1.25rem;text-align:left;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Schedule</th>
                <th style="padding:1rem 1.25rem;text-align:left;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Fee</th>
                <th style="padding:1rem 1.25rem;text-align:left;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.08em;">Status</th>
                <th style="padding:1rem 1.25rem;text-align:left;font-size:.72rem;color:#6b7280;font-weight:700;text-transform:uppercase;letter-spacing:.08em;"></th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $enr)
            <tr style="border-bottom:1px solid rgba(255,255,255,.04);">
                <td style="padding:1rem 1.25rem;">
                    <div style="font-weight:600;color:#e5e7eb;font-size:.875rem;">{{ $enr->student->user->name }}</div>
                    <div style="font-size:.72rem;color:#6b7280;">{{ $enr->created_at->format('d M Y') }}</div>
                </td>
                <td style="padding:1rem 1.25rem;">
                    <div style="font-size:.875rem;color:#e5e7eb;">{{ $enr->teacher->user->name }}</div>
                    <div style="font-size:.72rem;color:var(--green-400, #4ade80);">{{ $enr->teacher->specialization ?? '' }}</div>
                </td>
                <td style="padding:1rem 1.25rem;">
                    <span style="font-size:.82rem;color:#d1d5db;">{{ $enr->course->name ?? '—' }}</span>
                </td>
                <td style="padding:1rem 1.25rem;">
                    <div style="font-size:.78rem;color:#9ca3af;">{{ $enr->classes_per_week }}×/wk</div>
                    <div style="font-size:.72rem;color:#6b7280;">
                        {{ implode(', ', array_map(fn($d) => ucfirst(substr($d,0,3)), $enr->selected_days ?? [])) }}
                    </div>
                </td>
                <td style="padding:1rem 1.25rem;">
                    <span style="font-size:.875rem;color:#D4AF37;font-weight:700;">${{ number_format($enr->monthly_fee, 0) }}/mo</span>
                </td>
                <td style="padding:1rem 1.25rem;">
                    <span style="padding:.25rem .7rem;border-radius:99px;font-size:.7rem;font-weight:700;
                                 letter-spacing:.05em;text-transform:uppercase;{{ $enr->status_badge }}">
                        {{ ucfirst($enr->status) }}
                    </span>
                </td>
                <td style="padding:1rem 1.25rem;">
                    <a href="{{ route('admin.enrollments.show', $enr) }}"
                       style="font-size:.8rem;color:#93c5fd;text-decoration:none;white-space:nowrap;">
                        Review <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:3rem;color:#6b7280;">
                    <i class="fas fa-book-open" style="font-size:1.5rem;display:block;margin-bottom:.75rem;opacity:.3;"></i>
                    No enrollments found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div style="padding:1rem 1.25rem;border-top:1px solid rgba(255,255,255,.07);">
        {{ $enrollments->links() }}
    </div>
</div>

@endsection
