<!DOCTYPE html>
<html>
<head>
    <title>Payment Receipt</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .header { background-color: #28a745; color: white; padding: 15px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { padding: 20px; }
        .order-details { background-color: #f9f9f9; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; margin-top: 20px; font-size: 0.8em; color: #777; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #0d6efd; color: white; text-decoration: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Payment Successful!</h2>
        </div>
        <div class="content">
            <p>Dear {{ $order->user->name }},</p>
            <p>We have successfully received your payment. Thank you for shopping with us!</p>
            
            <div class="order-details">
                <h3>Receipt Details</h3>
                <p><strong>Order Number:</strong> #{{ $order->id }}</p>
                <p><strong>Amount Paid:</strong> UGX {{ number_format($order->total_amount) }}</p>
                <p><strong>Payment Method:</strong> PayPal (ID: {{ $order->paypal_order_id }})</p>
                <p><strong>Date:</strong> {{ $order->updated_at->format('M d, Y H:i') }}</p>
            </div>
            
            <p>Your order is now being processed. We will notify you once it's on its way!</p>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/') }}" class="btn">Continue Shopping</a>
            </div>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Cycle Mall. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
