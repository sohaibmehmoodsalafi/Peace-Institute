@extends('layouts.dashboard')
@section('title', 'Teacher Payouts')
@section('page-title', 'Payouts')
@section('page-subtitle', 'Manage and process teacher payout requests')

@section('sidebar-nav')
    <a href="{{ route('admin.dashboard') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chart-line"></i></span> Dashboard</a>
    <a href="{{ route('admin.teachers.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-chalkboard-teacher"></i></span> Teachers</a>
    <a href="{{ route('admin.students.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-users"></i></span> Students</a>
    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-calendar-alt"></i></span> Bookings</a>
    <a href="{{ route('admin.earnings.index') }}" class="sidebar-link"><span class="icon"><i class="fas fa-dollar-sign"></i></span> Earnings</a>
    <a href="{{ route('admin.earnings.payouts') }}" class="sidebar-link active"><span class="icon"><i class="fas fa-money-bill-wave"></i></span> Payouts</a>
@endsection

@section('content')

{{-- Flash --}}
@if(session('success'))
    <div class="mb-5 px-4 py-3 rounded-lg bg-green-500/10 border border-green-500/20 text-green-400 text-sm">
        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
    </div>
@endif

{{-- Filter --}}
<div class="flex gap-3 mb-6">
    <form method="GET" class="flex gap-2">
        <select name="status" class="input-dark">
            <option value="">All Status</option>
            @foreach(['requested','approved','processing','completed','rejected'] as $s)
                <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
        <button class="btn-gold px-4 py-2 rounded-lg">Filter</button>
        @if(request('status'))
            <a href="{{ route('admin.earnings.payouts') }}" class="btn-outline px-4 py-2 rounded-lg">Clear</a>
        @endif
    </form>
</div>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 text-xs uppercase border-b border-white/5">
                    <th class="text-left px-5 py-4">Teacher</th>
                    <th class="text-left px-5 py-4">Amount</th>
                    <th class="text-left px-5 py-4">Method</th>
                    <th class="text-left px-5 py-4">Status</th>
                    <th class="text-left px-5 py-4">Requested</th>
                    <th class="text-left px-5 py-4">Processed</th>
                    <th class="text-left px-5 py-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $payout)
                <tr class="table-row">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#1A6B3C,#22874D);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.8rem;color:#fff;flex-shrink:0">
                                {{ strtoupper(substr($payout->teacher->user->name ?? 'T', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-white text-sm font-medium">{{ $payout->teacher->user->name ?? '—' }}</div>
                                <div class="text-gray-500 text-xs">{{ $payout->teacher->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="text-gold-DEFAULT font-semibold text-base">${{ number_format($payout->amount, 2) }}</span>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-sm">
                        {{ $payout->payment_method ? ucfirst(str_replace('_',' ',$payout->payment_method)) : '—' }}
                        @if($payout->payment_details)
                            @php $details = is_array($payout->payment_details) ? $payout->payment_details : json_decode($payout->payment_details, true); @endphp
                            @if(!empty($details['account']))
                                <div class="text-xs text-gray-600 font-mono">{{ $details['account'] }}</div>
                            @endif
                        @endif
                    </td>
                    <td class="px-5 py-4">
                        <span class="badge badge-{{
                            $payout->status === 'completed' ? 'approved' :
                            ($payout->status === 'rejected' ? 'cancelled' :
                            ($payout->status === 'requested' ? 'pending' : 'pending'))
                        }}">{{ ucfirst($payout->status) }}</span>
                    </td>
                    <td class="px-5 py-4 text-gray-400 text-xs">{{ $payout->created_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-4 text-gray-400 text-xs">{{ $payout->processed_at?->format('M d, Y') ?? '—' }}</td>
                    <td class="px-5 py-4">
                        @if(in_array($payout->status, ['requested','approved','processing']))
                            <button onclick="openPayoutModal({{ $payout->id }}, '{{ $payout->teacher->id }}', {{ $payout->amount }})"
                                class="btn-gold" style="font-size:.75rem;padding:5px 12px">
                                <i class="fas fa-paper-plane mr-1"></i>Process
                            </button>
                        @elseif($payout->status === 'completed')
                            <span class="text-green-400 text-xs"><i class="fas fa-check-circle mr-1"></i>Done</span>
                        @else
                            <span class="text-gray-600 text-xs">{{ ucfirst($payout->status) }}</span>
                        @endif
                        @if($payout->admin_notes)
                            <div class="text-gray-600 text-xs mt-1" title="{{ $payout->admin_notes }}">
                                <i class="fas fa-sticky-note mr-1"></i>Note
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-5 py-16 text-center text-gray-600">
                        <i class="fas fa-money-bill-wave text-4xl mb-3 block opacity-30"></i>
                        No payout requests found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-4">{{ $payouts->links() }}</div>

{{-- Process Payout Modal --}}
<div id="payout-modal" class="fixed inset-0 bg-black/70 z-50 hidden flex items-center justify-center">
    <div class="card rounded-2xl p-6 w-full max-w-sm mx-4">
        <h3 class="text-white font-semibold mb-1">Process Payout</h3>
        <p id="payout-amount-text" class="text-gold-DEFAULT text-lg font-bold mb-4"></p>
        <form id="payout-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="text-xs text-gray-500 uppercase tracking-wider block mb-2">Payment Method</label>
                <select name="payment_method" class="input-dark w-full" required>
                    <option value="bank_transfer">Bank Transfer</option>
                    <option value="easypaisa">EasyPaisa</option>
                    <option value="jazzcash">JazzCash</option>
                    <option value="paypal">PayPal</option>
                    <option value="western_union">Western Union</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="text-xs text-gray-500 uppercase tracking-wider block mb-2">Admin Notes (optional)</label>
                <textarea name="admin_notes" rows="2" class="input-dark w-full" placeholder="Transaction ID, notes..."></textarea>
            </div>
            <input type="hidden" name="earning_ids" id="payout-earning-ids" value="[]">
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('payout-modal').classList.add('hidden')"
                    class="btn-outline flex-1 py-2 rounded-lg">Cancel</button>
                <button type="submit" class="btn-gold flex-1 py-2 rounded-lg">Confirm Payout</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openPayoutModal(payoutId, teacherId, amount) {
    document.getElementById('payout-form').action = '/admin/earnings/payouts/' + teacherId;
    document.getElementById('payout-amount-text').textContent = '$' + parseFloat(amount).toFixed(2);
    document.getElementById('payout-modal').classList.remove('hidden');
}
</script>
@endpush
