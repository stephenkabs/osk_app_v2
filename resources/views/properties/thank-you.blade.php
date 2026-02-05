<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Lease Signed</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="/assets/images/favicon.png">

  <style>
    :root{
      --brand:#0f766e;
      --brand-soft:#ecfdf5;
      --ink:#111827;
      --muted:#6b7280;
      --border:#e5e7eb;
    }

    body{
      background:linear-gradient(180deg,#f8fafc,#eef2f7);
      font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text",
      "Segoe UI",Roboto,Helvetica,Arial,sans-serif;
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:24px;
    }

    .card-box{
      width:100%;
      max-width:520px;
      background:#fff;
      border-radius:24px;
      padding:40px 34px;
      box-shadow:0 40px 90px rgba(0,0,0,.14);
      text-align:center;
    }

    .icon{
      font-size:64px;
      color:var(--brand);
      margin-bottom:18px;
    }

    h1{
      font-weight:800;
      font-size:22px;
      margin-bottom:12px;
      color:var(--ink);
    }

    .subtitle{
      color:var(--muted);
      font-size:14px;
      margin-bottom:28px;
      line-height:1.6;
    }

    .info-box{
      background:var(--brand-soft);
      border:1px solid #d1fae5;
      border-radius:16px;
      padding:16px;
      font-size:13px;
      color:#065f46;
      margin-bottom:28px;
    }

    .btn-pill{
      border-radius:999px;
      padding:10px 26px;
      font-weight:700;
      font-size:14px;
      background:var(--brand);
      color:#fff;
      text-decoration:none;
      display:inline-block;
    }

    .btn-pill:hover{
      background:#0d9488;
      color:#fff;
    }

    .footer-note{
      margin-top:24px;
      font-size:13px;
      color:#6b7280;
    }
  </style>
</head>

<body>

  <div class="card-box">

    <!-- ICON -->
    <div class="icon">
      <i class="fas fa-check-circle"></i>
    </div>

    <h1>Thank You 🎉</h1>

    <p class="subtitle">
      Your lease has been successfully signed.<br>
      A copy of your signed lease agreement has been sent to your email.
    </p>

    <div class="info-box">
      Please check your inbox (and spam folder just in case).
      If you do not receive the email within a few minutes,
      contact property management for assistance.
    </div>

    <a href="{{ url('/') }}" class="btn-pill">
      Close Page
    </a>

    <div class="footer-note">
      You may safely close this window.
    </div>

  </div>

</body>
</html>
