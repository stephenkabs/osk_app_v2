<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Lease Signed Successfully</title>
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
      max-width:560px;
      background:#fff;
      border-radius:24px;
      padding:36px;
      box-shadow:0 40px 90px rgba(0,0,0,.14);
      text-align:center;
    }

    .icon{
      font-size:64px;
      color:var(--brand);
      margin-bottom:16px;
    }

    h1{
      font-weight:800;
      font-size:22px;
      margin-bottom:10px;
      color:var(--ink);
    }

    .subtitle{
      color:var(--muted);
      font-size:14px;
      margin-bottom:26px;
    }

    .info-box{
      background:var(--brand-soft);
      border:1px solid #d1fae5;
      border-radius:16px;
      padding:16px;
      font-size:13px;
      color:#065f46;
      margin-bottom:26px;
    }

    .btn-pill{
      border-radius:999px;
      padding:10px 22px;
      font-weight:700;
      font-size:14px;
    }

    .btn-primary{
      background:var(--brand);
      border:none;
    }

    .btn-primary:hover{
      background:#0d9488;
    }

    .btn-outline{
      border:2px solid var(--brand);
      color:var(--brand);
      background:#fff;
    }

    .btn-outline:hover{
      background:var(--brand);
      color:#fff;
    }

    .footer-note{
      margin-top:22px;
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

    <h1>Lease Signed Successfully 🎉</h1>

    <p class="subtitle">
      Thank you <strong>{{ $tenant->name }}</strong>.<br>
      Your lease agreement for
      <strong>{{ $property->property_name }}</strong>
      has been successfully signed.
    </p>

    <div class="info-box">
      A copy of your signed lease agreement has been sent to
      <strong>{{ $tenant->email }}</strong>
    </div>

    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="{{ route('login') }}"
         class="btn btn-primary btn-pill">
        Go to Login
      </a>

      <a href="{{ route('property.agreements.download', [
          $property->slug,
          $agreement->slug
      ]) }}"
         class="btn btn-outline btn-pill">
        Download Lease PDF
      </a>
    </div>

    <div class="footer-note">
      This lease is now active and legally binding.
      If you have any questions, please contact property management.
    </div>

  </div>

</body>
</html>
