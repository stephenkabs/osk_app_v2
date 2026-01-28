<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Lease Agreement</title>

  <style>
    @page { margin: 26px 30px; }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 12px;
      color: #111;
      line-height: 1.55;
    }

    .wrap { max-width: 740px; margin: 0 auto; }

    h1 {
      font-size: 22px;
      letter-spacing: .5px;
      margin: 0;
    }

    h3 {
      font-size: 14px;
      margin: 0 0 6px;
    }

    .muted { color:#666; font-size:11px; }

    .row {
      display:flex;
      justify-content:space-between;
      align-items:flex-start;
      gap:16px;
    }

    .box {
      border:1px solid #ddd;
      border-radius:10px;
      padding:14px;
      margin-bottom:16px;
    }

    table {
      width:100%;
      border-collapse:collapse;
      margin-bottom:18px;
    }

    th, td {
      padding:8px;
      border-bottom:1px solid #eee;
      text-align:left;
      vertical-align:top;
    }

    th { width:160px; font-weight:700; }

    .divider {
      height:1px;
      background:#e5e5e5;
      margin:18px 0;
    }

    .avatar {
      width:160px;
      height:160px;
      border-radius:50%;
      object-fit:cover;
      border:2px solid #ccc;
    }

    .avatar-fallback {
      width:160px;
      height:160px;
      border-radius:50%;
      background:#111;
      color:#fff;
      display:flex;
      align-items:center;
      justify-content:center;
      font-weight:800;
      font-size:36px;
      border:2px solid #ccc;
    }

    .signline {
      border-top:1px solid #333;
      width:220px;
      margin-top:8px;
    }

    .small { font-size:11px; }

    .status {
      margin-top:20px;
      font-size:10px;
      color:#666;
    }
  </style>
<style>
  /* Tenant portrait – square/ID box (no cropping) */
  .tenant-box {
    width: 110px;
    height: 140px;               /* taller for portrait photos */
    border: 1px solid #ccc;
    border-radius: 6px;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
  }

  .tenant-box img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;         /* ✅ keeps original aspect ratio */
  }

  .tenant-fallback {
    font-weight: 800;
    font-size: 28px;
    color: #555;
  }
</style>


</head>

<body>
<div class="wrap">

  {{-- HEADER --}}
{{-- HEADER --}}
<div class="row mb-3" style="align-items:center; gap:14px;">

  {{-- PROPERTY LOGO --}}
  <div>
    @if($logoData)
      <img src="{{ $logoData }}" style="height:48px;">
    @endif
  </div>

  {{-- TENANT PHOTO (SQUARE BOXED) --}}
  <div class="tenant-box">
    @if($tenantPhotoData)
      <img src="{{ $tenantPhotoData }}" alt="Tenant">
    @else
      @php
        $parts = explode(' ', trim($agreement->tenant->name));
        $ini = strtoupper(
          substr($parts[0] ?? 'T', 0, 1) .
          substr($parts[1] ?? '', 0, 1)
        );
      @endphp
      <div class="tenant-fallback">{{ $ini }}</div>
    @endif
  </div>

  {{-- TITLE + META --}}
  <div style="flex:1;">
    <h1 style="margin-bottom:4px;">LEASE AGREEMENT</h1>
    <div class="muted">
      Ref: {{ $agreement->lease_number ?? strtoupper($agreement->slug) }}<br>
      Generated on {{ now()->format('d M Y H:i') }}
    </div>
  </div>

</div>


  {{-- PARTIES --}}
  <div class="box">
    <strong>Landlord:</strong> {{ $property->property_name }}<br>
    <strong>Address:</strong> {{ $property->address ?? '—' }}<br>
    <strong>Tenant:</strong> {{ $agreement->tenant->name }}<br>
    <strong>Email:</strong> {{ $agreement->tenant->email }}
  </div>

  {{-- QUICK FACTS --}}
  <table>
    <tr>
      <th>Unit</th>
      <td>{{ optional($agreement->unit)->code ?? '—' }}</td>
    </tr>
    <tr>
      <th>Lease Term</th>
      <td>
        {{ \Carbon\Carbon::parse($agreement->start_date)->format('d M Y') }}
        @if($agreement->end_date)
          – {{ \Carbon\Carbon::parse($agreement->end_date)->format('d M Y') }}
        @else
          (Open-ended)
        @endif
      </td>
    </tr>
    <tr>
      <th>Rent</th>
      <td>K {{ number_format($agreement->rent_amount,2) }} (monthly)</td>
    </tr>
    <tr>
      <th>Deposit</th>
      <td>
        {{ $agreement->deposit_amount ? 'K '.number_format($agreement->deposit_amount,2) : '—' }}
      </td>
    </tr>
  </table>

  <div class="divider"></div>

  {{-- ✅ LEASE TEMPLATE TERMS --}}
  <div class="box">
    {!! nl2br(e($template->terms)) !!}
  </div>

  {{-- EXTRA NOTES --}}
  @if($agreement->notes)
    <div class="box">
      <strong>Special Terms / Notes</strong><br>
      {{ $agreement->notes }}
    </div>
  @endif

  {{-- SIGNATURES --}}
  <div class="row" style="margin-top:30px;">
    <div>
      <strong>Tenant Signature</strong><br>
      @if($sigData)
        <img src="{{ $sigData }}" style="height:70px;">
      @else
        <em class="small">Not captured</em>
      @endif
      <div class="signline"></div>
      <div class="small">
        {{ $agreement->tenant->name }}<br>
        {{ optional($agreement->signed_at)->format('d M Y') }}
      </div>
    </div>

    <div>
      <strong>Landlord</strong>
      <div class="signline"></div>
      <div class="small">
        {{ $property->property_name }}
      </div>
    </div>
  </div>

  <div class="status">
    Status: {{ ucfirst($agreement->status) }} • Generated by {{ config('app.name') }}
  </div>

</div>
</body>
</html>
