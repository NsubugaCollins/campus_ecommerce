@extends('emails.layout')

@section('content')
<h1>Order Confirmed! 🛍️</h1>

<p>Hi <span class="highlight">{{ $order->user->name }}</span>, your order has been received and is being processed. Here's a summary:</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Order ID</span>
        <span class="info-value highlight">#{{ $order->id }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value"><span class="badge badge-warning">Pending</span></span>
    </div>
    <div class="info-row">
        <span class="info-label">Payment Method</span>
        <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Shipping Address</span>
        <span class="info-value">{{ $order->shipping_address }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Total Amount</span>
        <span class="info-value highlight">UGX {{ number_format($order->total_amount) }}</span>
    </div>
</div>

<p><strong style="color:#e2e8f0;">Items Ordered:</strong></p>
<div class="info-box" style="margin-top:10px;">
    @foreach($order->items as $item)
    <div class="info-row">
        <span class="info-label">{{ $item->product->name ?? 'Product' }} × {{ $item->quantity }}</span>
        <span class="info-value">UGX {{ number_format($item->price * $item->quantity) }}</span>
    </div>
    @endforeach
</div>

<div class="divider"></div>

<p>We'll notify you once your order is on its way. You can track your order at any time from your dashboard.</p>

<a href="{{ route('orders.index') }}" class="btn">View My Orders →</a>
@endsection
