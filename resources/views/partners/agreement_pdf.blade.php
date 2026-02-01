<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Partner Agreement</title>
  <style>
    body { font-family: DejaVu Sans, sans-serif; color:#0b0c0f; font-size:14px; line-height:1.5; }
    h1 { font-size:20px; font-weight:700; margin-bottom:10px; }
    .meta { margin-bottom:20px; }
    .signature { margin-top:40px; }
    img { max-width:200px; margin-top:10px; }
  </style>
</head>
<body>
  <h1>One Square K Partner Agreement ({{ $agreementVersion }})</h1>

  <div class="meta">
    <p><strong>Partner:</strong> {{ $partner->name }} ({{ $partner->nrc_no }})</p>
    <p><strong>Accepted At:</strong> {{ \Carbon\Carbon::parse($agreementDate)->format('Y-m-d H:i') }}</p>
    <p><strong>Email:</strong> {{ $partner->email }}</p>
  </div>

  <div>
    <p>{!! nl2br(e($agreementText)) !!}</p>
  </div>

  <div class="signature">
    <p><strong>Signature:</strong></p>
<img src="{{ $signature }}" style="max-width:200px;">

  </div>
</body>
</html>
