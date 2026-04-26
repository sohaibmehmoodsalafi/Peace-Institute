@extends('layouts.app')

@section('title', 'Peace Institute – Online Quran Academy')
@section('meta_description', 'Learn Quran online with certified male & female teachers. Courses in Qaida, Nazra, Tajweed, Hifz, Tafseer & Dars-e-Nizami. First class free.')

@push('styles')
<style>
    /* ── HERO ── */
    .hero {
        min-height: 620px;
        background: linear-gradient(135deg, #0F3D22 0%, #1A6B3C 60%, #22874D 100%);
        display: flex; align-items: stretch; overflow: hidden; position: relative;
    }
    .hero::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            repeating-linear-gradient(30deg,  rgba(255,255,255,.02) 0, rgba(255,255,255,.02) 1px, transparent 1px, transparent 44px),
            repeating-linear-gradient(-30deg, rgba(255,255,255,.02) 0, rgba(255,255,255,.02) 1px, transparent 1px, transparent 44px);
        pointer-events: none;
    }
    .hero-left  { flex: 1; padding: 72px 56px 72px 64px; position: relative; z-index: 1; display: flex; flex-direction: column; justify-content: center; }
    .hero-right { width: 400px; flex-shrink: 0; padding: 48px 48px 48px 32px; display: flex; align-items: center; position: relative; z-index: 1; }
    .arabic-wm {
        position: absolute; right: 420px; top: 50%; transform: translateY(-50%);
        font-family: 'Amiri', serif; font-size: 11rem; font-weight: 700;
        color: rgba(255,255,255,.04); pointer-events: none; direction: rtl; white-space: nowrap; line-height: 1;
    }

    /* Form card */
    .form-card { background: #fff; border-radius: 20px; padding: 32px 28px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,.2), 0 4px 16px rgba(0,0,0,.1); }
    .form-card h3 { font-size: 1.05rem; font-weight: 800; color: var(--text); margin-bottom: 4px; }
    .form-card p  { font-size: .76rem; color: var(--muted); margin-bottom: 20px; }
    .f-select, .f-input {
        width: 100%; padding: 10px 14px; border-radius: 10px;
        border: 1.5px solid #E5E7EB; background: #FAFAFA;
        font-size: .84rem; color: var(--text); font-family: 'Inter', sans-serif;
        margin-bottom: 10px; transition: border-color .2s; appearance: none;
    }
    .f-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%236B7280' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
        background-repeat: no-repeat; background-position: right 12px center; background-size: 15px; cursor: pointer;
    }
    .f-select:focus, .f-input:focus { outline: none; border-color: var(--green); }
    .f-input::placeholder { color: #9CA3AF; }
    .f-btn {
        width: 100%; padding: 13px; border-radius: 12px;
        background: linear-gradient(135deg, var(--green), var(--gl));
        color: #fff; font-weight: 800; font-size: .92rem;
        border: none; cursor: pointer; font-family: 'Inter', sans-serif;
        box-shadow: 0 6px 20px rgba(26,107,60,.35); transition: all .3s;
    }
    .f-btn:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(26,107,60,.45); }

    /* Buttons in hero */
    .hero-btn-gold {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 14px 32px; border-radius: 12px;
        background: linear-gradient(135deg, var(--goldl), var(--gold), var(--goldd));
        color: #0F1F0A; font-weight: 800; font-size: .92rem;
        text-decoration: none; box-shadow: 0 8px 24px rgba(201,164,39,.4);
        transition: all .3s;
    }
    .hero-btn-gold:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(201,164,39,.5); }
    .hero-btn-ghost {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 14px 32px; border-radius: 12px;
        background: rgba(255,255,255,.1); border: 1.5px solid rgba(255,255,255,.25);
        color: #fff; font-weight: 600; font-size: .92rem;
        text-decoration: none; transition: all .3s;
    }
    .hero-btn-ghost:hover { background: rgba(255,255,255,.18); border-color: rgba(255,255,255,.5); }

    /* ── STATS BAR ── */
    .stats-bar { background: #fff; border-bottom: 1px solid rgba(26,107,60,.07); padding: 22px 64px; display: flex; align-items: center; justify-content: space-around; flex-wrap: wrap; gap: 12px; }
    .stat-item  { text-align: center; }
    .stat-num   { font-size: 1.9rem; font-weight: 900; color: var(--green); font-family: 'Playfair Display', serif; line-height: 1; }
    .stat-lbl   { font-size: .7rem; color: var(--muted); text-transform: uppercase; letter-spacing: .12em; margin-top: 3px; }
    .stat-div   { width: 1px; height: 36px; background: rgba(26,107,60,.12); }

    /* ── FEATURES ── */
    .features    { background: #fff; padding: 64px; border-top: 1px solid rgba(0,0,0,.06); }
    .feat-grid   { display: grid; grid-template-columns: repeat(4,1fr); gap: 0; max-width: 1280px; margin: 0 auto; }
    .feat        { padding: 0 32px; border-right: 1px solid rgba(0,0,0,.07); }
    .feat:first-child { padding-left: 0; }
    .feat:last-child  { border-right: none; padding-right: 0; }
    .feat-icon   { width: 52px; height: 52px; border-radius: 14px; background: rgba(26,107,60,.08); display: flex; align-items: center; justify-content: center; margin-bottom: 18px; }
    .feat-icon i { font-size: 1.3rem; color: var(--green); }
    .feat h4     { font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 8px; }
    .feat p      { font-size: .8rem; color: var(--muted); line-height: 1.75; margin-bottom: 16px; }
    .feat-link   { display: inline-flex; align-items: center; gap: 6px; font-size: .78rem; font-weight: 700; color: var(--green); text-decoration: none; border: 1.5px solid rgba(26,107,60,.3); border-radius: 8px; padding: 7px 14px; transition: all .25s; }
    .feat-link:hover { background: var(--green); color: #fff; border-color: var(--green); }

    /* ── COURSES ── */
    .courses      { background: var(--cream); padding: 80px 0; }
    .courses-inner { max-width: 1280px; margin: 0 auto; padding: 0 32px; }
    .courses-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 22px; }
    .courses-row2 { display: grid; grid-template-columns: 2fr 1fr; gap: 22px; margin-top: 22px; }

    .c-card { background: #fff; border-radius: 18px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); transition: transform .35s, box-shadow .35s; display: flex; flex-direction: column; }
    .c-card:hover { transform: translateY(-6px); box-shadow: 0 16px 40px rgba(0,0,0,.12); }
    .c-img  { height: 180px; position: relative; overflow: hidden; display: flex; align-items: center; justify-content: center; flex-direction: column; gap: 8px; }
    .c-img i.icon-main { font-size: 2.5rem; color: rgba(255,255,255,.3); }
    .c-img .arabic-label { font-family: 'Amiri', serif; font-size: 1.6rem; color: rgba(255,255,255,.5); }
    .c-num-bg { position: absolute; bottom: -16px; right: 10px; font-family: 'Playfair Display', serif; font-size: 7rem; font-weight: 900; color: rgba(255,255,255,.06); line-height: 1; pointer-events: none; }
    .c-level  { position: absolute; top: 14px; left: 14px; background: rgba(255,255,255,.15); border: 1px solid rgba(255,255,255,.3); backdrop-filter: blur(8px); border-radius: 999px; padding: 4px 12px; font-size: .66rem; font-weight: 700; color: #fff; letter-spacing: .08em; text-transform: uppercase; }
    .c-gold-bar { position: absolute; bottom: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, transparent, var(--gold), transparent); }

    .c-body  { padding: 22px; flex: 1; display: flex; flex-direction: column; }
    .c-sub   { font-size: .68rem; font-weight: 700; color: var(--gold); letter-spacing: .1em; text-transform: uppercase; margin-bottom: 5px; }
    .c-body h3 { font-size: 1.05rem; font-weight: 700; color: var(--text); margin-bottom: 8px; }
    .c-body p  { font-size: .8rem; color: var(--muted); line-height: 1.7; flex: 1; margin-bottom: 16px; }
    .c-meta  { display: flex; align-items: center; justify-content: space-between; padding-top: 14px; border-top: 1px solid rgba(0,0,0,.06); }
    .c-dur   { font-size: .72rem; color: #9CA3AF; display: flex; align-items: center; gap: 5px; }
    .c-enroll { display: inline-flex; align-items: center; gap: 6px; background: var(--green); color: #fff; font-size: .76rem; font-weight: 700; padding: 7px 16px; border-radius: 8px; text-decoration: none; transition: all .25s; }
    .c-enroll:hover { background: var(--gl); }
    .c-enroll-gold { background: linear-gradient(135deg, var(--gold), var(--goldd)); color: #0F1F0A; }

    /* Wide course card */
    .c-card-wide .c-img { height: 150px; }
    .c-card-wide .c-body { flex-direction: row; gap: 24px; align-items: flex-start; }
    .c-body-aside { width: 185px; flex-shrink: 0; background: rgba(26,107,60,.05); border: 1px solid rgba(26,107,60,.1); border-radius: 12px; padding: 14px; }

    /* ── TEACHERS ── */
    .teachers      { background: #fff; padding: 80px 0; }
    .teachers-inner { max-width: 1280px; margin: 0 auto; padding: 0 32px; }
    .teachers-grid { display: grid; grid-template-columns: 1.4fr 1fr 1fr; gap: 22px; }

    /* Founder — big green */
    .t-main { background: var(--green); border-radius: 24px; overflow: hidden; box-shadow: 0 12px 40px rgba(26,107,60,.3); }
    .t-main-top { height: 190px; background: linear-gradient(160deg, var(--gd), var(--gl)); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
    .t-main-top::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 60px; background: linear-gradient(to top, var(--green), transparent); }
    .t-avatar-main { width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, rgba(201,164,39,.3), rgba(201,164,39,.1)); border: 3px solid rgba(201,164,39,.5); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 900; color: var(--gold); box-shadow: 0 0 0 6px rgba(201,164,39,.1); }
    .t-main-body { padding: 24px; }
    .t-main-body .t-role { font-size: .7rem; color: rgba(201,164,39,.85); font-weight: 700; letter-spacing: .1em; text-transform: uppercase; margin-bottom: 6px; }
    .t-main-body h3 { color: #fff; font-family: 'Playfair Display', serif; font-size: 1.2rem; font-weight: 700; margin-bottom: 12px; }
    .t-main-body p  { font-size: .81rem; color: rgba(255,255,255,.65); line-height: 1.8; margin-bottom: 18px; }
    .t-cred-w { display: flex; align-items: flex-start; gap: 8px; font-size: .77rem; color: rgba(255,255,255,.55); margin-bottom: 8px; }
    .t-cred-w i { color: var(--goldl); font-size: .7rem; margin-top: 3px; flex-shrink: 0; }
    .t-tag-w { display: inline-block; padding: 3px 10px; border-radius: 999px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18); font-size: .65rem; font-weight: 700; color: #fff; margin: 2px; }

    /* Secondary teacher cards */
    .t-card { background: var(--cream); border-radius: 20px; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,.06); transition: transform .35s, box-shadow .35s; }
    .t-card:hover { transform: translateY(-5px); box-shadow: 0 16px 36px rgba(0,0,0,.1); }
    .t-card-top { height: 150px; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
    .t-card-top::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 50px; background: linear-gradient(to top, var(--cream), transparent); }
    .t-avatar-sm { width: 76px; height: 76px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: 900; background: rgba(255,255,255,.15); border: 2.5px solid rgba(255,255,255,.4); color: #fff; }
    .t-card-body { padding: 18px 20px; }
    .t-card-body .t-role { font-size: .68rem; color: var(--gold); font-weight: 700; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 4px; }
    .t-card-body h3 { font-size: .95rem; font-weight: 700; color: var(--text); margin-bottom: 10px; }
    .t-card-body p  { font-size: .78rem; color: var(--muted); line-height: 1.65; margin-bottom: 14px; }
    .t-cred-sm { display: flex; align-items: flex-start; gap: 7px; font-size: .74rem; color: var(--muted); margin-bottom: 7px; }
    .t-cred-sm i { color: var(--green); font-size: .65rem; margin-top: 3px; flex-shrink: 0; }
    .t-tag { display: inline-block; padding: 3px 10px; border-radius: 999px; background: rgba(26,107,60,.07); border: 1px solid rgba(26,107,60,.15); font-size: .65rem; font-weight: 700; color: var(--green); margin: 2px; }

    /* ── CTA ── */
    .cta-sec {
        padding: 80px 0;
        background: linear-gradient(135deg, var(--gd) 0%, var(--green) 60%, #1A5C35 100%);
        position: relative; overflow: hidden;
    }
    .cta-sec::before {
        content: '';
        position: absolute; inset: 0;
        background-image:
            repeating-linear-gradient(60deg,  rgba(255,255,255,.02) 0, rgba(255,255,255,.02) 1px, transparent 1px, transparent 40px),
            repeating-linear-gradient(-60deg, rgba(255,255,255,.02) 0, rgba(255,255,255,.02) 1px, transparent 1px, transparent 40px);
        pointer-events: none;
    }
    .cta-sec::after {
        content: 'بِسْمِ اللهِ';
        position: absolute; right: -40px; top: 50%; transform: translateY(-50%);
        font-family: 'Amiri', serif; font-size: 12rem; font-weight: 700;
        color: rgba(255,255,255,.03); direction: rtl; pointer-events: none; white-space: nowrap;
    }
    .cta-inner { max-width: 1280px; margin: 0 auto; padding: 0 32px; display: grid; grid-template-columns: 1fr auto; gap: 64px; align-items: center; position: relative; z-index: 1; }
    .cta-ayah  { font-family: 'Amiri', serif; font-size: 1.7rem; color: var(--goldl); direction: rtl; margin-bottom: 6px; }
    .cta-ref   { font-size: .68rem; color: rgba(201,164,39,.55); letter-spacing: .15em; text-transform: uppercase; margin-bottom: 24px; }
    .cta-h2    { font-family: 'Playfair Display', serif; font-size: 2.8rem; font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 14px; }
    .cta-p     { font-size: .9rem; color: rgba(255,255,255,.6); line-height: 1.85; max-width: 460px; margin-bottom: 28px; }
    .cta-pills { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 32px; }
    .cta-pill  { display: inline-flex; align-items: center; gap: 7px; padding: 8px 16px; border-radius: 999px; background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15); font-size: .8rem; color: rgba(255,255,255,.8); font-weight: 500; }
    .cta-pill i { color: var(--goldl); font-size: .78rem; }
    .cta-btns  { display: flex; gap: 12px; flex-wrap: wrap; }
    .cta-btn-g { display: inline-flex; align-items: center; gap: 8px; padding: 15px 34px; border-radius: 12px; background: linear-gradient(135deg, var(--goldl), var(--gold), var(--goldd)); color: #0F1F0A; font-weight: 800; font-size: .92rem; text-decoration: none; box-shadow: 0 8px 24px rgba(201,164,39,.4); transition: all .3s; }
    .cta-btn-g:hover { transform: translateY(-2px); box-shadow: 0 14px 36px rgba(201,164,39,.5); }
    .cta-btn-w { display: inline-flex; align-items: center; gap: 8px; padding: 15px 34px; border-radius: 12px; background: rgba(255,255,255,.08); border: 1.5px solid rgba(255,255,255,.22); color: #fff; font-weight: 600; font-size: .92rem; text-decoration: none; transition: all .3s; }
    .cta-btn-w:hover { background: rgba(255,255,255,.16); border-color: rgba(255,255,255,.45); }

    /* Stats card */
    .cta-stats { background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.12); border-radius: 22px; padding: 32px 28px; backdrop-filter: blur(8px); min-width: 270px; }
    .cta-stats h4 { font-size: .68rem; color: rgba(201,164,39,.7); letter-spacing: .18em; text-transform: uppercase; margin-bottom: 22px; }
    .s-row { display: flex; align-items: center; justify-content: space-between; padding: 13px 0; border-bottom: 1px solid rgba(255,255,255,.07); }
    .s-row:last-child { border-bottom: none; }
    .s-row .slbl { font-size: .8rem; color: rgba(255,255,255,.5); }
    .s-row .sval { font-size: 1.4rem; font-weight: 900; color: var(--gold); font-family: 'Playfair Display', serif; }
</style>
@endpush

@section('content')

{{-- ╔══════════════════════════════════════════════════╗
     ║  HERO — Green bg + Enrollment Form              ║
     ╚══════════════════════════════════════════════════╝ --}}
<section class="hero">
    <div class="arabic-wm">قُرْآن</div>

    {{-- Left text --}}
    <div class="hero-left">

        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(201,164,39,.15);border:1px solid rgba(201,164,39,.3);border-radius:999px;padding:5px 14px;width:fit-content;margin-bottom:24px">
            <span style="width:6px;height:6px;border-radius:50%;background:var(--gold);display:inline-block"></span>
            <span style="font-size:.68rem;font-weight:700;letter-spacing:.18em;text-transform:uppercase;color:var(--gold)">Online Quran Academy</span>
        </div>

        <h1 style="font-family:'Playfair Display',serif;font-size:3.6rem;font-weight:900;color:#fff;line-height:1.1;margin-bottom:20px">
            Begin Your<br>
            <span style="background:linear-gradient(135deg,var(--goldl),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Quran Journey</span><br>
            From Home
        </h1>

        <p style="font-size:.96rem;color:rgba(255,255,255,.65);line-height:1.85;max-width:440px;margin-bottom:32px">
            Certified male &amp; female teachers. One-on-one live sessions. Structured courses from Qaida to Dars-e-Nizami — for every age and level.
        </p>

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-bottom:32px">
            <a href="{{ route('courses') }}" class="hero-btn-gold">
                <i class="fas fa-book-open"></i> Explore Courses
            </a>
            <a href="{{ route('teachers') }}" class="hero-btn-ghost">
                <i class="fas fa-users"></i> Meet Teachers
            </a>
        </div>

        {{-- Trust row --}}
        <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
            <div style="display:flex;align-items:center;gap:8px">
                <div style="display:flex">
                    @foreach(['SM','ZB','AA'] as $init)
                    <div style="width:30px;height:30px;border-radius:50%;background:linear-gradient(135deg,var(--gold),var(--goldd));border:2px solid rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;font-size:.6rem;font-weight:800;color:#0F1F0A;margin-right:-8px;z-index:{{ $loop->index + 1 }}">{{ $init }}</div>
                    @endforeach
                </div>
                <span style="font-size:.78rem;color:rgba(255,255,255,.55)">{{ $stats['teachers'] ?? 50 }}+ Certified Teachers</span>
            </div>
            <div style="width:1px;height:20px;background:rgba(255,255,255,.15)"></div>
            <div style="font-size:.78rem;color:rgba(255,255,255,.55)">
                <i class="fas fa-star" style="color:var(--gold)"></i>
                {{ $stats['students'] ?? 1200 }}+ Happy Students
            </div>
        </div>
    </div>

    {{-- Right — Form --}}
    <div class="hero-right">
        <div class="form-card">
            <h3>Book Your Free Trial Class</h3>
            <p>First class 100% free — no credit card needed.</p>

            <form action="{{ route('register', 'student') }}" method="GET">
                <select class="f-select" name="course">
                    <option value="" disabled selected>Select a Course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>

                <select class="f-select" name="gender">
                    <option value="" disabled selected>Preferred Teacher Gender</option>
                    <option value="male">Male Teacher</option>
                    <option value="female">Female Teacher (Hijab)</option>
                    <option value="any">No Preference</option>
                </select>

                <select class="f-select" name="timezone">
                    <option value="" disabled selected>Your Timezone</option>
                    <option>Pakistan Standard Time (PKT)</option>
                    <option>Gulf Standard Time (GST)</option>
                    <option>UK Time (BST/GMT)</option>
                    <option>US Eastern (EST)</option>
                    <option>Other</option>
                </select>

                <input class="f-input" type="text"  name="name"  placeholder="Your Full Name">
                <input class="f-input" type="tel"   name="phone" placeholder="WhatsApp Number">

                <button type="submit" class="f-btn">Book Free Class →</button>
            </form>

            <p style="font-size:.67rem;color:#9CA3AF;text-align:center;margin-top:10px">
                <i class="fas fa-lock" style="font-size:.6rem"></i> No spam. We'll only contact you to confirm.
            </p>
        </div>
    </div>
</section>


{{-- ── STATS BAR ─────────────────────────────────────────────── --}}
<div class="stats-bar">
    <div class="stat-item"><div class="stat-num">{{ $stats['teachers'] ?? 50 }}+</div><div class="stat-lbl">Certified Teachers</div></div>
    <div class="stat-div"></div>
    <div class="stat-item"><div class="stat-num">{{ $stats['students'] ?? 1200 }}+</div><div class="stat-lbl">Active Students</div></div>
    <div class="stat-div"></div>
    <div class="stat-item"><div class="stat-num">{{ $stats['countries'] ?? 25 }}+</div><div class="stat-lbl">Countries</div></div>
    <div class="stat-div"></div>
    <div class="stat-item"><div class="stat-num">{{ $stats['sessions'] ?? 8500 }}+</div><div class="stat-lbl">Sessions Completed</div></div>
    <div class="stat-div"></div>
    <div class="stat-item"><div class="stat-num">6</div><div class="stat-lbl">Core Courses</div></div>
</div>


{{-- ── FEATURES BAR ──────────────────────────────────────────── --}}
<section class="features">
    <div class="feat-grid">

        <div class="feat reveal d1">
            <div class="feat-icon"><i class="fas fa-user-graduate"></i></div>
            <h4>One-on-One Classes</h4>
            <p>Personalised attention with your dedicated teacher — no group distractions. Just you and your Ustadh focused entirely on your progress.</p>
            <a href="{{ route('register', 'student') }}" class="feat-link">Enroll Free →</a>
        </div>

        <div class="feat reveal d2">
            <div class="feat-icon"><i class="fas fa-shield-alt"></i></div>
            <h4>Certified Scholars</h4>
            <p>All teachers are graduates of renowned Islamic institutions — Mahad As Salafi, Jamia Albayan, and Burooj Institute.</p>
            <a href="{{ route('teachers') }}" class="feat-link">Meet Them →</a>
        </div>

        <div class="feat reveal d3">
            <div class="feat-icon"><i class="fas fa-video"></i></div>
            <h4>Live Zoom Sessions</h4>
            <p>Real-time interaction via Zoom or Google Meet. Instant correction, two-way communication, and recorded sessions for review.</p>
            <a href="{{ route('register', 'student') }}" class="feat-link">How It Works →</a>
        </div>

        <div class="feat reveal d4">
            <div class="feat-icon"><i class="fas fa-clock"></i></div>
            <h4>Flexible Timings</h4>
            <p>Pick any time that suits your schedule — morning, evening, or night. We cover all timezones from the UK to Australia.</p>
            <a href="{{ route('register', 'student') }}" class="feat-link">Book a Slot →</a>
        </div>

    </div>
</section>


{{-- ╔══════════════════════════════════════════════════╗
     ║  COURSES SECTION                                 ║
     ╚══════════════════════════════════════════════════╝ --}}
<section class="courses">
    <div class="courses-inner">

        <div class="text-center reveal" style="margin-bottom:48px">
            <div class="section-label">Our Courses</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:2.6rem;font-weight:900;color:var(--text);line-height:1.15">
                Islamic <span style="color:var(--green)">Education</span> Programs
            </h2>
            <p style="font-size:.9rem;color:var(--muted);max-width:480px;margin:12px auto 0;line-height:1.75">
                A complete structured path — from the first Arabic letter to becoming a fully qualified Islamic scholar.
            </p>
        </div>

        {{-- Row 1: 3 cards --}}
        <div class="courses-grid">

            {{-- Noorani Qaida --}}
            <div class="c-card reveal d1">
                <div class="c-img" style="background:linear-gradient(135deg,#0F3D22,#1A6B3C)">
                    <i class="fas fa-book icon-main"></i>
                    <div class="arabic-label">ق</div>
                    <span class="c-level">Beginner</span>
                    <div class="c-num-bg">01</div>
                    <div class="c-gold-bar"></div>
                </div>
                <div class="c-body">
                    <div class="c-sub">Foundation Course</div>
                    <h3>Noorani Qaida</h3>
                    <p>Master the Arabic alphabet with correct pronunciation. Learn Harakat, Sukoon, Tanween and compound letters — the essential first step.</p>
                    <div class="c-meta">
                        <div class="c-dur"><i class="fas fa-clock"></i> 3–6 months</div>
                        <a href="{{ route('register', 'student') }}" class="c-enroll">Enroll →</a>
                    </div>
                </div>
            </div>

            {{-- Tajweed — Featured --}}
            <div class="c-card reveal d2" style="border:2px solid rgba(201,164,39,.25)">
                <div class="c-img" style="background:linear-gradient(135deg,#5C3A00,#8B6000)">
                    <i class="fas fa-music icon-main"></i>
                    <div class="arabic-label">تجويد</div>
                    <span class="c-level" style="background:rgba(201,164,39,.25);border-color:rgba(201,164,39,.5)">★ Most Popular</span>
                    <div class="c-num-bg">03</div>
                    <div class="c-gold-bar"></div>
                </div>
                <div class="c-body">
                    <div class="c-sub">Beautify Recitation</div>
                    <h3>Tajweed Course</h3>
                    <p>Recite the Quran exactly as revealed — with perfect Makhraj, Sifaat, Madd, Ghunna and all Tajweed rules from Ikhfa to Qalqala.</p>
                    <div class="c-meta">
                        <div class="c-dur"><i class="fas fa-clock"></i> 6–8 months</div>
                        <a href="{{ route('register', 'student') }}" class="c-enroll c-enroll-gold">Enroll →</a>
                    </div>
                </div>
            </div>

            {{-- Hifz --}}
            <div class="c-card reveal d3">
                <div class="c-img" style="background:linear-gradient(135deg,#1A1A4A,#2D2D7A)">
                    <i class="fas fa-heart icon-main"></i>
                    <div class="arabic-label">حفظ</div>
                    <span class="c-level">Advanced</span>
                    <div class="c-num-bg">04</div>
                    <div class="c-gold-bar"></div>
                </div>
                <div class="c-body">
                    <div class="c-sub">Full Memorization</div>
                    <h3>Hifz-ul-Quran</h3>
                    <p>Become a Hafiz-e-Quran with our Sabaq, Sabqi &amp; Manzil system. Structured daily memorization with strong retention testing.</p>
                    <div class="c-meta">
                        <div class="c-dur"><i class="fas fa-clock"></i> 2–4 years</div>
                        <a href="{{ route('register', 'student') }}" class="c-enroll">Enroll →</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Row 2: Nazrah (wide) + Dars-e-Nizami --}}
        <div class="courses-row2">

            {{-- Nazrah — wide --}}
            <div class="c-card c-card-wide reveal d4">
                <div class="c-img" style="background:linear-gradient(135deg,#1A3A2A,#2A6040)">
                    <i class="fas fa-file-alt icon-main"></i>
                    <div class="arabic-label">نظرہ</div>
                    <span class="c-level">Elementary</span>
                    <div class="c-num-bg">02</div>
                    <div class="c-gold-bar"></div>
                </div>
                <div class="c-body">
                    <div style="flex:1">
                        <div class="c-sub">Quran Reading</div>
                        <h3>Nazrah Quran</h3>
                        <p>Fluent Quran reading — Para by Para — from Surah Al-Fatiha to An-Nas. Build accuracy in Makhraj and Sifaat across all 30 Paras.</p>
                    </div>
                    <div class="c-body-aside">
                        <div style="font-size:.65rem;color:var(--green);font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:10px">Includes</div>
                        <div style="font-size:.75rem;color:var(--muted);display:flex;flex-direction:column;gap:6px">
                            <div><i class="fas fa-check" style="color:var(--green);font-size:.65rem;margin-right:5px"></i> 30 Paras complete coverage</div>
                            <div><i class="fas fa-check" style="color:var(--green);font-size:.65rem;margin-right:5px"></i> Waqf &amp; Ibtidaa rules</div>
                            <div><i class="fas fa-check" style="color:var(--green);font-size:.65rem;margin-right:5px"></i> Weekly progress report</div>
                        </div>
                        <div class="c-meta" style="margin-top:14px;padding-top:12px">
                            <div class="c-dur" style="font-size:.68rem"><i class="fas fa-clock"></i> 6–12 mo</div>
                            <a href="{{ route('register', 'student') }}" class="c-enroll">Enroll →</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Dars-e-Nizami --}}
            <div class="c-card reveal d5">
                <div class="c-img" style="background:linear-gradient(135deg,#1A0A00,#3A1800)">
                    <i class="fas fa-graduation-cap icon-main"></i>
                    <div class="arabic-label">درس نظامی</div>
                    <span class="c-level" style="background:rgba(201,164,39,.2);border-color:rgba(201,164,39,.4)">Scholar Level</span>
                    <div class="c-num-bg">06</div>
                    <div class="c-gold-bar"></div>
                </div>
                <div class="c-body">
                    <div class="c-sub">Aalim / Aalima Course</div>
                    <h3>Dars-e-Nizami</h3>
                    <p>Complete traditional scholars' curriculum — Quran, Hadith, Fiqh, Aqeedah, Nahw &amp; Sarf. Full Aalim certification program.</p>
                    <div class="c-meta">
                        <div class="c-dur"><i class="fas fa-clock"></i> 6–8 years</div>
                        <a href="{{ route('register', 'student') }}" class="c-enroll c-enroll-gold">Apply →</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- View all --}}
        <div class="text-center reveal" style="margin-top:40px">
            <a href="{{ route('courses') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--green);font-weight:700;font-size:.9rem;text-decoration:none;border:1.5px solid rgba(26,107,60,.3);padding:12px 28px;border-radius:10px;transition:all .25s"
               onmouseover="this.style.background='var(--green)';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='var(--green)'">
                View All Courses <i class="fas fa-arrow-right"></i>
            </a>
        </div>

    </div>
</section>


{{-- ╔══════════════════════════════════════════════════╗
     ║  TEACHERS SECTION                                ║
     ╚══════════════════════════════════════════════════╝ --}}
<section class="teachers">
    <div class="teachers-inner">

        <div class="text-center reveal" style="margin-bottom:48px">
            <div class="section-label">Our Scholars</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:2.6rem;font-weight:900;color:var(--text);line-height:1.15">
                Learn From <span style="color:var(--green)">Certified</span> Islamic Scholars
            </h2>
            <p style="font-size:.9rem;color:var(--muted);max-width:480px;margin:12px auto 0;line-height:1.75">
                Graduates of renowned institutions — teaching with decades of experience directly to your home via live sessions.
            </p>
        </div>

        <div class="teachers-grid">

            {{-- Founder — big green --}}
            <div class="t-main reveal d1">
                <div class="t-main-top">
                    <div class="t-avatar-main">SM</div>
                </div>
                <div class="t-main-body">
                    <div class="t-role">Founder &amp; Head Teacher</div>
                    <h3>Ustadh Sohaib Mehmood</h3>
                    <p>A passionate educator bridging traditional Islamic scholarship with modern digital teaching. Built Peace Institute to bring world-class Quran education to every Muslim household globally.</p>
                    <div class="t-cred-w"><i class="fas fa-check-circle"></i> Graduate — Mahad As Salafi</div>
                    <div class="t-cred-w"><i class="fas fa-laptop"></i> EdTech Expert — Soft Desk Solution</div>
                    <div class="t-cred-w"><i class="fas fa-book"></i> Speciality: Quran &amp; Online Education</div>
                    <div style="margin-top:14px">
                        <span class="t-tag-w">Quran</span>
                        <span class="t-tag-w">Tajweed</span>
                        <span class="t-tag-w">Nazrah</span>
                        <span class="t-tag-w">EdTech</span>
                    </div>
                </div>
            </div>

            {{-- Shaikh Zia --}}
            <div class="t-card reveal d2">
                <div class="t-card-top" style="background:linear-gradient(135deg,var(--goldd),var(--gold),var(--goldl))">
                    <div class="t-avatar-sm">ZB</div>
                </div>
                <div class="t-card-body">
                    <div class="t-role">Shaikh-ul-Hadith</div>
                    <h3>Shaikh Zia ul Haq Bhatti</h3>
                    <p>Renowned scholar with deep expertise in Hadith sciences &amp; Tafseer. Currently Shaikh-ul-Hadith at Burooj Institute with decades of teaching experience.</p>
                    <div class="t-cred-sm"><i class="fas fa-check-circle"></i> Shaikh-ul-Hadith — Burooj Institute</div>
                    <div class="t-cred-sm"><i class="fas fa-university"></i> Jamia Albayan University</div>
                    <div class="t-cred-sm"><i class="fas fa-check-circle"></i> Graduate — Mahad As Salafi</div>
                    <div style="margin-top:12px">
                        <span class="t-tag">Hadith</span>
                        <span class="t-tag">Tafseer</span>
                        <span class="t-tag">Fiqh</span>
                        <span class="t-tag">Aqeedah</span>
                    </div>
                </div>
            </div>

            {{-- Ustadh Ali --}}
            <div class="t-card reveal d3">
                <div class="t-card-top" style="background:linear-gradient(135deg,var(--gd),var(--gl))">
                    <div class="t-avatar-sm">AA</div>
                </div>
                <div class="t-card-body">
                    <div class="t-role">Quran Teacher</div>
                    <h3>Ustadh Ali Akhtar</h3>
                    <p>Patient &amp; dedicated — known for his clear teaching style guiding students of all ages from Qaida to advanced Tajweed with great care.</p>
                    <div class="t-cred-sm"><i class="fas fa-check-circle"></i> Graduate — Mahad As Salafi</div>
                    <div class="t-cred-sm"><i class="fas fa-book-open"></i> Active Teacher — Peace Institute</div>
                    <div class="t-cred-sm"><i class="fas fa-star"></i> Speciality: Qaida, Nazrah &amp; Tajweed</div>
                    <div style="margin-top:12px">
                        <span class="t-tag">Qaida</span>
                        <span class="t-tag">Nazrah</span>
                        <span class="t-tag">Tajweed</span>
                    </div>
                </div>
            </div>

            {{-- Featured teachers from DB --}}
            @if($featuredTeachers->count())
                @foreach($featuredTeachers->take(3) as $teacher)
                <div class="t-card reveal" style="display:@if(!$loop->first) block @else block @endif">
                    <div class="t-card-top" style="background:linear-gradient(135deg,var(--gd),var(--green))">
                        <img src="{{ $teacher->user->avatar_url }}" alt="{{ $teacher->user->name }}"
                             style="width:76px;height:76px;border-radius:50%;border:2.5px solid rgba(255,255,255,.4);object-fit:cover;position:relative;z-index:1">
                    </div>
                    <div class="t-card-body">
                        <div class="t-role">{{ $teacher->specialization }}</div>
                        <h3>{{ $teacher->user->name }}</h3>
                        <p>{{ Str::limit($teacher->bio, 90) }}</p>
                        <div class="t-cred-sm">
                            <i class="fas fa-star" style="color:var(--gold)"></i>
                            Rating: {{ number_format($teacher->rating, 1) }}/5 ({{ $teacher->total_reviews }} reviews)
                        </div>
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:14px;padding-top:12px;border-top:1px solid rgba(0,0,0,.06)">
                            <span style="font-size:.8rem;font-weight:700;color:var(--text)">${{ number_format($teacher->hourly_rate,0) }}/hr</span>
                            <a href="{{ route('teachers.show', $teacher) }}" class="c-enroll" style="font-size:.74rem;padding:6px 14px">Book →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif

        </div>

        {{-- View all + join CTA --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:40px;flex-wrap:wrap;gap:16px" class="reveal">
            <a href="{{ route('teachers') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--green);font-weight:700;font-size:.9rem;text-decoration:none;border:1.5px solid rgba(26,107,60,.3);padding:12px 28px;border-radius:10px;transition:all .25s"
               onmouseover="this.style.background='var(--green)';this.style.color='#fff'" onmouseout="this.style.background='transparent';this.style.color='var(--green)'">
                View All Teachers <i class="fas fa-arrow-right"></i>
            </a>
            <div style="display:flex;align-items:center;gap:14px;background:rgba(26,107,60,.05);border:1px solid rgba(26,107,60,.12);border-radius:12px;padding:14px 22px">
                <i class="fas fa-chalkboard-teacher" style="color:var(--green)"></i>
                <span style="color:var(--muted);font-size:.84rem">Are you a qualified Islamic scholar?</span>
                <a href="{{ route('register', 'teacher') }}" style="color:var(--green);font-weight:700;font-size:.84rem;text-decoration:none;border-bottom:1px solid rgba(26,107,60,.3);padding-bottom:1px">Join Our Teaching Team →</a>
            </div>
        </div>

    </div>
</section>


{{-- ── STUDENT REVIEWS ───────────────────────────────────────── --}}
@if($recentReviews->count())
<section style="background:var(--cream);padding:72px 0">
    <div style="max-width:1280px;margin:0 auto;padding:0 32px">
        <div class="text-center reveal" style="margin-bottom:40px">
            <div class="section-label">Student Reviews</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:900;color:var(--text)">What Our Students Say</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:22px">
            @foreach($recentReviews as $review)
            <div style="background:#fff;border-radius:16px;padding:24px;box-shadow:0 2px 12px rgba(0,0,0,.06)" class="reveal">
                <div style="display:flex;gap:3px;margin-bottom:12px">
                    @for($i=1;$i<=5;$i++)
                        <i class="fas fa-star" style="font-size:.78rem;color:{{ $i<=$review->rating ? '#F59E0B' : '#E5E7EB' }}"></i>
                    @endfor
                </div>
                <p style="font-size:.83rem;color:var(--muted);line-height:1.75;margin-bottom:16px">"{{ $review->comment }}"</p>
                <div style="display:flex;align-items:center;gap:10px;padding-top:14px;border-top:1px solid rgba(0,0,0,.06)">
                    <img src="{{ $review->student->user->avatar_url }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover" alt="">
                    <div>
                        <div style="font-size:.82rem;font-weight:700;color:var(--text)">{{ $review->student->user->name }}</div>
                        <div style="font-size:.72rem;color:var(--muted)">Student of {{ $review->teacher->user->name }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ╔══════════════════════════════════════════════════╗
     ║  CTA SECTION                                     ║
     ╚══════════════════════════════════════════════════╝ --}}
<section class="cta-sec">
    <div class="cta-inner reveal">

        <div class="cta-text">
            <div class="cta-ayah">وَرَتِّلِ الْقُرْآنَ تَرْتِيلًا</div>
            <p class="cta-ref">"And recite the Quran with measured recitation" — Surah Muzzammil [73:4]</p>

            <h2 class="cta-h2">
                Start Your Quran<br>
                <span style="background:linear-gradient(135deg,var(--goldl),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Journey Today</span>
            </h2>
            <p class="cta-p">Join thousands of students from 25+ countries. Male and female certified teachers available. First class is absolutely free — no commitment, no card.</p>

            <div class="cta-pills">
                <div class="cta-pill"><i class="fas fa-check-circle"></i> Free Trial Class</div>
                <div class="cta-pill"><i class="fas fa-clock"></i> Flexible Timings</div>
                <div class="cta-pill"><i class="fas fa-globe"></i> 25+ Countries</div>
                <div class="cta-pill"><i class="fas fa-users"></i> Male &amp; Female</div>
            </div>

            <div class="cta-btns">
                <a href="{{ route('register', 'student') }}" class="cta-btn-g">
                    <i class="fas fa-plus"></i> Enroll Now — It's Free
                </a>
                <a href="{{ route('teachers') }}" class="cta-btn-w">
                    <i class="fas fa-eye"></i> Meet Our Scholars
                </a>
            </div>
        </div>

        {{-- Stats card --}}
        <div class="cta-stats">
            <h4>Our Impact</h4>
            <div class="s-row"><span class="slbl">Expert Teachers</span><span class="sval">{{ $stats['teachers'] ?? 50 }}+</span></div>
            <div class="s-row"><span class="slbl">Active Students</span><span class="sval">{{ $stats['students'] ?? 1200 }}+</span></div>
            <div class="s-row"><span class="slbl">Countries</span><span class="sval">{{ $stats['countries'] ?? 25 }}+</span></div>
            <div class="s-row"><span class="slbl">Sessions Done</span><span class="sval">{{ $stats['sessions'] ?? 8500 }}+</span></div>
            <div class="s-row" style="border:none"><span class="slbl">Core Courses</span><span class="sval">6</span></div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
// Trigger hero elements immediately
document.querySelectorAll('.reveal').forEach(el => el.classList.add('in'));
</script>
@endpush
