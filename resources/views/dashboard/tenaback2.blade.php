@extends('layouts.app')

@section('content')
<style>
:root{
  --ink:#0b0c0f; --muted:#6b7280; --card:#fff; --border:#e6e8ef;
  --ok:#065f46; --okbg:#ecfdf5;
  --warn:#92400e; --warnbg:#fffbeb;
}
.apple-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:18px;
  padding:20px;
  box-shadow:0 8px 22px rgba(0,0,0,.06);
}
.header{
  display:flex; justify-content:space-between; align-items:flex-end; gap:16px;
  margin-bottom:18px;
}
.title{
  font-size:22px; font-weight:800; letter-spacing:-.02em;
}
.sub{
  font-size:12px; color:var(--muted); font-weight:700; text-transform:uppercase;
}
.pill{
  display:inline-flex; align-items:center; gap:6px;
  padding:6px 12px; border-radius:999px;
  font-weight:800; font-size:12px; border:1px solid var(--border);
}
.pill.ok{ background:var(--okbg); color:var(--ok); border-color:#bbf7d0; }
.pill.warn{ background:var(--warnbg); color:var(--warn); border-color:#fde68a; }

.stat{
  border-radius:16px; border:1px solid var(--border);
  padding:14px; background:#fff;
}
.stat .k{ font-size:11px; color:var(--muted); font-weight:700; text-transform:uppercase; }
.stat .v{ font-size:18px; font-weight:800; }

.month{
  position:relative; border:1px solid var(--border);
  border-radius:14px; padding:12px; background:#fff;
  transition:.2s; width:100%;
}
.month:hover{ transform:translateY(-2px); box-shadow:0 10px 18px rgba(0,0,0,.08); }
.month.paid{ background:#ecfdf5; border-color:#bbf7d0; }
.month.partial{ background:#fffbeb; border-color:#fde68a; }
.month.unpaid{ background:#fef2f2; border-color:#fecaca; }

.receipt{
  position:absolute; top:8px; right:8px;
  width:30px; height:30px; border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  background:#fff; border:1px solid var(--border);
  box-shadow:0 4px 10px rgba(0,0,0,.12);
}

/* ===============================
   🍎 APPLE MODAL STYLE
================================ */

.modal-apple .modal-content {
  border-radius: 22px;
  border: 1px solid #e6e8ef;
  background: rgba(255,255,255,.96);
  backdrop-filter: blur(14px);
  box-shadow: 0 30px 80px rgba(0,0,0,.25);
}

/* Header */
.apple-modal-header {
  padding: 18px 22px;
}

.apple-close {
  opacity: .7;
}
.apple-close:hover {
  opacity: 1;
}

/* Body */
.apple-modal-body {
  padding: 10px 22px 22px;
}

/* Rent summary */
.rent-summary {
  background: #f9fafb;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  padding: 12px 14px;
  font-size: 13px;
  font-weight: 600;
  color: #374151;
}
.rent-summary .balance {
  margin-top: 4px;
  color: #065f46;
  font-weight: 800;
}

/* Inputs */
.apple-input {
  width: 100%;
  border: 1px solid #e6e8ef;
  border-radius: 12px;
  padding: 10px 12px;
  font-weight: 600;
  background: #fff;
}
.apple-input:focus {
  outline: none;
  border-color: #0071e3;
  box-shadow: 0 0 0 3px rgba(0,113,227,.15);
}

/* Buttons */
.apple-btn-primary {
  background: #0b0c0f;
  color: #fff;
  border: none;
  border-radius: 12px;
  padding: 10px 18px;
  font-weight: 800;
}
.apple-btn-primary:hover {
  background: #000;
}

.apple-btn-soft {
  border-radius: 12px;
  padding: 10px 16px;
  font-weight: 700;
  border: 1px solid #e6e8ef;
}



</style>

<div class="container-fluid">
  <div class="col-lg-10 mx-auto">

    {{-- HEADER --}}
    <div class="header">
      <div>
        <div class="title">My Home</div>
        <div class="sub">{{ $property->property_name }} • Tenant</div>
      </div>

      {{-- QBO STATUS --}}
      @if($user->quickbooks_customer_id)
        <span class="pill ok">
          <i class="fas fa-check-circle"></i> QBO Synced
        </span>
      @else
        <span class="pill warn">
          <i class="fas fa-clock"></i> QBO Pending
        </span>
      @endif
    </div>

    {{-- STATS --}}
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <div class="stat">
          <div class="k">Unit</div>
          <div class="v">
            @if($latestSignedLease && $latestSignedLease->unit)
              <i class="fas fa-door-open me-1"></i> {{ $latestSignedLease->unit->code }}
            @else
              —
            @endif
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat">
          <div class="k">Lease Status</div>
          <div class="v">{{ ucfirst($latestSignedLease->status ?? 'N/A') }}</div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat">
          <div class="k">Monthly Rent</div>
          <div class="v">
            K{{ number_format($latestSignedLease->rent_amount ?? 0, 2) }}
          </div>
        </div>
      </div>
    </div>

    {{-- RENT CALENDAR --}}
    <div class="apple-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Rent Payments</strong>
        <small class="text-muted">{{ now()->year }}</small>
      </div>

      <div class="row g-2">
        @foreach($months as $m)
          @php
            $s = $rentSummary[$m['key']] ?? ['paid'=>0,'status'=>'unpaid'];
          @endphp

<div class="col-md-3 col-6">
  <div
    class="month {{ $s['status'] }}"
    role="button"
    tabindex="0"

    data-month="{{ $m['key'] }}"
    data-label="{{ $m['label'] }}"
    data-rent="{{ $latestSignedLease->rent_amount }}"
    data-paid="{{ $s['paid'] }}"
    data-lease-start="{{ $latestSignedLease->start_date }}"
  >

    {{-- RECEIPT (separate click target) --}}
    @if($s['paid'] > 0)
      <a href="{{ route('property.payments.receipt', [
            $property->slug,
            $latestSignedLease->id,
            $m['key']
      ]) }}"
         class="receipt"
         title="Download receipt"
         onclick="event.stopPropagation();">
        <i class="fas fa-file-pdf"></i>
      </a>
    @endif

    <div class="fw-bold">{{ $m['label'] }}</div>

    <small class="text-muted">
      {{ $s['paid'] > 0
        ? 'Paid: K'.number_format($s['paid'],2)
        : 'No payment yet' }}
    </small>

  </div>
</div>



        @endforeach
      </div>
    </div>

    {{-- SUPPORT --}}
    <div class="apple-card mt-3">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
          <strong>Need help?</strong>
          <div class="text-muted small">Contact property management</div>
        </div>

        @if($property->contact_phone)
          <a href="https://wa.me/{{ preg_replace('/\D/','',$property->contact_phone) }}"
             target="_blank" class="pill">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
        @endif
      </div>
    </div>

  </div>
</div>

{{-- PAY RENT MODAL --}}
<div class="modal fade modal-apple" id="rentPayModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content">

<form method="POST"
      action="{{ route('property.payments.mobile', $property->slug) }}">
@csrf

<input type="hidden" name="lease_id" value="{{ $latestSignedLease->id }}">
<input type="hidden" name="payment_month" id="tp_month">

<div class="modal-header border-0">
  <h5 class="fw-bold mb-0" id="tp_title">Pay Rent</h5>

  {{-- ❗ MUST be type="button" --}}
  <button type="button"
          class="btn-close"
          data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

  <div class="mb-3 small text-muted">
    Balance: <strong>K<span id="tp_balance">0.00</span></strong>
  </div>

  <div class="mb-3">
    <label class="small fw-bold">Amount</label>
    <input type="number"
           step="0.01"
           name="amount"
           id="tp_amount"
           class="af-input"
           required>
  </div>

  <div class="mb-3">
    <label class="small fw-bold">Mobile Money</label>
    <select name="gateway" class="af-input">
      <option value="airtel">Airtel Money</option>
      <option value="mtn">MTN MoMo</option>
    </select>
  </div>

  <div class="mb-2">
    <label class="small fw-bold">Phone</label>
    <input name="phone"
           value="{{ $user->whatsapp_phone }}"
           class="af-input"
           required>
  </div>
</div>

<div class="modal-footer border-0 d-flex justify-content-between">
  <button type="button"
          class="btn btn-light soft-btn"
          data-bs-dismiss="modal">
    Cancel
  </button>

  <button type="submit" class="af-btn">
    <i class="fas fa-mobile-alt me-1"></i> Pay Now
  </button>
</div>

</form>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {

  const modalEl = document.getElementById('rentPayModal');
  const modal   = new bootstrap.Modal(modalEl);

  document.querySelectorAll('.month').forEach(card => {

    card.addEventListener('click', (e) => {

      // ✅ If clicking receipt, DO NOTHING
      if (e.target.closest('.receipt')) return;

      const rent       = parseFloat(card.dataset.rent || 0);
      const paid       = parseFloat(card.dataset.paid || 0);
      const monthKey   = card.dataset.month;
      const monthLabel = card.dataset.label;
      const leaseStart = card.dataset.leaseStart;

      let rentDue = rent;

      // PRORATION (first month only)
      if (leaseStart && leaseStart.startsWith(monthKey)) {
        const startDate = new Date(leaseStart);
        const daysInMonth = new Date(
          startDate.getFullYear(),
          startDate.getMonth() + 1,
          0
        ).getDate();

        const remainingDays = daysInMonth - startDate.getDate() + 1;
        rentDue = (remainingDays / daysInMonth) * rent;
      }

      const balance = Math.max(rentDue - paid, 0);

      // Fill modal
      document.getElementById('tp_title').innerText =
        'Pay Rent • ' + monthLabel;

      document.getElementById('tp_month').value = monthKey;
      document.getElementById('tp_balance').innerText = balance.toFixed(2);

      const amount = document.getElementById('tp_amount');
      amount.value = balance > 0 ? balance.toFixed(2) : '';
      amount.max   = balance.toFixed(2);

      modal.show();
    });

  });

});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('rentPayModal');

  modal.addEventListener('show.bs.modal', e => {
    const b = e.relatedTarget;

    const rent = parseFloat(b.dataset.rent || 0);
    const paid = parseFloat(b.dataset.paid || 0);
    const month = b.dataset.month;
    const label = b.dataset.label;
    const start = b.dataset.leaseStart;

    let due = rent;

    if (start && start.startsWith(month)) {
      const d = new Date(start);
      const days = new Date(d.getFullYear(), d.getMonth()+1, 0).getDate();
      due = ((days - d.getDate() + 1) / days) * rent;
    }

    const balance = Math.max(due - paid, 0);

    document.getElementById('tp_title').innerText = 'Pay Rent • ' + label;
    document.getElementById('tp_month').value = month;
    document.getElementById('tp_rent').innerText = due.toFixed(2);
    document.getElementById('tp_paid').innerText = paid.toFixed(2);
    document.getElementById('tp_balance').innerText = balance.toFixed(2);

    const amount = document.getElementById('tp_amount');
    amount.value = balance > 0 ? balance.toFixed(2) : '';
    amount.max = balance.toFixed(2);
  });
});
</script>



@endsection
