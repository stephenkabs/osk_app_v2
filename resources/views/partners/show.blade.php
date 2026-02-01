<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Partner - {{ $partner->slug }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Partner KYC Information" name="description" />
    <meta content="Frameworx" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body data-sidebar="dark">

@include('includes.preloader')
@include('toast.success_toast')

<div id="layout-wrapper">
    @include('includes.header')
    @include('includes.sidebar')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

<style>
  .aw-show{font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif;color:#0b0c0f}
  .aw-back{display:inline-flex;align-items:center;gap:8px;padding:8px 12px;border-radius:12px;border:1px solid #e6e8ef;background:#fff;
    font-weight:800;font-size:13px;color:#0b0c0f;text-decoration:none;transition:border-color .2s,box-shadow .2s,transform .06s}
  .aw-back:hover{border-color:#0071e3;box-shadow:0 0 0 4px color-mix(in srgb,#0071e3 16%,transparent)}
  .aw-card{background:#fff;border:1px solid #e6e8ef;border-radius:18px;box-shadow:0 8px 30px rgba(16,24,40,.06);padding:20px}
  .aw-title{font-size:24px;font-weight:800;letter-spacing:-.02em;margin:0}
  .aw-sub{color:#5b5f6b;font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:.04em;margin-top:4px}
  .aw-grid{display:grid;grid-template-columns:1fr;gap:14px;margin-top:16px}
  @media (min-width: 720px){ .aw-grid{grid-template-columns:1fr 1fr} }
  .aw-item{display:flex;flex-direction:column;gap:6px;border:1px solid #e6e8ef;border-radius:14px;padding:12px;background:#fbfbfd}
  .aw-label{color:#5b5f6b;font-weight:800;font-size:12px;text-transform:uppercase;letter-spacing:.03em}
  .aw-value{font-weight:800;font-size:16px}
  .aw-tag{display:inline-block;padding:.2rem .6rem;border-radius:999px;font-size:12px;font-weight:800;border:1px solid #dee2eb;background:#eef1f6;color:#3b4252}
  .aw-tag--green{background:#eaf8ef;border-color:#c9f0d7;color:#216e3a}
  .aw-tag--red{background:#fdecea;border-color:#facdcd;color:#b71c1c}
  .aw-meta{display:flex;align-items:center;gap:10px;margin-top:8px}
  .aw-copy{border:1px solid #e6e8ef;background:#fff;border-radius:10px;padding:6px 10px;font-size:12px;font-weight:800;cursor:pointer}
  .aw-copy:hover{border-color:#0071e3;box-shadow:0 0 0 3px color-mix(in srgb,#0071e3 14%,transparent)}
</style>

<div class="container-fluid aw-show">
    @role('admin')
  <a href="{{ route('partners.index') }}" class="aw-back mb-3">‹ Back</a>
  @endrole

  <div class="aw-card">
    <div style="display:flex;align-items:flex-end;justify-content:space-between;gap:12px">
      <div>
        <h1 class="aw-title">{{ $partner->name }}</h1>
        <div class="aw-sub">Partner details</div>
      </div>
      <span class="aw-tag @if($partner->status=='approved') aw-tag--green @elseif($partner->status=='rejected') aw-tag--red @endif">
        {{ ucfirst($partner->status) }}
      </span>
    </div>

    <!-- Info Grid -->
    <div class="aw-grid">
      <div class="aw-item">
        <span class="aw-label">NRC No</span>
        <span class="aw-value">{{ $partner->nrc_no }}</span>
      </div>

      <div class="aw-item">
        <span class="aw-label">Phone</span>
        <span class="aw-value">{{ $partner->phone_number ?: '—' }}</span>
      </div>

      <div class="aw-item">
        <span class="aw-label">Previous Address</span>
        <span class="aw-value">{{ $partner->previous_address ?: '—' }}</span>
      </div>

      <div class="aw-item">
        <span class="aw-label">Slug</span>
        <div class="aw-meta">
          <span class="aw-value" id="partner-slug">{{ $partner->slug }}</span>
          <button class="aw-copy" type="button" id="copy-slug">Copy</button>
        </div>
      </div>

      <div class="aw-item" style="grid-column:1 / -1">
        <span class="aw-label">Agreement</span>
        <span class="aw-value" style="font-weight:600">
          Accepted: {{ $partner->agreement_accepted ? 'Yes' : 'No' }} <br>
          Version: {{ $partner->agreement_version ?? '—' }} <br>
          IP: {{ $partner->agreement_ip ?? '—' }}
        </span>
      </div>
    </div>

    <!-- NRC & Signature -->
    <div style="margin-top:18px">
      <div class="aw-sub" style="margin-bottom:8px">Documents</div>
      <div class="aw-grid">
        <div class="aw-item">
          <span class="aw-label">NRC Image</span>
          @if($partner->nrc_image)
            <img class="aw-thumb" src="{{ asset('storage/' . $partner->nrc_image) }}" alt="NRC image" style="max-width:200px;border-radius:8px">
          @else
            <span class="aw-value" style="color:#5b5f6b">Not uploaded</span>
          @endif

                    @if($partner->agreement_signature)
  <a href="{{ route('partners.downloadAgreement', $partner->slug) }}"
     class="btn btn-dark mt-3" style="border-radius:12px;">
    <i class="fas fa-file-pdf"></i> Download Agreement
  </a>
@endif
        </div>
        <div class="aw-item">
          <span class="aw-label">Signature</span>
          @if($partner->agreement_signature)
            <img class="aw-thumb" src="{{ asset('storage/' . $partner->agreement_signature) }}" alt="Signature" style="max-width:200px;border-radius:8px">
          @else
            <span class="aw-value" style="color:#5b5f6b">Not signed</span>
          @endif


        </div>




      </div>
{{-- @if($partner->payments->isNotEmpty())
  <div class="aw-card" style="margin-top:24px">
    <h2 class="aw-title" style="font-size:20px;margin-bottom:16px">Flats Invested</h2>

    <div class="table-responsive">
      <table class="table align-middle" style="font-size:14px;">
        <thead style="background:#f9fafb;">
          <tr>
            <th style="font-weight:700;">Property</th>
            <th style="font-weight:700;">Flat</th>
            <th style="font-weight:700;">Amount</th>
            <th style="font-weight:700;">Status</th>
            <th style="font-weight:700;">Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($partner->payments as $payment)
            <tr>
              <!-- Property -->
              <td>
                {{ $payment->division?->property?->property_name ?? $payment->unit?->property?->property_name ?? '—' }}
              </td>

              <!-- Flat -->
              <td>
                @if($payment->division)
                  {{ $payment->division->type }} • Flat {{ $payment->division->flow }}
                @elseif($payment->division)
                  {{ $payment->division->flow ?? 'Unit' }}
                @else
                  —
                @endif
              </td>

              <!-- Amount -->
              <td>
                <span style="font-weight:700; color:#05701a;">
                  {{ strtoupper($payment->currency ?? 'USD') }} {{ number_format($payment->amount, 2) }}
                </span>
              </td>

              <!-- Status -->
              <td>
                <span style="font-weight:700; color:{{ $payment->status === 'succeeded' ? '#05701a' : '#b91c1c' }}">
                  {{ ucfirst($payment->status) }}
                </span>
              </td>

              <!-- Date -->
              <td>{{ $payment->created_at->format('Y-m-d') }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
@else
  <div class="aw-card" style="margin-top:24px">
    <p style="margin:0;color:#6e6e73;font-size:14px;text-align:center">
      This partner has no recorded payments yet.
    </p>
  </div>
@endif --}}

    </div>
  </div>
</div>

<script>
  document.getElementById('copy-slug')?.addEventListener('click', () => {
    const txt = document.getElementById('partner-slug')?.textContent?.trim() || '';
    if (!txt) return;
    navigator.clipboard.writeText(txt).then(() => {
      const btn = document.getElementById('copy-slug');
      const prev = btn.textContent;
      btn.textContent = 'Copied';
      setTimeout(()=> btn.textContent = prev, 900);
    });
  });
</script>

            </div>
        </div>

    </div>
</div>

<div class="rightbar-overlay"></div>

<!-- JAVASCRIPT -->
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/js/app.js"></script>
@include('toast.error_success_js')

</body>
</html>
