<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Partner Updated</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Helvetica,Arial,sans-serif;
             background:#f9f9f9;margin:0;display:flex;align-items:center;justify-content:center;height:100vh}
        .card{background:#fff;border:1px solid #e6e8ef;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,.08);
              max-width:480px;padding:30px;text-align:center}
        h1{font-size:22px;font-weight:700;margin-bottom:12px;color:#0b0c0f}
        p{color:#5b5f6b;font-size:15px;margin-bottom:20px}
        a.btn{display:inline-block;padding:10px 18px;background:#0a84ff;color:#fff;border-radius:10px;
              text-decoration:none;font-weight:600}
        a.btn:hover{background:#0071e3}
    </style>
</head>
<body>
    <div class="card">
        <h1>Thank You</h1>
      <p>{{ $message ?? 'Thank you for updating this Partner Information.' }}</p>
        <a href="/dashboard" class="btn">Dashboard</a>
    </div>
</body>
</html>
