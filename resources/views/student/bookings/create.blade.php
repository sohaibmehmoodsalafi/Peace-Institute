@extends('layouts.dashboard')
@section('title', 'Book a Session')
@section('page-title', 'Book a Session')
@section('page-subtitle', 'Choose your time slot and confirm')

@section('sidebar-nav')
    <a href="{{ route('student.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teachers') }}" class="sidebar-link"><span class="icon"><i class="fas fa-search"></i></span> Find Teachers</a>
    <a href="{{ route('student.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> My Bookings</a>
@endsection

@section('content')
<div class="max-w-3xl mx-auto">

    {{-- Teacher Profile Card --}}
    <div class="card p-6 mb-6 flex items-start gap-5">
        <img src="{{ $teacher->user->avatar_url }}" class="w-20 h-20 rounded-xl object-cover border border-gold-DEFAULT/20" alt="{{ $teacher->user->name }}">
        <div class="flex-1">
            <h2 class="text-white text-xl font-semibold">{{ $teacher->user->name }}</h2>
            <p class="text-gold-DEFAULT text-sm">{{ $teacher->specialization }}</p>
            <div class="flex items-center gap-4 mt-2 text-sm text-gray-400">
                <span><i class="fas fa-star text-yellow-400 mr-1"></i>{{ number_format($teacher->rating, 1) }} ({{ $teacher->total_reviews }} reviews)</span>
                <span><i class="fas fa-briefcase mr-1"></i>{{ $teacher->experience_years }} yrs exp</span>
                <span><i class="fas fa-dollar-sign mr-1"></i>${{ number_format($teacher->hourly_rate, 2) }}/hr</span>
            </div>
        </div>
    </div>

    {{-- Booking Form --}}
    <form action="{{ route('student.bookings.store') }}" method="POST" id="booking-form">
        @csrf
        <input type="hidden" name="teacher_id" value="{{ $teacher->id }}">
        <input type="hidden" name="scheduled_at" id="scheduled_at_input">

        <div class="card p-6 space-y-6">

            {{-- Course --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Course</label>
                <select name="course_id" class="input-dark">
                    <option value="">General / No specific course</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Duration --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Session Duration</label>
                <div class="grid grid-cols-4 gap-3">
                    @foreach([30 => '30 min', 45 => '45 min', 60 => '1 hour', 90 => '1.5 hrs'] as $mins => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="duration" value="{{ $mins }}" {{ $mins === 60 ? 'checked' : '' }} class="sr-only peer">
                            <div class="border border-gold-DEFAULT/20 rounded-lg p-3 text-center text-sm text-gray-400 peer-checked:border-gold-DEFAULT peer-checked:text-gold-DEFAULT peer-checked:bg-gold-DEFAULT/10 transition-all hover:border-gold-DEFAULT/40">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Booking Type --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Booking Type</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach(['single' => 'Single Session', 'package' => 'Monthly Package'] as $val => $label)
                        <label class="cursor-pointer">
                            <input type="radio" name="booking_type" value="{{ $val }}" {{ $val === 'single' ? 'checked' : '' }} class="sr-only peer">
                            <div class="border border-gold-DEFAULT/20 rounded-lg p-3 text-center text-sm text-gray-400 peer-checked:border-gold-DEFAULT peer-checked:text-gold-DEFAULT peer-checked:bg-gold-DEFAULT/10 transition-all">
                                {{ $label }}
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- Date Picker --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Select Date</label>
                <input type="date" id="date-picker" min="{{ now()->addDay()->format('Y-m-d') }}"
                    class="input-dark w-full" onchange="loadSlots()">
            </div>

            {{-- Time Slots --}}
            <div id="slots-container" class="hidden">
                <label class="block text-gray-400 text-sm mb-3">Available Time Slots</label>
                <div id="slots-grid" class="grid grid-cols-3 sm:grid-cols-4 gap-2"></div>
                <div id="no-slots" class="hidden text-gray-600 text-sm py-4 text-center">
                    <i class="fas fa-calendar-times mr-2"></i>No available slots for this date
                </div>
            </div>

            {{-- Price Preview --}}
            <div id="price-preview" class="hidden bg-gold-DEFAULT/5 border border-gold-DEFAULT/20 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm">Session Cost</span>
                    <span class="text-white font-semibold text-lg" id="price-display">$0.00</span>
                </div>
                <div class="text-xs text-gray-600 mt-1">Based on teacher's rate ${{ number_format($teacher->hourly_rate, 2) }}/hr</div>
            </div>

            {{-- Notes --}}
            <div>
                <label class="block text-gray-400 text-sm mb-2">Notes for Teacher (optional)</label>
                <textarea name="notes" rows="2" class="input-dark w-full" placeholder="Your learning goals, current level, specific topics..."></textarea>
            </div>

            <button type="submit" id="submit-btn" disabled
                class="btn-gold w-full py-3 rounded-xl text-base opacity-50 disabled:cursor-not-allowed"
                title="Select a date and time slot to proceed">
                <i class="fas fa-calendar-check mr-2"></i> Confirm Booking
            </button>

        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const teacherId   = {{ $teacher->id }};
    const hourlyRate  = {{ $teacher->hourly_rate }};
    let selectedSlot  = null;

    function getDuration() {
        return parseInt(document.querySelector('input[name="duration"]:checked').value);
    }

    function loadSlots() {
        const date = document.getElementById('date-picker').value;
        if (!date) return;

        const duration = getDuration();

        document.getElementById('slots-grid').innerHTML = '<div class="col-span-full text-center text-gray-600 py-4"><i class="fas fa-spinner fa-spin mr-2"></i>Loading slots...</div>';
        document.getElementById('slots-container').classList.remove('hidden');
        document.getElementById('no-slots').classList.add('hidden');

        fetch(`/student/bookings/slots?teacher_id=${teacherId}&date=${date}&duration=${duration}`)
            .then(r => r.json())
            .then(data => {
                const grid = document.getElementById('slots-grid');
                grid.innerHTML = '';
                if (!data.slots || data.slots.length === 0) {
                    document.getElementById('no-slots').classList.remove('hidden');
                    return;
                }
                data.slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.textContent = slot.time_label;
                    btn.className = 'py-2 px-3 rounded-lg text-sm border border-gold-DEFAULT/20 text-gray-400 hover:border-gold-DEFAULT hover:text-gold-DEFAULT transition-all';
                    btn.onclick = () => selectSlot(slot, btn);
                    grid.appendChild(btn);
                });
            });
    }

    function selectSlot(slot, btn) {
        document.querySelectorAll('#slots-grid button').forEach(b => {
            b.className = 'py-2 px-3 rounded-lg text-sm border border-gold-DEFAULT/20 text-gray-400 hover:border-gold-DEFAULT hover:text-gold-DEFAULT transition-all';
        });
        btn.className = 'py-2 px-3 rounded-lg text-sm border-2 border-gold-DEFAULT bg-gold-DEFAULT/15 text-gold-DEFAULT font-semibold';

        document.getElementById('scheduled_at_input').value = slot.datetime;

        const duration = getDuration();
        const cost = ((duration / 60) * hourlyRate).toFixed(2);
        document.getElementById('price-display').textContent = '$' + cost;
        document.getElementById('price-preview').classList.remove('hidden');

        const submitBtn = document.getElementById('submit-btn');
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-50', 'disabled:cursor-not-allowed');
    }

    document.querySelectorAll('input[name="duration"]').forEach(r => r.addEventListener('change', loadSlots));
</script>
@endpush
