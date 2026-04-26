@extends('layouts.app')
@section('title', 'Register – Peace Institute')

@push('styles')
<style>
/* ── Page shell ──────────────────────────────────────────── */
.reg-page{
    min-height:100vh;
    background:var(--cream);
    display:flex;align-items:flex-start;justify-content:center;
    padding:6rem 1.5rem 4rem;
}
.reg-wrap{width:100%;max-width:580px;}

/* ── Top badge ───────────────────────────────────────────── */
.reg-badge{
    display:inline-flex;align-items:center;gap:.5rem;
    background:rgba(26,107,60,.1);color:var(--green);
    font-size:.78rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase;
    padding:.35rem .85rem;border-radius:99px;margin-bottom:1.25rem;
}

/* ── Role toggle ─────────────────────────────────────────── */
.role-toggle{
    display:flex;gap:.5rem;
    background:#fff;
    border:1.5px solid #E5E7EB;
    border-radius:14px;padding:.35rem;
    margin-bottom:1.75rem;
}
.role-tab{
    flex:1;display:flex;align-items:center;justify-content:center;gap:.5rem;
    padding:.6rem 1rem;border-radius:10px;
    font-size:.875rem;font-weight:600;
    text-decoration:none;transition:all .2s;
    color:var(--muted);
}
.role-tab.active{
    background:var(--green);color:#fff;
    box-shadow:0 2px 8px rgba(26,107,60,.25);
}
.role-tab:not(.active):hover{background:var(--creamd);color:var(--text);}

/* ── Card ────────────────────────────────────────────────── */
.reg-card{
    background:#fff;
    border-radius:24px;
    box-shadow:0 4px 32px rgba(26,107,60,.08);
    padding:2.5rem;
}
.reg-card-title{font-size:1.5rem;font-weight:800;color:var(--text);margin-bottom:.25rem;}
.reg-card-sub{color:var(--muted);font-size:.875rem;margin-bottom:2rem;}

/* ── Form elements ───────────────────────────────────────── */
.frm-row{display:grid;gap:1rem;}
.frm-row.cols-2{grid-template-columns:1fr 1fr;}
@media(max-width:520px){.frm-row.cols-2{grid-template-columns:1fr;}}
.frm-group{display:flex;flex-direction:column;gap:.4rem;}

