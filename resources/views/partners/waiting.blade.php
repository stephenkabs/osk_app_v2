<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Waiting Approval • {{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">

  <style>
    body {
      background: #0b0c0f;
      font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      color: #fff;
    }
    .apple-card {
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(20px) saturate(180%);
      -webkit-backdrop-filter: blur(20px) saturate(180%);
      border-radius: 22px;
      box-shadow: 0 8px 32px rgba(0,0,0,0.45);
      padding: 40px 30px;
      width: 100%;
      max-width: 500px;
      text-align: center;
      border: 1px solid rgba(255,255,255,0.15);
    }
    .apple-icon {
      font-size: 56px;
      margin-bottom: 20px;
      color: #de9b0a; /* Blue tone for waiting */
    }
    .apple-title {
      font-size: 26px;
      font-weight: 800;
      margin-bottom: 14px;
    }
    .apple-sub {
      color: #d1d1d6;
      font-size: 15px;
      line-height: 1.6;
      margin-bottom: 30px;
    }
    .logout-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #de9b0a;
      color: #fff;
      font-weight: 700;
      border: none;
      border-radius: 12px;
      padding: 12px 20px;
      font-size: 14px;
      cursor: pointer;
      transition: background .2s ease;
      text-decoration: none;
    }
    .logout-btn:hover {
      background: #b78008;
    }
    .apple-footer {
      margin-top: 20px;
      font-size: 12px;
      color: #8e8e93;
    }
  </style>
</head>
<body>
  <div class="apple-card">
    <div class="apple-icon">
      <i class="fa-solid fa-clock"></i>
    </div>
    <h1 class="apple-title">Application Under Review</h1>
    <p class="apple-sub">
      Hey {{ auth()->user()->name }}, your application has been submitted successfully.
      Our team is reviewing it and you’ll be notified once it’s approved.
    </p>

    <!-- Logout -->
    <a href="/dashboard" class="logout-btn"
      >
       Dashboard
    </a>


    <p class="apple-footer">Powered by AwCloud Technologies</p>
  </div>
</body>
</html>
