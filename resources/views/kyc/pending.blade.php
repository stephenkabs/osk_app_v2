<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Application Under Review</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <style>
    :root{
        --brand:#9b5a00;
        --brand-dark:#c2840a;
        --muted:#6b7280;
        --border:#e5e7eb;
    }

    body{
        background:linear-gradient(180deg,#f8fafc,#eef2f7);
        font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif;
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

    .logo{
        width:120px;
        margin-bottom:16px;
    }

    .badge-status{
        display:inline-block;
        background:rgba(155,90,0,.1);
        color:var(--brand);
        padding:6px 14px;
        border-radius:999px;
        font-size:12px;
        font-weight:700;
        margin-bottom:16px;
    }

    h1{
        font-weight:800;
        font-size:22px;
        margin-bottom:10px;
    }

    .subtitle{
        color:var(--muted);
        font-size:14px;
        margin-bottom:26px;
    }

    .info-box{
        background:#fafafa;
        border:1px solid var(--border);
        border-radius:16px;
        padding:18px;
        text-align:left;
        font-size:13px;
        color:#374151;
        margin-bottom:26px;
    }

    .info-box ul{
        padding-left:18px;
        margin:10px 0 0;
    }

    .info-box li{
        margin-bottom:6px;
    }

    .btn-outline{
        border-radius:999px;
        border:2px solid var(--brand);
        color:var(--brand);
        font-weight:700;
        padding:10px 22px;
        background:#fff;
        transition:.15s;
    }

    .btn-outline:hover{
        background:var(--brand);
        color:#fff;
    }

    .footer-links{
        margin-top:22px;
        font-size:13px;
    }

    .footer-links a{
        color:var(--brand);
        font-weight:600;
        text-decoration:none;
    }

    .footer-links a:hover{
        text-decoration:underline;
    }
    </style>
</head>

<body>

<div class="card-box">

    <!-- LOGO -->
    <img src="/logo.webp" class="logo" alt="Logo">
    <br>

    <!-- STATUS -->
    <span class="badge-status">
        Application Under Review
    </span>

    <h1>Your apartment application is being reviewed</h1>

    <p class="subtitle">
        Thank you for applying to rent an apartment in our building.
        Our management team is currently reviewing your application.
    </p>

    <!-- INFO -->
    <div class="info-box">
        <strong>What happens next?</strong>
        <ul>
            <li>Our property management team reviews your application details</li>
            <li>This process usually takes <strong>24–48 hours</strong></li>
            <li>You may be contacted if additional information is required</li>
            <li>You will be notified once a decision has been made</li>
        </ul>
    </div>

    <!-- ACTIONS -->
    <div class="d-flex justify-content-center gap-3 flex-wrap">
        <a href="mailto:rentals@yourdomain.com" class="btn-outline">
            Contact Management
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-outline" type="submit">
                Logout
            </button>
        </form>
    </div>

    <!-- FOOTER -->
    <div class="footer-links">
        <p class="mt-3 text-muted">
            This review helps us ensure a safe, fair, and well-managed
            living environment for all tenants.
        </p>
    </div>

</div>

</body>
</html>
