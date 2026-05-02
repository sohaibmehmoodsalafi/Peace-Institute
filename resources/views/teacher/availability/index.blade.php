@extends('layouts.dashboard')
@section('title', 'Set Availability')
@section('page-title', 'Availability Schedule')
@section('page-subtitle', 'Define when students can book you')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Sessions</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
@endsection

@section('content')
<div class="max-w-2xl">
    <div class="card p-6">
        <form action="{{ route('teacher.availability.update') }}" method="POST" id="availability-form">
            @csrf
            <div class="space-y-4" id="slots-container">
                @php
                    $days = [0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday'];
                @endphp
                @foreach($days as $dayNum => $dayName)
                    @php $slot = $availabilities->get($dayNum); @endphp
                    <div class="border border-white/5 rounded-xl p-4">
                        <div class="flex items-center gap-4 avail-row">
                            <label class="flex items-center gap-2 w-36 cursor-pointer">
                                <input type="checkbox" name="slots[{{ $dayNum }}][is_available]" value="1"
                                    onchange="toggleDay({{ $dayNum }}, this.checked)"
                                    {{ $slot && $slot->is_available ? 'checked' : '' }}
                                    class="w-4 h-4 rounded">
                                <span class="text-white text-sm font-medium">{{ $dayName }}</span>
                                <input type="hidden" name="slots[{{ $dayNum }}][day_of_week]" value="{{ $dayNum }}">
                            </label>
                            <div class="flex items-center gap-2 flex-1 avail-times day-times-{{ $dayNum }} {{ $slot && $slot->is_available ? '' : 'opacity-40 pointer-events-none' }}">
                                <input type="time" name="slots[{{ $dayNum }}][start_time]"
                                    value="{{ $slot ? $slot->start_time : '09:00' }}"
                                    class="input-dark flex-1">
                                <span class="text-gray-500 text-sm">to</span>
                                <input type="time" name="slots[{{ $dayNum }}][end_time]"
                                    value="{{ $slot ? $slot->end_time : '17:00' }}"
                                    class="input-dark flex-1">
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 p-4 bg-gold-DEFAULT/5 border border-gold-DEFAULT/15 rounded-xl text-sm text-gray-400">
                <i class="fas fa-info-circle text-gold-DEFAULT mr-2"></i>
                Students can book any slot within your available hours. Each session is 30–90 minutes.
            </div>

            <button type="submit" class="btn-gold w-full py-3 rounded-xl mt-6">
                <i class="fas fa-save mr-2"></i> Save Availability
            </button>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
@media (max-width: 640px) {
    .avail-row   { flex-direction: column !important; align-items: flex-start !important; gap: 10px !important; }
    .avail-times { width: 100% !important; }
}
</style>
@endpush

@push('scripts')
<script>
function toggleDay(dayNum, enabled) {
    const el = document.querySelector('.day-times-' + dayNum);
    el.style.opacity = enabled ? '1' : '0.4';
    el.style.pointerEvents = enabled ? 'auto' : 'none';
}
</script>
@endpush
