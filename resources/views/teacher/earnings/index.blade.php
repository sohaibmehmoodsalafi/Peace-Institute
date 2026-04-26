@extends('layouts.dashboard')
@section('title', 'My Earnings')
@section('page-title', 'Earnings & Payouts')
@section('page-subtitle', 'Track your income and request payouts')

@section('sidebar-nav')
    <a href="{{ route('teacher.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-home"></i></span> Dashboard</a>
    <a href="{{ route('teacher.sessions') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-check"></i></span> Sessions</a>
    <a href="{{ route('teacher.sessions.history') }}" class="sidebar-link"><span class="icon"><i class="fas fa-history"></i></span> History</a>
    <a href="{{ route('teacher.availability') }}" class="sidebar-link"><span class="icon"><i class="fas fa-clock"></i></span> Availability</a>
    <a href="{{ route('teacher.earnings') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
@endsection

@section('content')

{{-- Summary Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card p-5">
        <div class="text-xs text-gray-500 uppercase mb-2">This Month</div>
        <div class="text-2xl font-bold text-gold-DEFAULT">${{ number_format($monthlySummary['net_earnings'], 2) }}</div>
        <div class="text-xs text-gray-600 mt-1">{{ $monthlySummary['total_sessions'] }} sessions · {{ number_format($monthlySummary['total_hours'], 1) }}h</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-xs text-gray-500 uppercase mb-2">Gross Earned</div>
        <div class="text-2xl font-bold text-white">${{ number_format($monthlySummary['gross_earnings'], 2) }}</div>
        <div class="text-xs text-gray-600 mt-1">Before platform fee</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-xs text-gray-500 uppercase mb-2">Platform Fee</div>
        <div class="text-2xl font-bold text-red-400">${{ number_format($monthlySummary['platform_fee_total'], 2) }}</div>
        <div class="text-xs text-gray-600 mt-1">15% commission</div>
    </div>
    <div class="stat-card p-5">
        <div class="text-xs text-gray-500 uppercase mb-2">Pending Payout</div>
        <div class="text-2xl font-bold text-green-400">${{ number_format($teacher->pending_payout, 2) }}</div>
        <div class="text-xs text-gray-600 mt-1">Available to withdraw</div>
    </div>
</div>

{{-- Salary Formula Explainer --}}
<div class="card p-5 mb-6 border-l-4 border-gold-DEFAULT">
    <div class="flex items-center gap-3">
        <i class="fas fa-calculator text-gold-DEFAULT text-xl"></i>
        <div>
            <div class="text-white font-semibold text-sm">Salary Calculation Formula</div>
            <div class="text-gray-400 text-sm mt-1">
                <span class="font-mono bg-black/40 px-2 py-0.5 rounded text-gold-DEFAULT">
                    Earned = (Session Duration ÷ 60) × Hourly Rate
                </span>
                &nbsp;·&nbsp; Your rate: <strong class="text-gold-DEFAULT">${{ number_format($teacher->hourly_rate, 2) }}/hr</strong>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Earnings Table --}}
    <div class="lg:col-span-2">

        {{-- Filter --}}
        <div class="flex gap-3 mb-4">
            <form action="" method="GET" class="flex gap-2">
                <select name="month" class="input-dark">
                    @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                    @endforeach
                </select>
                <select name="year" class="input-dark">
                    @foreach(range(now()->year, now()->year-3) as $y)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>{{ $y }}</option>
                    @endforeach
                </select>
                <button class="btn-gold px-4 py-2 rounded-lg">Go</button>
            </form>
        </div>

        <div class="card overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                        <th class="text-left px-5 py-4">Date</th>
                        <th class="text-left px-5 py-4">Student</th>
                        <th class="text-left px-5 py-4">Duration</th>
                        <th class="text-left px-5 py-4">Rate</th>
                        <th class="text-left px-5 py-4">Gross</th>
                        <th class="text-left px-5 py-4">Net</th>
                        <th class="text-left px-5 py-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($earnings as $earning)
                        <tr class="table-row">
                            <td class="px-5 py-3 text-gray-400 text-xs">{{ $earning->created_at->format('M d') }}</td>
                            <td class="px-5 py-3 text-gray-300">{{ $earning->booking->student->user->name }}</td>
                            <td class="px-5 py-3 text-gray-400">{{ number_format($earning->session_duration_hours, 2) }}h</td>
                            <td class="px-5 py-3 text-gray-400">${{ number_format($earning->hourly_rate, 2) }}</td>
                            <td class="px-5 py-3 text-gray-300">${{ number_format($earning->amount, 2) }}</td>
                            <td class="px-5 py-3 text-gold-DEFAULT font-semibold">${{ number_format($earning->net_amount, 2) }}</td>
                            <td class="px-5 py-3"><span class="badge badge-{{ $earning->status === 'pending' ? 'pending' : ($earning->status === 'approved' ? 'approved' : 'completed') }}">{{ $earning->status }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-5 py-10 text-center text-gray-600">No earnings for this period.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $earnings->links() }}</div>
    </div>

    {{-- Payout Request --}}
    <div>
        <div class="card p-6 mb-4">
            <h3 class="text-white font-semibold mb-4">Request Payout</h3>
            <div class="text-3xl font-bold text-gold-DEFAULT mb-1">${{ number_format($teacher->pending_payout, 2) }}</div>
            <div class="text-gray-500 text-xs mb-5">Available balance</div>

            <form action="{{ route('teacher.earnings.payout') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Amount</label>
                    <input type="number" name="amount" max="{{ $teacher->pending_payout }}" min="10" step="0.01"
                        value="{{ $teacher->pending_payout }}" class="input-dark w-full">
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Payment Method</label>
                    <select name="payment_method" class="input-dark w-full">
                        <option>Bank Transfer</option>
                        <option>PayPal</option>
                        <option>Wise</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-2">Account Details</label>
                    <textarea name="payment_details" rows="2" class="input-dark w-full" placeholder="Account number, email, etc."></textarea>
                </div>
                <button class="btn-gold w-full py-3 rounded-xl">Request Payout</button>
            </form>
        </div>

        {{-- Yearly chart data --}}
        <div class="card p-5">
            <h4 class="text-white font-medium mb-4 text-sm">Yearly Breakdown ({{ $year }})</h4>
            <div class="space-y-2">
                @foreach($yearlyBreakdown as $m => $amount)
                    @if($amount > 0)
                        <div class="flex items-center gap-2 text-xs">
                            <span class="text-gray-500 w-8">{{ date('M', mktime(0,0,0,$m,1)) }}</span>
                            <div class="flex-1 h-2 bg-gray-800 rounded-full overflow-hidden">
                                <div class="h-full gold-gradient rounded-full"
                                    style="width: {{ $yearlyBreakdown->max() > 0 ? ($amount / $yearlyBreakdown->max() * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-gold-DEFAULT w-16 text-right">${{ number_format($amount, 0) }}</span>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
