<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Session Expired • Frameworx</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #f5f5f7;
            --card: #ffffff;
            --ink: #1d1d1f;
            --muted: #6e6e73;
            --accent: #ff9f0a;
            --radius: 22px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text",
                         "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--ink);
        }

        .apple-card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 44px 36px;
            width: min(440px, 92%);
            text-align: center;
            box-shadow:
                0 30px 80px rgba(0,0,0,.08),
                0 8px 24px rgba(0,0,0,.05);
            animation: lift .45s ease;
        }

        @keyframes lift {
            from {
                transform: translateY(18px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .icon-wrap {
            width: 86px;
            height: 86px;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: linear-gradient(135deg,#fff7ed,#ffedd5);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pop .55s ease;
        }

        @keyframes pop {
            0% { transform: scale(.6); opacity: 0; }
            70% { transform: scale(1.08); }
            100% { transform: scale(1); opacity: 1; }
        }

        .icon-wrap i {
            font-size: 34px;
            color: var(--accent);
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            letter-spacing: -0.02em;
            margin: 0 0 6px;
        }

        p {
            font-size: 15px;
            line-height: 1.6;
            color: var(--muted);
            margin: 0 0 30px;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            border-radius: 999px;
            padding: 11px 22px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: all .2s ease;
        }

        .btn-primary {
            background: #000;
            color: #fff;
            box-shadow: 0 10px 24px rgba(0,0,0,.18);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 34px rgba(0,0,0,.28);
        }

        .btn-secondary {
            background: #f5f5f7;
            color: #1d1d1f;
        }

        .btn-secondary:hover {
            background: #eaeaef;
        }

        .footer-note {
            margin-top: 22px;
            font-size: 12px;
            color: #9b9ba0;
        }
    </style>
</head>
<body>

    <div class="apple-card">
        <div class="icon-wrap">
            <i class="fas fa-hourglass-end"></i>
        </div>

        <h1>Session Expired</h1>
        <p>
            Your session has expired for security reasons.<br>
            Please sign in again to continue.
        </p>

        <div class="actions">
            <a href="{{ route('login') }}" class="btn btn-primary">
                Sign In Again
            </a>
            <a href="{{ url('/') }}" class="btn btn-secondary">
                Go Home
            </a>
        </div>

        <div class="footer-note">
            Error 419 • Frameworx
        </div>
    </div>

</body>
</html>
