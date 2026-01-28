<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    <!-- Font Awesome CDN for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle, rgba(167, 167, 167, 0.8) 0%, rgba(255, 255, 255, 0.8) 100%);
            background-size: cover;
            background-repeat: no-repeat;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .card .icon {
            color: rgb(212, 8, 8);
            font-size: 50px;
            margin-bottom: 20px;
            animation: scaleUp 1s ease-in-out;
        }
        @keyframes scaleUp {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
                opacity: 0.7;
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        .card h2 {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 10px;
        }
        .card p {
            font-size: 16px;
            color: #848484;
            margin: 0 0 20px;
        }
        .card .back-button {
            text-decoration: none;
            color: #555;
            display: inline-block;
            font-size: 14px;
            border: 1px solid #ddd;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }
        .card .back-button:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body class="font-body">

    {{-- <div style="background-image: url(/login_style/images/login-bg-3.jpg);"> --}}
        <div class="bg-cover bg-center relative bg-no-repeat"  style="
        position: fixed;
        top: -2.5%;
        left: -2.5%;
        width: 105%;
        height: 105%;
        background:
            @php
                $latestBackground = $background->where('type', 'login_background')->sortByDesc('created_at')->first();
            @endphp
            @if ($latestBackground)
                url('{{ asset('background_images/' . $latestBackground->image) }}') no-repeat center center;
            @else
                /* radial-gradient(circle, rgb(17, 92, 122) 30%, rgba(10,37,83,1) 100%); */
                 url('{{ asset('assets/defult-01.webp' ) }}') no-repeat center center;
            @endif
        background-size: cover;
        filter: blur(0px);
        z-index: -2;">
    </div>

    <div class="card">
        <div class="icon"><i class="fas fa-times-circle"></i></div>

        <h2>Access Restricted</h2>
        <p>Sorry, this page is only accessible between 08:30 AM and 1:00 PM.
        {{-- @foreach ($displaySettings as $setting )
        <span style="font-size: 12px">{{ $setting->email  }} | {{ $setting->phone  }} </span><br>
        @endforeach --}}
    </p>

        <a href="/attendance" class="back-button">Log Book</a>
    </div>
</body>
</html>
