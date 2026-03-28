<!DOCTYPE html>
<html>
<head>
    <title>Wirepick Token Status</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 40px;
        }

        .card {
            max-width: 520px;
            margin: auto;
            background: #ffffff;
            padding: 25px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }

        h2 {
            margin-top: 0;
            font-size: 22px;
            font-weight: 700;
            color: #111;
        }

        .label {
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
            color: #555;
        }

        .value {
            font-size: 15px;
            font-weight: 500;
            background: #f0f2f5;
            padding: 12px;
            border-radius: 12px;
            word-break: break-all;
        }

        .status-valid {
            background: #e8fff0;
            color: #0f9d58;
            padding: 8px 14px;
            border-radius: 12px;
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
        }

        .status-expired {
            background: #ffe8e8;
            color: #d93025;
            padding: 8px 14px;
            border-radius: 12px;
            display: inline-block;
            font-size: 14px;
            font-weight: 600;
        }

        .refresh-btn {
            margin-top: 20px;
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #007aff, #0051a8);
            color: white;
            border-radius: 14px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
        }
    </style>
</head>

<body>

<div class="card">
    <h2>Wirepick Access Token</h2>

    @if (!$token)
        <p>No token found.</p>
    @else

        <div class="label">Status</div>
        <div class="{{ $status === 'VALID' ? 'status-valid' : 'status-expired' }}">
            {{ $status }}
        </div>

        <div class="label">Access Token</div>
        <div class="value">{{ $token->access_token }}</div>

        <div class="label">Expires At</div>
        <div class="value">{{ $token->expires_at }}</div>

        <div class="label">Minutes Remaining</div>
        <div class="value">
            {{ $minutes_remaining > 0 ? $minutes_remaining . ' min' : 'Expired' }}
        </div>

    @endif

    <a href="{{ route('wirepick.token.refresh.manual') }}" class="refresh-btn">Refresh Token</a>
</div>

</body>
</html>
