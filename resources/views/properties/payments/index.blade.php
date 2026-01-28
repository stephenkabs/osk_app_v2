@extends('layouts.app')

@section('content')

<style>
/* =========================
   APPLE-LIKE DESIGN SYSTEM
========================= */
:root{
  --ink:#0b0c0f;
  --muted:#6b7280;
  --ring:#0071e3;
  --line:#e6e8ef;
  --card:#ffffff;
  --radius:16px;
}

.apple-card{
  background:var(--card);
  border:1px solid var(--line);
  border-radius:var(--radius);
  padding:20px;
  box-shadow:0 6px 18px rgba(0,0,0,.06);
}

.af-input{
  width:100%;
  border:1px solid var(--line);
  border-radius:12px;
  padding:10px 12px;
  font-weight:600;
}
.af-input:focus{
  outline:none;
  border-color:var(--ring);
  box-shadow:0 0 0 3px rgba(0,113,227,.15);
}

.af-btn{
  background:#0b0c0f;
  color:#fff;
  border:none;
  padding:10px 16px;
  border-radius:12px;
  font-weight:800;
  display:inline-flex;
  align-items:center;
  gap:6px;
}
.af-btn:hover{ background:#000; }

.af-btn-outline{
  background:#fff;
  border:1px solid var(--line);
  border-radius:12px;
  padding:8px 14px;
  font-weight:700;
}

.apple-modal{
  border-radius:20px;
  border:1px solid var(--line);
  box-shadow:0 20px 40px rgba(0,0,0,.18);
}

.skeleton{
  height:14px;
  background:linear-gradient(90deg,#eee,#f5f5f5,#eee);
  border-radius:6px;
  background-size:200% 100%;
  animation:pulse 1.2s infinite;
}
@keyframes pulse{
  0%{background-position:0%}
  100%{background-position:100%}
}
</style>

<div class="container py-4">
  <div class="col-lg-10 mx-auto">

    {{-- HEADER --}}
    <div class="apple-card mb-3 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="fw-bold mb-0">Rent Payments</h4>
        <p class="text-muted mb-0">{{ $property->property_name }}</p>
      </div>

      <button class="af-btn" data-bs-toggle="modal" data-bs-target="#recordPaymentModal">
        <i class="fas fa-plus"></i> Record Payment
      </button>
    </div>

    {{-- CONTENT --}}
    @forelse($payments as $payment)
      <div class="apple-card mb-2">
        <div class="d-flex justify-content-between align-items-center">

          {{-- LEFT --}}
          <div>
            <strong>{{ $payment->tenant->name }}</strong><br>
            <span class="text-muted small">
              Unit {{ optional($payment->lease?->unit)->code ?? '—' }}
              • {{ \Carbon\Carbon::parse($payment->payment_month.'-01')->format('F Y') }}
            </span>
          </div>

          {{-- RIGHT --}}
          <div class="text-end">
            <strong>K{{ number_format($payment->amount,2) }}</strong><br>
            <span class="text-muted small">
              {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}
            </span>
          </div>
        </div>

        {{-- ACTIONS --}}
        <div class="d-flex gap-2 justify-content-end mt-2">

 <a href="{{ route('property.payments.receipt', [
    'property' => $property->slug,
    'lease'    => $payment->lease_agreement_id,
    'month'    => $payment->payment_month,
]) }}"
   class="af-btn-outline">
  <i class="fas fa-file-pdf"></i>
</a>


  <!-- EDIT -->
  <button
    class="af-btn-outline"
    data-bs-toggle="modal"
    data-bs-target="#editPaymentModal"
    data-id="{{ $payment->id }}"
    data-amount="{{ $payment->amount }}"
    data-date="{{ $payment->payment_date }}"
    data-method="{{ $payment->method }}"
    data-reference="{{ $payment->reference }}"
  >
    <i class="fas fa-pen"></i>
  </button>

<button
  type="button"
  class="af-btn-outline text-danger"
  data-bs-toggle="modal"
  data-bs-target="#deletePaymentModal"
  data-id="{{ $payment->id }}"
  data-amount="{{ number_format($payment->amount,2) }}"
  data-tenant="{{ $payment->tenant->name }}"
>
  <i class="fas fa-trash"></i>
</button>


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

