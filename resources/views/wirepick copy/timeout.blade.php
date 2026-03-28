<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Timeout</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #042e72;
            --accent: #ff8c00;
            --text-dark: #1a1a1a;
            --text-muted: #6a6a6a;
        }

        body {
            margin: 0;
            font-family: "Inter", sans-serif;
            background: linear-gradient(160deg, #f4f4f4, #eaeaea);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            color: var(--text-dark);
        }

        .card {
            width: 380px;
            padding: 46px 36px;
            border-radius: 26px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(14px);
            box-shadow:
                0 18px 40px rgba(0,0,0,0.12),
                0 3px 6px rgba(0,0,0,0.05);
            text-align: center;
            animation: fadeIn 0.7s ease-out, floatUp 1.2s ease-out;
            border: 1px solid rgba(255,255,255,0.6);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes floatUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .wp-logo {
            width: 100px;
            margin-bottom: 20px;
            /* filter: drop-shadow(0 3px 6px rgba(0,0,0,0.12)); */
        }

        h2 {
            font-size: 24px;
            margin-bottom: 10px;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        p {
            font-size: 15px;
            line-height: 1.65;
            color: var(--text-muted);
            margin-bottom: 32px;
        }

        .btn {
            padding: 13px 24px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), var(--primary));
            color: #fff;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 6px 20px rgba(0,0,0,0.18);
            transition: 0.25s ease;
        }

        .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 26px rgba(0,0,0,0.25);
        }

        .subtle {
            font-size: 13px;
            color: #8b8b8b;
            margin-top: 20px;
            animation: fadeIn 1.8s ease;
        }

        .pulse {
            color: var(--accent);
            animation: pulse 1.6s infinite ease-in-out;
            font-weight: 600;
        }

        @keyframes pulse {
            0% { opacity: 0.45; }
            50% { opacity: 1; }
            100% { opacity: 0.45; }
        }

    </style>
</head>

<body>

<div class="card">

    <img src="/wirelogo.png" class="wp-logo">

    <h2>Payment Timeout</h2>

    <p>
        {{ $message ?? "Your payment request is taking longer than expected." }}
        <br>
        <span class="pulse">Please wait or try again.</span>
    </p>

    <a href="{{ $retryUrl }}" class="btn">Try Again</a>

    <div class="subtle">
        ⚠️ Ensure your internet connection is stable.
    </div>
</div>

</body>
</html>
