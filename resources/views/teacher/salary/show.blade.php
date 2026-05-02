@extends('layouts.dashboard')
@section('title', 'Salary Slip – '.$slip->period)
@section('page-title', 'Salary Slip')
@section('page-subtitle', $slip->period)

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Sessions</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('teacher.salary.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary Slips</a>
@endsection

@push('styles')
<style>
    .slip-card { background: linear-gradient(135deg,#0d0d0d,#111); border:1px solid rgba(212,175,55,.2); border-radius:16px; }
    .slip-header { background:linear-gradient(135deg,#1A6B3C,#22874D); border-radius:14px 14px 0 0; padding:28px 32px; }
    .slip-row { display:flex; justify-content:space-between; align-items:center; padding:14px 0; border-bottom:1px solid rgba(255,255,255,.05); }
    .slip-row:last-child { border-bottom:none; }
    .slip-total { background:rgba(212,175,55,.08); border:1px solid rgba(212,175,55,.2); border-radius:10px; padding:18px 24px; }
    @media print {
        .no-print { display:none !important; }
        body { background:#fff !important; color:#000 !important; }
        .slip-card { border:1px solid #ccc !important; background:#fff !important; }
        .slip-header { background:#1A6B3C !important; -webkit-print-color-adjust:exact; }
        .slip-total { background:#f9f5e8 !important; border:1px solid #C9A427 !important; }
    }
</style>
@endpush

@section('content')

<div class="max-w-2xl mx-auto">

    {{-- Actions --}}
    <div class="flex items-center justify-between mb-5 no-print">
        <a href="{{ route('teacher.salary.index') }}" class="btn-outline px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <button onclick="window.print()" class="btn-gold px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-print mr-2"></i>Print / Save PDF
        </button>
    </div>

    {{-- Salary Slip --}}
    <div class="slip-card">

        {{-- Header --}}
        <div class="slip-header">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px">
                <div>
                    <div style="font-size:.65rem;color:rgba(255,255,255,.55);letter-spacing:.18em;text-transform:uppercase;margin-bottom:8px">
                        Peace Institute — Official Salary Slip
                    </div>
                    <div style="font-size:1.4rem;font-weight:800;color:#fff">{{ $teacher->user->name }}</div>
                    <div style="font-size:.82rem;color:rgba(255,255,255,.65);margin-top:4px">
                        {{ $teacher->specialization ?? 'Teacher' }}
                    </div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:1.1rem;font-weight:700;color:#F0D060">{{ $slip->period }}</div>
                    <div style="margin-top:8px">
                        <span style="font-size:.68rem;padding:4px 14px;border-radius:999px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;
                            {{ $slip->status === 'paid'
                               ? 'background:rgba(52,211,153,.2);color:#34d399;border:1px solid rgba(52,211,153,.4)'
                               : 'background:rgba(212,175,55,.2);color:#D4AF37;border:1px solid rgba(212,175,55,.4)' }}">
                            {{ $slip->status === 'paid' ? '✓ Paid' : 'Approved' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px">

            {{-- Attendance --}}
            <div style="margin-bottom:24px">
                <div style="font-size:.68rem;color:rgba(212,175,55,.8);text-transform:uppercase;letter-spacing:.15em;font-weight:700;margin-bottom:14px">
                    Class Attendance — {{ $slip->period }}
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:10px">
                    <div style="background:rgba(52,211,153,.06);border:1px solid rgba(52,211,153,.18);border-radius:10px;padding:14px;text-align:center">
                        <div style="font-size:1.8rem;font-weight:800;color:#34d399">{{ $slip->conducted_classes }}</div>
                        <div style="font-size:.68rem;color:#6b7280;margin-top:3px">Conducted</div>
                    </div>
                    <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.18);border-radius:10px;padding:14px;text-align:center">
                        <div style="font-size:1.8rem;font-weight:800;color:#f87171">{{ $slip->missed_classes }}</div>
                        <div style="font-size:.68rem;color:#6b7280;margin-top:3px">Missed</div>
                    </div>
                    <div style="background:rgba(99,102,241,.06);border:1px solid rgba(99,102,241,.18);border-radius:10px;padding:14px;text-align:center">
                        <div style="font-size:1.8rem;font-weight:800;color:#818cf8">{{ $slip->target_classes }}</div>
                        <div style="font-size:.68rem;color:#6b7280;margin-top:3px">Target</div>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            {{-- Salary Breakdown --}}
            <div style="margin:20px 0">
                <div style="font-size:.68rem;color:rgba(212,175,55,.8);text-transform:uppercase;letter-spacing:.15em;font-weight:700;margin-bottom:14px">
                    Salary Breakdown
                </div>

                <div class="slip-row">
                    <span style="color:#9ca3af">Fixed Monthly Salary</span>
                    <span style="color:#fff;font-weight:600">${{ number_format($slip->fixed_salary, 2) }}</span>
                </div>
                <div class="slip-row">
                    <span style="color:#9ca3af">Per Class Value ({{ $slip->target_classes }} classes)</span>
                    <span style="color:#9ca3af">${{ number_format($slip->deduction_per_class, 2) }}</span>
                </div>
                @if($slip->missed_classes > 0)
                <div class="slip-row">
                    <span style="color:#f87171">
                        Missed Class Deduction
                        <span style="font-size:.75rem;color:#6b7280;display:block">
                            {{ $slip->missed_classes }} × ${{ number_format($slip->deduction_per_class, 2) }}
                        </span>
                    </span>
                    <span style="color:#f87171;font-weight:600">-${{ number_format($slip->total_deduction, 2) }}</span>
                </div>
                @endif
                @if($slip->admin_adjustment != 0)
                <div class="slip-row">
                    <span style="color:#fbbf24">
                        Admin Adjustment {{ $slip->admin_adjustment > 0 ? '(Bonus)' : '(Extra Deduction)' }}
                        @if($slip->adjustment_note)
                            <span style="font-size:.72rem;color:#6b7280;display:block">{{ $slip->adjustment_note }}</span>
                        @endif
                    </span>
                    <span style="color:#fbbf24;font-weight:600">
                        {{ $slip->admin_adjustment > 0 ? '+' : '' }}${{ number_format($slip->admin_adjustment, 2) }}
                    </span>
                </div>
                @endif
            </div>

            {{-- Net Salary --}}
            <div class="slip-total">
                <div style="display:flex;justify-content:space-between;align-items:center">
                    <div>
                        <div style="font-size:.68rem;color:rgba(212,175,55,.7);text-transform:uppercase;letter-spacing:.15em;margin-bottom:4px">Net Salary Payable</div>
                        @if($slip->status === 'paid' && $slip->paid_at)
                            <div style="font-size:.72rem;color:#34d399">
                                <i class="fas fa-check-circle mr-1"></i>Paid on {{ $slip->paid_at->format('M d, Y') }}
                            </div>
                        @else
                            <div style="font-size:.72rem;color:#D4AF37">
                                <i class="fas fa-clock mr-1"></i>Payment Pending
                            </div>
                        @endif
                    </div>
                    <div style="font-size:2.2rem;font-weight:900;color:#D4AF37">${{ number_format($slip->net_salary, 2) }}</div>
                </div>
            </div>

            {{-- Footer --}}
            <div style="margin-top:20px;padding-top:16px;border-top:1px solid rgba(255,255,255,.05);display:flex;justify-content:space-between;font-size:.7rem;color:#4b5563">
                <span>Slip #{{ str_pad($slip->id, 5, '0', STR_PAD_LEFT) }}</span>
                <span>Approved: {{ $slip->approved_at?->format('M d, Y') ?? '—' }}</span>
                <span>peace.org.pk</span>
            </div>
        </div>
    </div>

</div>
@endsection
