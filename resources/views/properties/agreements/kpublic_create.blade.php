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
    body { background:#f5f6f8; font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif; }
    .apple-card{ background:#fff; border-radius:14px; padding:24px; box-shadow:0 8px 24px rgba(0,0,0,.05); }
    .af-input, .af-select, .af-textarea{ width:100%; border:1px solid #e6e8ef; border-radius:12px; padding:10px 12px; font-weight:600; }
    .af-btn{ background:#0b0c0f; color:#fff; border:none; padding:10px 18px; border-radius:12px; font-weight:800; }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="col-lg-8 mx-auto">
    <div class="apple-card">
      <div class="d-flex align-items-center gap-3 mb-3">
        @if($property->logo_path)
          <img src="{{ asset('storage/'.$property->logo_path) }}" style="height:40px;border-radius:8px;">
        @endif
        <h3 class="m-0">Lease Agreement • {{ $property->property_name }}</h3>
      </div>

<form action="{{ route('property.agreements.public.store', $property->slug) }}" method="POST">
  @csrf

  {{-- show errors if any --}}
  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- IMPORTANT: use numeric id, not slug --}}
  <input type="hidden" name="user_id" value="{{ $user->id }}">

  <div class="row g-3">
    <div class="col-md-6">
      <label>Tenant Name</label>
      <input type="text" name="name" class="af-input" value="{{ $user->name }}" readonly>
    </div>
    <div class="col-md-6">
      <label>Email</label>
      <input type="text" name="email" class="af-input" value="{{ $user->email }}" readonly>
    </div>

    <div class="col-md-6">
      <label>Unit</label>
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
    </div>

    <div class="col-md-6">
      <label>Start Date</label>
      <input type="date" name="start_date" class="af-input" required>
    </div>

    <div class="col-md-6">
      <label>Rent (K)</label>
      <input id="rentInput" type="number" step="0.01" name="rent_amount" class="af-input" required>
    </div>

    <div class="col-md-6">
      <label>Deposit (K)</label>
      <input id="depositInput" type="number" step="0.01" name="deposit_amount" class="af-input">
    </div>

    <div class="col-12">
      <label>Notes</label>
      <textarea name="notes" class="af-textarea" placeholder="Any special terms or comments..."></textarea>
    </div>

    <div class="col-12 text-end">
      <button class="af-btn"><i class="fas fa-file-contract"></i> Submit Lease</button>
    </div>
  </div>
</form>

<script>
document.getElementById('unitSelect')?.addEventListener('change', e => {
  const opt = e.target.selectedOptions[0];
  if (!opt) return;
  document.getElementById('rentInput').value    = opt.dataset.rent    || '';
  document.getElementById('depositInput').value = opt.dataset.deposit || '';
});
</script>

    </div>
  </div>
</div>
{{--
<script>
document.getElementById('unitSelect').addEventListener('change', e => {
  const opt = e.target.selectedOptions[0];
  document.getElementById('rentInput').value = opt.dataset.rent || '';
  document.getElementById('depositInput').value = opt.dataset.deposit || '';
});
</script> --}}
@include('includes.modals.error')
@include('includes.modals.success')
</body>
</html>
