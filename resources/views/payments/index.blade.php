@extends('layouts.app')

@section('content')

<style>
/* 🍏 Apple Cards */
.apple-card {
  background: #ffffff;
  border-radius: 20px;
  border: 1px solid #e6e8ef;
  box-shadow: 0 10px 30px rgba(0,0,0,.06);
  padding: 20px;
}

.apple-title {
  font-weight: 800;
  font-size: 22px;
  letter-spacing: -0.02em;
}

/* 🍏 Summary Cards */
.summary-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}

.summary-card {
  padding: 18px;
  border-radius: 18px;
  background: linear-gradient(180deg, #fafafa, #f1f3f6);
  border: 1px solid #e6e8ef;
}

.summary-label {
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: .05em;
  font-weight: 700;
  color: #6b7280;
}

.summary-value {
  font-size: 22px;
  font-weight: 800;
  margin-top: 6px;
  color: #111827;
}

/* 🍏 Table */
.table thead th {
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: .05em;
  color: #6b7280;
  border-bottom: 1px solid #e6e8ef;
}

.table tbody tr:hover {
  background: #fafafa;
}

.table td {
  vertical-align: middle;
  font-size: 14px;
}

/* 🍏 Pills */
.badge {
  border-radius: 999px;
  padding: 6px 12px;
  font-weight: 700;
  font-size: 11px;
  text-transform: uppercase;
}
</style>

<div class="container py-4">

  {{-- HEADER --}}
  <div class="apple-card mb-4">
    <h3 class="apple-title mb-1">Payments</h3>
    <p class="text-muted small mb-0">
      Your investment payment history & performance
    </p>
  </div>

  {{-- SUMMARY --}}
  <div class="summary-grid">

    <div class="summary-card">
      <div class="summary-label">Total Paid</div>
      <div class="summary-value">
        ZMW {{ number_format($totalAmount, 2) }}
      </div>
    </div>

    <div class="summary-card">
      <div class="summary-label">Total Shares Owned</div>
      <div class="summary-value">
        {{ number_format($totalShares, 2) }}
      </div>
    </div>

    <div class="summary-card">
      <div class="summary-label">Total Transactions</div>
      <div class="summary-value">
        {{ $totalCount }}
      </div>
    </div>

  </div>

  {{-- TABLE --}}
  <div class="apple-card p-0 overflow-hidden">

    <table class="table table-hover mb-0 align-middle">
      <thead class="table-light">
        <tr>
          <th>Property</th>
          <th>Amount</th>
          <th>Shares</th>
          <th>Method</th>
          <th>Status</th>
          <th>Reference</th>
          <th>Date</th>
        </tr>
      </thead>

      <tbody>
        @forelse($payments as $payment)

          @php
            $statusColors = [
              'paid' => 'success',
              'payment_completed' => 'success',
              'pending' => 'warning',
              'failed' => 'danger',
            ];
          @endphp

          <tr>
            <td class="fw-bold">
              {{ $payment->property?->property_name ?? '—' }}
            </td>

            <td>
              {{ $payment->currency }}
              {{ number_format($payment->amount, 2) }}
            </td>

            <td>
              {{ number_format($payment->my_total_shares, 2) }}
            </td>

            <td>
              <span class="badge bg-secondary">
                {{ strtoupper($payment->method) }}
              </span>
            </td>

            <td>
              <span class="badge bg-{{ $statusColors[$payment->status] ?? 'secondary' }}">
                {{ str_replace('_', ' ', $payment->status) }}
              </span>
            </td>

            <td class="text-muted small">
              {{ $payment->reference }}
            </td>

            <td class="text-muted small">
              {{ $payment->created_at->format('d M Y · H:i') }}
            </td>
          </tr>

        @empty
          <tr>
            <td colspan="7" class="text-center py-4 text-muted">
              No payments recorded yet.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>

  </div>

  <div class="mt-4">
    {{ $payments->links() }}
  </div>

</div>
@endsection