{{-- RECORD PAYMENT MODAL --}}
<div class="modal fade" id="recordPaymentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
    <div class="modal-content apple-modal">

      <form method="POST" action="{{ route('property.payments.store', $property->slug) }}">
        @csrf

        <div class="modal-header border-0">
          <h5 class="fw-bold mb-0">Record Rent Payment</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="small fw-bold">Tenant / Unit</label>
            <select name="lease_id" id="leaseSelect" class="af-input" required>
              <option value="">Select tenant</option>
              @foreach($leases as $lease)
                <option value="{{ $lease->id }}"
                        data-rent="{{ $lease->rent_amount ?? optional($lease->unit)->rent_amount ?? 0 }}">
                  {{ $lease->tenant->name }} — {{ optional($lease->unit)->code }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Payment Month</label>
            <input type="month" name="payment_month" class="af-input" required>
          </div>

          <div class="mb-2">
            <label class="small fw-bold text-muted">Expected Rent (K)</label>
            <input type="text" id="expectedRent" class="af-input" readonly>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Amount Paid (K)</label>
            <input type="number" step="0.01" name="amount" id="amountPaid" class="af-input" required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold text-muted">Balance (K)</label>
            <input type="text" id="balanceAmount" class="af-input" readonly>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Method</label>
            <select name="method" class="af-input">
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="mobile_money">Mobile Money</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Payment Date</label>
            <input type="date"
                   name="payment_date"
                   value="{{ now()->toDateString() }}"
                   class="af-input"
                   required>
          </div>

        </div>

        <div class="modal-footer border-0">
          <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Cancel
          </button>
          <button class="af-btn">
            <i class="fas fa-check"></i> Save Payment
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<script>
const leaseSelect   = document.getElementById('leaseSelect');
const amountInput   = document.getElementById('amountPaid');
const expectedInput = document.getElementById('expectedRent');
const balanceInput  = document.getElementById('balanceAmount');

let expectedRent = 0;

leaseSelect?.addEventListener('change', function () {
  const option = this.selectedOptions[0];
  expectedRent = parseFloat(option?.dataset?.rent || 0);

  expectedInput.value = expectedRent.toFixed(2);
  amountInput.value   = expectedRent.toFixed(2);
  balanceInput.value  = '0.00';
});

amountInput?.addEventListener('input', function () {
  const paid = parseFloat(this.value || 0);
  balanceInput.value = (expectedRent - paid).toFixed(2);
});
</script>

<div class="modal fade" id="editPaymentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content apple-modal">

      <form method="POST" id="editPaymentForm">
        @csrf
        @method('PUT')

        <div class="modal-header border-0">
          <h5 class="fw-bold mb-0">Edit Payment</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="small fw-bold">Amount (K)</label>
            <input type="number" step="0.01"
                   name="amount" id="ep_amount"
                   class="af-input" required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Payment Date</label>
            <input type="date"
                   name="payment_date"
                   id="ep_date"
                   class="af-input" required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Method</label>
            <select name="method" id="ep_method" class="af-input">
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="mobile_money">Mobile Money</option>
            </select>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Reference</label>
            <input name="reference"
                   id="ep_reference"
                   class="af-input">
          </div>

        </div>

        <div class="modal-footer border-0">
          <button class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
          <button class="af-btn">Update</button>
        </div>

      </form>

    </div>
  </div>
</div>
<script>
const editModal = document.getElementById('editPaymentModal');
const form      = document.getElementById('editPaymentForm');

editModal.addEventListener('show.bs.modal', e => {
  const btn = e.relatedTarget;

  form.action = `/properties/{{ $property->slug }}/payments/${btn.dataset.id}`;

  document.getElementById('ep_amount').value    = btn.dataset.amount;
  document.getElementById('ep_date').value      = btn.dataset.date;
  document.getElementById('ep_method').value    = btn.dataset.method;
  document.getElementById('ep_reference').value = btn.dataset.reference;
});
</script>

<div class="modal fade" id="deletePaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
    <div class="modal-content apple-modal">

      <form method="POST" id="deletePaymentForm">
        @csrf
        @method('DELETE')

        <div class="modal-body text-center p-4">

          {{-- ICON --}}
          <div class="mb-3">
            <div style="
              width:64px;height:64px;
              border-radius:50%;
              background:#fdecec;
              color:#b91c1c;
              display:flex;
              align-items:center;
              justify-content:center;
              margin:0 auto;
              font-size:26px;">
              <i class="fas fa-trash-alt"></i>
            </div>
          </div>

          {{-- TITLE --}}
          <h5 class="fw-bold mb-1">Delete Payment?</h5>

          {{-- MESSAGE --}}
          <p class="text-muted mb-3" style="font-size:14px;">
            You are about to delete a payment of
            <strong>K<span id="dp_amount"></span></strong><br>
            for <strong id="dp_tenant"></strong>.
            <br><br>
            <strong>This action cannot be undone.</strong>
          </p>

          {{-- ACTIONS --}}
          <div class="d-flex justify-content-center gap-2 mt-3">
            <button type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
              Cancel
            </button>

            <button type="submit"
                    class="btn btn-danger rounded-pill px-4 fw-bold">
              Delete
            </button>
          </div>

        </div>

      </form>

    </div>
  </div>
</div>
<script>
const deleteModal = document.getElementById('deletePaymentModal');
const deleteForm  = document.getElementById('deletePaymentForm');

deleteModal.addEventListener('show.bs.modal', event => {
  const btn = event.relatedTarget;

  const paymentId = btn.dataset.id;
  const amount    = btn.dataset.amount;
  const tenant    = btn.dataset.tenant;

  // Update modal text
  document.getElementById('dp_amount').innerText = amount;
  document.getElementById('dp_tenant').innerText = tenant;

  // Set form action dynamically
  deleteForm.action =
    `/properties/{{ $property->slug }}/payments/${paymentId}`;
});
</script>

@endsection
