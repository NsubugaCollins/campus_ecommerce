@extends('emails.layout')

@section('content')
<h1>Your Order Has Been Delivered! 🚀</h1>

<p>Hi <span class="highlight">{{ $order->user->name }}</span>, great news — your order has been marked as <strong style="color:#10b981;">delivered</strong>. We hope you love what you received!</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Order ID</span>
        <span class="info-value highlight">#{{ $order->id }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value"><span class="badge badge-success">Delivered</span></span>
    </div>
    <div class="info-row">
        <span class="info-label">Shipping Address</span>
        <span class="info-value">{{ $order->shipping_address }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Total Paid</span>
        <span class="info-value highlight">UGX {{ number_format($order->total_amount) }}</span>
    </div>
</div>

<p><strong style="color:#e2e8f0;">Items Delivered:</strong></p>
<div class="info-box" style="margin-top:10px;">
    @foreach($order->items as $item)
    <div class="info-row">
        <span class="info-label">{{ $item->product->name ?? 'Product' }} × {{ $item->quantity }}</span>
        <span class="info-value">UGX {{ number_format($item->price * $item->quantity) }}</span>
    </div>
    @endforeach
</div>

<div class="divider"></div>

<p>Did everything arrive in good condition? We'd love to hear from you. Leave a rating to help other shoppers on campus.</p>

<a href="{{ config('app.url') }}/user/orders" class="btn">Rate Your Order →</a>
@endsection
