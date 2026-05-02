<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Peace Institute') – Online Quran Academy</title>
    <meta name="description" content="@yield('meta_description', 'Learn Quran online with certified teachers. Courses in Qaida, Nazra, Tajweed, Hifz, Tafseer & Arabic Grammar.')">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        pi: {
                            green:  '#1A6B3C',
                            gd:     '#0F3D22',
                            gl:     '#22874D',
                            gold:   '#C9A427',
                            goldl:  '#E2BB3A',
                            goldd:  '#A07E1A',
                            cream:  '#F7F4EE',
                            creamd: '#EDE9E0',
                        }
                    },
                    fontFamily: {
                        sans:   ['Inter', 'sans-serif'],
                        serif:  ['Playfair Display', 'serif'],
                        arabic: ['Amiri', 'serif'],
                    },
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --green:  #1A6B3C;
            --gd:     #0F3D22;
            --gl:     #22874D;
            --gold:   #C9A427;
            --goldl:  #E2BB3A;
            --goldd:  #A07E1A;
            --cream:  #F7F4EE;
            --creamd: #EDE9E0;
            --text:   #1C1C1C;
            --muted:  #6B7280;
            --white:  #FFFFFF;
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { background: var(--cream); color: var(--text); font-family: 'Inter', sans-serif; }

        /* ── NAV ── */
        .site-nav {
            background: rgba(255,255,255,.97);
            border-bottom: 1px solid rgba(26,107,60,.1);
            backdrop-filter: blur(12px);
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            height: 68px;
        }
        .nav-inner {
            max-width: 1280px; margin: 0 auto;
            padding: 0 32px; height: 100%;
            display: flex; align-items: center; justify-content: space-between;
        }
        .nav-logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
        .nav-logo-mark {
            width: 42px; height: 42px; border-radius: 10px;
            background: linear-gradient(135deg, var(--green), var(--gl));
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; font-size: 14px; color: #fff; letter-spacing: -.5px;
            overflow: hidden; flex-shrink: 0;
        }
        .nav-logo-mark img { width: 100%; height: 100%; object-fit: contain; }
        .nav-brand-name  { font-weight: 800; font-size: .95rem; color: var(--text); line-height: 1.1; }
        .nav-brand-sub   { font-size: .6rem; letter-spacing: .16em; color: var(--gold); text-transform: uppercase; }

        .nav-links { display: flex; align-items: center; gap: 2px; }
        .nav-links a {
            padding: 8px 16px; border-radius: 8px;
            color: var(--muted); font-size: .86rem; font-weight: 500;
            text-decoration: none; transition: all .2s;
        }
        .nav-links a:hover { color: var(--green); background: rgba(26,107,60,.06); }
        .nav-links a.active { color: var(--green); font-weight: 700; }

        .btn-nav-primary {
            background: var(--green); color: #fff;
            padding: 10px 22px; border-radius: 10px;
            font-size: .86rem; font-weight: 700; text-decoration: none;
            box-shadow: 0 4px 14px rgba(26,107,60,.25);
            transition: all .25s; white-space: nowrap;
        }
        .btn-nav-primary:hover { background: var(--gl); box-shadow: 0 8px 22px rgba(26,107,60,.35); transform: translateY(-1px); }

        .btn-nav-ghost {
            border: 1.5px solid rgba(26,107,60,.3); color: var(--green);
            padding: 9px 18px; border-radius: 10px;
            font-size: .86rem; font-weight: 600; text-decoration: none;
            transition: all .25s;
        }
        .btn-nav-ghost:hover { background: rgba(26,107,60,.06); border-color: var(--green); }

        /* ── FLASH ── */
        .flash-success { background: #ECFDF5; border: 1px solid #6EE7B7; color: #065F46; }
        .flash-error   { background: #FEF2F2; border: 1px solid #FCA5A5; color: #991B1B; }

        /* ── FOOTER ── */
        .site-footer { background: var(--gd); padding: 56px 0 0; color: rgba(255,255,255,.65); }
        .footer-inner { max-width: 1280px; margin: 0 auto; padding: 0 32px; }
        .footer-grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 48px; padding-bottom: 48px; }
        .footer-brand h3 { color: #fff; font-family: 'Playfair Display', serif; font-size: 1.15rem; margin-bottom: 4px; }
        .footer-brand p { font-size: .78rem; line-height: 1.75; color: rgba(255,255,255,.4); margin-top: 10px; max-width: 240px; }
        .footer-col h4 { font-size: .7rem; font-weight: 700; letter-spacing: .15em; text-transform: uppercase; color: rgba(255,255,255,.3); margin-bottom: 16px; }
        .footer-col a { display: block; font-size: .82rem; color: rgba(255,255,255,.5); text-decoration: none; margin-bottom: 10px; transition: color .2s; }
        .footer-col a:hover { color: var(--gold); }
        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08);
            padding: 20px 0;
            display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;
        }
        .footer-bottom p { font-size: .75rem; color: rgba(255,255,255,.3); }
        .social-row { display: flex; gap: 8px; }
        .social-row a {
            width: 34px; height: 34px; border-radius: 8px;
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            display: flex; align-items: center; justify-content: center;
            color: rgba(255,255,255,.45); font-size: .8rem;
            text-decoration: none; transition: all .25s;
        }
        .social-row a:hover { background: var(--gold); border-color: var(--gold); color: #fff; }

        /* ── GLOBAL UTILS ── */
        .section-label {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: .7rem; font-weight: 700; letter-spacing: .18em; text-transform: uppercase;
            color: var(--gold); margin-bottom: 14px;
        }
        .section-label::before, .section-label::after { content: ''; width: 28px; height: 1px; background: var(--gold); opacity: .5; }

        .divider-gg { height: 1px; background: linear-gradient(90deg, transparent, rgba(26,107,60,.15), rgba(201,164,39,.2), transparent); }

        /* Dashboard/Auth pages — keep white bg */
        .page-light { background: #fff; }

        /* Green button (global) */
        .btn-green {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--green); color: #fff;
            padding: 12px 28px; border-radius: 10px;
            font-weight: 700; font-size: .9rem; text-decoration: none;
            box-shadow: 0 4px 14px rgba(26,107,60,.25);
            transition: all .3s; border: none; cursor: pointer;
        }
        .btn-green:hover { background: var(--gl); transform: translateY(-1px); box-shadow: 0 8px 22px rgba(26,107,60,.35); }

        /* Gold button (global) */
        .btn-gold {
            display: inline-flex; align-items: center; gap: 8px;
            background: linear-gradient(135deg, var(--goldl), var(--gold), var(--goldd));
            color: #0F1F0A; padding: 12px 28px; border-radius: 10px;
            font-weight: 800; font-size: .9rem; text-decoration: none;
            box-shadow: 0 4px 16px rgba(201,164,39,.3);
            transition: all .3s; border: none; cursor: pointer;
        }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 10px 28px rgba(201,164,39,.45); }

        /* Input styles */
        .input-field {
            width: 100%; padding: 11px 14px; border-radius: 10px;
            border: 1.5px solid #E5E7EB; background: #FAFAFA;
            font-size: .9rem; color: var(--text); font-family: 'Inter', sans-serif;
            transition: border-color .2s;
        }
        .input-field:focus { outline: none; border-color: var(--green); box-shadow: 0 0 0 3px rgba(26,107,60,.08); }
        .input-field::placeholder { color: #9CA3AF; }

        /* Cards */
        .card-white {
            background: #fff; border: 1px solid rgba(0,0,0,.06);
            border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.05);
        }
        .card-white:hover { box-shadow: 0 12px 32px rgba(0,0,0,.1); transform: translateY(-2px); }

        /* Gold text */
        .text-gold { background: linear-gradient(135deg, var(--goldl), var(--gold)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .text-green-pi { color: var(--green); }

        /* Reveal animation */
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .65s ease, transform .65s ease; }
        .reveal.in { opacity: 1; transform: none; }
        .d1 { transition-delay: .05s; } .d2 { transition-delay: .12s; } .d3 { transition-delay: .19s; }
        .d4 { transition-delay: .26s; } .d5 { transition-delay: .33s; } .d6 { transition-delay: .4s; }

        /* Mobile nav */
        .mobile-menu-btn {
            display: none; align-items: center; justify-content: center;
            width: 40px; height: 40px; border-radius: 10px;
            background: rgba(26,107,60,.08); border: 1.5px solid rgba(26,107,60,.15);
            cursor: pointer; flex-direction: column; gap: 5px; flex-shrink: 0;
        }
        .mobile-menu-btn span {
            display: block; width: 20px; height: 2px;
            background: var(--green); border-radius: 2px; transition: all .3s;
        }
        .mobile-menu-btn.open span:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .mobile-menu-btn.open span:nth-child(2) { opacity: 0; }
        .mobile-menu-btn.open span:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        .mobile-menu {
            display: none; position: fixed; top: 68px; left: 0; right: 0;
            background: #fff; border-bottom: 1px solid rgba(26,107,60,.1);
            box-shadow: 0 8px 32px rgba(0,0,0,.1); z-index: 99; padding: 16px;
            flex-direction: column; gap: 4px;
        }
        .mobile-menu.open { display: flex; }
        .mobile-menu a {
            padding: 12px 16px; border-radius: 10px; color: var(--text);
            font-size: .9rem; font-weight: 500; text-decoration: none;
            transition: all .2s; display: flex; align-items: center; gap: 10px;
        }
        .mobile-menu a:hover { background: rgba(26,107,60,.06); color: var(--green); }
        .mobile-menu a.active { color: var(--green); font-weight: 700; background: rgba(26,107,60,.06); }
        .mobile-menu-divider { height: 1px; background: rgba(0,0,0,.06); margin: 8px 0; }
        .mobile-menu-btns { display: flex; gap: 8px; padding: 8px 0 4px; }
        .mobile-menu-btns a { flex: 1; justify-content: center; }

        @media (max-width: 900px) {
            .nav-links, .nav-auth-desktop { display: none !important; }
            .mobile-menu-btn { display: flex; }
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 28px; }
        }
        @media (max-width: 640px) {
            .footer-grid { grid-template-columns: 1fr 1fr; gap: 20px; }
            /* Stack grids on small screens */
            [style*="grid-template-columns: repeat(3"], [style*="grid-template-columns: repeat(4"] { grid-template-columns: 1fr 1fr !important; }
        }
        @media (max-width: 480px) {
            .footer-grid { grid-template-columns: 1fr; }
            .nav-inner { padding: 0 16px; }
            /* Full width buttons on mobile */
            .mobile-menu-btns a { padding: 12px; font-size: .88rem; }
        }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen">

    @php
        // Check actual public_html path (not Laravel's default public/)
        $pubPath = '/home/peaceorg/public_html';
        $logoUrl = null;
        if (file_exists("$pubPath/images/logo.png"))      $logoUrl = asset('images/logo.png');
        elseif (file_exists("$pubPath/images/logo.svg"))  $logoUrl = asset('images/logo.svg');

        $logoMarkUrl = null;
        if (file_exists("$pubPath/images/logo-mark.png"))     $logoMarkUrl = asset('images/logo-mark.png');
        elseif (file_exists("$pubPath/images/logo-mark.svg"))  $logoMarkUrl = asset('images/logo-mark.svg');

        $socialLinks = [
            ['icon' => 'fab fa-facebook-f',  'url' => 'https://www.facebook.com/peaceinstituteglobal',    'label' => 'Facebook'],
            ['icon' => 'fab fa-instagram',   'url' => 'https://www.instagram.com/peaceinstituteglobal/',  'label' => 'Instagram'],
            ['icon' => 'fab fa-youtube',     'url' => 'https://www.youtube.com/@peaceinstituteglobal',    'label' => 'YouTube'],
        ];
    @endphp

    <!-- ── NAVIGATION ───────────────────────────────────────── -->
    <nav class="site-nav">
        <div class="nav-inner">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="nav-logo" style="text-decoration:none">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Peace Institute" style="height:52px;width:auto;object-fit:contain;display:block">
                @elseif($logoMarkUrl)
                    <img src="{{ $logoMarkUrl }}" alt="Peace Institute" style="height:52px;width:auto;object-fit:contain;display:block">
                @else
                    <div class="nav-logo-mark">PI</div>
                    <div>
                        <div class="nav-brand-name">Peace Institute</div>
                        <div class="nav-brand-sub">Online Quran Academy</div>
                    </div>
                @endif
            </a>

            <!-- Desktop Links -->
            <div class="nav-links">
                <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses') ? 'active' : '' }}">Courses</a>
                <a href="{{ route('teachers') }}" class="{{ request()->routeIs('teachers') ? 'active' : '' }}">Teachers</a>
                <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a>
            </div>

            <!-- Hamburger -->
            <button class="mobile-menu-btn" id="mobileBtn" onclick="toggleMobileMenu()" aria-label="Menu">
                <span></span><span></span><span></span>
            </button>

            <!-- Auth -->
            <div class="nav-auth-desktop" style="display:flex;align-items:center;gap:8px">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="btn-nav-primary">Dashboard</a>
                    @elseif(auth()->user()->isTeacher())
                        <a href="{{ route('teacher.dashboard') }}" class="btn-nav-primary">Dashboard</a>
                    @else
                        <a href="{{ route('student.dashboard') }}" class="btn-nav-primary">Dashboard</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button style="font-size:.84rem;color:var(--muted);cursor:pointer;border:none;background:none;transition:color .2s" onmouseover="this.style.color='var(--text)'" onmouseout="this.style.color='var(--muted)'">Logout</button>
                    </form>
                @else
                    <!-- Social icons -->
                    <div style="display:flex;gap:4px;margin-right:4px">
                        @foreach($socialLinks as $sl)
                            <a href="{{ $sl['url'] }}" target="_blank" rel="noopener" aria-label="{{ $sl['label'] }}"
                               style="width:32px;height:32px;border-radius:8px;border:1px solid rgba(26,107,60,.2);color:var(--muted);display:flex;align-items:center;justify-content:center;font-size:.75rem;text-decoration:none;transition:all .2s"
                               onmouseover="this.style.color='var(--green)';this.style.borderColor='rgba(26,107,60,.5)'"
                               onmouseout="this.style.color='var(--muted)';this.style.borderColor='rgba(26,107,60,.2)'">
                                <i class="{{ $sl['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                    <a href="{{ route('login') }}" class="btn-nav-ghost">Login</a>
                    <a href="{{ route('register') }}" class="btn-nav-primary">Enroll Free</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- ── MOBILE MENU ────────────────────────────────────────── -->
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home" style="width:16px;color:var(--green)"></i> Home</a>
        <a href="{{ route('courses') }}" class="{{ request()->routeIs('courses') ? 'active' : '' }}"><i class="fas fa-book-open" style="width:16px;color:var(--green)"></i> Courses</a>
        <a href="{{ route('teachers') }}" class="{{ request()->routeIs('teachers') ? 'active' : '' }}"><i class="fas fa-chalkboard-teacher" style="width:16px;color:var(--green)"></i> Teachers</a>
        <a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}"><i class="fas fa-envelope" style="width:16px;color:var(--green)"></i> Contact</a>
        <a href="tel:+923022702808"><i class="fas fa-phone" style="width:16px;color:var(--green)"></i> +92 302 2702808</a>
        <div class="mobile-menu-divider"></div>
        @auth
            @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="btn-nav-primary" style="justify-content:center">Admin Dashboard</a>
            @elseif(auth()->user()->isTeacher())
                <a href="{{ route('teacher.dashboard') }}" class="btn-nav-primary" style="justify-content:center">Teacher Dashboard</a>
            @else
                <a href="{{ route('student.dashboard') }}" class="btn-nav-primary" style="justify-content:center">My Dashboard</a>
            @endif
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button style="width:100%;padding:12px;border-radius:10px;background:rgba(0,0,0,.04);border:none;font-size:.9rem;cursor:pointer;color:var(--muted)">Logout</button>
            </form>
        @else
            <div class="mobile-menu-btns">
                <a href="{{ route('login') }}" class="btn-nav-ghost">Login</a>
                <a href="{{ route('register') }}" class="btn-nav-primary">Enroll Free</a>
            </div>
        @endauth
    </div>

    <!-- ── FLASH MESSAGES ───────────────────────────────────── -->
    <div style="position:fixed;top:80px;right:16px;z-index:200;display:flex;flex-direction:column;gap:8px" id="flash-msgs">
        @if(session('success'))
            <div class="flash-success" style="padding:12px 18px;border-radius:10px;display:flex;align-items:center;gap:8px;font-size:.85rem;box-shadow:0 4px 16px rgba(0,0,0,.1)">
                <i class="fas fa-check-circle" style="color:#10B981"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="flash-error" style="padding:12px 18px;border-radius:10px;display:flex;align-items:center;gap:8px;font-size:.85rem;box-shadow:0 4px 16px rgba(0,0,0,.1)">
                <i class="fas fa-exclamation-circle" style="color:#EF4444"></i> {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- ── MAIN CONTENT ──────────────────────────────────────── -->
    <main style="padding-top:68px">
        @yield('content')
    </main>

    <!-- ── WHATSAPP FLOATING POPUP ────────────────────────────── -->
    @unless(request()->routeIs('admin.*') || request()->routeIs('teacher.*') || request()->routeIs('student.*'))
    <style>
        .wa-float { position:fixed; bottom:28px; right:28px; z-index:999; display:flex; flex-direction:column; align-items:flex-end; gap:12px; }
        .wa-popup {
            background:#fff; border-radius:16px; padding:16px 18px;
            box-shadow:0 8px 40px rgba(0,0,0,.18); width:260px;
            display:none; flex-direction:column; gap:10px;
            animation: waSlideIn .25s ease;
            border:1px solid rgba(37,211,102,.15);
        }
        .wa-popup.show { display:flex; }
        @keyframes waSlideIn { from{opacity:0;transform:translateY(12px)} to{opacity:1;transform:none} }
        .wa-popup-header { display:flex; align-items:center; gap:10px; padding-bottom:10px; border-bottom:1px solid #F3F4F6; }
        .wa-popup-avatar { width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#25D366,#128C7E);display:flex;align-items:center;justify-content:center;flex-shrink:0; }
        .wa-popup-name { font-weight:700;font-size:.88rem;color:#1C1C1C;line-height:1.2; }
        .wa-popup-status { font-size:.72rem;color:#25D366;display:flex;align-items:center;gap:4px; }
        .wa-popup-msg { background:#F0FDF4;border-radius:10px 10px 10px 2px;padding:10px 12px;font-size:.82rem;color:#374151;line-height:1.55; }
        .wa-popup-btn {
            display:flex;align-items:center;justify-content:center;gap:8px;
            background:#25D366;color:#fff;font-weight:700;font-size:.85rem;
            padding:11px;border-radius:10px;text-decoration:none;
            box-shadow:0 4px 14px rgba(37,211,102,.35);transition:all .2s;
        }
        .wa-popup-btn:hover { background:#22c55e;transform:translateY(-1px);box-shadow:0 6px 20px rgba(37,211,102,.45); }
        .wa-btn {
            width:58px;height:58px;border-radius:999px;
            background:#25D366;border:none;cursor:pointer;
            display:flex;align-items:center;justify-content:center;
            box-shadow:0 6px 24px rgba(37,211,102,.5);transition:all .3s;
            position:relative;
        }
        .wa-btn:hover { transform:scale(1.08);box-shadow:0 10px 32px rgba(37,211,102,.6); }
        .wa-btn i { font-size:1.6rem;color:#fff; }
        .wa-pulse {
            position:absolute;top:0;right:0;width:16px;height:16px;
            background:#ef4444;border-radius:999px;border:2px solid #fff;
            display:flex;align-items:center;justify-content:center;
            font-size:.55rem;font-weight:800;color:#fff;
        }
        @media(max-width:480px){ .wa-float{bottom:18px;right:16px;} .wa-popup{width:230px;} }
    </style>

    <div class="wa-float" id="waFloat">
        <!-- Popup -->
        <div class="wa-popup" id="waPopup">
            <div class="wa-popup-header">
                <div class="wa-popup-avatar"><i class="fab fa-whatsapp" style="color:#fff;font-size:1.3rem"></i></div>
                <div>
                    <div class="wa-popup-name">Peace Institute</div>
                    <div class="wa-popup-status"><span style="width:7px;height:7px;background:#25D366;border-radius:999px;display:inline-block"></span> Typically replies instantly</div>
                </div>
                <button onclick="closeWa()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#9CA3AF;font-size:1rem;padding:0 0 0 8px">✕</button>
            </div>
            <div class="wa-popup-msg">
                👋 Assalamu Alaikum! How can we help you today?<br><br>
                Ask about our <strong>free trial class</strong> or any course!
            </div>
            <a href="https://wa.me/923022702808?text=Assalamu+Alaikum!+I+want+to+know+more+about+Peace+Institute+courses." target="_blank" class="wa-popup-btn">
                <i class="fab fa-whatsapp"></i> Start Chat
            </a>
        </div>

        <!-- Float Button -->
        <button class="wa-btn" id="waBtn" onclick="toggleWa()" aria-label="WhatsApp">
            <i class="fab fa-whatsapp" id="waIcon"></i>
            <span class="wa-pulse">1</span>
        </button>
    </div>

    <script>
        var waOpen = false;
        // Auto show popup after 4 seconds
        setTimeout(function(){ if(!waOpen){ openWa(); } }, 4000);

        function toggleWa(){ waOpen ? closeWa() : openWa(); }
        function openWa(){
            waOpen = true;
            document.getElementById('waPopup').classList.add('show');
            document.getElementById('waIcon').className = 'fas fa-times';
            document.querySelector('.wa-pulse').style.display = 'none';
        }
        function closeWa(){
            waOpen = false;
            document.getElementById('waPopup').classList.remove('show');
            document.getElementById('waIcon').className = 'fab fa-whatsapp';
        }
    </script>
    @endunless

    <!-- ── FOOTER ────────────────────────────────────────────── -->
    @unless(request()->routeIs('admin.*') || request()->routeIs('teacher.*') || request()->routeIs('student.*'))
    <footer class="site-footer">
        <div class="footer-inner">
            <div class="footer-grid">

                <!-- Brand -->
                <div class="footer-brand">
                    <div style="margin-bottom:12px">
                        @if($logoUrl)
                            <img src="{{ $logoUrl }}" alt="Peace Institute" style="height:60px;width:auto;object-fit:contain;filter:brightness(0) invert(1)">
                        @elseif($logoMarkUrl)
                            <img src="{{ $logoMarkUrl }}" alt="Peace Institute" style="height:60px;width:auto;object-fit:contain;filter:brightness(0) invert(1)">
                        @else
                            <h3>Peace Institute</h3>
                        @endif
                    </div>
                    <p>Online Quran Academy — bringing certified Islamic education to every Muslim home worldwide.</p>
                    <div style="font-family:'Amiri',serif;font-size:1.1rem;color:rgba(201,164,39,.6);direction:rtl;margin-top:14px">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
                    <div class="social-row" style="margin-top:16px">
                        @foreach($socialLinks as $sl)
                            <a href="{{ $sl['url'] }}" target="_blank" rel="noopener" aria-label="{{ $sl['label'] }}">
                                <i class="{{ $sl['icon'] }}"></i>
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Courses -->
                <div class="footer-col">
                    <h4>Courses</h4>
                    <a href="{{ route('courses') }}">Noorani Qaida</a>
                    <a href="{{ route('courses') }}">Nazrah Quran</a>
                    <a href="{{ route('courses') }}">Tajweed Course</a>
                    <a href="{{ route('courses') }}">Hifz-ul-Quran</a>
                    <a href="{{ route('courses') }}">Tafseer-ul-Quran</a>
                    <a href="{{ route('courses') }}">Dars-e-Nizami</a>
                </div>

                <!-- Quick Links -->
                <div class="footer-col">
                    <h4>Quick Links</h4>
                    <a href="{{ route('home') }}">Home</a>
                    <a href="{{ route('teachers') }}">Find a Teacher</a>
                    <a href="{{ route('contact') }}">Contact Us</a>
                    <a href="{{ route('register', 'student') }}">Become a Student</a>
                    <a href="{{ route('register', 'teacher') }}">Teach With Us</a>
                    <a href="{{ route('login') }}">Student Login</a>
                </div>

                <!-- Contact + CTA -->
                <div class="footer-col">
                    <h4>Contact</h4>
                    <a href="tel:+923022702808"><i class="fas fa-phone" style="width:14px"></i> +92 302 2702808</a>
                    <a href="https://wa.me/923022702808" target="_blank"><i class="fab fa-whatsapp" style="width:14px"></i> WhatsApp Chat</a>
                    <a href="mailto:info@peaceinstitute.pk"><i class="fas fa-envelope" style="width:14px"></i> info@peaceinstitute.pk</a>

                    <div style="margin-top:20px;padding:16px;background:rgba(201,164,39,.08);border:1px solid rgba(201,164,39,.15);border-radius:12px">
                        <div style="font-size:.65rem;color:var(--gold);font-weight:700;text-transform:uppercase;letter-spacing:.1em;margin-bottom:6px">Free Trial Class</div>
                        <div style="font-size:.75rem;color:rgba(255,255,255,.4);margin-bottom:12px">First class 100% free</div>
                        <a href="{{ route('register', 'student') }}"
                           style="display:block;background:var(--gold);color:#0F1F0A;font-weight:700;font-size:.78rem;text-align:center;padding:9px;border-radius:8px;text-decoration:none;transition:filter .2s"
                           onmouseover="this.style.filter='brightness(1.1)'" onmouseout="this.style.filter='none'">Book Now →</a>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} Peace Institute. All rights reserved.</p>
                <p style="font-family:'Amiri',serif;font-size:.85rem;color:rgba(201,164,39,.35);direction:rtl">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ</p>
            </div>
        </div>
    </footer>
    @endunless

    <script>
        // Auto-dismiss flash messages
        setTimeout(() => { const el = document.getElementById('flash-msgs'); if(el) el.innerHTML = ''; }, 5000);

        // Scroll-reveal
        const revealObs = new IntersectionObserver(entries => {
            entries.forEach(e => { if(e.isIntersecting){ e.target.classList.add('in'); revealObs.unobserve(e.target); } });
        }, { threshold: 0.06 });
        document.querySelectorAll('.reveal').forEach(el => { revealObs.observe(el); el.classList.add('in'); });

        // Mobile menu toggle
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            const btn  = document.getElementById('mobileBtn');
            menu.classList.toggle('open');
            btn.classList.toggle('open');
        }
        // Close on outside click
        document.addEventListener('click', function(e) {
            const menu = document.getElementById('mobileMenu');
            const btn  = document.getElementById('mobileBtn');
            if (menu && btn && !menu.contains(e.target) && !btn.contains(e.target)) {
                menu.classList.remove('open');
                btn.classList.remove('open');
            }
        });
        // Close on nav link click
        document.querySelectorAll('.mobile-menu a').forEach(a => {
            a.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.remove('open');
                document.getElementById('mobileBtn').classList.remove('open');
            });
        });
    </script>
    @stack('scripts')
</body>
</html>
