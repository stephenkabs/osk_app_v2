<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body style="font-family:Arial, sans-serif; background:#f9fafb; padding:20px">

  <div style="max-width:600px; margin:auto; background:#fff; padding:24px; border-radius:12px">
    <h2 style="margin-bottom:8px;">Lease Agreement</h2>

    <p>Hello {{ $lease->tenant->name }},</p>

    <p>
      Your lease agreement for <strong>{{ $property->property_name }}</strong>
      is ready for review and signature.
    </p>

    <p style="margin:24px 0; text-align:center;">
      <a href="{{ $signUrl }}"
         style="background:#111827; color:#fff; padding:14px 22px;
                border-radius:10px; text-decoration:none; font-weight:bold;">
        Review & Sign Lease
      </a>
    </p>

    <p style="color:#6b7280; font-size:14px;">
      If you have questions, please contact management.
    </p>

    <p style="margin-top:32px;">
      Regards,<br>
      <strong>{{ config('mail.from.name') }}</strong>
    </p>
  </div>

</body>
</html>
