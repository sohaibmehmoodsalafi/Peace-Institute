@extends('layouts.app')
@section('title', 'Enroll in a Course – Peace Institute')

@push('styles')
<style>
/* ── Hero ── */
.enroll-hero{
    background:linear-gradient(160deg,var(--gd) 0%,var(--green) 100%);
    padding:3.5rem 1.5rem 5rem;
    text-align:center;position:relative;overflow:hidden;
}
.enroll-hero::after{
    content:'';position:absolute;bottom:-1px;left:0;right:0;height:70px;
    background:var(--cream);clip-path:ellipse(55% 100% at 50% 100%);
}
.enroll-hero h1{font-family:'Playfair Display',serif;font-size:clamp(1.6rem,4vw,2.4rem);
    font-weight:700;color:#fff;margin-bottom:.5rem;}
.enroll-hero p{color:rgba(255,255,255,.75);font-size:.95rem;}

/* ── Page layout ── */
.enroll-wrap{max-width:1100px;margin:0 auto;padding:2.5rem 1.25rem 4rem;
    display:grid;grid-template-columns:1fr 340px;gap:2rem;}
@media(max-width:900px){.enroll-wrap{grid-template-columns:1fr;}}

/* ── Card ── */
.enroll-card{background:#fff;border-radius:20px;border:1.5px solid #EDE9E0;
    box-shadow:0 2px 16px rgba(26,107,60,.07);overflow:hidden;margin-bottom:1.5rem;}
.enroll-card:last-child{margin-bottom:0;}
.ec-head{padding:1.25rem 1.5rem .9rem;border-bottom:1px solid #F0EDE6;
    font-size:.85rem;font-weight:700;color:var(--text);
    display:flex;align-items:center;gap:.5rem;}
.ec-head i{color:var(--green);}
.ec-body{padding:1.5rem;}

/* ── Teacher card preview ── */
.t-preview{display:flex;align-items:center;gap:1rem;padding:1rem;
    background:var(--creamd);border-radius:14px;border:1.5px solid #EDE9E0;}
.t-preview img{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid rgba(26,107,60,.15);}
.t-preview-name{font-weight:700;color:var(--text);font-size:.95rem;}
.t-preview-spec{font-size:.8rem;color:var(--green);font-weight:600;}

/* ── Form controls ── */
.enroll-label{display:block;font-size:.82rem;font-weight:700;color:var(--muted);
    letter-spacing:.04em;margin-bottom:.5rem;}
.enroll-select{width:100%;padding:.7rem 1rem;border-radius:10px;
    border:1.5px solid #E5E7EB;font-size:.9rem;color:var(--text);
    background:#fff;outline:none;transition:border .2s;}
.enroll-select:focus{border-color:var(--green);}

/* ── Day picker ── */
.day-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:.5rem;}
@media(max-width:500px){.day-grid{grid-template-columns:repeat(4,1fr);}}
.day-btn{position:relative;}
.day-btn input{position:absolute;opacity:0;width:0;height:0;}
.day-btn label{
    display:block;text-align:center;padding:.6rem .2rem;
    border-radius:10px;border:1.5px solid #E5E7EB;
    font-size:.78rem;font-weight:700;cursor:pointer;
    color:#9CA3AF;background:#fff;
    transition:all .2s;user-select:none;
}
.day-btn label span{display:block;font-size:.65rem;font-weight:500;margin-top:2px;opacity:.7;}
.day-btn input:checked + label{
    background:var(--green);color:#fff;border-color:var(--green);
    box-shadow:0 4px 12px rgba(26,107,60,.25);
}
.day-btn.avail label{border-color:rgba(26,107,60,.3);color:var(--text);}
.day-btn.avail label::after{content:'✓';display:block;font-size:.6rem;
    color:var(--green);margin-top:1px;}
.day-btn input:checked + label::after{content:'';display:none;}

/* ── Time select ── */
.time-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:.5rem;}
@media(max-width:500px){.time-grid{grid-template-columns:repeat(3,1fr);}}
.time-btn{position:relative;}
.time-btn input{position:absolute;opacity:0;width:0;height:0;}
.time-btn label{
    display:block;text-align:center;padding:.5rem .25rem;
    border-radius:9px;border:1.5px solid #E5E7EB;
    font-size:.78rem;font-weight:600;cursor:pointer;
    color:#9CA3AF;background:#fff;transition:all .2s;
}
.time-btn input:checked + label{
    background:rgba(26,107,60,.08);color:var(--green);
    border-color:var(--green);
}

/* ── Notes ── */
.enroll-textarea{width:100%;padding:.75rem 1rem;border-radius:10px;
    border:1.5px solid #E5E7EB;font-size:.875rem;resize:vertical;
    min-height:80px;color:var(--text);outline:none;transition:border .2s;
    font-family:inherit;}
.enroll-textarea:focus{border-color:var(--green);}

/* ── Submit btn ── */
.enroll-submit{
    display:block;width:100%;padding:1rem;border:none;cursor:pointer;
    border-radius:14px;background:var(--green);color:#fff;
    font-size:1rem;font-weight:700;text-align:center;
    transition:background .2s,transform .15s;
}
.enroll-submit:hover{background:var(--gl);transform:translateY(-2px);}

/* ── Summary sidebar ── */
.summary-card{background:#fff;border-radius:20px;border:1.5px solid #EDE9E0;
    box-shadow:0 2px 16px rgba(26,107,60,.07);padding:1.5rem;
    position:sticky;top:1.5rem;}
.summary-title{font-size:.75rem;font-weight:700;color:var(--muted);
    letter-spacing:.08em;text-transform:uppercase;margin-bottom:1rem;}
.sum-row{display:flex;justify-content:space-between;align-items:flex-start;
    padding:.6rem 0;border-bottom:1px solid #F0EDE6;font-size:.85rem;}
.sum-row:last-of-type{border-bottom:none;}
.sum-key{color:var(--muted);}
.sum-val{font-weight:700;color:var(--text);text-align:right;max-width:60%;}
.sum-fee{margin-top:1rem;background:rgba(26,107,60,.06);
    border-radius:14px;padding:1rem;text-align:center;}
.sum-fee-amt{font-size:2rem;font-weight:900;color:var(--green);}
.sum-fee-lbl{font-size:.75rem;color:var(--muted);margin-top:.15rem;}
</style>
@endpush

@section('content')

<div class="enroll-hero">
    <h1>Enroll in a Course</h1>
    <p>Choose your teacher, course, and preferred class schedule</p>
</div>

<div class="enroll-wrap">
    <div>

        @if(session('error'))
            <div style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3);color:#dc2626;
                        padding:.875rem 1.25rem;border-radius:12px;margin-bottom:1.5rem;font-size:.875rem;">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('student.enroll.store') }}" id="enrollForm">
        @csrf

        {{-- ── Step 1: Teacher + Course ── --}}
        <div class="enroll-card">
            <div class="ec-head"><i class="fas fa-chalkboard-teacher"></i> Step 1 — Select Teacher & Course</div>
            <div class="ec-body">

                {{-- Teacher --}}
                <div style="margin-bottom:1.25rem;">
                    <label class="enroll-label">Teacher</label>
                    @if($teacher)
                        {{-- Pre-selected teacher preview --}}
                        <div class="t-preview" style="margin-bottom:.75rem;">
                            <img src="{{ $teacher->user->avatar_url }}"
                                 alt="{{ $teacher->user->name }}"
                                 onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($teacher->user->name) }}&background=0F3D22&color=fff&size=100&bold=true'">
                            <div>
                                <div class="t-preview-name">{{ $teacher->user->name }}</div>
                                <div class="t-preview-spec">{{ $teacher->specialization ?? 'Quran Teacher' }}</div>
                            </div>
                        </div>
                        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
                        <a href="{{ route('student.enroll.create') }}" style="font-size:.78rem;color:var(--muted);">
                            <i class="fas fa-sync-alt mr-1"></i> Change Teacher
                        </a>
                    @else
                        <select name="teacher_id" class="enroll-select" id="teacherSelect"
                                onchange="this.form.submit()" required>
                            <option value="">— Choose a Teacher —</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                                    {{ $t->user->name }} — {{ $t->specialization ?? 'Teacher' }}
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="_teacher_select" value="1">
                    @endif
                </div>

                {{-- Course --}}
                <div>
                    <label class="enroll-label">Course</label>
                    <select name="course_id" class="enroll-select" id="courseSelect" required>
                        <option value="">— Choose a Course —</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}"
                                    data-fee="{{ $c->monthly_price ?? 30 }}"
                                    data-name="{{ $c->name }}"
                                    {{ old('course_id') == $c->id ? 'selected' : '' }}>
                                {{ $c->name }}
                                @if($c->monthly_price) — ${{ number_format($c->monthly_price, 0) }}/mo @endif
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

        @if($teacher)

        {{-- ── Step 2: Days ── --}}
        <div class="enroll-card">
            <div class="ec-head"><i class="fas fa-calendar-week"></i> Step 2 — Select Class Days</div>
            <div class="ec-body">
                <p style="font-size:.82rem;color:var(--muted);margin-bottom:1rem;">
                    Choose which days of the week you want your classes.
                    @if($teacher->availabilities->count())
                        <strong style="color:var(--green);">✓ marks = teacher available</strong>
                    @endif
                </p>

                @php
                    $availDays = $teacher->availabilities->pluck('day_of_week')->toArray();
                    // day_of_week: 0=Sunday,1=Monday...6=Saturday
                    $dayMap = [
                        'monday'=>1,'tuesday'=>2,'wednesday'=>3,
                        'thursday'=>4,'friday'=>5,'saturday'=>6,'sunday'=>0
                    ];
                    $dayLabels = [
                        'monday'=>['Mon','Monday'],'tuesday'=>['Tue','Tuesday'],
                        'wednesday'=>['Wed','Wednesday'],'thursday'=>['Thu','Thursday'],
                        'friday'=>['Fri','Friday'],'saturday'=>['Sat','Saturday'],
                        'sunday'=>['Sun','Sunday']
                    ];
                @endphp

                <div class="day-grid">
                    @foreach($dayLabels as $val => [$short, $full])
                        @php $isAvail = in_array($dayMap[$val], $availDays); @endphp
                        <div class="day-btn {{ $isAvail ? 'avail' : '' }}">
                            <input type="checkbox" name="selected_days[]" value="{{ $val }}"
                                   id="day_{{ $val }}"
                                   onchange="updateSummary()"
                                   {{ in_array($val, old('selected_days', [])) ? 'checked' : '' }}>
                            <label for="day_{{ $val }}">
                                {{ $short }}
                                <span>{{ $full }}</span>
                            </label>
                        </div>
                    @endforeach
                </div>

                @error('selected_days')
                    <p style="color:#dc2626;font-size:.78rem;margin-top:.5rem;">Please select at least one day.</p>
                @enderror
            </div>
        </div>

        {{-- ── Step 3: Time ── --}}
        <div class="enroll-card">
            <div class="ec-head"><i class="fas fa-clock"></i> Step 3 — Preferred Time</div>
            <div class="ec-body">
                @if($teacher->availabilities->count())
                    <p style="font-size:.82rem;color:var(--muted);margin-bottom:1rem;">
                        Select a time slot that works for you. Teacher availability shown.
                    </p>
                    <div class="time-grid">
                        @foreach($teacher->availabilities->sortBy('start_time') as $slot)
                            <div class="time-btn">
                                <input type="radio" name="preferred_time"
                                       id="time_{{ $loop->index }}"
                                       value="{{ date('H:i', strtotime($slot->start_time)) }}"
                                       {{ old('preferred_time') == date('H:i', strtotime($slot->start_time)) ? 'checked' : '' }}>
                                <label for="time_{{ $loop->index }}">
                                    {{ date('h:i A', strtotime($slot->start_time)) }}
                                    <span style="display:block;font-size:.65rem;opacity:.7;">{{ $slot->day_name }}</span>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <label class="enroll-label">Preferred Start Time</label>
                    <input type="time" name="preferred_time" class="enroll-select"
                           style="max-width:200px;" value="{{ old('preferred_time') }}">
                    <p style="font-size:.78rem;color:var(--muted);margin-top:.5rem;">
                        Teacher hasn't set fixed slots — enter your preferred time.
                    </p>
                @endif
            </div>
        </div>

        {{-- ── Step 4: Notes ── --}}
        <div class="enroll-card">
            <div class="ec-head"><i class="fas fa-comment-alt"></i> Step 4 — Additional Notes <span style="font-weight:400;color:var(--muted);font-size:.75rem;">(optional)</span></div>
            <div class="ec-body">
                <textarea name="notes" class="enroll-textarea"
                    placeholder="e.g. My current level, special requirements, questions...">{{ old('notes') }}</textarea>
            </div>
        </div>

        @endif

        </form>

    </div>

    {{-- ── Summary sidebar ── --}}
    <div>
        <div class="summary-card">
            <div class="summary-title"><i class="fas fa-receipt mr-1"></i> Enrollment Summary</div>

            <div class="sum-row">
                <span class="sum-key">Teacher</span>
                <span class="sum-val" id="sumTeacher">
                    {{ $teacher ? $teacher->user->name : '—' }}
                </span>
            </div>
            <div class="sum-row">
                <span class="sum-key">Course</span>
                <span class="sum-val" id="sumCourse">—</span>
            </div>
            <div class="sum-row">
                <span class="sum-key">Days / Week</span>
                <span class="sum-val" id="sumDays">0 days</span>
            </div>
            <div class="sum-row">
                <span class="sum-key">Classes / Month</span>
                <span class="sum-val" id="sumMonthly">~0 classes</span>
            </div>
            <div class="sum-row">
                <span class="sum-key">Selected Days</span>
                <span class="sum-val" id="sumDayNames" style="font-size:.78rem;">—</span>
            </div>

            <div class="sum-fee">
                <div class="sum-fee-amt">$<span id="sumFee">0</span></div>
                <div class="sum-fee-lbl">per month</div>
            </div>

            @if($teacher)
            <button type="submit" form="enrollForm" class="enroll-submit" style="margin-top:1.25rem;">
                <i class="fas fa-paper-plane mr-2"></i> Submit Enrollment Request
            </button>
            <p style="font-size:.72rem;color:var(--muted);text-align:center;margin-top:.75rem;">
                <i class="fas fa-info-circle mr-1"></i>
                Our team will confirm your schedule within 24 hours.
            </p>
            @else
            <p style="font-size:.82rem;color:var(--muted);text-align:center;margin-top:1rem;">
                Select a teacher to continue
            </p>
            @endif
        </div>

        @if($teacher)
        <div style="margin-top:1rem;background:#fff;border-radius:16px;border:1.5px solid #EDE9E0;padding:1.25rem;">
            <div style="font-size:.75rem;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:.75rem;">
                <i class="fas fa-shield-alt mr-1" style="color:var(--green);"></i> What Happens Next
            </div>
            <div style="font-size:.8rem;color:#374151;line-height:1.8;">
                <div>✅ Your request is reviewed by our team</div>
                <div>✅ Teacher is notified & schedule confirmed</div>
                <div>✅ You receive class joining details</div>
                <div>✅ Classes begin on your confirmed start date</div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function updateSummary() {
    // Days
    const checked = document.querySelectorAll('input[name="selected_days[]"]:checked');
    const count   = checked.length;
    const names   = Array.from(checked).map(c => c.value.charAt(0).toUpperCase() + c.value.slice(1, 3));

    document.getElementById('sumDays').textContent    = count + ' day' + (count !== 1 ? 's' : '');
    document.getElementById('sumMonthly').textContent = '~' + (count * 4) + ' classes';
    document.getElementById('sumDayNames').textContent = names.length ? names.join(', ') : '—';
}

// Course summary
const courseSelect = document.getElementById('courseSelect');
if (courseSelect) {
    courseSelect.addEventListener('change', function() {
        const opt = this.options[this.selectedIndex];
        document.getElementById('sumCourse').textContent = opt.dataset.name || '—';
        document.getElementById('sumFee').textContent    = opt.dataset.fee || '0';
    });
    // Run on load if pre-selected
    if (courseSelect.value) courseSelect.dispatchEvent(new Event('change'));
}

// Init summary
updateSummary();
</script>

@endsection