.frm-label{font-size:.82rem;font-weight:600;color:var(--text);}
.frm-label .req{color:#DC2626;margin-left:.1rem;}
.frm-label .opt{color:var(--muted);font-weight:400;font-size:.75rem;margin-left:.3rem;}

.frm-input,.frm-select{
    width:100%;padding:.7rem 1rem;
    border:1.5px solid #D1D5DB;border-radius:10px;
    font-size:.875rem;color:var(--text);background:#fff;
    transition:border-color .2s,box-shadow .2s;outline:none;
    appearance:none;-webkit-appearance:none;
}
.frm-input:focus,.frm-select:focus{
    border-color:var(--green);box-shadow:0 0 0 3px rgba(26,107,60,.1);
}
.frm-input::placeholder{color:#9CA3AF;}

.frm-hint{font-size:.75rem;color:var(--muted);margin-top:.25rem;}

/* Select wrapper */
.sel-wrap{position:relative;}
.sel-wrap::after{
    content:'\f107';font-family:'Font Awesome 6 Free';font-weight:900;
    position:absolute;right:.9rem;top:50%;transform:translateY(-50%);
    color:var(--muted);pointer-events:none;font-size:.8rem;
}

/* Teacher rate box */
.rate-box{
    background:rgba(201,164,39,.06);
    border:1.5px solid rgba(201,164,39,.25);
    border-radius:12px;padding:1rem 1.25rem;
}
.rate-box-label{font-size:.78rem;font-weight:700;color:var(--text);text-transform:uppercase;letter-spacing:.04em;margin-bottom:.5rem;}

/* Submit */
.reg-submit{
    width:100%;padding:.85rem;border-radius:12px;
    background:var(--green);color:#fff;
    font-weight:700;font-size:.95rem;border:none;cursor:pointer;
    transition:background .2s,transform .15s;
    display:flex;align-items:center;justify-content:center;gap:.5rem;
}
.reg-submit:hover{background:var(--gl);transform:translateY(-1px);}
.reg-submit:active{transform:translateY(0);}

/* Divider + link */
.auth-divider{
    display:flex;align-items:center;gap:.75rem;
    color:var(--muted);font-size:.8rem;margin:1.25rem 0;
}
.auth-divider::before,.auth-divider::after{content:'';flex:1;height:1px;background:#E5E7EB;}
.auth-link{color:var(--green);font-weight:600;text-decoration:none;}
.auth-link:hover{text-decoration:underline;}

/* Errors */
.err-box{
    background:#FEF2F2;border:1px solid #FECACA;
    color:#DC2626;font-size:.85rem;
    padding:.75rem 1rem;border-radius:10px;margin-bottom:1.25rem;
}
.err-box ul{padding-left:1rem;margin:0;}
.err-box li{margin-top:.2rem;}

/* Trust strip */
.trust-strip{
    display:flex;align-items:center;justify-content:center;gap:1.5rem;
    margin-top:1.5rem;flex-wrap:wrap;
}
.trust-item{
    display:flex;align-items:center;gap:.4rem;
    font-size:.78rem;color:var(--muted);
}
.trust-item i{color:var(--green);}
</style>
@endpush

@section('content')
<div class="reg-page">
    <div class="reg-wrap">

        <div class="text-center">
            <span class="reg-badge"><i class="fas fa-star"></i> Free to Join</span>
            <h1 style="font-size:2rem;font-weight:800;color:var(--text);margin-bottom:.3rem;">
                {{ $role === 'teacher' ? 'Apply to Teach' : 'Start Learning Today' }}
            </h1>
            <p style="color:var(--muted);font-size:.95rem;margin-bottom:1.5rem;">
                {{ $role === 'teacher' ? 'Join our team of certified Quran teachers worldwide' : 'Create your free student account in under a minute' }}
            </p>
        </div>

        {{-- Role Toggle --}}
        <div class="role-toggle">
            <a href="{{ route('register', 'student') }}"
               class="role-tab {{ $role === 'student' ? 'active' : '' }}">
                <i class="fas fa-graduation-cap"></i> Student
            </a>
            <a href="{{ route('register', 'teacher') }}"
               class="role-tab {{ $role === 'teacher' ? 'active' : '' }}">
                <i class="fas fa-chalkboard-teacher"></i> Teacher
            </a>
        </div>

        <div class="reg-card">
            <h2 class="reg-card-title">
                {{ $role === 'teacher' ? 'Teacher Application' : 'Create Your Account' }}
            </h2>
            <p class="reg-card-sub">
                {{ $role === 'teacher' ? 'Fill in your details — our team will review and contact you.' : 'All fields marked * are required.' }}
            </p>

            @if($errors->any())
                <div class="err-box">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf
                <input type="hidden" name="role" value="{{ $role }}">

                <div style="display:flex;flex-direction:column;gap:1rem;">

                    {{-- Name + Phone --}}
                    <div class="frm-row cols-2">
                        <div class="frm-group">
                            <label class="frm-label" for="name">Full Name <span class="req">*</span></label>
                            <input class="frm-input" id="name" type="text" name="name"
                                value="{{ old('name') }}" required placeholder="Your full name">
                        </div>
                        <div class="frm-group">
                            <label class="frm-label" for="phone">Phone <span class="opt">(optional)</span></label>
                            <input class="frm-input" id="phone" type="tel" name="phone"
                                value="{{ old('phone') }}" placeholder="+1 234 567 890">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="frm-group">
                        <label class="frm-label" for="email">Email Address <span class="req">*</span></label>
                        <input class="frm-input" id="email" type="email" name="email"
                            value="{{ old('email') }}" required placeholder="you@example.com">
                    </div>

                    {{-- Timezone --}}
                    <div class="frm-group">
                        <label class="frm-label" for="timezone">Your Timezone</label>
                        <div class="sel-wrap">
                            <select class="frm-select" id="timezone" name="timezone">
                                @foreach(timezone_identifiers_list() as $tz)
                                    <option value="{{ $tz }}" {{ $tz === 'UTC' ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Passwords --}}
                    <div class="frm-row cols-2">
                        <div class="frm-group">
                            <label class="frm-label" for="password">Password <span class="req">*</span></label>
                            <input class="frm-input" id="password" type="password" name="password"
                                required placeholder="Min. 8 characters">
                        </div>
                        <div class="frm-group">
                            <label class="frm-label" for="password_confirmation">Confirm Password <span class="req">*</span></label>
                            <input class="frm-input" id="password_confirmation" type="password"
                                name="password_confirmation" required placeholder="Repeat password">
                        </div>
                    </div>

                    @if($role === 'teacher')
                        {{-- Teacher-only: hourly rate --}}
                        <div class="rate-box">
                            <div class="rate-box-label">
                                <i class="fas fa-dollar-sign" style="color:var(--gold);margin-right:.4rem;"></i>
                                Desired Hourly Rate (USD)
                            </div>
                            <div class="frm-group">
                                <input class="frm-input" type="number" name="hourly_rate"
                                    value="{{ old('hourly_rate', 15) }}" min="5" step="0.50"
                                    placeholder="15.00">
                                <span class="frm-hint">Admin may adjust this after reviewing your application.</span>
                            </div>
                        </div>
                    @endif

                    {{-- Submit --}}
                    <button type="submit" class="reg-submit" style="margin-top:.5rem;">
                        <i class="fas fa-user-plus"></i>
                        {{ $role === 'teacher' ? 'Submit Application' : 'Create Free Account' }}
                    </button>
                </div>
            </form>

            <div class="auth-divider">already have an account?</div>

            <p style="text-align:center;font-size:.875rem;color:var(--muted);">
                <a href="{{ route('login') }}" class="auth-link">Sign in to your account →</a>
            </p>
        </div>

        {{-- Trust strip --}}
        <div class="trust-strip">
            <div class="trust-item"><i class="fas fa-shield-alt"></i> Secure & Private</div>
            <div class="trust-item"><i class="fas fa-lock"></i> No credit card needed</div>
            <div class="trust-item"><i class="fas fa-check-circle"></i> Free trial class</div>
        </div>

    </div>
</div>
@endsection
