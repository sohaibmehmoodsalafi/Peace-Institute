@extends('layouts.app')
@section('title', $teacher->user->name.' – Peace Institute')

@push('styles')
<style>
/* ── Profile hero banner ─────────────────────────────────── */
.prof-banner{
    background:linear-gradient(160deg,var(--gd) 0%,var(--green) 100%);
    padding:4.5rem 1.5rem 5.5rem;
    position:relative;overflow:hidden;
}
.prof-banner::after{
    content:'';
    position:absolute;bottom:-1px;left:0;right:0;height:70px;
    background:var(--cream);
    clip-path:ellipse(55% 100% at 50% 100%);
}
.prof-banner-inner{
    max-width:900px;margin:0 auto;
    display:flex;align-items:center;gap:1.75rem;flex-wrap:wrap;
    position:relative;z-index:1;
}
.prof-banner-avatar{
    width:110px;height:110px;border-radius:50%;
    border:4px solid rgba(255,255,255,.35);
    object-fit:cover;
    box-shadow:0 6px 24px rgba(0,0,0,.35);
    flex-shrink:0;
}
.prof-banner-info{flex:1;min-width:200px;}
.prof-banner-name{
    font-size:2.1rem;font-weight:800;color:#fff;
    margin-bottom:.3rem;line-height:1.15;
    text-shadow:0 2px 8px rgba(0,0,0,.2);
}
.prof-banner-spec{color:var(--goldl);font-size:.95rem;font-weight:700;margin-bottom:.65rem;letter-spacing:.02em;}
.prof-banner-stars{display:flex;align-items:center;gap:.3rem;margin-top:.1rem;}
.prof-banner-stars i{font-size:.85rem;color:rgba(255,255,255,.25);}
.prof-banner-stars i.lit{color:#FBBF24;}
.prof-banner-stars span{color:rgba(255,255,255,.9);font-size:.875rem;font-weight:600;margin-left:.5rem;}

/* ── Body layout ─────────────────────────────────────────── */
.prof-body{
    max-width:960px;margin:0 auto;
    padding:2rem 1.5rem 4rem;
    display:grid;
    grid-template-columns:280px 1fr;
    gap:1.75rem;
}
@media(max-width:768px){
    .prof-body{grid-template-columns:1fr;}
}

/* ── Sidebar cards ───────────────────────────────────────── */
.prof-side-card{
    background:#fff;
    border-radius:20px;
    box-shadow:0 2px 16px rgba(26,107,60,.07);
    border:1.5px solid #EDE9E0;
    overflow:hidden;
}
.prof-side-card + .prof-side-card{margin-top:1rem;}

.psc-head{
    padding:1.25rem 1.5rem .75rem;
    border-bottom:1px solid #F0EDE6;
    font-size:.8rem;font-weight:700;
    color:var(--muted);letter-spacing:.06em;text-transform:uppercase;
}
.psc-body{padding:1.25rem 1.5rem;}

/* Stats grid */
.stat-grid{display:grid;grid-template-columns:1fr 1fr;gap:.75rem;}
.stat-box{
    background:var(--creamd);
    border-radius:12px;padding:.75rem;text-align:center;
}
.stat-box-val{font-size:1.1rem;font-weight:800;color:var(--text);}
.stat-box-lbl{font-size:.7rem;color:var(--muted);margin-top:.15rem;}

/* Info rows */
.info-row{display:flex;justify-content:space-between;align-items:center;
    padding:.6rem 0;border-bottom:1px solid #F0EDE6;}
.info-row:last-child{border-bottom:none;padding-bottom:0;}
.info-row-key{font-size:.825rem;color:var(--muted);}
.info-row-key i{color:var(--green);width:14px;margin-right:.4rem;font-size:.75rem;}
.info-row-val{font-size:.825rem;font-weight:600;color:var(--text);}

/* Availability */
.avail-row{display:flex;justify-content:space-between;padding:.45rem 0;border-bottom:1px solid #F0EDE6;}
.avail-row:last-child{border-bottom:none;}
.avail-day{font-size:.825rem;color:var(--text);font-weight:600;}
.avail-time{font-size:.8rem;color:var(--green);}

/* Book CTA button */
.book-btn{
    display:block;width:100%;padding:.8rem;
    border-radius:12px;
    background:var(--green);color:#fff;
    font-weight:700;font-size:.9rem;
    text-align:center;text-decoration:none;
    transition:background .2s,transform .15s;
    margin-top:1rem;
}
.book-btn:hover{background:var(--gl);transform:translateY(-1px);}

/* ── Main content cards ───────────────────────────────────── */
.prof-main-card{
    background:#fff;
    border-radius:20px;
    box-shadow:0 2px 16px rgba(26,107,60,.07);
    border:1.5px solid #EDE9E0;
    padding:1.75rem;
    margin-bottom:1.25rem;
}
.prof-main-card:last-child{margin-bottom:0;}
.pmc-title{
    font-size:1rem;font-weight:700;color:var(--text);
    margin-bottom:1rem;padding-bottom:.75rem;
    border-bottom:1.5px solid #F0EDE6;
    display:flex;align-items:center;gap:.5rem;
}
.pmc-title i{color:var(--green);}

/* Bio text */
.bio-text{font-size:.9rem;color:#374151;line-height:1.75;}

/* Education / cert badge */
.edu-item{margin-top:1rem;padding-top:1rem;border-top:1px solid #F0EDE6;}
.edu-label{font-size:.72rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);font-weight:700;margin-bottom:.3rem;}
.edu-val{font-size:.875rem;color:var(--text);}

/* Subject tags */
.subj-tags{display:flex;flex-wrap:wrap;gap:.5rem;}
.subj-tag{
    padding:.4rem 1rem;border-radius:99px;
    background:rgba(26,107,60,.08);
    border:1px solid rgba(26,107,60,.15);
    color:var(--green);font-size:.8rem;font-weight:600;
}

/* Reviews */
.review-item{
    display:flex;gap:.875rem;
    padding:1rem 0;
    border-bottom:1px solid #F0EDE6;
}
.review-item:last-child{border-bottom:none;padding-bottom:0;}
.rev-avatar{width:38px;height:38px;border-radius:50%;object-fit:cover;flex-shrink:0;}
.rev-meta{display:flex;align-items:center;justify-content:space-between;margin-bottom:.35rem;}
.rev-name{font-size:.875rem;font-weight:600;color:var(--text);}
.rev-stars i{font-size:.65rem;color:#D1D5DB;}
.rev-stars i.lit{color:#F59E0B;}
.rev-body{font-size:.85rem;color:#374151;line-height:1.6;}
.rev-date{font-size:.75rem;color:var(--muted);margin-top:.25rem;}

.no-reviews{text-align:center;padding:2rem;color:var(--muted);font-size:.875rem;}
.no-reviews i{font-size:1.5rem;display:block;margin-bottom:.5rem;opacity:.4;}
</style>
@endpush

@section('content')

{{-- ── BANNER ────────────────────────────────────────────── --}}
<div class="prof-banner">
    <div class="prof-banner-inner">
        <img src="{{ $teacher->user->avatar_url }}"
             class="prof-banner-avatar"
             alt="{{ $teacher->user->name }}">
        <div class="prof-banner-info">
            <h1 class="prof-banner-name">{{ $teacher->user->name }}</h1>
            <div class="prof-banner-spec">{{ $teacher->specialization ?? 'Quran Teacher' }}</div>
            @if($teacher->total_reviews > 0)
            <div class="prof-banner-stars">
                @for($i=1;$i<=5;$i++)
                    <i class="fas fa-star {{ $i <= round($teacher->rating) ? 'lit' : '' }}"></i>
                @endfor
                <span>{{ number_format($teacher->rating,1) }} · {{ $teacher->total_reviews }} {{ $teacher->total_reviews == 1 ? 'review' : 'reviews' }}</span>
            </div>
            @else
            <div class="prof-banner-stars">
                <span style="background:rgba(255,255,255,.15);padding:.2rem .7rem;border-radius:99px;font-size:.75rem;color:rgba(255,255,255,.85);font-weight:600;">
                    <i class="fas fa-user-check mr-1"></i> Verified Teacher
                </span>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- ── BODY GRID ─────────────────────────────────────────── --}}
<div class="prof-body">

    {{-- ── SIDEBAR ─────────────────────────────────────────── --}}
    <div>
        {{-- Stats --}}
        <div class="prof-side-card">
            <div class="psc-head">Quick Stats</div>
            <div class="psc-body">
                <div class="stat-grid">
                    <div class="stat-box">
                        <div class="stat-box-val">{{ number_format($teacher->rating, 1) }}</div>
                        <div class="stat-box-lbl">Rating</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-box-val">{{ $teacher->total_reviews }}</div>
                        <div class="stat-box-lbl">Reviews</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-box-val">{{ $teacher->total_sessions }}</div>
                        <div class="stat-box-lbl">Sessions</div>
                    </div>
                    <div class="stat-box">
                        <div class="stat-box-val">{{ $teacher->experience_years }}yr</div>
                        <div class="stat-box-lbl">Experience</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Details --}}
        <div class="prof-side-card" style="margin-top:1rem;">
            <div class="psc-head">Details</div>
            <div class="psc-body" style="padding-top:.75rem;padding-bottom:.75rem;">
                @if(!empty($teacher->city))
                    <div class="info-row">
                        <span class="info-row-key"><i class="fas fa-map-marker-alt"></i>City</span>
                        <span class="info-row-val">{{ $teacher->city }}</span>
                    </div>
                @elseif($teacher->nationality)
                    <div class="info-row">
                        <span class="info-row-key"><i class="fas fa-globe"></i>Nationality</span>
                        <span class="info-row-val">{{ $teacher->nationality }}</span>
                    </div>
                @endif
                @if($teacher->language)
                    <div class="info-row">
                        <span class="info-row-key"><i class="fas fa-language"></i>Language</span>
                        <span class="info-row-val">{{ $teacher->language }}</span>
                    </div>
                @endif
                @if($teacher->experience_years)
                <div class="info-row">
                    <span class="info-row-key"><i class="fas fa-briefcase"></i>Experience</span>
                    <span class="info-row-val">{{ $teacher->experience_years }} years</span>
                </div>
                @endif
                @if($teacher->gender)
                <div class="info-row">
                    <span class="info-row-key"><i class="fas fa-user"></i>Gender</span>
                    <span class="info-row-val">{{ ucfirst($teacher->gender) }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Availability --}}
        @if($teacher->availabilities->count())
            <div class="prof-side-card" style="margin-top:1rem;">
                <div class="psc-head">Availability</div>
                <div class="psc-body" style="padding-top:.75rem;padding-bottom:.75rem;">
                    @foreach($teacher->availabilities->sortBy('day_of_week') as $slot)
                        <div class="avail-row">
                            <span class="avail-day">{{ $slot->day_name }}</span>
                            <span class="avail-time">{{ date('h:i A', strtotime($slot->start_time)) }} – {{ date('h:i A', strtotime($slot->end_time)) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Enroll CTA --}}
        @auth
            @if(auth()->user()->isStudent())
                <a href="{{ route('student.enroll.create', ['teacher_id' => $teacher->id]) }}" class="book-btn">
                    <i class="fas fa-graduation-cap mr-2"></i> Enroll with This Teacher
                </a>
            @endif
        @else
            <a href="{{ route('register', 'student') }}" class="book-btn">
                <i class="fas fa-graduation-cap mr-2"></i> Enroll Now
            </a>
        @endauth

        {{-- Back link --}}
        <div style="text-align:center;margin-top:1rem;">
            <a href="{{ route('teachers') }}" style="font-size:.8rem;color:var(--muted);text-decoration:none;">
                <i class="fas fa-arrow-left mr-1"></i> All Teachers
            </a>
        </div>
    </div>

    {{-- ── MAIN CONTENT ─────────────────────────────────────── --}}
    <div>

        {{-- About --}}
        <div class="prof-main-card">
            <div class="pmc-title"><i class="fas fa-user"></i> About {{ $teacher->user->name }}</div>
            <p class="bio-text">{{ $teacher->bio ?? 'No biography provided yet.' }}</p>

            @if($teacher->education)
                <div class="edu-item">
                    <div class="edu-label">Education</div>
                    <div class="edu-val">{{ $teacher->education }}</div>
                </div>
            @endif
            @if($teacher->certification)
                <div class="edu-item">
                    <div class="edu-label">Certification / Ijazah</div>
                    <div class="edu-val">{{ $teacher->certification }}</div>
                </div>
            @endif
        </div>

        {{-- Subjects --}}
        @if($teacher->subjects)
            <div class="prof-main-card">
                <div class="pmc-title"><i class="fas fa-book-open"></i> Subjects Taught</div>
                <div class="subj-tags">
                    @foreach($teacher->subjects as $subject)
                        <span class="subj-tag">{{ $subject }}</span>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Reviews --}}
        <div class="prof-main-card">
            <div class="pmc-title">
                <i class="fas fa-star"></i>
                Student Reviews
                <span style="background:rgba(26,107,60,.1);color:var(--green);font-size:.75rem;
                             padding:.15rem .5rem;border-radius:99px;font-weight:700;margin-left:.25rem;">
                    {{ $teacher->total_reviews }}
                </span>
            </div>

            @forelse($teacher->reviews->sortByDesc('created_at')->take(6) as $review)
                <div class="review-item">
                    <img src="{{ $review->student->user->avatar_url }}"
                         class="rev-avatar"
                         alt="{{ $review->student->user->name }}">
                    <div style="flex:1;">
                        <div class="rev-meta">
                            <span class="rev-name">{{ $review->student->user->name }}</span>
                            <div class="rev-stars">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fas fa-star {{ $i <= $review->rating ? 'lit' : '' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="rev-body">{{ $review->comment }}</p>
                        <div class="rev-date">{{ $review->created_at->diffForHumans() }}</div>
                    </div>
                </div>
            @empty
                <div class="no-reviews">
                    <i class="fas fa-comment-slash"></i>
                    No reviews yet — be the first to leave one after your session!
                </div>
            @endforelse
        </div>

    </div>
</div>

@endsection
