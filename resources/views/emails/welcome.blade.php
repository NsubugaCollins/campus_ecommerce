@extends('emails.layout')

@section('content')
<h1>Welcome to Cycle, {{ $user->name }}! 🎉</h1>

<p>Your account is all set. You can now browse, buy, and sell pre-loved campus items — all in one place.</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Name</span>
        <span class="info-value">{{ $user->name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Email</span>
        <span class="info-value">{{ $user->email }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Your Referral Code</span>
        <span class="info-value highlight">{{ $user->referral_code }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Starting Points</span>
        <span class="info-value">
            <span class="badge badge-info">{{ $user->points }} pts</span>
        </span>
    </div>
</div>

<p>Share your referral code with friends — you earn <span class="highlight">50 points</span> for every friend who signs up, and they get <span class="highlight">20 bonus points</span> to start!</p>

<div class="divider"></div>

<p style="margin-bottom: 6px;">Ready to explore the marketplace?</p>
<a href="{{ config('app.url') }}" class="btn">Start Shopping on Cycle →</a>
@endsection
