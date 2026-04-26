@extends('layouts.app')
@section('title', 'Sign In – Peace Institute')

@push('styles')
<style>
.auth-wrap{min-height:100vh;display:grid;grid-template-columns:1fr 1fr;}
@media(max-width:900px){.auth-wrap{grid-template-columns:1fr;}.auth-panel{display:none;}}

/* Left green panel */
.auth-panel{
    background:linear-gradient(160deg,var(--gd) 0%,var(--green) 60%,#22874D 100%);
    display:flex;flex-direction:column;justify-content:space-between;
    padding:3rem;position:relative;overflow:hidden;
}
.auth-panel::before{
    content:'';position:absolute;top:-80px;right:-80px;
    width:320px;height:320px;
    border-radius:50%;
    background:rgba(201,164,39,.12);
}
.auth-panel::after{
    content:'بِسْمِ اللّٰهِ الرَّحْمٰنِ الرَّحِيْمِ';
    font-family:'Amiri',serif;font-size:2rem;color:rgba(255,255,255,.08);
    position:absolute;bottom:6rem;right:2rem;letter-spacing:.04em;
    writing-mode:horizontal-tb;
}
.panel-logo{
    display:flex;align-items:center;gap:.75rem;
}
.panel-logo-mark{
    width:48px;height:48px;border-radius:12px;
    background:rgba(255,255,255,.15);
    display:flex;align-items:center;justify-content:center;
    font-weight:800;font-size:1.1rem;color:#fff;letter-spacing:-.5px;
}
.panel-logo-text{color:#fff;font-size:1.15rem;font-weight:700;letter-spacing:-.02em;}
.panel-tagline{
    font-family:'Playfair Display',serif;
    font-size:2.2rem;font-weight:700;color:#fff;line-height:1.25;
}
.panel-tagline span{color:var(--gold);}
.panel-quote{
    background:rgba(255,255,255,.1);
    border:1px solid rgba(255,255,255,.15);
    border-radius:16px;padding:1.25rem 1.5rem;
}
.panel-quote p{color:rgba(255,255,255,.85);font-size:.9rem;line-height:1.6;}
.panel-quote cite{color:var(--goldl);font-size:.8rem;display:block;margin-top:.5rem;}
.panel-features{display:flex;flex-direction:column;gap:.75rem;}
.panel-feat{display:flex;align-items:center;gap:.75rem;color:rgba(255,255,255,.8);font-size:.875rem;}
.panel-feat-icon{
    width:32px;height:32px;border-radius:8px;
    background:rgba(201,164,39,.2);
    display:flex;align-items:center;justify-content:center;
    color:var(--goldl);font-size:.8rem;flex-shrink:0;
}

/* Right form area */
.auth-form-side{
    background:var(--cream);
    display:flex;align-items:center;justify-content:center;
    padding:3rem 2rem;
}
.auth-card{
    width:100%;max-width:420px;
    background:#fff;
    border-radius:24px;
    box-shadow:0 4px 32px rgba(26,107,60,.08);
    padding:2.5rem;
}
.auth-card-title{font-size:1.6rem;font-weight:800;color:var(--text);margin-bottom:.25rem;}
.auth-card-sub{color:var(--muted);font-size:.9rem;margin-bottom:2rem;}

.auth-label{display:block;font-size:.82rem;font-weight:600;color:var(--text);margin-bottom:.4rem;}
.auth-input{
    width:100%;padding:.7rem 1rem;
    border:1.5px solid #D1D5DB;border-radius:10px;
    font-size:.9rem;color:var(--text);background:#fff;
    transition:border-color .2s,box-shadow .2s;outline:none;
}
.auth-input:focus{border-color:var(--green);box-shadow:0 0 0 3px rgba(26,107,60,.1);}
.auth-input::placeholder{color:#9CA3AF;}

.auth-submit{
    width:100%;padding:.8rem;border-radius:12px;
    background:var(--green);color:#fff;
    font-weight:700;font-size:.95rem;border:none;cursor:pointer;
    transition:background .2s,transform .15s;margin-top:.5rem;
}
.auth-submit:hover{background:var(--gl);transform:translateY(-1px);}
.auth-submit:active{transform:translateY(0);}

.auth-divider{
    display:flex;align-items:center;gap:.75rem;
    color:var(--muted);font-size:.8rem;margin:1.25rem 0;
}
.auth-divider::before,.auth-divider::after{
    content:'';flex:1;height:1px;background:#E5E7EB;
}
.auth-link{color:var(--green);font-weight:600;text-decoration:none;}
.auth-link:hover{text-decoration:underline;}

.remember-row{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;}
.remember-label{display:flex;align-items:center;gap:.5rem;font-size:.85rem;color:var(--muted);cursor:pointer;}
.remember-label input[type=checkbox]{
    width:16px;height:16px;accent-color:var(--green);cursor:pointer;
}

.err-box{
    background:#FEF2F2;border:1px solid #FECACA;
    color:#DC2626;font-size:.85rem;
    padding:.75rem 1rem;border-radius:10px;margin-bottom:1.25rem;
}

/* Mobile logo (shown only when panel hidden) */
.mobile-auth-logo{
    display:none;text-align:center;margin-bottom:1.5rem;
}
@media(max-width:900px){
    .mobile-auth-logo{display:block;}
    .auth-form-side{background:var(--cream);padding:5rem 1.5rem 3rem;}
}
</style>
@endpush

@section('content')
<div class="auth-wrap">

    {{-- ── LEFT: Green Brand Panel ────────────────────────── --}}
    <div class="auth-panel">
        <div class="panel-logo">
            <div class="panel-logo-mark">PI</div>
            <span class="panel-logo-text">Peace Institute</span>
        </div>

        <div>
            <p class="panel-tagline">
                Learn the Quran<br>from <span>certified scholars</span>
            </p>
            <p style="color:rgba(255,255,255,.65);font-size:.9rem;margin-top:.75rem;">
                One-on-one live Zoom sessions. Flexible timings. Every age welcome.
            </p>
        </div>

        <div class="panel-features">
            <div class="panel-feat">
                <div class="panel-feat-icon"><i class="fas fa-video"></i></div>
                Live Zoom classes, any timezone
            </div>
            <div class="panel-feat">
                <div class="panel-feat-icon"><i class="fas fa-graduation-cap"></i></div>
                Certified male &amp; female teachers
            </div>
            <div class="panel-feat">
                <div class="panel-feat-icon"><i class="fas fa-child"></i></div>
                Kids, adults, beginners &amp; advanced
            </div>
            <div class="panel-feat">
                <div class="panel-feat-icon"><i class="fas fa-globe"></i></div>
                Students from 40+ countries
            </div>
        </div>

        <div class="panel-quote">
            <p>اقْرَأْ بِاسْمِ رَبِّكَ الَّذِي خَلَقَ</p>
            <cite>Surah Al-Alaq 96:1 — "Read in the name of your Lord who created."</cite>
        </div>
    </div>

    {{-- ── RIGHT: Login Form ───────────────────────────────── --}}
    <div class="auth-form-side">
        <div class="auth-card">

            <div class="mobile-auth-logo">
                <div style="width:52px;height:52px;border-radius:14px;background:var(--green);display:flex;align-items:center;justify-content:center;margin:0 auto .75rem;font-weight:800;font-size:1.1rem;color:#fff;">PI</div>
                <p style="font-weight:700;font-size:1rem;color:var(--text);">Peace Institute</p>
            </div>

            <h1 class="auth-card-title">Welcome back</h1>
            <p class="auth-card-sub">Sign in to your account to continue</p>

            @if($errors->any())
                <div class="err-box"><i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}</div>
            @endif

            @if(session('status'))
                <div style="background:#F0FDF4;border:1px solid #BBF7D0;color:#166534;font-size:.85rem;padding:.75rem 1rem;border-radius:10px;margin-bottom:1.25rem;">
                    {{ session('status') }}
                </div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf

                <div style="margin-bottom:1rem;">
                    <label class="auth-label" for="email">Email Address</label>
                    <input class="auth-input" id="email" type="email" name="email"
                        value="{{ old('email') }}" required autocomplete="email"
                        placeholder="you@example.com">
                </div>

                <div style="margin-bottom:1rem;">
                    <label class="auth-label" for="password">Password</label>
                    <input class="auth-input" id="password" type="password" name="password"
                        required autocomplete="current-password" placeholder="••••••••">
                </div>

                <div class="remember-row">
                    <label class="remember-label">
                        <input type="checkbox" name="remember"> Remember me
                    </label>
                </div>

                <button type="submit" class="auth-submit">
                    Sign In &nbsp;<i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="auth-divider">or</div>

            <p style="text-align:center;font-size:.875rem;color:var(--muted);">
                Don't have an account?
                <a href="{{ route('register') }}" class="auth-link">Create one free</a>
            </p>

            <p style="text-align:center;font-size:.75rem;color:#9CA3AF;margin-top:1.5rem;">
                Are you a teacher?
                <a href="{{ route('register', 'teacher') }}" class="auth-link" style="font-size:.75rem;">Apply to teach →</a>
            </p>
        </div>
    </div>

</div>
@endsection
