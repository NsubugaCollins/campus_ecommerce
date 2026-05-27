<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Cycle' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', Arial, sans-serif;
            background-color: #0f0f13;
            color: #e2e8f0;
            padding: 30px 16px;
        }

        .wrapper {
            max-width: 580px;
            margin: 0 auto;
        }

        /* Header */
        .header {
            background: linear-gradient(135deg, #DC143C 0%, #b01030 50%, #B87333 100%);
            border-radius: 16px 16px 0 0;
            padding: 32px 40px 28px;
            text-align: center;
        }

        .logo {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: 2px;
            color: #ffffff;
            text-decoration: none;
        }

        .logo span {
            color: #B87333;
            font-weight: 900;
        }

        /* Body card */
        .card {
            background: #1a1a2e;
            border: 1px solid rgba(220, 20, 60, 0.15);
            border-top: none;
            padding: 40px;
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 12px;
        }

        p {
            font-size: 15px;
            line-height: 1.7;
            color: #94a3b8;
            margin-bottom: 14px;
        }

        .highlight {
            color: #B87333;
            font-weight: 600;
        }

        /* Info table */
        .info-box {
            background: rgba(220, 20, 60, 0.04);
            border: 1px solid rgba(220, 20, 60, 0.2);
            border-radius: 10px;
            padding: 20px 24px;
            margin: 24px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 8px 0;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            font-size: 14px;
        }

        .info-row:last-child { border-bottom: none; }

        .info-label {
            color: #64748b;
            font-weight: 500;
            flex-shrink: 0;
            margin-right: 16px;
        }

        .info-value {
            color: #e2e8f0;
            font-weight: 500;
            text-align: right;
        }

        /* Status badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .badge-success { background: rgba(16,185,129,0.15); color: #10b981; border: 1px solid rgba(16,185,129,0.3); }
        .badge-warning { background: rgba(245,158,11,0.15); color: #f59e0b; border: 1px solid rgba(245,158,11,0.3); }
        .badge-danger  { background: rgba(239,68,68,0.15);  color: #ef4444; border: 1px solid rgba(239,68,68,0.3); }
        .badge-info    { background: rgba(99,102,241,0.15); color: #818cf8; border: 1px solid rgba(99,102,241,0.3); }

        /* CTA Button */
        .btn {
            display: inline-block;
            margin-top: 8px;
            padding: 13px 30px;
            background: linear-gradient(135deg, #DC143C, #B87333);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        /* Points pill */
        .points-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, rgba(220,20,60,0.1), rgba(184,115,51,0.1));
            border: 1px solid rgba(220,20,60,0.3);
            border-radius: 50px;
            padding: 8px 18px;
            font-size: 16px;
            font-weight: 700;
            color: #B87333;
            margin: 16px 0;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 28px 0;
        }

        /* Footer */
        .footer {
            background: #13131f;
            border: 1px solid rgba(220,20,60,0.12);
            border-top: none;
            border-radius: 0 0 16px 16px;
            padding: 24px 40px;
            text-align: center;
        }

        .footer p {
            font-size: 12px;
            color: #475569;
            margin-bottom: 4px;
        }

        .footer a { color: #DC143C; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div class="logo">CYCLE<span>.</span></div>
    </div>

    <!-- Card Body -->
    <div class="card">
        @yield('content')
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>© {{ date('Y') }} Cycle – Campus Marketplace. All rights reserved.</p>
        <p>This email was sent to you because you have an account on <a href="{{ config('app.url') }}">Cycle</a>.</p>
    </div>
</div>
</body>
</html>
