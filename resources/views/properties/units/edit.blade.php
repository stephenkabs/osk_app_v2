@extends('layouts.app')

@section('content')

<style>
  :root{
    --card:#fff;
    --ink:#0b0c0f;
    --muted:#5b5f6b;
    --ring:#0071e3;
    --border:#e6e8ef;
    --radius:16px;
  }

  body{
    font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  }

  .apple-card{
    background:var(--card);
    border-radius:var(--radius);
    padding:24px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
  }

  .apple-title{
    font-weight:800;
    font-size:20px;
    letter-spacing:-.02em;
  }

  .apple-sub{
    color:var(--muted);
    font-weight:600;
    font-size:11px;
    text-transform:uppercase;
  }

  label{
    font-weight:700;
    font-size:13px;
    margin-bottom:6px;
    display:block;
  }

  .af-input,.af-select,.af-textarea{
    width:100%;
    border:1px solid var(--border);
    border-radius:12px;
    padding:10px 12px;
    font-size:14px;
    font-weight:600;
    transition:.2s;
  }

  .af-input:focus,
  .af-select:focus,
  .af-textarea:focus{
    border-color:var(--ring);
    box-shadow:0 0 0 3px rgba(0,113,227,.15);
  }

  .af-textarea{ min-height:100px; }

  .af-btn{
    background:var(--ink);
    color:#fff;
    border:none;
    border-radius:12px;
    padding:10px 18px;
    font-weight:800;
  }

  .af-btn-outline{
    border:1px solid var(--border);
    background:#fff;
    color:var(--ink);
    border-radius:12px;
    padding:10px 14px;
    font-weight:800;
  }

  /* ===============================
     Skeleton Loader
  ============================== */
  .skeleton{
    background:linear-gradient(90deg,#f0f0f0 25%,#e6e6e6 37%,#f0f0f0 63%);
    background-size:400% 100%;
    animation:shimmer 1.4s ease infinite;
    border-radius:12px;
  }

  @keyframes shimmer{
    0%{background-position:-400px 0}
    100%{background-position:400px 0}
  }

  .sk-line{ height:14px; margin-bottom:10px; }
  .sk-lg{ width:80%; }
  .sk-md{ width:60%; }
  .sk-sm{ width:40%; }

</style>

<div class="container-fluid">
  <div class="col-lg-9 mx-auto">

    {{-- 🔹 Skeleton --}}
    <div id="edit-skeleton" class="apple-card">
      <div class="skeleton sk-line sk-lg"></div>
      <div class="skeleton sk-line sk-sm"></div>
      <br>
      <div class="skeleton sk-line sk-md"></div>
      <div class="skeleton sk-line sk-md"></div>
      <div class="skeleton sk-line sk-md"></div>
    </div>

    {{-- 🔹 Real Content --}}
    <div id="edit-content" class="apple-card d-none">

      {{-- Header --}}
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <div class="apple-title">Edit Unit</div>
          <div class="apple-sub">{{ $property->property_name }} • Units</div>
        </div>

        <a href="{{ route('property.units.index', $property->slug) }}"
           class="af-btn-outline">
          ← Back
        </a>
      </div>

      <form method="POST"
            enctype="multipart/form-data"
            action="{{ route('property.units.update', [$property->slug, $unit->slug]) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
          <div class="col-md-4">
            <label>Code</label>
            <input name="code" class="af-input"
                   value="{{ old('code',$unit->code) }}" required>
          </div>

          <div class="col-md-4">
            <label>Type</label>
            <input name="type" class="af-input"
                   value="{{ old('type',$unit->type) }}">
          </div>

          <div class="col-md-4">
            <label>Floor</label>
            <input type="number" name="floor" class="af-input"
                   value="{{ old('floor',$unit->floor) }}">
          </div>
        </div>

        <div class="row g-3 mt-2">
          <div class="col-md-4">
            <label>Rent Amount</label>
            <input type="number" step="0.01" name="rent_amount"
                   class="af-input"
                   value="{{ old('rent_amount',$unit->rent_amount) }}">
          </div>

          <div class="col-md-4">
            <label>Deposit Amount</label>
            <input type="number" step="0.01" name="deposit_amount"
                   class="af-input"
                   value="{{ old('deposit_amount',$unit->deposit_amount) }}">
          </div>

          <div class="col-md-4">
            <label>Status</label>
            <select name="status" class="af-select">
              <option value="available"   @selected($unit->status==='available')>Available</option>
              <option value="occupied"    @selected($unit->status==='occupied')>Occupied</option>
              <option value="maintenance" @selected($unit->status==='maintenance')>Maintenance</option>
            </select>
          </div>
        </div>

        <div class="mt-3">
          <label>Notes</label>
          <textarea name="notes" class="af-textarea">{{ old('notes',$unit->notes) }}</textarea>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-4">
          <a href="{{ route('property.units.index', $property->slug) }}"
             class="af-btn-outline">Cancel</a>
          <button class="af-btn">Save Changes</button>
        </div>

      </form>

    </div>

  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    document.getElementById('edit-skeleton')?.classList.add('d-none');
    document.getElementById('edit-content')?.classList.remove('d-none');
  }, 300);
});
</script>

@endsection
