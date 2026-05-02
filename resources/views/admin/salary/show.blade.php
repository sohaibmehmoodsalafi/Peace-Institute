@extends('layouts.dashboard')
@section('title', 'Salary Slip – '.$slip->teacher->user->name)
@section('page-title', 'Salary Slip')
@section('page-subtitle', $slip->period.' — '.$slip->teacher->user->name)

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.salary.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary Slips</a>
@endsection

@push('styles')
<style>
    .slip-card { background: linear-gradient(135deg, #0d0d0d, #111); border: 1px solid rgba(212,175,55,.2); border-radius: 16px; }
    .slip-header { background: linear-gradient(135deg, #1A6B3C, #22874D); border-radius: 14px 14px 0 0; padding: 28px 32px; }
    .slip-row { display: flex; justify-content: space-between; align-items: center; padding: 14px 0; border-bottom: 1px solid rgba(255,255,255,.05); }
    .slip-row:last-child { border-bottom: none; }
    .slip-total { background: rgba(212,175,55,.08); border: 1px solid rgba(212,175,55,.2); border-radius: 10px; padding: 18px 24px; }
    @media print {
        .no-print { display: none !important; }
        body { background: #fff !important; color: #000 !important; }
        .slip-card { border: 1px solid #ccc !important; }
        .slip-header { background: #1A6B3C !important; -webkit-print-color-adjust: exact; }
    }
</style>
@endpush

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Top Actions --}}
    <div class="flex items-center justify-between mb-5 no-print">
        <a href="{{ route('admin.salary.index') }}" class="btn-outline px-4 py-2 rounded-lg text-sm">
            <i class="fas fa-arrow-left mr-2"></i>Back
        </a>
        <div class="flex gap-2">
            <button onclick="window.print()" class="btn-outline px-4 py-2 rounded-lg text-sm">
                <i class="fas fa-print mr-2"></i>Print
            </button>
            @if($slip->status === 'draft')
                <form action="{{ route('admin.salary.approve', $slip) }}" method="POST">
                    @csrf
                    <button class="btn-gold px-4 py-2 rounded-lg text-sm">
                        <i class="fas fa-check mr-2"></i>Approve Slip
                    </button>
                </form>
            @elseif($slip->status === 'approved')
                <form action="{{ route('admin.salary.pay', $slip) }}" method="POST">
                    @csrf
                    <button class="btn-gold px-4 py-2 rounded-lg text-sm" style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                        <i class="fas fa-money-bill mr-2"></i>Mark as Paid
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Salary Slip --}}
    <div class="slip-card">

        {{-- Header --}}
        <div class="slip-header">
            <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px">
                <div>
                    <div style="font-size:.7rem;color:rgba(255,255,255,.6);letter-spacing:.15em;text-transform:uppercase;margin-bottom:6px">Peace Institute — Salary Slip</div>
                    <div style="font-size:1.5rem;font-weight:800;color:#fff">{{ $slip->teacher->user->name }}</div>
                    <div style="font-size:.85rem;color:rgba(255,255,255,.7);margin-top:4px">{{ $slip->teacher->specialization ?? 'Teacher' }}</div>
                </div>
                <div style="text-align:right">
                    <div style="font-size:1.1rem;font-weight:700;color:#F0D060">{{ $slip->period }}</div>
                    <div style="margin-top:8px">
                        <span style="font-size:.7rem;padding:4px 12px;border-radius:999px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;
                            {{ $slip->status === 'paid' ? 'background:rgba(52,211,153,.2);color:#34d399;border:1px solid rgba(52,211,153,.3)' :
                               ($slip->status === 'approved' ? 'background:rgba(212,175,55,.2);color:#D4AF37;border:1px solid rgba(212,175,55,.3)' :
                               'background:rgba(251,191,36,.15);color:#fbbf24;border:1px solid rgba(251,191,36,.3)') }}">
                            {{ ucfirst($slip->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div style="padding:28px 32px">

            {{-- Attendance Summary --}}
            <div style="margin-bottom:24px">
                <div style="font-size:.7rem;color:rgba(212,175,55,.8);text-transform:uppercase;letter-spacing:.15em;font-weight:700;margin-bottom:16px">
                    Class Attendance
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px">
                    <div style="background:rgba(52,211,153,.06);border:1px solid rgba(52,211,153,.2);border-radius:10px;padding:16px;text-align:center">
                        <div style="font-size:2rem;font-weight:800;color:#34d399">{{ $slip->conducted_classes }}</div>
                        <div style="font-size:.72rem;color:#6b7280;margin-top:4px">Classes Conducted</div>
                    </div>
                    <div style="background:rgba(239,68,68,.06);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:16px;text-align:center">
                        <div style="font-size:2rem;font-weight:800;color:#f87171">{{ $slip->missed_classes }}</div>
                        <div style="font-size:.72rem;color:#6b7280;margin-top:4px">Classes Missed</div>
                    </div>
                    <div style="background:rgba(99,102,241,.06);border:1px solid rgba(99,102,241,.2);border-radius:10px;padding:16px;text-align:center">
                        <div style="font-size:2rem;font-weight:800;color:#818cf8">{{ $slip->target_classes }}</div>
                        <div style="font-size:.72rem;color:#6b7280;margin-top:4px">Target Classes</div>
                    </div>
                </div>
            </div>

            {{-- Divider --}}
            <div class="divider"></div>

            {{-- Salary Breakdown --}}
            <div style="margin:20px 0">
                <div style="font-size:.7rem;color:rgba(212,175,55,.8);text-transform:uppercase;letter-spacing:.15em;font-weight:700;margin-bottom:16px">
                    Salary Breakdown
                </div>

                <div class="slip-row">
                    <span style="color:#9ca3af;font-size:.875rem">Fixed Monthly Salary</span>
                    <span style="color:#fff;font-weight:600">${{ number_format($slip->fixed_salary, 2) }}</span>
                </div>
                <div class="slip-row">
                    <span style="color:#9ca3af;font-size:.875rem">Per Class Value</span>
                    <span style="color:#9ca3af">${{ number_format($slip->deduction_per_class, 2) }}/class</span>
                </div>
                <div class="slip-row">
                    <span style="color:#f87171;font-size:.875rem">
                        Deduction ({{ $slip->missed_classes }} missed × ${{ number_format($slip->deduction_per_class, 2) }})
                    </span>
                    <span style="color:#f87171;font-weight:600">
                        {{ $slip->total_deduction > 0 ? '-$'.number_format($slip->total_deduction, 2) : '—' }}
                    </span>
                </div>
                @if($slip->admin_adjustment != 0)
                <div class="slip-row">
                    <div>
                        <span style="color:#fbbf24;font-size:.875rem">
                            Admin Adjustment {{ $slip->admin_adjustment > 0 ? '(Bonus)' : '(Deduction)' }}
                        </span>
                        @if($slip->adjustment_note)
                            <div style="font-size:.72rem;color:#6b7280;margin-top:2px">{{ $slip->adjustment_note }}</div>
                        @endif
                    </div>
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
                        <div style="font-size:.7rem;color:rgba(212,175,55,.7);text-transform:uppercase;letter-spacing:.15em;margin-bottom:4px">Net Salary Payable</div>
                        @if($slip->status === 'paid' && $slip->paid_at)
                            <div style="font-size:.72rem;color:#34d399"><i class="fas fa-check-circle mr-1"></i>Paid on {{ $slip->paid_at->format('M d, Y') }}</div>
                        @endif
                    </div>
                    <div style="font-size:2rem;font-weight:900;color:#D4AF37">${{ number_format($slip->net_salary, 2) }}</div>
                </div>
            </div>

            {{-- Classes Conducted Detail --}}
            @if($bookings->count())
            <div style="margin-top:24px">
                <div style="font-size:.7rem;color:rgba(212,175,55,.8);text-transform:uppercase;letter-spacing:.15em;font-weight:700;margin-bottom:12px">
                    Classes Conducted — Detail
                </div>
                <div style="border:1px solid rgba(255,255,255,.07);border-radius:10px;overflow:hidden">
                    <div style="display:grid;grid-template-columns:2fr 2fr 1fr;background:rgba(255,255,255,.04);padding:10px 16px;font-size:.7rem;color:#6b7280;text-transform:uppercase;letter-spacing:.08em;font-weight:700;">
                        <span>Date & Student</span>
                        <span>Course</span>
                        <span style="text-align:right">Earned</span>
                    </div>
                    @foreach($bookings as $booking)
                    <div style="display:grid;grid-template-columns:2fr 2fr 1fr;padding:10px 16px;border-top:1px solid rgba(255,255,255,.05);{{ $loop->even ? 'background:rgba(255,255,255,.02)' : '' }}">
                        <div>
                            <div style="font-size:.825rem;color:#e5e7eb;font-weight:600">{{ $booking->scheduled_at->format('d M Y') }}</div>
                            <div style="font-size:.72rem;color:#6b7280;margin-top:2px">{{ $booking->student->user->name ?? '—' }}</div>
                        </div>
                        <div style="font-size:.8rem;color:#9ca3af;align-self:center">{{ $booking->course->title ?? $booking->subject ?? '—' }}</div>
                        <div style="font-size:.825rem;color:#34d399;font-weight:700;text-align:right;align-self:center">
                            ${{ number_format($slip->deduction_per_class, 2) }}
                        </div>
                    </div>
                    @endforeach
                    <div style="display:grid;grid-template-columns:2fr 2fr 1fr;padding:10px 16px;border-top:1px solid rgba(212,175,55,.2);background:rgba(212,175,55,.04)">
                        <div style="font-size:.8rem;color:#d4af37;font-weight:700;grid-column:1/3">Total ({{ $bookings->count() }} classes)</div>
                        <div style="font-size:.875rem;color:#d4af37;font-weight:800;text-align:right">
                            ${{ number_format($slip->deduction_per_class * $bookings->count(), 2) }}
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Admin info --}}
            @if($slip->approved_at)
                <div style="margin-top:16px;font-size:.72rem;color:#4b5563;text-align:center">
                    Approved by {{ $slip->approvedBy->name ?? 'Admin' }} on {{ $slip->approved_at->format('M d, Y') }}
                </div>
            @endif

        </div>
    </div>

    {{-- Edit Form (draft only) --}}
    @if($slip->status === 'draft')
    <div class="card p-6 mt-6 no-print">
        <h3 class="text-white font-semibold mb-4"><i class="fas fa-edit text-gold-DEFAULT mr-2"></i>Adjust Salary Slip</h3>
        <form action="{{ route('admin.salary.update', $slip) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Classes Conducted</label>
                    <input type="number" name="conducted_classes" value="{{ $slip->conducted_classes }}" min="0" class="input-dark w-full">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Classes Missed</label>
                    <input type="number" name="missed_classes" value="{{ $slip->missed_classes }}" min="0" class="input-dark w-full">
                </div>
            </div>
            <div>
                <label class="block text-gray-400 text-sm mb-2">Admin Adjustment ($) <span class="text-gray-600 text-xs">Positive = bonus, Negative = deduction</span></label>
                <input type="number" name="admin_adjustment" value="{{ $slip->admin_adjustment }}" step="0.01" class="input-dark w-full">
            </div>
            <div>
                <label class="block text-gray-400 text-sm mb-2">Adjustment Note (optional)</label>
                <textarea name="adjustment_note" rows="2" class="input-dark w-full" placeholder="e.g. Performance bonus, special deduction...">{{ $slip->adjustment_note }}</textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="btn-gold px-6 py-2 rounded-lg">
                    <i class="fas fa-save mr-2"></i>Update & Recalculate
                </button>
                <form action="{{ route('admin.salary.approve', $slip) }}" method="POST" class="inline">
                    @csrf
                    <button class="btn-gold px-6 py-2 rounded-lg" style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                        <i class="fas fa-check mr-2"></i>Approve Now
                    </button>
                </form>
            </div>
        </form>
    </div>
    @endif

</div>
@endsection
