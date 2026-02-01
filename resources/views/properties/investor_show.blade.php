
@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<style>
/* =========================
   🍏 APPLE PROPERTY SHOW
========================= */

.apple-card {
  background:#fff;
  border-radius:18px;
  padding:18px;
  border:1px solid #e6e8ef;
  box-shadow:0 8px 24px rgba(0,0,0,.05);
}

.apple-title {
  font-weight:800;
  font-size:24px;
  letter-spacing:-.02em;
}

.apple-sub {
  font-size:14px;
  color:#6b7280;
}

.info-box {
  background:#f9fafb;
  border:1px solid #e6e8ef;
  border-radius:14px;
  padding:12px;
}

.info-box .label {
  font-size:11px;
  color:#6b7280;
  text-transform:uppercase;
  font-weight:700;
}

.info-box .value {
  font-weight:800;
  font-size:15px;
}

/* 🍏 Carousel */
.apple-carousel-img {
  height:320px;
  object-fit:cover;
}

.apple-carousel-placeholder {
  height:320px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(180deg,#f9fafb,#eef1f5);
  border-radius:18px;
  color:#9ca3af;
}

/* 🍏 Skeleton */
@keyframes shimmer {
  0% { background-position:-300px 0 }
  100% { background-position:300px 0 }
}

.skeleton {
  background:linear-gradient(
    90deg,#f0f1f5 25%,#e5e7eb 37%,#f0f1f5 63%
  );
  background-size:400% 100%;
  animation:shimmer 1.4s infinite;
  border-radius:18px;
}

.skeleton-card { height:160px; }
.skeleton-img  { height:320px; }
</style>
@endpush

@section('content')

<div class="container py-4">

  {{-- 🍏 Skeleton Loader --}}
  <div id="property-skeleton">
    <div class="row g-4">
      <div class="col-lg-6">
        <div class="skeleton skeleton-card mb-3"></div>
        <div class="skeleton skeleton-card"></div>
      </div>
      <div class="col-lg-6">
        <div class="skeleton skeleton-img"></div>
      </div>
    </div>
  </div>

  {{-- 🍏 Real Content --}}
  <div id="property-content" style="display:none">

    <div class="row g-4">

      {{-- LEFT --}}
      <div class="col-lg-6">

        <div class="apple-card mb-3">
          <h2 class="apple-title">{{ $property->property_name }}</h2>
          <p class="apple-sub">Your Secured Investment Platform</p>

          <a href="{{ route('wirepick.checkout', $property) }}"
             class="btn btn-dark rounded-pill mt-3">
            Invest Now
          </a>

          <a href="/payments"
             class="btn btn-outline-dark rounded-pill mt-3 ms-2">
            My Investments
          </a>
        </div>

        {{-- Info --}}
        <div class="apple-card">
          <div class="row g-3">
            <div class="col-6">
              <div class="info-box">
                <span class="label">Unit Price</span>
                <span class="value">
                  USD {{ number_format($property->qbo_unit_price,2) }}
                </span>
              </div>
            </div>
            <div class="col-6">
              <div class="info-box">
                <span class="label">Available Shares</span>
                <span class="value">{{ $property->qbo_qty_on_hand }}</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      {{-- RIGHT --}}
      <div class="col-lg-6">
        <div class="apple-card p-0 overflow-hidden">

@php
  $images = json_decode($property->images ?? '[]', true);
@endphp

@if(count($images))
  <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      @foreach($images as $i => $img)
        <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
          <img src="{{ Storage::disk('public')->url($img) }}"
               class="d-block w-100 apple-carousel-img">
        </div>
      @endforeach
    </div>
  </div>
@else
  <div class="apple-carousel-placeholder">
    <i class="fas fa-building fa-3x"></i>
  </div>
@endif

        </div>
      </div>

    </div>
  </div>

</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    document.getElementById('property-skeleton')?.remove();
    document.getElementById('property-content').style.display = 'block';
  }, 450);
});
</script>
@endpush
