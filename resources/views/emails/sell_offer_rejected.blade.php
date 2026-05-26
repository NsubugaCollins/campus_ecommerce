@extends('emails.layout')

@section('content')
<h1>Update on Your Sell Request</h1>

<p>Hi <span class="highlight">{{ $userSale->user->name }}</span>, thank you for submitting your item to Cycle. After careful review, we're unable to accept your sell request at this time.</p>

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
        <span class="info-label">Status</span>
        <span class="info-value"><span class="badge badge-danger">Rejected</span></span>
    </div>
</div>

@if($userSale->admin_notes)
<p><strong style="color:#e2e8f0;">Reason from our team:</strong></p>
<div class="info-box" style="margin-top:8px; font-style:italic; color:#94a3b8;">
    {{ $userSale->admin_notes }}
</div>
@endif

<div class="divider"></div>

<p>Don't be discouraged! You're welcome to submit a different item or adjust the details and try again. We're always looking for quality campus items to list on Cycle.</p>

<a href="{{ config('app.url') }}/user-sales/create" class="btn">Submit Another Item →</a>
@endsection
