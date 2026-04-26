@extends('layouts.app')
@section('title', 'Account Under Review – Peace Institute')

@push('styles')
<style>
.pending-page{
    min-height:100vh;
    display:flex;align-items:center;justify-content:center;
    padding:6rem 1.5rem;
    background:var(--cream);
}
.pending-card{
    max-width:520px;width:100%;
    background:#fff;
    border-radius:28px;
    box-shadow:0 4px 40px rgba(26,107,60,.1);
    border:1.5px solid #EDE9E0;
    padding:3rem 2.5rem;
    text-align:center;
}
.pending-icon{
    width:80px;height:80px;border-radius:50%;
    background:rgba(245,158,11,.08);
    border:2px solid rgba(245,158,11,.25);
    display:flex;align-items:center;justify-content:center;
    margin:0 auto 1.5rem;
}
.pending-icon i{font-size:2rem;color:#F59E0B;}
.pending-title{font-size:1.6rem;font-weight:800;color:var(--text);margin-bottom:.6rem;}
.pending-desc{color:var(--muted);font-size:.9rem;line-height:1.65;margin-bottom:2rem;}
.pending-desc strong{color:var(--green);font-weight:700;}

.steps-list{
    background:var(--creamd);
    border-radius:16px;padding:1.25rem 1.5rem;
    text-align:left;margin-bottom:2rem;
    display:flex;flex-direction:column;gap:.75rem;
}
.step-item{display:flex;align-items:center;gap:.75rem;font-size:.875rem;}
.step-item.done{color:var(--text);}
.step-item.active{color:var(--text);font-weight:600;}
.step-item.future{color:#9CA3AF;}
.step-dot{
    width:22px;height:22px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    flex-shrink:0;font-size:.65rem;
}
.step-dot.done{background:rgba(26,107,60,.15);color:var(--green);}
.step-dot.active{background:rgba(245,158,11,.15);color:#F59E0B;}
.step-dot.future{background:#E5E7EB;color:#9CA3AF;}

.logout-btn{
    display:inline-flex;align-items:center;gap:.5rem;
    padding:.75rem 2rem;border-radius:12px;
    border:1.5px solid var(--green);
    color:var(--green);font-weight:700;font-size:.9rem;
    background:transparent;cursor:pointer;
    transition:background .2s,color .2s;
}
.logout-btn:hover{background:var(--green);color:#fff;}
</style>
@endpush

@section('content')
<div class="pending-page">
    <div class="pending-card">

        <div class="pending-icon">
            <i class="fas fa-hourglass-half"></i>
        </div>

        <h1 class="pending-title">Account Under Review</h1>
        <p class="pending-desc">
            Your teacher application for <strong>Peace Institute</strong> is being reviewed by our admin team.
            You'll receive an email notification once approved — usually within 24 hours.
        </p>

        <div class="steps-list">
            <div class="step-item done">
                <div class="step-dot done"><i class="fas fa-check"></i></div>
                Application submitted
            </div>
            <div class="step-item active">
                <div class="step-dot active"><i class="fas fa-spinner fa-spin"></i></div>
                Admin review in progress
            </div>
            <div class="step-item future">
                <div class="step-dot future"><i class="fas fa-circle"></i></div>
                Account activation
            </div>
            <div class="step-item future">
                <div class="step-dot future"><i class="fas fa-circle"></i></div>
                Start teaching on Peace Institute
            </div>
        </div>

        <p style="font-size:.8rem;color:var(--muted);margin-bottom:1.5rem;">
            Questions? Contact us on
            <a href="https://wa.me/923022702808" target="_blank" style="color:var(--green);font-weight:600;">WhatsApp</a>
            — we're happy to help.
        </p>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </button>
        </form>

    </div>
</div>
@endsection
