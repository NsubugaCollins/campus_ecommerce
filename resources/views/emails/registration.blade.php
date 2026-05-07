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
        .footer {
            background-color: #f9f9f9;
            color: #777777;
            padding: 20px;
            text-align: center;
            font-size: 12px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #b87333; /* Copper */
            color: #ffffff;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Cycle!</h1>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            <p>Thank you for joining Cycle. We're excited to have you on board!</p>
            <p>You can now start shopping for the best deals on campus. Explore our wide range of products and enjoy a seamless shopping experience.</p>
            <a href="{{ url('/') }}" class="button">Start Shopping</a>
            <p>If you have any questions, feel free to reply to this email.</p>
            <p>Best regards,<br>The Cycle Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Cycle. All rights reserved.
        </div>
    </div>
</body>
</html>
