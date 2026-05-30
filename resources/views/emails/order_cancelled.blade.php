@extends('emails.layout')

@section('content')
<h1>Order Cancelled ❌</h1>

<p>Hi <span class="highlight">{{ $order->user->name }}</span>, your order has been successfully cancelled. Here's a summary of the cancelled order:</p>

<div class="info-box">
    <div class="info-row">
        <span class="info-label">Order ID</span>
        <span class="info-value highlight">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Status</span>
        <span class="info-value"><span class="badge badge-danger">Cancelled</span></span>
    </div>
    <div class="info-row">
        <span class="info-label">Payment Method</span>
        <span class="info-value">{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Delivery Address</span>
        <span class="info-value">{{ $order->shipping_address }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Order Total</span>
        <span class="info-value highlight">UGX {{ number_format($order->total_amount) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Cancelled On</span>
        <span class="info-value">{{ now()->format('M d, Y \a\t H:i') }}</span>
    </div>
</div>

<p><strong style="color:#e2e8f0;">Items in Cancelled Order:</strong></p>
<div class="info-box" style="margin-top:10px;">
    @foreach($order->items as $item)
    <div class="info-row">
        <span class="info-label">{{ $item->product->name ?? 'Product' }} × {{ $item->quantity }}</span>
        <span class="info-value">UGX {{ number_format($item->price * $item->quantity) }}</span>
    </div>
    @endforeach
</div>

<div class="divider"></div>

<p>If you cancelled this order by mistake, you're welcome to place a new order anytime. If you have any questions or concerns, feel free to reach out to our support team.</p>

<a href="{{ route('home') }}" class="btn">Continue Shopping →</a>
@endsection
