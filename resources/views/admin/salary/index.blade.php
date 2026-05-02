@extends('layouts.dashboard')
@section('title', 'Salary Management')
@section('page-title', 'Salary Management')
@section('page-subtitle', 'Generate, review and approve teacher salary slips')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.salary.index') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-file-invoice-dollar"></i></span> Salary Slips</a>
@endsection

@section('content')

{{-- Generate Form --}}
<div class="card p-5 mb-6">
    <h3 class="text-white font-semibold mb-4"><i class="fas fa-magic text-gold-DEFAULT mr-2"></i>Generate Monthly Salary Slips</h3>
    <form action="{{ route('admin.salary.generate') }}" method="POST" class="flex flex-wrap gap-3 items-end">
        @csrf
        <div>
            <label class="block text-gray-400 text-xs mb-1 uppercase tracking-wider">Month</label>
            <select name="month" class="input-dark">
                @foreach(range(1,12) as $m)
                    <option value="{{ $m }}" {{ $m == now()->month ? 'selected' : '' }}>
                        {{ date('F', mktime(0,0,0,$m,1)) }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-400 text-xs mb-1 uppercase tracking-wider">Year</label>
            <select name="year" class="input-dark">
                @foreach(range(now()->year, now()->year-2) as $y)
                    <option value="{{ $y }}">{{ $y }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-400 text-xs mb-1 uppercase tracking-wider">Teacher (optional)</label>
            <select name="teacher_id" class="input-dark">
                <option value="">All Teachers</option>
                @foreach($teachers as $t)
                    <option value="{{ $t->id }}">{{ $t->user->name }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn-gold px-5 py-2 rounded-lg">
            <i class="fas fa-cog mr-2"></i>Generate Slips
        </button>
    </form>
</div>

{{-- Filters --}}
<div class="flex flex-wrap gap-3 mb-5">
    <form method="GET" class="flex flex-wrap gap-2">
        <select name="month" class="input-dark">
            <option value="">All Months</option>
            @foreach(range(1,12) as $m)
                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                    {{ date('F', mktime(0,0,0,$m,1)) }}
                </option>
            @endforeach
        </select>
        <select name="year" class="input-dark">
            <option value="">All Years</option>
            @foreach(range(now()->year, now()->year-3) as $y)
                <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
        <select name="status" class="input-dark">
            <option value="">All Status</option>
            @foreach(['draft','approved','paid'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <select name="teacher_id" class="input-dark">
            <option value="">All Teachers</option>
            @foreach($teachers as $t)
                <option value="{{ $t->id }}" {{ request('teacher_id') == $t->id ? 'selected' : '' }}>
                    {{ $t->user->name }}
                </option>
            @endforeach
        </select>
        <button class="btn-gold px-4 py-2 rounded-lg">Filter</button>
        @if(request()->hasAny(['month','year','status','teacher_id']))
            <a href="{{ route('admin.salary.index') }}" class="btn-outline px-4 py-2 rounded-lg">Clear</a>
        @endif
    </form>
</div>

{{-- Table --}}
<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                    <th class="text-left px-5 py-4">Teacher</th>
                    <th class="text-left px-5 py-4">Period</th>
                    <th class="text-left px-5 py-4">Fixed Salary</th>
                    <th class="text-left px-5 py-4">Classes</th>
                    <th class="text-left px-5 py-4">Deduction</th>
                    <th class="text-left px-5 py-4">Net Salary</th>
                    <th class="text-left px-5 py-4">Status</th>
                    <th class="text-left px-5 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($slips as $slip)
                <tr class="table-row">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#1A6B3C,#22874D);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;color:#fff;flex-shrink:0">
                                {{ strtoupper(substr($slip->teacher->user->name ?? 'T', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white text-sm font-medium">{{ $slip->teacher->user->name ?? '—' }}</div>
                                <div class="text-gray-500 text-xs">{{ $slip->teacher->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4 text-gray-300">{{ $slip->period }}</td>
                    <td class="px-5 py-4 text-white font-semibold">${{ number_format($slip->fixed_salary, 2) }}</td>
                    <td class="px-5 py-4">
                        <span class="text-green-400">{{ $slip->conducted_classes }}</span>
                        <span class="text-gray-600">/</span>
                        <span class="text-gray-400">{{ $slip->target_classes }}</span>
                        @if($slip->missed_classes > 0)
                            <span class="text-red-400 text-xs ml-1">({{ $slip->missed_classes }} missed)</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-red-400">
                        {{ $slip->total_deduction > 0 ? '-$'.number_format($slip->total_deduction, 2) : '—' }}
                        @if($slip->admin_adjustment != 0)
                            <div class="text-xs text-yellow-400">
                                Adj: {{ $slip->admin_adjustment > 0 ? '+' : '' }}${{ number_format($slip->admin_adjustment, 2) }}
                            </div>
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-gold-DEFAULT font-bold text-base">${{ number_format($slip->net_salary, 2) }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <span class="badge badge-{{ $slip->status_color }}">{{ ucfirst($slip->status) }}</span>
                    </td>
                    <td class="px-5 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.salary.show', $slip) }}" class="btn-outline px-3 py-1 text-xs rounded-lg">
                                <i class="fas fa-eye mr-1"></i>View
                            </a>
                            @if($slip->status === 'draft')
                                <form action="{{ route('admin.salary.approve', $slip) }}" method="POST">
                                    @csrf
                                    <button class="btn-gold px-3 py-1 text-xs rounded-lg">
                                        <i class="fas fa-check mr-1"></i>Approve
                                    </button>
                                </form>
                            @elseif($slip->status === 'approved')
                                <form action="{{ route('admin.salary.pay', $slip) }}" method="POST">
                                    @csrf
                                    <button class="btn-gold px-3 py-1 text-xs rounded-lg" style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                                        <i class="fas fa-money-bill mr-1"></i>Mark Paid
                                    </button>
                                </form>
                            @else
                                <span class="text-green-400 text-xs"><i class="fas fa-check-circle mr-1"></i>Paid</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-5 py-16 text-center text-gray-600">
                        <i class="fas fa-file-invoice-dollar text-4xl mb-3 block opacity-20"></i>
                        No salary slips found. Generate slips above.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $slips->links() }}</div>

@endsection
