@extends('layouts.app')

@section('content')
<style>
:root{
  --card:#fff;
  --border:#e6e8ef;
  --ink:#0b0c0f;
  --muted:#6b7280;
  --radius:16px;
}

.apple-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius);
  padding:24px;
  box-shadow:0 8px 24px rgba(0,0,0,.06);
}

.apple-title{
  font-size:20px;
  font-weight:800;
  letter-spacing:-.02em;
}

.apple-sub{
  font-size:12px;
  font-weight:600;
  color:var(--muted);
  text-transform:uppercase;
}

.af-input,
.af-textarea{
  width:100%;
  border:1px solid var(--border);
  border-radius:12px;
  padding:10px 12px;
  font-weight:600;
}

.af-textarea{
  min-height:320px;
  font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
}

.af-input:focus,
.af-textarea:focus{
  border-color:#0071e3;
  box-shadow:0 0 0 3px rgba(0,113,227,.15);
}

.af-btn{
  background:#0b0c0f;
  color:#fff;
  border:none;
  border-radius:12px;
  padding:10px 18px;
  font-weight:800;
}

.af-btn:hover{ background:#000; }
</style>

<div class="container-fluid">
  <div class="col-lg-9 mx-auto">

    <div class="apple-card">

      {{-- Header --}}
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <div class="apple-title">Lease Template</div>
          <div class="apple-sub">
            {{ $property->property_name }} • Default Agreement Wording
          </div>
        </div>

        <a href="{{ route('properties.show', $property->slug) }}"
           class="btn btn-light btn-sm rounded-pill">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
      </div>

      {{-- Form --}}
      <form method="POST"
            action="{{ route('property.lease-template.update', $property->slug) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
          <label class="fw-bold mb-1">Template Title</label>
          <input class="af-input"
                 name="title"
                 value="{{ old('title', $template->title) }}"
                 required>
        </div>

        <div class="mb-3">
          <label class="fw-bold mb-1">Lease Terms</label>
          <textarea class="af-textarea"
                    name="terms"
                    required>{{ old('terms', $template->terms) }}</textarea>

          <small class="text-muted">
            Tip: You can write free-text legal wording. This template will be used for all new leases.
          </small>
        </div>

        <div class="text-end">
          <button class="af-btn">
            <i class="fas fa-save me-1"></i> Save Template
          </button>
        </div>

      </form>

    </div>

  </div>
</div>
@endsection
