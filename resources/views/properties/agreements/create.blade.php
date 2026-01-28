<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Create Lease • {{ $property->property_name }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/assets/css/app.min.css" rel="stylesheet" />
  <link href="/assets/css/icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  <style>
    :root{--card:#fff;--ink:#0b0c0f;--muted:#5b5f6b;--ring:#0071e3;--border:#e6e8ef;--radius:14px;}
    body{font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif;}
    .apple-card{background:var(--card);border-radius:var(--radius);padding:24px;}
    label{font-weight:700;font-size:13px;margin-bottom:6px;display:block;}
    .af-input,.af-select,.af-textarea{width:100%;border:1px solid var(--border);border-radius:12px;padding:10px 12px;font-size:14px;font-weight:600;color:var(--ink);outline:none;transition:.2s;}
    .af-input:focus,.af-select:focus,.af-textarea:focus{border-color:var(--ring);box-shadow:0 0 0 3px color-mix(in srgb,var(--ring) 18%,transparent);}
    .af-btn{background:var(--ink);color:#fff;border:none;padding:10px 18px;border-radius:12px;font-weight:800;font-size:14px;cursor:pointer;display:inline-flex;align-items:center;gap:8px;transition:.2s;}
    .af-btn:hover{background:#000;transform:translateY(-1px);}
  </style>
</head>
<body data-sidebar="dark">
@include('includes.preloader')
@include('includes.header')
@include('includes.sidebar')
@include('includes.validation')

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="col-lg-9 mx-auto">
        <div class="apple-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0">New Lease Agreement</h4>
            <a href="{{ route('property.agreements.index', $property->slug) }}" class="btn btn-outline-secondary">Back</a>
          </div>

<form action="{{ route('property.agreements.store', $property->slug) }}" method="POST">
  @csrf

  <div class="row g-3">
    <div class="col-md-6">
      <label>Tenant</label>
      <select name="user_id" class="af-select" required>
        <option value="">-- Select Tenant --</option>
        @foreach($tenants as $tenant)
          <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label>Unit</label>
      <select name="unit_id" id="unitSelect" class="af-select">
        <option value="">-- Optional: Select Unit --</option>
        @foreach($units as $unit)
          <option
            value="{{ $unit->id }}"
            data-rent="{{ $unit->rent_amount ?? '' }}"
            data-deposit="{{ $unit->deposit_amount ?? '' }}"
          >
            {{ $unit->code }}
          </option>
        @endforeach
      </select>
      <small class="text-muted">Selecting a unit will auto-fill rent & deposit (you can still edit).</small>
    </div>

    <div class="col-md-6">
      <label>Start Date</label>
      <input type="date" name="start_date" class="af-input" required>
    </div>

    <div class="col-md-6">
      <label>End Date</label>
      <input type="date" name="end_date" class="af-input">
    </div>

    <div class="col-md-4">
      <label>Rent (K)</label>
      <input id="rentInput" type="number" step="0.01" name="rent_amount" class="af-input" placeholder="e.g. 3500.00">
    </div>

    <div class="col-md-4">
      <label>Deposit</label>
      <input id="depositInput" type="number" step="0.01" name="deposit_amount" class="af-input" placeholder="e.g. 3500.00">
    </div>

    <div class="col-md-4">
      <label>Payment Day</label>
      <input type="number" min="1" max="31" name="payment_day" class="af-input" value="1">
    </div>

    <!-- NEW: Status -->
    <div class="col-md-4">
      <label>Status</label>
      <select name="status" class="af-select">
        <option value="active" selected>Active</option>
        <option value="pending">Pending</option>
        <option value="ended">Ended</option>
      </select>
    </div>

    <div class="col-12">
      <label>Notes</label>
      <textarea name="notes" class="af-textarea" placeholder="Additional remarks..."></textarea>
    </div>

    <div class="col-12 d-flex justify-content-end">
      <button class="af-btn"><i class="fas fa-file-contract"></i> Save Lease</button>
    </div>
  </div>
</form>

<script>
  // Auto-fill rent & deposit from unit choice
  document.getElementById('unitSelect')?.addEventListener('change', function () {
    const opt = this.selectedOptions[0];
    if (!opt) return;
    const rent = opt.getAttribute('data-rent');
    const dep  = opt.getAttribute('data-deposit');

    const rentInput = document.getElementById('rentInput');
    const depositInput = document.getElementById('depositInput');

    if (rent && !rentInput.value)   rentInput.value = rent;
    if (dep  && !depositInput.value) depositInput.value = dep;
  });
</script>


        </div>
      </div>
    </div>
  </div>
</div>

<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
