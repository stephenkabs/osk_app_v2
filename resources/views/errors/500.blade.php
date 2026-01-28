<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>500 • Server Error</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

    <style>
        :root {
            --bg: #f5f5f7;
            --card: #ffffff;
            --ink: #1d1d1f;
            --muted: #6e6e73;
            --danger: #ff3b30;
            --radius: 22px;
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

        .card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 44px 36px;
            width: min(440px, 92%);
            text-align: center;
            box-shadow:
                0 30px 80px rgba(0,0,0,.08),
                0 8px 24px rgba(0,0,0,.05);
            animation: fadeUp .45s ease;
        }

        @keyframes fadeUp {
            from { transform: translateY(18px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .icon {
            width: 86px;
            height: 86px;
            margin: 0 auto 22px;
            border-radius: 50%;
            background: linear-gradient(135deg,#ffe4e6,#fecdd3);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pop .6s ease;
        }

        @keyframes pop {
            0% { transform: scale(.6); opacity: 0; }
            70% { transform: scale(1.08); }
            100% { transform: scale(1); opacity: 1; }
        }

        .icon i {
            font-size: 34px;
            color: var(--danger);
        }

        h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 6px;
        }

        p {
            font-size: 15px;
            color: var(--muted);
            margin-bottom: 30px;
            line-height: 1.6;
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

        .note {
            margin-top: 22px;
            font-size: 12px;
            color: #9b9ba0;
        }
    </style>
</head>
<body>

<div class="card">
    <div class="icon">
        <i class="fas fa-bug"></i>
    </div>

    <h1>Something Went Wrong</h1>
    <p>
        An unexpected error occurred on the server.<br>
        Our team has been notified.
    </p>

    <div class="actions">
        <a href="{{ url()->previous() }}" class="btn btn-primary">Go Back</a>
        <a href="/dashboard" class="btn btn-secondary">Dashboard</a>
    </div>

    <div class="note">
        Error 500 • Frameworx
    </div>
</div>

</body>
</html>
