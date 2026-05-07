<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #800000; /* Crimson */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
            color: #333333;
            line-height: 1.6;
        }
        .order-details {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .order-details th, .order-details td {
            padding: 10px;
            border-bottom: 1px solid #eeeeee;
            text-align: left;
        }
        .order-details th {
            background-color: #f9f9f9;
        }
        .total {
            font-weight: bold;
            font-size: 18px;
            text-align: right;
            margin-top: 10px;
        }
        .footer {
            background-color: #f9f9f9;
            color: #777777;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Order Confirmed!</h1>
        </div>
        <div class="content">
            <p>Hi {{ $order->user->name }},</p>
            <p>Thank you for your order! We've received it and are processing it now.</p>
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p><strong>Delivery Address:</strong> {{ $order->shipping_address }}</p>

            <table class="order-details">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>UGX {{ number_format($item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total">
                Total: UGX {{ number_format($order->total_amount, 2) }}
            </div>

            <p>We'll notify you when your order is on its way.</p>
            <p>Best regards,<br>The Cycle Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Cycle. All rights reserved.
        </div>
    </div>
</body>
</html>
