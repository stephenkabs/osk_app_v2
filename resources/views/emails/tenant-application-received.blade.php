<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Application Under Review</title>
</head>

<body style="
  background:#f3f4f6;
  font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;
  padding:24px;
">

<div style="
  max-width:560px;
  margin:auto;
  background:#ffffff;
  border-radius:20px;
  padding:32px;
  box-shadow:0 20px 60px rgba(0,0,0,.15);
  text-align:center;
">

  {{-- LOGO --}}
  {{-- <img src="{{ asset('logo.webp') }}"
       style="width:120px;margin-bottom:16px"
       alt="{{ config('app.name') }}"> --}}

  {{-- STATUS --}}
  <div style="
    display:inline-block;
    background:#fef3c7;
    color:#92400e;
    padding:6px 14px;
    border-radius:999px;
    font-size:12px;
    font-weight:700;
    margin-bottom:16px;
  ">
    Application Under Review
  </div>

  <h2 style="font-size:22px;font-weight:800;margin:10px 0">
    Hello {{ $user->name }},
  </h2>

  <p style="color:#6b7280;font-size:14px;margin-bottom:24px">
    Thank you for applying to rent an apartment at
    <strong>{{ $property->property_name }}</strong>.
    <br>
    Your application has been successfully received and is now under review.
  </p>

  {{-- INFO BOX --}}
  <div style="
    background:#fafafa;
    border:1px solid #e5e7eb;
    border-radius:16px;
    padding:18px;
    text-align:left;
    font-size:13px;
    color:#374151;
    margin-bottom:24px;
  ">
    <strong>What happens next?</strong>
    <ul style="margin:10px 0 0;padding-left:18px">
      <li>Our management team reviews your application</li>
      <li>This usually takes <strong>24–48 hours</strong></li>
      <li>You may be contacted if additional details are needed</li>
      <li>You will be notified once a decision is made</li>
    </ul>
  </div>

  {{-- ACTION --}}
  <a href="mailto:rentals@onesquarek.com"
     style="
       display:inline-block;
       border:2px solid #9b5a00;
       color:#9b5a00;
       padding:10px 22px;
       border-radius:999px;
       font-weight:700;
       text-decoration:none;
     ">
    Contact Management
  </a>

  <p style="margin-top:26px;font-size:13px;color:#9ca3af">
    This review helps us ensure a safe, fair,
    and well-managed living environment for all tenants.
  </p>

</div>

</body>
</html>
