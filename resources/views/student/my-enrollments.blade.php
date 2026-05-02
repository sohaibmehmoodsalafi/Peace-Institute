@extends('layouts.app')
@section('title', 'My Enrollments – Peace Institute')

@push('styles')
<style>
.menr-hero{background:linear-gradient(160deg,var(--gd) 0%,var(--green) 100%);
    padding:3rem 1.5rem 4.5rem;text-align:center;position:relative;overflow:hidden;}
.menr-hero::after{content:'';position:absolute;bottom:-1px;left:0;right:0;height:60px;
    background:var(--cream);clip-path:ellipse(55% 100% at 50% 100%);}
.menr-hero h1{font-family:'Playfair Display',serif;font-size:clamp(1.6rem,4vw,2.2rem);
    font-weight:700;color:#fff;margin-bottom:.4rem;}
.menr-hero p{color:rgba(255,255,255,.75);font-size:.9rem;}

.menr-body{max-width:900px;margin:0 auto;padding:2.5rem 1.25rem 4rem;}

.enr-card{background:#fff;border-radius:18px;border:1.5px solid #EDE9E0;
    box-shadow:0 2px 12px rgba(26,107,60,.06);padding:1.5rem;margin-bottom:1.25rem;
    display:grid;grid-template-columns:60px 1fr auto;gap:1.25rem;align-items:start;}
@media(max-width:600px){.enr-card{grid-template-columns:1fr;}}
.enr-avatar{width:56px;height:56px;border-radius:50%;object-fit:cover;
    border:2px solid rgba(26,107,60,.15);}
.enr-badge{display:inline-block;padding:.2rem .65rem;border-radius:99px;
    font-size:.7rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
.enr-days{display:flex;flex-wrap:wrap;gap:.35rem;margin-top:.5rem;}
.enr-day{padding:.2rem .6rem;border-radius:99px;font-size:.7rem;font-weight:600;
    background:rgba(26,107,60,.08);color:var(--green);border:1px solid rgba(26,107,60,.15);}
.enr-actions{display:flex;flex-direction:column;gap:.5rem;align-items:flex-end;}
@media(max-width:600px){.enr-actions{align-items:flex-start;flex-direction:row;flex-wrap:wrap;}}

.empty-enr{text-align:center;padding:4rem 1rem;}
.empty-enr i{font-size:2.5rem;color:#D1D5DB;display:block;margin-bottom:1rem;}
.empty-enr p{color:var(--muted);margin-bottom:1.5rem;}
</style>
@endpush

@section('content')
<div class="menr-hero">
    <h1>My Enrollments</h1>
    <p>Track and manage your course enrollment requests</p>
</div>

<div class="menr-body">

    @if(session('success'))
        <div style="background:rgba(52,211,153,.1);border:1px solid rgba(52,211,153,.3);color:#065f46;
                    padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif

    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:.75rem;">
        <h2 style="font-size:1.1rem;font-weight:700;color:var(--text);">
            {{ $enrollments->count() }} Enrollment{{ $enrollments->count() !== 1 ? 's' : '' }}
        </h2>
        <a href="{{ route('student.enroll.create') }}"
           style="background:var(--green);color:#fff;padding:.6rem 1.25rem;border-radius:10px;
                  font-size:.85rem;font-weight:700;text-decoration:none;">
            <i class="fas fa-plus mr-1"></i> New Enrollment
        </a>
    </div>

    @forelse($enrollments as $enr)
    <div class="enr-card">

        {{-- Avatar --}}
        <div>
            <img src="{{ $enr->teacher->user->avatar_url }}"
                 class="enr-avatar"
                 alt="{{ $enr->teacher->user->name }}"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($enr->teacher->user->name) }}&background=0F3D22&color=fff&size=100&bold=true'">
        </div>

        {{-- Info --}}
        <div>
            <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:.35rem;">
                <span style="font-weight:800;color:var(--text);font-size:1rem;">{{ $enr->teacher->user->name }}</span>
                <span class="enr-badge" style="{{ $enr->status_badge }}">{{ ucfirst($enr->status) }}</span>
            </div>
            <div style="font-size:.85rem;color:var(--green);font-weight:600;margin-bottom:.5rem;">
                {{ $enr->course->name ?? '—' }}
            </div>
            <div class="enr-days">
                @foreach($enr->selected_days ?? [] as $day)
                    <span class="enr-day">{{ ucfirst($day) }}</span>
                @endforeach
            </div>
            <div style="margin-top:.6rem;font-size:.78rem;color:var(--muted);display:flex;gap:1rem;flex-wrap:wrap;">
                <span><i class="fas fa-calendar-week mr-1"></i>{{ $enr->classes_per_week }}×/week (~{{ $enr->classes_per_month }}/month)</span>
                @if($enr->preferred_time)
                    <span><i class="fas fa-clock mr-1"></i>{{ date('h:i A', strtotime($enr->preferred_time)) }}</span>
                @endif
                <span><i class="fas fa-dollar-sign mr-1"></i>${{ number_format($enr->monthly_fee, 0) }}/month</span>
                <span><i class="fas fa-calendar-plus mr-1"></i>{{ $enr->created_at->format('d M Y') }}</span>
            </div>
            @if($enr->start_date && $enr->status === 'active')
                <div style="margin-top:.4rem;font-size:.78rem;color:var(--green);font-weight:600;">
                    <i class="fas fa-play-circle mr-1"></i> Started: {{ $enr->start_date->format('d M Y') }}
                </div>
            @endif
            @if($enr->admin_note)
                <div style="margin-top:.5rem;font-size:.78rem;color:#374151;
                            background:var(--creamd);padding:.4rem .75rem;border-radius:8px;">
                    <strong>Note:</strong> {{ $enr->admin_note }}
                </div>
            @endif
        </div>

        {{-- Actions --}}
        <div class="enr-actions">
            <a href="{{ route('teachers.show', $enr->teacher) }}"
               style="font-size:.78rem;color:var(--green);text-decoration:none;white-space:nowrap;">
                <i class="fas fa-eye mr-1"></i> View Teacher
            </a>
            @if($enr->status === 'pending')
                <form method="POST" action="{{ route('student.enrollments.cancel', $enr) }}"
                      onsubmit="return confirm('Cancel this enrollment request?')">
                    @csrf @method('DELETE')
                    <button style="font-size:.78rem;color:#dc2626;background:none;border:none;cursor:pointer;padding:0;">
                        <i class="fas fa-times mr-1"></i> Cancel
                    </button>
                </form>
            @endif
        </div>

    </div>
    @empty
        <div class="empty-enr">
            <i class="fas fa-book-open"></i>
            <p>You have no enrollments yet.</p>
            <a href="{{ route('student.enroll.create') }}"
               style="background:var(--green);color:#fff;padding:.75rem 2rem;border-radius:12px;
                      font-weight:700;text-decoration:none;font-size:.9rem;">
                <i class="fas fa-plus mr-2"></i> Enroll in a Course
            </a>
        </div>
    @endforelse

</div>
@endsection
