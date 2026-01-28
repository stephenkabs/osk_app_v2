@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="col-lg-8 mx-auto">

    <div class="apple-card mb-3">
      <h4 class="fw-bold mb-0">My Rent Payments</h4>
      <p class="text-muted">
        {{ $property->property_name }}
      </p>
    </div>

    {{-- Skeleton Loader --}}
    <div id="skeleton">
      @for($i=0;$i<3;$i++)
        <div class="apple-card mb-2 animate-pulse">
          <div class="skeleton h-3 w-40 mb-2"></div>
          <div class="skeleton h-3 w-24"></div>
        </div>
      @endfor
    </div>

    <div id="content" style="display:none;">
      @forelse($payments as $payment)
        <div class="apple-card mb-2">
          <div class="d-flex justify-content-between">
            <strong>K{{ number_format($payment->amount,2) }}</strong>
            <span class="badge bg-success">
              {{ ucfirst($payment->status) }}
            </span>
          </div>
          <div class="text-muted small">
            {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
            • {{ ucfirst($payment->method) }}
          </div>
        </div>
      @empty
        <div class="text-center text-muted">
          No payments recorded yet.
        </div>
      @endforelse

      {{ $payments->links() }}
    </div>

  </div>
</div>

<script>
window.addEventListener('load', () => {
  document.getElementById('skeleton').style.display = 'none';
  document.getElementById('content').style.display = 'block';
});
</script>
@endsection
