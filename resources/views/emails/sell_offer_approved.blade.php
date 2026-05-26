@extends('emails.layout')

@section('content')
<h1>We Have an Offer for Your Item! 💰</h1>

<p>Hi <span class="highlight">{{ $userSale->user->name }}</span>, our team has reviewed your sell request and we're excited to make you an offer!</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Item</span>
        <span class="info-value">{{ $userSale->product_name }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Category</span>
        <span class="info-value">{{ $userSale->category }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Condition</span>
        <span class="info-value">{{ $userSale->condition }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Your Expected Price</span>
        <span class="info-value">UGX {{ number_format($userSale->expected_price) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Our Offer</span>
        <span class="info-value highlight" style="font-size:17px;">UGX {{ number_format($userSale->offered_price) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value"><span class="badge badge-info">Offer Made</span></span>
    </div>
</div>

@if($userSale->admin_notes)
<p><strong style="color:#e2e8f0;">Note from our team:</strong></p>
<div class="info-box" style="margin-top:8px; font-style:italic; color:#94a3b8;">
    {{ $userSale->admin_notes }}
</div>
@endif

<p>Log in to your account to <span class="highlight">accept or decline</span> this offer. The offer is time-sensitive, so don't wait too long!</p>

<div class="divider"></div>

<a href="{{ config('app.url') }}/my-sales" class="btn">Review My Offer →</a>
@endsection
