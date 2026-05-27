@extends('emails.layout')

@section('content')
<h1>You Used Your Points! ⚡</h1>

<p>Hi <span class="highlight">{{ $user->name }}</span>, you redeemed loyalty points on your latest order. Here's the breakdown:</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Points Redeemed</span>
        <span class="info-value">
            <div class="points-pill">⭐ {{ number_format($pointsUsed) }} pts</div>
        </span>
    </div>
    <div class="info-row">
        <span class="info-label">Discount Applied</span>
        <span class="info-value highlight">− UGX {{ number_format($discount) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Remaining Points</span>
        <span class="info-value"><span class="badge badge-info">{{ number_format($remainingPoints) }} pts</span></span>
    </div>
</div>

<p>Keep shopping to earn more points! Every <span class="highlight">UGX 5,000</span> you spend earns you <span class="highlight">1 point</span>, and every <span class="highlight">100 points</span> gives you a <span class="highlight">UGX 1,000 discount</span>.</p>

<div class="divider"></div>

<p>You can also earn more by referring friends using your code: <span class="highlight">{{ $user->referral_code }}</span></p>

<a href="{{ url('/') }}" class="btn">Continue Shopping →</a>
@endsection
