<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Lease Agreement • {{ $property->property_name }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="/assets/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="/assets/css/app.min.css" rel="stylesheet"/>
  <link href="/assets/css/icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <style>
    body {
      background:#f5f6f8;
      font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif;
    }
    .apple-card{
      background:#fff;
      border-radius:14px;
      padding:24px;
      box-shadow:0 8px 24px rgba(0,0,0,.05);
    }
    .af-input, .af-select, .af-textarea{
      width:100%;
      border:1px solid #e6e8ef;
      border-radius:12px;
      padding:10px 12px;
      font-weight:600;
    }
    .af-btn{
      background:#0b0c0f;
      color:#fff;
      border:none;
      padding:10px 18px;
      border-radius:12px;
      font-weight:800;
    }
    .agreement-terms{
      background:#fafafa;
      border:1px solid #e6e8ef;
      border-radius:12px;
      padding:18px;
      font-size:14px;
      line-height:1.7;
      color:#333;
    }
    .sig-box{
      border:2px dashed #e6e8ef;
      border-radius:12px;
      background:#fafafa;
      padding:12px;
    }
    #sigCanvas{
      width:100%;
      height:200px;
      background:#fff;
      border-radius:10px;
      touch-action:none;
    }
    .sig-actions .btn{
      border-radius:10px;
      font-weight:800;
    }
  </style>
</head>

<body>
<div class="container py-5">
  <div class="col-lg-8 mx-auto">
    <div class="apple-card">

      {{-- HEADER --}}
      <div class="d-flex align-items-center gap-3 mb-3">
        @if($property->logo_path)
          <img src="{{ asset('storage/'.$property->logo_path) }}" style="height:40px;border-radius:8px;">
        @endif
        <h3 class="m-0">Lease Agreement • {{ $property->property_name }}</h3>
      </div>

      {{-- ✅ LANDLORD LEASE TEMPLATE (DYNAMIC) --}}
      <div class="agreement-terms mb-4">
        {!! nl2br(e(
          optional($property->leaseTemplate)->terms
          ?? 'No lease template has been configured by the landlord.'
        )) !!}
      </div>

      {{-- ✅ LEASE FORM --}}
      <form action="{{ route('property.agreements.public.store', $property->slug) }}" method="POST">
        @csrf
        @if(isset($lease))
  <input type="hidden" name="lease_id" value="{{ $lease->id }}">
@endif


        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <input type="hidden" name="user_id" value="{{ $user->id }}">

        <div class="row g-3">

     {{-- TENANT --}}
<div class="col-md-6">
  <label>Tenant Name</label>
  <input
    type="text"
    name="name"
    class="af-input"
    value="{{ $user->name }}"
    readonly>
</div>

<div class="col-md-6">
  <label>Email</label>
  <input
    type="email"
    name="email"
    class="af-input"
    value="{{ $user->email }}"
    readonly>
</div>

{{-- UNIT --}}
<div class="col-md-6">
  <label>Unit</label>

  @if(isset($lease))
    {{-- LOCKED (ADMIN ASSIGNED) --}}
    <input type="text"
           class="af-input"
           value="{{ $lease->unit->code }}"
           readonly>

    <input type="hidden"
           name="unit_id"
           value="{{ $lease->unit_id }}">
  @else
    {{-- SELECTABLE (SELF APPLY) --}}
    <select name="unit_id" id="unitSelect" class="af-select" required>
      <option value="">-- Select Unit --</option>
      @foreach($units as $unit)
        <option value="{{ $unit->id }}"
                data-rent="{{ $unit->rent_amount }}"
                data-deposit="{{ $unit->deposit_amount }}">
          {{ $unit->code }}
        </option>
      @endforeach
    </select>
  @endif
</div>

{{-- START DATE --}}
<div class="col-md-6">
  <label>Start Date</label>

  @if(isset($lease))
    {{-- LOCKED --}}
    <input type="text"
           class="af-input"
           value="{{ \Carbon\Carbon::parse($lease->start_date)->format('d M Y') }}"
           readonly>

    <input type="hidden"
           name="start_date"
           value="{{ $lease->start_date }}">
  @else
    {{-- SELECTABLE --}}
    <input type="date"
           name="start_date"
           class="af-input"
           required>
  @endif
</div>


{{-- RENT --}}
<div class="col-md-6">
  <label>Rent (K)</label>
  <input type="number"
         class="af-input"
         name="rent_amount"
         value="{{ $lease->rent_amount ?? '' }}"
         {{ isset($lease) ? 'readonly' : 'required' }}>
</div>

{{-- DEPOSIT --}}
<div class="col-md-6">
  <label>Deposit (K)</label>
  <input type="number"
         class="af-input"
         name="deposit_amount"
         value="{{ $lease->deposit_amount ?? '' }}"
         {{ isset($lease) ? 'readonly' : '' }}>
</div>


          {{-- SIGNATURE --}}
          <div class="col-12 mt-2">
            <label class="fw-bold">Signature</label>
            <div class="sig-box">
              <canvas id="sigCanvas"></canvas>
              <div class="sig-actions mt-2 d-flex gap-2">
                <button type="button" id="clearSig" class="btn btn-light">Clear</button>
                <button type="button" id="undoSig" class="btn btn-light">Undo</button>
              </div>
              <input type="hidden" name="signature_data" id="signatureData">
            </div>
            <small class="text-muted">Use your finger or mouse to sign.</small>
          </div>

          {{-- AGREEMENT --}}
          <div class="col-12">
            <label class="d-flex align-items-center gap-2">
              <input type="checkbox" name="agree_terms" value="1" required>
              <span>I have read and agree to the lease terms above.</span>
            </label>
          </div>

          {{-- NOTES --}}
          <div class="col-12">
            <label>Notes</label>
            <textarea name="notes" class="af-textarea"
                      placeholder="Any special terms or comments..."></textarea>
          </div>

          {{-- SUBMIT --}}
          <div class="col-12 text-end">
            <button class="af-btn">
              <i class="fas fa-file-contract me-1"></i> Submit Lease
            </button>
          </div>

        </div>
      </form>

    </div>
  </div>
</div>

{{-- AUTO FILL RENT --}}
<script>
document.getElementById('unitSelect')?.addEventListener('change', e => {
  const opt = e.target.selectedOptions[0];
  if (!opt) return;
  document.getElementById('rentInput').value    = opt.dataset.rent || '';
  document.getElementById('depositInput').value = opt.dataset.deposit || '';
});
</script>

{{-- SIGNATURE SCRIPT (UNCHANGED, SAFE) --}}
@include('includes.modals.error')
@include('includes.modals.success')
@include('properties.agreements.signature-script')

</body>
</html>
