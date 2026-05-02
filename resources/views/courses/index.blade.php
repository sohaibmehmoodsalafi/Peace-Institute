@extends('layouts.app')
@section('title', 'Courses – Peace Institute')
@section('meta_description', 'Explore Quran courses: Noorani Qaida, Nazra, Tajweed, Hifz, Tafseer, Dars-e-Nizami. Learn online with certified scholars.')

@push('styles')
<style>
.courses-hero {
    background: linear-gradient(135deg, #0F3D22 0%, #1A6B3C 60%, #22874D 100%);
    padding: 72px 0 56px;
    position: relative; overflow: hidden;
}
.courses-hero::before {
    content:''; position:absolute; inset:0;
    background-image:
        repeating-linear-gradient(30deg,  rgba(255,255,255,.02) 0, rgba(255,255,255,.02) 1px, transparent 1px, transparent 44px),
        repeating-linear-gradient(-30deg, rgba(255,255,255,.02) 0, rgba(255,255,255,.02) 1px, transparent 1px, transparent 44px);
    pointer-events:none;
}
.course-card {
    background: #fff; border-radius: 18px;
    border: 1px solid rgba(0,0,0,.07);
    overflow: hidden; transition: all .3s;
    display: flex; flex-direction: column;
}
.course-card:hover { transform: translateY(-6px); box-shadow: 0 20px 48px rgba(0,0,0,.12); }
.course-thumb {
    height: 180px; display: flex; align-items: center; justify-content: center;
    font-size: 3rem; position: relative; overflow: hidden;
}
.course-body { padding: 22px 24px; flex: 1; display: flex; flex-direction: column; }
.course-level {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: .65rem; font-weight: 700; letter-spacing: .12em;
    text-transform: uppercase; padding: 3px 10px; border-radius: 999px;
    margin-bottom: 10px;
}
.level-beginner    { background: #ECFDF5; color: #065F46; }
.level-intermediate{ background: #FFF7ED; color: #9A3412; }
.level-advanced    { background: #EFF6FF; color: #1E40AF; }
.level-all         { background: #F5F3FF; color: #4C1D95; }
.course-name  { font-size: 1.05rem; font-weight: 800; color: #1C1C1C; margin-bottom: 8px; line-height: 1.3; }
.course-desc  { font-size: .82rem; color: #6B7280; line-height: 1.65; flex: 1; margin-bottom: 16px; }
.course-meta  { display: flex; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
.course-meta-item { display: flex; align-items: center; gap: 5px; font-size: .76rem; color: #6B7280; }
.course-meta-item i { color: var(--green); font-size: .72rem; }
.course-price { font-size: 1.35rem; font-weight: 900; color: var(--green); line-height: 1; }
.course-price span { font-size: .72rem; font-weight: 500; color: #9CA3AF; }
.enroll-btn {
    display: block; width: 100%; padding: 12px;
    background: linear-gradient(135deg, var(--green), var(--gl));
    color: #fff; font-weight: 700; font-size: .88rem;
    border-radius: 10px; text-align: center; text-decoration: none;
    border: none; cursor: pointer; transition: all .25s;
    box-shadow: 0 4px 14px rgba(26,107,60,.2);
}
.enroll-btn:hover { background: linear-gradient(135deg, var(--gl), var(--green)); box-shadow: 0 8px 22px rgba(26,107,60,.35); transform: translateY(-1px); }
.enrolled-btn {
    display: block; width: 100%; padding: 12px;
    background: #ECFDF5; color: #065F46; font-weight: 700; font-size: .88rem;
    border-radius: 10px; text-align: center; text-decoration: none;
    border: 2px solid #6EE7B7; cursor: default;
}
.unenroll-link { display:block; text-align:center; font-size:.72rem; color:#9CA3AF; margin-top:6px; cursor:pointer; text-decoration:underline; }

/* Default course icons & colors */
.thumb-qaida      { background: linear-gradient(135deg, #ECFDF5, #D1FAE5); }
.thumb-nazra      { background: linear-gradient(135deg, #EFF6FF, #DBEAFE); }
.thumb-tajweed    { background: linear-gradient(135deg, #FFF7ED, #FED7AA); }
.thumb-hifz       { background: linear-gradient(135deg, #F5F3FF, #DDD6FE); }
.thumb-tafseer    { background: linear-gradient(135deg, #FEF2F2, #FECACA); }
.thumb-nizami     { background: linear-gradient(135deg, #FFFBEB, #FDE68A); }
.thumb-default    { background: linear-gradient(135deg, #F3F4F6, #E5E7EB); }

.feat-strip { background: #fff; border-top: 1px solid rgba(26,107,60,.08); border-bottom: 1px solid rgba(26,107,60,.08); padding: 28px 0; }
.feat-item  { display: flex; align-items: center; gap: 12px; }
.feat-icon  { width: 44px; height: 44px; border-radius: 12px; background: rgba(26,107,60,.08); display: flex; align-items: center; justify-content: center; color: var(--green); font-size: 1rem; flex-shrink: 0; }

.faq-item { border: 1px solid rgba(0,0,0,.07); border-radius: 12px; overflow: hidden; margin-bottom: 10px; }
.faq-q    { padding: 18px 20px; font-weight: 600; font-size: .9rem; cursor: pointer; display: flex; justify-content: space-between; align-items: center; background: #fff; transition: background .2s; }
.faq-q:hover { background: #FAFAFA; }
.faq-a    { padding: 0 20px; max-height: 0; overflow: hidden; transition: all .3s; background: #FAFAFA; font-size: .85rem; color: #6B7280; line-height: 1.7; }
.faq-a.open { padding: 16px 20px; max-height: 200px; }
.faq-icon { transition: transform .3s; color: var(--green); }
.faq-icon.open { transform: rotate(45deg); }

@media(max-width:768px){
    .courses-hero { padding: 48px 0 40px; }
    .courses-grid { grid-template-columns: 1fr !important; }
}
</style>
@endpush

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
<div style="background:#ECFDF5;border:1px solid #6EE7B7;color:#065F46;padding:14px 20px;margin:0 0 0 0;font-size:.9rem;font-weight:600;text-align:center">
    <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
</div>
@endif
@if(session('info'))
<div style="background:#EFF6FF;border:1px solid #BFDBFE;color:#1E40AF;padding:14px 20px;font-size:.9rem;font-weight:600;text-align:center">
    <i class="fas fa-info-circle mr-2"></i>{{ session('info') }}
</div>
@endif

{{-- Hero --}}
<section class="courses-hero">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;position:relative;z-index:1;text-align:center">
        <div class="section-label" style="justify-content:center;color:rgba(201,164,39,.9)">
            Our Curriculum
        </div>
        <h1 style="font-family:'Playfair Display',serif;font-size:clamp(2rem,5vw,3rem);font-weight:900;color:#fff;margin-bottom:16px;line-height:1.15">
            Quran & Islamic <span style="color:#C9A427">Courses</span>
        </h1>
        <p style="font-size:1rem;color:rgba(255,255,255,.72);max-width:540px;margin:0 auto 32px;line-height:1.7">
            From beginner Qaida to advanced Dars-e-Nizami — certified scholars, flexible timing, first class free.
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
            @auth
                @if(auth()->user()->role === 'student')
                    <a href="{{ route('student.bookings.create') }}?teacher_id=1" class="btn-gold" style="font-size:.88rem;padding:12px 28px">
                        <i class="fas fa-play-circle"></i> Book a Session
                    </a>
                @endif
            @else
                <a href="{{ route('register', 'student') }}" class="btn-gold" style="font-size:.88rem;padding:12px 28px">
                    <i class="fas fa-play-circle"></i> Start Free Trial
                </a>
            @endauth
            <a href="{{ route('teachers') }}" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);color:#fff;border:1.5px solid rgba(255,255,255,.25);padding:12px 24px;border-radius:10px;font-weight:600;font-size:.88rem;text-decoration:none;transition:all .2s">
                <i class="fas fa-chalkboard-teacher"></i> Browse Teachers
            </a>
        </div>
    </div>
</section>

{{-- Features Strip --}}
<div class="feat-strip">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px">
        @foreach([
            ['fas fa-medal',       'Certified Scholars',   'Ijazah-certified teachers'],
            ['fas fa-clock',       'Flexible Timing',      'Classes at your convenience'],
            ['fas fa-video',       'Live 1-on-1 Classes',  'Zoom/Meet sessions daily'],
            ['fas fa-gift',        'First Class Free',     'No payment required to start'],
        ] as $f)
        <div class="feat-item">
            <div class="feat-icon"><i class="{{ $f[0] }}"></i></div>
            <div>
                <div style="font-weight:700;font-size:.88rem;color:#1C1C1C">{{ $f[1] }}</div>
                <div style="font-size:.75rem;color:#6B7280">{{ $f[2] }}</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Courses Grid --}}
<section style="background:var(--cream);padding:64px 0">
    <div style="max-width:1280px;margin:0 auto;padding:0 24px">

        <div style="text-align:center;margin-bottom:48px">
            <div class="section-label" style="justify-content:center">All Courses</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:900;color:#1C1C1C">
                What Would You Like to Learn?
            </h2>
            <p style="color:#6B7280;font-size:.9rem;margin-top:8px">Monthly subscription — cancel anytime</p>
        </div>

        @php
        $thumbs = ['thumb-qaida','thumb-nazra','thumb-tajweed','thumb-hifz','thumb-tafseer','thumb-nizami'];
        $icons  = ['📖','🕌','🎵','💚','📚','🌙'];

        // Use DB courses if available, else show defaults
        $showCourses = $courses->count() > 0 ? $courses : collect([
            (object)['id'=>null,'name'=>'Noorani Qaida',    'slug'=>null,'level'=>'beginner',    'description'=>'Perfect for beginners. Learn Arabic letters, pronunciation rules, and basic reading with proper Makhaarij.','sessions_per_week'=>2,'duration_minutes'=>45,'monthly_price'=>30,'enrolled_count'=>0,'thumbnail'=>null],
            (object)['id'=>null,'name'=>'Nazrah Quran',     'slug'=>null,'level'=>'beginner',    'description'=>'Read the Quran fluently with correct pronunciation. Build confidence in recitation with personal guidance.','sessions_per_week'=>3,'duration_minutes'=>60,'monthly_price'=>25,'enrolled_count'=>0,'thumbnail'=>null],
            (object)['id'=>null,'name'=>'Tajweed Course',   'slug'=>null,'level'=>'intermediate','description'=>'Master Tajweed rules with a certified Qari. Learn Noon Saakin, Maddaat, Waqf rules and advanced recitation.','sessions_per_week'=>3,'duration_minutes'=>60,'monthly_price'=>30,'enrolled_count'=>0,'thumbnail'=>null],
            (object)['id'=>null,'name'=>'Hifz-ul-Quran',   'slug'=>null,'level'=>'advanced',    'description'=>'Memorize the entire Quran under the supervision of a Hafiz. Daily revision, custom pace, full support.','sessions_per_week'=>5,'duration_minutes'=>60,'monthly_price'=>30,'enrolled_count'=>0,'thumbnail'=>null],
            (object)['id'=>null,'name'=>'Tafseer-ul-Quran','slug'=>null,'level'=>'advanced',    'description'=>'Understand the meaning and context of Quranic verses. Deep dive into classical Tafseer with Arabic explanations.','sessions_per_week'=>3,'duration_minutes'=>60,'monthly_price'=>30,'enrolled_count'=>0,'thumbnail'=>null],
            (object)['id'=>null,'name'=>'Dars-e-Nizami',   'slug'=>null,'level'=>'advanced',    'description'=>'Complete Islamic curriculum: Fiqh, Hadith, Aqeedah, Arabic Grammar, and more. Taught by qualified Ulama.','sessions_per_week'=>5,'duration_minutes'=>90,'monthly_price'=>30,'enrolled_count'=>0,'thumbnail'=>null],
        ]);
        $idx = 0;
        @endphp

        <div class="courses-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px">
            @foreach($showCourses as $course)
            @php
            $tClass     = $thumbs[$idx % count($thumbs)] ?? 'thumb-default';
            $icon       = $icons[$idx % count($icons)];
            $levelClass = 'level-'.($course->level ?? 'all');
            $isEnrolled = $course->id && in_array($course->id, $enrolledIds ?? []);
            $idx++;
            @endphp
            <div class="course-card reveal">
                <div class="course-thumb {{ $tClass }}">
                    @if($course->thumbnail)
                        <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="{{ $course->name }}" style="width:100%;height:100%;object-fit:cover">
                    @else
                        <span>{{ $icon }}</span>
                    @endif
                </div>
                <div class="course-body">
                    <span class="course-level {{ $levelClass }}">{{ ucfirst($course->level ?? 'All Levels') }}</span>
                    <h3 class="course-name">{{ $course->name }}</h3>
                    <p class="course-desc">{{ Str::limit($course->description, 110) }}</p>
                    <div class="course-meta">
                        <div class="course-meta-item"><i class="fas fa-calendar-week"></i> {{ $course->sessions_per_week }}×/week</div>
                        <div class="course-meta-item"><i class="fas fa-clock"></i> {{ $course->duration_minutes }} min</div>
                        <div class="course-meta-item"><i class="fas fa-video"></i> Live Online</div>
                        @if($course->enrolled_count > 0)
                        <div class="course-meta-item"><i class="fas fa-users"></i> {{ $course->enrolled_count }} enrolled</div>
                        @endif
                    </div>

                    {{-- Price --}}
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                        <div class="course-price">
                            ${{ number_format($course->monthly_price, 0) }}
                            <span style="font-size:.78rem;font-weight:600;color:#6B7280">/ month</span>
                        </div>
                        <div style="font-size:.72rem;color:#059669;background:#ECFDF5;padding:3px 8px;border-radius:999px;font-weight:600">
                            <i class="fas fa-gift"></i> First class free
                        </div>
                    </div>

                    {{-- Enroll Button --}}
                    @if($isEnrolled)
                        <div class="enrolled-btn">
                            <i class="fas fa-check-circle mr-1"></i> Enrolled
                        </div>
                        @auth
                        <form action="{{ route('student.courses.unenroll', $course) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" class="unenroll-link">Unenroll</button>
                        </form>
                        @endauth
                    @elseif($course->id)
                        @auth
                            @if(auth()->user()->role === 'student')
                                <form action="{{ route('student.courses.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="enroll-btn">
                                        <i class="fas fa-plus-circle mr-1"></i> Enroll Now
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('teachers') }}" class="enroll-btn">Find a Teacher</a>
                            @endif
                        @else
                            <a href="{{ route('register', 'student') }}" class="enroll-btn">Enroll Now — First Class Free</a>
                        @endauth
                    @else
                        <a href="{{ route('register', 'student') }}" class="enroll-btn">Enroll Now — First Class Free</a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

    </div>
</section>

{{-- How It Works --}}
<section style="background:#fff;padding:64px 0">
    <div style="max-width:960px;margin:0 auto;padding:0 24px;text-align:center">
        <div class="section-label" style="justify-content:center">Simple Process</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.6rem,3.5vw,2.2rem);font-weight:900;color:#1C1C1C;margin-bottom:48px">
            How It Works
        </h2>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:32px">
            @foreach([
                ['1', 'fas fa-user-plus',     '#C9A427', 'Register Free',   'Create your account in 2 minutes — no credit card needed.'],
                ['2', 'fas fa-book-open',      '#1A6B3C', 'Pick a Course',   'Choose from Qaida, Nazra, Tajweed, Hifz and more.'],
                ['3', 'fas fa-calendar-check', '#1A6B3C', 'Book a Session',  'Pick your teacher and schedule at your preferred time.'],
                ['4', 'fas fa-video',          '#C9A427', 'Join Live Class', 'Connect via Zoom/Meet — learn 1-on-1 with a certified teacher.'],
            ] as [$num, $ico, $col, $title, $desc])
            <div class="reveal" style="text-align:center">
                <div style="width:60px;height:60px;border-radius:16px;background:{{ $col == '#C9A427' ? 'rgba(201,164,39,.1)' : 'rgba(26,107,60,.08)' }};display:flex;align-items:center;justify-content:center;margin:0 auto 16px;position:relative">
                    <i class="{{ $ico }}" style="font-size:1.3rem;color:{{ $col }}"></i>
                    <span style="position:absolute;top:-6px;right:-6px;width:20px;height:20px;background:{{ $col }};color:{{ $col == '#C9A427' ? '#0F1F0A' : '#fff' }};border-radius:999px;font-size:.65rem;font-weight:800;display:flex;align-items:center;justify-content:center">{{ $num }}</span>
                </div>
                <h3 style="font-weight:700;font-size:.95rem;color:#1C1C1C;margin-bottom:8px">{{ $title }}</h3>
                <p style="font-size:.8rem;color:#6B7280;line-height:1.6">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ --}}
<section style="background:var(--cream);padding:64px 0">
    <div style="max-width:720px;margin:0 auto;padding:0 24px">
        <div style="text-align:center;margin-bottom:40px">
            <div class="section-label" style="justify-content:center">FAQ</div>
            <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.5rem,3vw,2rem);font-weight:900;color:#1C1C1C">Common Questions</h2>
        </div>
        @foreach([
            ['How does the free trial work?',          'Your first class is completely free with no commitment. You can try any course with any teacher. If you\'re satisfied, you can book more sessions.'],
            ['How are live classes conducted?',        'All classes are live 1-on-1 sessions via Zoom or Google Meet. Once your teacher approves your booking, they share a meeting link which you can join from your dashboard.'],
            ['Do I need any prior knowledge?',         'No! We have courses for absolute beginners (Noorani Qaida) to advanced students. Our teachers will assess your level and guide you accordingly.'],
            ['Can I choose male or female teachers?',  'Yes, we have both male and female certified teachers. You can filter by gender when browsing teachers.'],
            ['Can I change my teacher?',               'Absolutely. If you feel another teacher would be a better fit, you can switch at any time without any extra cost.'],
            ['What is the monthly fee?',               'Most courses are $30/month. Nazra Quran is $25/month. The fee covers all your sessions for that month based on the course schedule.'],
        ] as [$q, $a])
        <div class="faq-item">
            <div class="faq-q" onclick="toggleFaq(this)">
                <span>{{ $q }}</span>
                <i class="fas fa-plus faq-icon"></i>
            </div>
            <div class="faq-a">{{ $a }}</div>
        </div>
        @endforeach
    </div>
</section>

{{-- CTA --}}
<section style="background:linear-gradient(135deg,#0F3D22,#1A6B3C);padding:72px 24px;text-align:center">
    <div style="max-width:600px;margin:0 auto">
        <div style="font-family:'Amiri',serif;font-size:1.8rem;color:rgba(201,164,39,.7);margin-bottom:12px">بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ</div>
        <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,2.5rem);font-weight:900;color:#fff;margin-bottom:16px">
            Ready to Begin Your <span style="color:#C9A427">Quran Journey?</span>
        </h2>
        <p style="color:rgba(255,255,255,.7);font-size:.95rem;margin-bottom:32px;line-height:1.7">Join thousands of students learning Quran online. First class is always free.</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
            <a href="{{ route('register', 'student') }}" class="btn-gold">
                <i class="fas fa-play-circle"></i> Start Free Today
            </a>
            <a href="https://wa.me/923022702808" target="_blank" style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.1);color:#fff;border:1.5px solid rgba(255,255,255,.25);padding:12px 24px;border-radius:10px;font-weight:600;font-size:.88rem;text-decoration:none">
                <i class="fab fa-whatsapp"></i> Chat on WhatsApp
            </a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
function toggleFaq(el) {
    const ans  = el.nextElementSibling;
    const icon = el.querySelector('.faq-icon');
    const isOpen = ans.classList.contains('open');
    document.querySelectorAll('.faq-a').forEach(a => a.classList.remove('open'));
    document.querySelectorAll('.faq-icon').forEach(i => i.classList.remove('open'));
    if (!isOpen) { ans.classList.add('open'); icon.classList.add('open'); }
}
</script>
@endpush
