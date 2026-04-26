<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') – Peace Institute</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        gold: { DEFAULT: '#D4AF37', light: '#F0D060', dark: '#A08020' },
                        silver: '#C0C0C0',
                        surface: { DEFAULT: '#0d0d0d', 100: '#111', 200: '#161616', 300: '#1c1c1c' }
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        body { background: #050505; color: #fff; font-family: 'Inter', sans-serif; }
        .sidebar { background: #0a0a0a; border-right: 1px solid rgba(212,175,55,0.12); width: 260px; }
        .sidebar-link { color: #9ca3af; transition: all 0.2s; padding: 10px 16px; border-radius: 8px; display: flex; align-items: center; gap: 10px; font-size: 0.875rem; }
        .sidebar-link:hover, .sidebar-link.active { color: #D4AF37; background: rgba(212,175,55,0.08); }
        .sidebar-link .icon { width: 18px; text-align: center; }
        .gold-gradient { background: linear-gradient(135deg, #D4AF37, #F0D060, #A08020); }
        .gold-text { background: linear-gradient(135deg, #D4AF37, #F0D060); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .card { background: #0d0d0d; border: 1px solid rgba(212,175,55,0.1); border-radius: 12px; }
        .card:hover { border-color: rgba(212,175,55,0.25); }
        .stat-card { background: linear-gradient(135deg, #0d0d0d, #111); border: 1px solid rgba(212,175,55,0.15); border-radius: 12px; }
        .btn-gold { background: linear-gradient(135deg, #D4AF37, #A08020); color: #000; font-weight: 600; padding: 8px 18px; border-radius: 8px; font-size: 0.875rem; transition: all 0.3s; border: none; cursor: pointer; }
        .btn-gold:hover { background: linear-gradient(135deg, #F0D060, #D4AF37); transform: translateY(-1px); box-shadow: 0 4px 15px rgba(212,175,55,0.35); }
        .btn-outline { border: 1px solid rgba(212,175,55,0.3); color: #D4AF37; padding: 7px 16px; border-radius: 8px; font-size: 0.875rem; transition: all 0.2s; background: transparent; cursor: pointer; }
        .btn-outline:hover { background: rgba(212,175,55,0.08); border-color: #D4AF37; }
        .btn-danger { background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #f87171; padding: 7px 16px; border-radius: 8px; font-size: 0.875rem; transition: all 0.2s; cursor: pointer; }
        .btn-danger:hover { background: rgba(239,68,68,0.25); }
        .badge-pending  { background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.3); }
        .badge-approved { background: rgba(52,211,153,0.15); color: #34d399; border: 1px solid rgba(52,211,153,0.3); }
        .badge-completed{ background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.3); }
        .badge-cancelled{ background: rgba(239,68,68,0.15); color: #f87171; border: 1px solid rgba(239,68,68,0.3); }
        .badge { font-size: 0.7rem; padding: 2px 10px; border-radius: 999px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; display: inline-block; }
        .input-dark { background: #111; border: 1px solid rgba(212,175,55,0.2); color: #fff; border-radius: 8px; padding: 8px 12px; font-size: 0.875rem; width: 100%; }
        .input-dark:focus { outline: none; border-color: #D4AF37; box-shadow: 0 0 0 2px rgba(212,175,55,0.1); }
        .table-row { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s; }
        .table-row:hover { background: rgba(255,255,255,0.02); }
        .divider { height: 1px; background: linear-gradient(90deg, transparent, rgba(212,175,55,0.2), transparent); margin: 1rem 0; }
    </style>
    @stack('styles')
</head>
<body>
<div class="flex min-h-screen">

    <!-- Sidebar -->
    <aside class="sidebar fixed inset-y-0 left-0 z-40 flex flex-col overflow-y-auto">
        <!-- Logo -->
        <div class="p-5 border-b border-gold-DEFAULT/10">
            @php
                $logoUrl = null;
                if (file_exists(public_path('images/logo.png'))) {
                    $logoUrl = asset('images/logo.png');
                } elseif (file_exists(public_path('images/logo.svg'))) {
                    $logoUrl = asset('images/logo.svg');
                }

                $logoMarkUrl = null;
                if (file_exists(public_path('images/logo-mark.png'))) {
                    $logoMarkUrl = asset('images/logo-mark.png');
                } elseif (file_exists(public_path('images/logo-mark.svg'))) {
                    $logoMarkUrl = asset('images/logo-mark.svg');
                }

                $socialLinks = [
                    ['icon' => 'fab fa-facebook-f', 'url' => 'https://www.facebook.com/peaceinstituteglobal', 'label' => 'Facebook'],
                    ['icon' => 'fab fa-instagram', 'url' => 'https://www.instagram.com/peaceinstituteglobal/', 'label' => 'Instagram'],
                    ['icon' => 'fab fa-youtube', 'url' => 'https://www.youtube.com/@peaceinstituteglobal', 'label' => 'YouTube'],
                ];
            @endphp

            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-gold-DEFAULT/20 flex items-center justify-center flex-shrink-0 overflow-hidden p-1">
                    @if($logoMarkUrl)
                        <img src="{{ $logoMarkUrl }}" alt="Peace Institute logo mark" class="w-full h-full object-contain" />
                    @elseif($logoUrl)
                        <img src="{{ $logoUrl }}" alt="Peace Institute logo" class="w-full h-full object-contain" />
                    @else
                        <span class="text-black font-bold text-xs">PI</span>
                    @endif
                </div>
                <div>
                    <div class="font-bold text-white text-sm">Peace Institute</div>
                    <div class="text-xs text-gold-DEFAULT/70">Quran Academy</div>
                </div>
            </a>
        </div>

        <!-- User Info -->
        <div class="p-4 border-b border-white/5">
            <div class="flex items-center gap-3">
                <img src="{{ auth()->user()->avatar_url }}" class="w-9 h-9 rounded-full object-cover" alt="">
                <div class="min-w-0">
                    <div class="text-sm font-medium text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-xs text-gold-DEFAULT capitalize">{{ auth()->user()->role }}</div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1">
            @yield('sidebar-nav')
        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-white/5">
            <a href="tel:+923022702808" class="sidebar-link mb-2 text-xs">
                <span class="icon"><i class="fas fa-phone"></i></span>
                +92 302 2702808
            </a>

            <div class="flex items-center gap-2 mb-3 px-3">
                @foreach($socialLinks as $social)
                    <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}" class="w-8 h-8 rounded-md border border-gold-DEFAULT/25 text-gray-300 hover:text-gold-DEFAULT hover:border-gold-DEFAULT/45 flex items-center justify-center transition-colors">
                        <i class="{{ $social['icon'] }} text-xs"></i>
                    </a>
                @endforeach
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="sidebar-link w-full text-left">
                    <span class="icon"><i class="fas fa-sign-out-alt"></i></span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-[260px] flex flex-col min-h-screen">
        <!-- Top Bar -->
        <header class="sticky top-0 z-30 px-6 py-4 border-b border-gold-DEFAULT/10" style="background: linear-gradient(120deg, rgba(6,6,6,0.94), rgba(12,12,12,0.92)); backdrop-filter: blur(10px);">
            <div class="flex items-center justify-between gap-4">
                <div class="min-w-0">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-[10px] uppercase tracking-[0.25em] bg-gold-DEFAULT/10 text-gold-DEFAULT mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-gold-DEFAULT"></span>
                        Peace Institute Dashboard
                    </div>
                    <h1 class="text-lg font-semibold text-white truncate">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-500">@yield('page-subtitle', '')</p>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('contact') }}" class="text-xs text-gray-300 hover:text-gold-DEFAULT transition-colors">Contact Us</a>
                    <div class="text-xs text-gray-300 px-3 py-2 rounded-lg border border-gold-DEFAULT/20 bg-white/[0.02]">
                        <i class="fas fa-calendar-alt text-gold-DEFAULT mr-2"></i>{{ now()->format('D, M d Y') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 p-6">

            @if(session('success'))
                <div class="mb-6 bg-green-900/40 border border-green-500/40 text-green-300 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-red-900/40 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 bg-red-900/40 border border-red-500/40 text-red-300 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
