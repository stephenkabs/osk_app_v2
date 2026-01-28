@extends('layouts.app')

@section('content')
<div class="container py-4">
  <div class="col-lg-6 mx-auto">

    {{-- Header --}}
    <div class="apple-card mb-3">
      <h4 class="fw-bold mb-1">Pay Rent</h4>
      <p class="text-muted mb-0">
        {{ $property->property_name }} • Unit {{ optional($lease->unit)->code }}
      </p>
    </div>

    {{-- Lease Summary --}}
    <div class="apple-card mb-3">
      <div class="d-flex justify-content-between">
        <span>Monthly Rent</span>
        <strong>K{{ number_format($lease->rent_amount,2) }}</strong>
      </div>
      <div class="d-flex justify-content-between mt-1">
        <span>Payment Day</span>
        <strong>{{ $lease->payment_day ?? 1 }}</strong>
      </div>
    </div>

    {{-- Payment Form --}}
    <div class="apple-card">
      <form method="POST" action="{{ route('tenant.payments.store', $property->slug) }}">
        @csrf

        <div class="mb-3">
          <label class="fw-bold">Amount (K)</label>
          <input type="number" name="amount" step="0.01"
                 class="af-input"
                 value="{{ $lease->rent_amount }}"
                 required>
        </div>

        <div class="mb-3">
          <label class="fw-bold">Payment Date</label>
          <input type="date" name="payment_date"
                 class="af-input"
                 value="{{ now()->toDateString() }}"
                 required>
        </div>

        <div class="mb-3">
          <label class="fw-bold">Payment Method</label>
          <select name="method" class="af-select">
            <option value="cash">Cash</option>
            <option value="mobile_money">Mobile Money</option>
            <option value="bank">Bank Transfer</option>
          </select>
        </div>

        <div class="mb-3">
          <label>Reference (optional)</label>
          <input name="reference" class="af-input"
                 placeholder="Transaction / Receipt ref">
        </div>

        <div class="mb-3">
          <label>Notes</label>
          <textarea name="notes" class="af-textarea"
                    placeholder="Optional notes"></textarea>
        </div>

        <button class="af-btn w-100">
          <i class="fas fa-money-bill-wave me-1"></i> Submit Payment
        </button>
      </form>
    </div>

  </div>
</div>
@endsection
