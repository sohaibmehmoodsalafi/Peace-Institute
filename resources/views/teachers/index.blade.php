@extends('layouts.app')
@section('title', 'Our Teachers – Peace Institute')

@push('styles')
<style>
.tlist-hero{
    background:linear-gradient(160deg,var(--gd) 0%,var(--green) 100%);
    padding:5rem 1.5rem 4rem;
    text-align:center;position:relative;overflow:hidden;
}
.tlist-hero::before{
    content:'المعلمون';font-family:'Amiri',serif;font-size:8rem;
    color:rgba(255,255,255,.04);position:absolute;top:50%;left:50%;
    transform:translate(-50%,-50%);white-space:nowrap;pointer-events:none;
}
.tlist-hero h1{font-family:'Playfair Display',serif;font-size:clamp(2rem,5vw,3rem);font-weight:700;color:#fff;margin-bottom:.75rem;}
.tlist-hero h1 span{color:var(--goldl);}
.tlist-hero p{color:rgba(255,255,255,.75);font-size:1rem;max-width:520px;margin:0 auto 2rem;}

.filter-bar{display:flex;align-items:center;gap:.5rem;flex-wrap:wrap;justify-content:center;}
.filter-chip{padding:.4rem 1rem;border-radius:99px;font-size:.8rem;font-weight:600;border:1.5px solid rgba(255,255,255,.25);color:rgba(255,255,255,.75);text-decoration:none;transition:all .2s;background:transparent;}
.filter-chip:hover,.filter-chip.active{background:#fff;color:var(--green);border-color:#fff;}

.tlist-body{max-width:1280px;margin:0 auto;padding:3rem 1.5rem 4rem;}

/* ── Teacher Card ── */
.teacher-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(230px,1fr));
    gap:1.5rem;
}
.t-card{
    background:#fff;
    border-radius:16px;
    border:1px solid #E8E8E8;
    overflow:hidden;
    transition:transform .25s,box-shadow .25s;
    display:flex;flex-direction:column;
}
.t-card:hover{
    transform:translateY(-5px);
    box-shadow:0 14px 36px rgba(0,0,0,.1);
}

/* Photo */
.t-photo{
    width:100%;
    height:220px;
    object-fit:cover;
    object-position:top center;
    display:block;
    background:#F3F4F6;
}

/* Body */
.t-body{
    padding:1.1rem 1.25rem 1rem;
    flex:1;display:flex;flex-direction:column;
}
.t-name{
    font-size:1rem;font-weight:800;color:#111827;
    line-height:1.2;margin-bottom:.45rem;
}
.t-name-line{
    width:32px;height:3px;
    background:linear-gradient(90deg,#C9A427,#E8C547);
    border-radius:99px;margin-bottom:.65rem;
}
.t-spec{
    font-size:.78rem;color:var(--green);
    font-weight:600;margin-bottom:.6rem;
}

.t-stars{display:flex;align-items:center;gap:.15rem;margin-bottom:.65rem;}
.t-stars i{font-size:.6rem;color:#E5E7EB;}
.t-stars i.lit{color:#F59E0B;}
.t-stars span{font-size:.7rem;color:#9CA3AF;margin-left:.3rem;}

.t-meta{
    display:flex;gap:1rem;flex-wrap:wrap;
    margin-top:auto;
}
.t-meta-item{
    display:flex;align-items:center;gap:.3rem;
    font-size:.76rem;color:#6B7280;
}
.t-meta-item i{font-size:.65rem;color:var(--green);}

/* Footer */
.t-foot{
    padding:.75rem 1.25rem .9rem;
    border-top:1px solid #F3F4F6;
    display:flex;gap:.5rem;
}
.t-btn-main{
    flex:1;padding:.6rem;border-radius:9px;
    background:var(--green);color:#fff;
    font-size:.82rem;font-weight:700;
    text-decoration:none;text-align:center;
    transition:background .2s;
}
.t-btn-main:hover{background:var(--gl);}
.t-btn-book{
    padding:.6rem .75rem;border-radius:9px;
    border:1.5px solid #E5E7EB;color:#9CA3AF;
    font-size:.8rem;text-decoration:none;
    transition:all .2s;
}
.t-btn-book:hover{border-color:var(--green);color:var(--green);}

.empty-state{text-align:center;padding:5rem 1rem;grid-column:1/-1;}
.empty-state i{font-size:2.5rem;color:#D1D5DB;display:block;margin-bottom:1rem;}
.empty-state p{color:var(--muted);}
.pag-wrap{margin-top:2.5rem;display:flex;justify-content:center;}
</style>
@endpush

@section('content')

<div class="tlist-hero">
    <h1>Meet Our <span>Certified Teachers</span></h1>
    <p>Every teacher is verified, experienced, and dedicated to your Quranic journey.</p>
    <div class="filter-bar">
        <a href="{{ route('teachers') }}" class="filter-chip {{ !request('subject') ? 'active' : '' }}">All</a>
        @foreach(['Tajweed','Hifz','Noorani Qaida','Nazrah','Dars-e-Nizami'] as $sub)
            <a href="{{ route('teachers', ['subject' => $sub]) }}"
               class="filter-chip {{ request('subject') === $sub ? 'active' : '' }}">{{ $sub }}</a>
        @endforeach
    </div>
</div>

<div class="tlist-body">
    <div class="teacher-grid">
        @forelse($teachers as $teacher)
        <div class="t-card reveal">

            {{-- Photo --}}
            <img src="{{ $teacher->user->avatar_url }}"
                 class="t-photo"
                 alt="{{ $teacher->user->name }}"
                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($teacher->user->name) }}&background=0F3D22&color=fff&size=400&bold=true'">

            <div class="t-body">
                <div class="t-name">{{ $teacher->user->name }}</div>
                <div class="t-name-line"></div>
                <div class="t-spec">{{ $teacher->specialization ?? 'Quran Teacher' }}</div>

                <div class="t-stars">
                    @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star {{ $i <= $teacher->rating ? 'lit' : '' }}"></i>
                    @endfor
                    <span>{{ number_format($teacher->rating,1) }} ({{ $teacher->total_reviews }})</span>
                </div>

                <div class="t-meta">
                    @if($teacher->experience_years)
                    <span class="t-meta-item">
                        <i class="fas fa-briefcase"></i>
                        {{ $teacher->experience_years }} yrs exp
                    </span>
                    @endif
                    @if(!empty($teacher->city))
                    <span class="t-meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $teacher->city }}
                    </span>
                    @elseif($teacher->nationality)
                    <span class="t-meta-item">
                        <i class="fas fa-globe"></i>
                        {{ $teacher->nationality }}
                    </span>
                    @endif
                </div>
            </div>

            <div class="t-foot">
                <a href="{{ route('teachers.show', $teacher) }}" class="t-btn-main">View Profile</a>
                @auth
                    @if(auth()->user()->isStudent())
                        <a href="{{ route('student.bookings.create', ['teacher_id' => $teacher->id]) }}" class="t-btn-book" title="Book">
                            <i class="fas fa-calendar-plus"></i>
                        </a>
                    @endif
                @else
                    <a href="{{ route('register', 'student') }}" class="t-btn-book" title="Book">
                        <i class="fas fa-calendar-plus"></i>
                    </a>
                @endauth
            </div>
        </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-user-slash"></i>
                <p>No teachers found. Please check back soon.</p>
            </div>
        @endforelse
    </div>
    <div class="pag-wrap">{{ $teachers->links() }}</div>
</div>

@endsection
