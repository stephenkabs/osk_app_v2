@extends('layouts.app')

@section('content')

<style>
:root{
  --card:#fff; --ink:#0b0c0f; --muted:#6b7280;
  --border:#e6e8ef; --ring:#0071e3;
}
.apple-card{
  background:var(--card);
  border-radius:18px;
  border:1px solid var(--border);
  padding:22px;
  box-shadow:0 6px 20px rgba(0,0,0,.06);
}
.apple-header{
  display:flex;
  justify-content:space-between;
  align-items:flex-end;
  gap:16px;
  margin-bottom:22px;
}
.apple-title{
  font-size:22px;
  font-weight:800;
  letter-spacing:-.02em;
}
.apple-sub{
  font-size:12px;
  color:var(--muted);
  font-weight:600;
  text-transform:uppercase;
}
.avatar{
  width:140px; height:140px;
  border-radius:20px;
  object-fit:cover;
  border:1px solid var(--border);
}
.avatar-fallback{
  width:140px; height:140px;
  border-radius:20px;
  display:flex; align-items:center; justify-content:center;
  font-size:42px; font-weight:900;
  background:#0b0c0f; color:#fff;
}
.af-btn, .af-btn-outline{
  display:inline-flex; align-items:center; gap:8px;
  border-radius:12px; padding:8px 14px;
  font-weight:800; font-size:13px;
  transition:.2s;
}
.af-btn{ background:#0b0c0f; color:#fff; border:none; }
.af-btn:hover{ background:#000; }
.af-btn-outline{
  background:#fff; border:1px solid #d1d5db; color:#111;
}
.af-btn-outline:hover{ background:#f3f4f6; }
.kv-label{
  font-size:11px; text-transform:uppercase;
  color:var(--muted); font-weight:700;
}
.kv-value{
  font-weight:700; font-size:14px;
}
.pill{
  border:1px solid var(--border);
  padding:6px 10px; border-radius:999px;
  font-size:12px; font-weight:700;
}

.pill{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  border-radius:999px;
  font-weight:800;
  font-size:12px;
  border:1px solid #d1fae5;
  background:#ecfdf5;
  color:#065f46;
}


.modal-apple .modal-content{
  border-radius:16px;
  border:1px solid #e6e8ef;
  box-shadow:0 20px 60px rgba(0,0,0,.12);
}

.status-option{
  display:flex;
  align-items:center;
  gap:12px;
  padding:12px 14px;
  border-radius:14px;
  border:1px solid #e6e8ef;
  cursor:pointer;
  transition:.2s;
  background:#fff;
}

.status-option:hover{
  background:#f9fafb;
}

.status-option.active{
  border-color:#22c55e;
  background:#ecfdf5;
}

.status-option input{
  display:none;
}

.status-option span{
  display:flex;
  flex-direction:column;
  font-size:14px;
}

.status-option small{
  color:#6b7280;
  font-weight:500;
}

.soft-btn{
  font-weight:700;
  border-radius:12px;
  padding:10px 16px;
  border:1px solid #e6e8ef;
}
.month-card{
  background:#fff;
  transition:.2s;
}
.month-card.unpaid{
  border-color:#fee2e2;
  background:#fef2f2;
}
.month-card.partial{
  border-color:#fde68a;
  background:#fffbeb;
}
.month-card.paid{
  border-color:#bbf7d0;
  background:#ecfdf5;
}
.month-card:hover{
  transform:translateY(-2px);
  box-shadow:0 8px 18px rgba(0,0,0,.08);
}


</style>
@push('styles')
<style>
.modal-apple .modal-content{
  border-radius:18px;
  border:1px solid #e6e8ef;
  box-shadow:0 20px 60px rgba(0,0,0,.15);
  background:#fff;
}

.af-input{
  width:100%;
  border:1px solid #e6e8ef;
  border-radius:12px;
  padding:10px 12px;
  font-weight:600;
}

.af-input:focus{
  outline:none;
  border-color:#0071e3;
  box-shadow:0 0 0 3px rgba(0,113,227,.15);
}

.af-btn{
  background:#0b0c0f;
  color:#fff;
  border:none;
  border-radius:12px;
  padding:10px 16px;
  font-weight:800;
}

.af-btn:hover{
  background:#000;
}

.soft-btn{
  border-radius:12px;
  padding:10px 16px;
  border:1px solid #e6e8ef;
  font-weight:700;
}

.receipt-icon{
  position:absolute;
  top:10px;
  right:10px;
  z-index:3;

  width:32px;
  height:32px;
  border-radius:10px;

  display:flex;
  align-items:center;
  justify-content:center;

  background:#fff;
  border:1px solid #e6e8ef;
  color:#dc2626; /* PDF red */

  box-shadow:0 4px 12px rgba(0,0,0,.12);
  transition:.15s ease;
}

.receipt-icon:hover{
  background:#fef2f2;
  transform:scale(1.05);
}

.month-card{
  position:relative;
}

.pill {
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  border-radius:999px;
  font-weight:800;
  font-size:12px;
  border:1px solid #d1fae5;
}


</style>
@endpush


<div class="container-fluid">
  <div class="col-lg-10 mx-auto">
    <meta name="assign-route"
      content="{{ route('property.lease.assign', [$property->slug, $user->slug]) }}">


    {{-- HEADER --}}
    <div class="apple-header">
      <div>
        <h1 class="apple-title">{{ $user->name }}</h1>
        <div class="apple-sub">{{ $property->property_name }} • Tenant</div>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('property.users.edit', [$property->slug, $user->slug]) }}" class="af-btn">
          <i class="fas fa-pen"></i> Edit
        </a>

        <a href="{{ route('property.users.index', $property->slug) }}" class="af-btn-outline">
          <i class="fas fa-arrow-left"></i> Back
        </a>
      </div>
    </div>

    {{-- PROFILE --}}
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <div class="apple-card text-center">
          @php
            $initials = collect(explode(' ', $user->name))
              ->map(fn($w)=>mb_substr($w,0,1))->take(2)->implode('');
          @endphp

          @if($user->profile_image)
            <img src="{{ asset('storage/'.$user->profile_image) }}" class="avatar mb-2">
          @else
            <div class="avatar-fallback mb-2">{{ strtoupper($initials) }}</div>
          @endif

          <div class="text-muted small">{{ $user->email ?? '—' }}</div>

          <div class="d-flex justify-content-center gap-2 mt-2 flex-wrap">
            <span class="pill">{{ $user->special_code ?? 'TENANT' }}</span>
            @if($user->type)
              <span class="pill">{{ $user->type }}</span>
            @endif
          </div>
        </div>
      </div>

      {{-- DETAILS --}}
      <div class="col-md-8">
        <div class="apple-card">
          <div class="row g-3">
            <div class="col-6">
              <div class="kv-label">Phone</div>
              <div class="kv-value">{{ $user->whatsapp_phone ?? '—' }}</div>
            </div>

            <div class="col-6">
              <div class="kv-label">Unit</div>
  @if($latestSignedLease && $latestSignedLease->unit)

      <span style="font-size: 11px"></span> <strong><i class="fas fa-door-open me-1"></i>
     {{ $latestSignedLease->unit->code }}</strong></span>

  @else
    Not assigned
  @endif
            </div>

            <div class="col-12">
              <div class="kv-label">Address</div>
              <div class="kv-value">{{ $user->address ?? '—' }}</div>
            </div>

            <div class="col-6">
              <div class="kv-label">Joined</div>
              <div class="kv-value">{{ optional($user->created_at)->format('d M Y') }}</div>
            </div>

            <div class="col-6">
              <div class="kv-label">Lease Status</div>
              <div class="kv-value">
                {{ $activeLease ? 'Active' : 'Not Assigned' }}
              </div>
            </div>
            <div class="col-6">
  <div class="kv-label">Arrived</div>
  <div class="kv-value">
    {{ $user->arrived_date ? \Carbon\Carbon::parse($user->arrived_date)->format('d M Y') : '—' }}
  </div>
</div>

<div class="col-6">
  <div class="kv-label">Left</div>
  <div class="kv-value">
    {{ $user->leave_date ? \Carbon\Carbon::parse($user->leave_date)->format('d M Y') : '—' }}
  </div>
</div>

          </div>
        </div>
      </div>
    </div>

{{-- ACTIONS --}}
<div class="apple-card">
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

    {{-- LEFT --}}
    <div class="d-flex align-items-center gap-3 flex-wrap">

      {{-- LEASE STATUS / ACTIONS --}}
      @if($hasSignedLease && $latestSignedLease)

        {{-- STATUS PILL --}}
        <button type="button"
                class="pill text-success border-0"
                data-bs-toggle="modal"
                data-bs-target="#leaseStatusModal">
          <i class="fas fa-check-circle me-1"></i>
          Lease Signed ({{ ucfirst($latestSignedLease->status) }})
        </button>

        {{-- DOWNLOAD PDF --}}
        <a href="{{ route('property.agreements.download', [
            $property->slug,
            $latestSignedLease->slug
        ]) }}"
           class="af-btn-outline">
          <i class="fas fa-file-pdf"></i> Download Lease
        </a>

@if($latestSignedLease && $latestSignedLease->status === 'pending')
  {{-- COPY LEASE LINK --}}
  <button type="button"
          class="af-btn-outline"
          id="copyExistingLeaseBtn"
          data-link="{{ route('property.agreements.public.create', $property->slug) }}?lease={{ $latestSignedLease->slug }}">
    <i class="fas fa-copy"></i> Copy Lease Link
  </button>
@endif



      @else

        {{-- START LEASE --}}
        <a href="#"
           target="_blank"
           class="af-btn-outline">
          <i class="fas fa-file-signature"></i> No lease signed
        </a>



      @endif

      {{-- WHATSAPP --}}
      @if($user->whatsapp_phone)
        <a href="https://wa.me/{{ preg_replace('/\D/','',$user->whatsapp_phone) }}"
           target="_blank"
           class="af-btn-outline">
          <i class="fab fa-whatsapp"></i> WhatsApp
        </a>
      @endif

      {{-- QBO SYNC STATUS --}}
      @if($user->quickbooks_customer_id)
        <span class="pill"
              style="background:#ecfdf5;border-color:#bbf7d0;color:#065f46;">
          <i class="fas fa-check-circle me-1"></i>
          QBO Synced
        </span>
      @else
        <button type="button"
                class="af-btn-outline"
                data-bs-toggle="modal"
                data-bs-target="#syncQboModal">
          <i class="fas fa-cloud-upload-alt"></i>
          Sync to QBO
        </button>
      @endif
      <button type="button"
        class="af-btn-outline"
        data-bs-toggle="modal"
        data-bs-target="#occupancyDatesModal">
  <i class="fas fa-calendar-alt"></i>
  Occupancy Dates
</button>


    </div>

    {{-- DELETE --}}
    <button type="button"
            class="af-btn-outline text-danger"
            data-delete
            data-title="Delete Tenant"
            data-message="You are about to permanently delete {{ $user->name }}. This cannot be undone."
            data-action="{{ route('property.users.destroy', [$property->slug, $user->slug]) }}">
      <i class="fas fa-trash"></i> Delete
    </button>

  </div>
</div>

{{-- RENT CALENDAR --}}
{{-- RENT CALENDAR --}}
@if($latestSignedLease)

  <div class="apple-card mt-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="fw-bold mb-0">Rent Payments</h5>
      <span class="text-muted small">{{ now()->year }}</span>
    </div>

    <div class="row g-2">
      @foreach($months as $month)
        @php
          $key     = $month['key']; // YYYY-MM
          $label   = $month['label'];
          $summary = $rentSummary[$key] ?? null;

          $paid   = $summary['paid'] ?? 0;
          $status = $summary['status'] ?? 'unpaid';
        @endphp

        <div class="col-md-3 col-6">
          <div class="position-relative">

            {{-- PDF RECEIPT --}}
            @if($paid > 0)
              <a href="{{ route('property.payments.receipt', [
                    $property->slug,
                    $latestSignedLease->id,
                    $key
                ]) }}"
                 class="receipt-icon"
                 title="Download receipt"
                 onclick="event.stopPropagation();">
                <i class="fas fa-file-pdf"></i>
              </a>
            @endif

            {{-- MONTH CARD --}}
            <button
              type="button"
              class="w-100 text-start p-3 rounded-4 border month-card {{ $status }}"
              data-bs-toggle="modal"
              data-bs-target="#rentMonthModal"
              data-month="{{ $key }}"
              data-month-name="{{ $label }}"
              data-rent="{{ $latestSignedLease->rent_amount }}"
              data-paid="{{ $paid }}"
              data-lease-start="{{ $latestSignedLease->start_date }}"
            >
              <div class="fw-bold">{{ $label }}</div>

              @if($paid > 0)
                <small class="text-muted">
                  Paid: K{{ number_format($paid, 2) }}
                </small>
              @else
                <small class="text-muted">No payment</small>
              @endif
            </button>

          </div>
        </div>
      @endforeach
    </div>
  </div>

@else
  {{-- NO LEASE SIGNED --}}
  <div class="apple-card mt-3">
    <div class="alert alert-info mb-0 d-flex align-items-center gap-2">
      <i class="fas fa-info-circle"></i>
      <div>
        <strong>No signed lease yet.</strong><br>
        Rent payments will appear once the lease agreement is completed.
      </div>
    </div>

    <div class="mt-3">
      {{-- <a href="{{ route('property.agreements.public.create', [
          'property' => $property->slug,
          'user'     => $user->id
      ]) }}"
         target="_blank"
         class="af-btn-outline">
        <i class="fas fa-file-signature"></i> Start Lease Agreement
      </a> --}}
    </div>
  </div>
@endif






  </div>
</div>
{{-- LEASE STATUS MODAL --}}
@if($latestSignedLease)
<div class="modal fade modal-apple" id="leaseStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px">
    <div class="modal-content">

      <div class="modal-header border-0">
        <h5 class="modal-title fw-800">Lease Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

<form method="POST"
      action="{{ route('property.agreements.updateStatus', [
          $property->slug,
          $latestSignedLease->slug
      ]) }}">
  @csrf
  @method('PUT')

        <div class="modal-body pt-0">

          <p class="text-muted small mb-3">
            Update the lease status for <strong>{{ $user->name }}</strong>.
          </p>

          {{-- STATUS OPTIONS --}}
          <div class="d-grid gap-2">

            <label class="status-option {{ $latestSignedLease->status === 'pending' ? 'active' : '' }}">
              <input type="radio" name="status" value="pending"
                     {{ $latestSignedLease->status === 'pending' ? 'checked' : '' }}>
              <span>
                <strong>Pending</strong>
                <small>Signed but not yet active</small>
              </span>
            </label>

            <label class="status-option {{ $latestSignedLease->status === 'active' ? 'active' : '' }}">
              <input type="radio" name="status" value="active"
                     {{ $latestSignedLease->status === 'active' ? 'checked' : '' }}>
              <span>
                <strong>Active</strong>
                <small>Tenant is currently occupying</small>
              </span>
            </label>

            <label class="status-option {{ $latestSignedLease->status === 'ended' ? 'active' : '' }}">
              <input type="radio" name="status" value="ended"
                     {{ $latestSignedLease->status === 'ended' ? 'checked' : '' }}>
              <span>
                <strong>Ended</strong>
                <small>Lease completed or terminated</small>
              </span>
            </label>

          </div>

        </div>

        <div class="modal-footer border-0 d-flex justify-content-between">
          <button type="button" class="btn btn-light soft-btn" data-bs-dismiss="modal">
            Cancel
          </button>

          <button type="submit" class="btn btn-dark af-btn">
            <i class="fas fa-save me-1"></i> Update Status
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
@endif
<script>
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.status-option').forEach(option => {
    option.addEventListener('click', () => {

      // Remove active class from all
      document.querySelectorAll('.status-option')
        .forEach(o => o.classList.remove('active'));

      // Add active to clicked
      option.classList.add('active');

      // Check its radio input
      const radio = option.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    });
  });
});
</script>
@include('components.apple-delete-modal')
@if($latestSignedLease)
{{-- RENT PAYMENT MODAL --}}
<div class="modal fade modal-apple" id="rentMonthModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px;">
    <div class="modal-content">

      <form method="POST"
            action="{{ route('property.payments.store', $property->slug) }}">
        @csrf

        {{-- REQUIRED HIDDEN FIELDS --}}
        <input type="hidden" name="lease_id" value="{{ $latestSignedLease->id }}">
        <input type="hidden" name="payment_month" id="rm_month">
        <input type="hidden" name="payment_date" value="{{ now()->toDateString() }}">

        <div class="modal-header border-0">
          <h5 class="fw-bold mb-0" id="rm_title">Rent Payment</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          {{-- SUMMARY --}}
          <div class="mb-3 small text-muted">
            Rent Due:
            <strong>K<span id="rm_rent">0.00</span></strong><br>

            Paid So Far:
            <strong>K<span id="rm_paid">0.00</span></strong><br>

            Balance:
            <strong>K<span id="rm_balance">0.00</span></strong>
          </div>

          {{-- PRORATION NOTICE --}}
          <div id="rm_prorated"
               class="mb-3 small text-info"
               style="display:none;">
            <i class="fas fa-info-circle me-1"></i>
            First month rent is prorated based on lease start date
          </div>

          {{-- AMOUNT --}}
          <div class="mb-3">
            <label class="small fw-bold">Amount Paying Now</label>
            <input type="number"
                   step="0.01"
                   name="amount"
                   id="rm_amount"
                   class="af-input"
                   required>
          </div>

          {{-- METHOD --}}
          <div class="mb-3">
            <label class="small fw-bold">Payment Method</label>
            <select name="method" class="af-input">
              <option value="cash">Cash</option>
              <option value="bank">Bank Transfer</option>
              <option value="mobile_money">Mobile Money</option>
            </select>
          </div>

          {{-- REFERENCE --}}
          <div class="mb-2">
            <label class="small fw-bold">Reference (optional)</label>
            <input name="reference" class="af-input">
          </div>

        </div>

        <div class="modal-footer border-0 d-flex justify-content-between">
          {{-- IMPORTANT: type="button" --}}
          <button type="button"
                  class="btn btn-light soft-btn"
                  data-bs-dismiss="modal">
            Cancel
          </button>

          <button type="submit" class="af-btn">
            <i class="fas fa-check me-1"></i> Record Payment
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
@endif


<script>
document.addEventListener('DOMContentLoaded', () => {

  const modal = document.getElementById('rentMonthModal');

  modal.addEventListener('show.bs.modal', event => {
    const btn = event.relatedTarget;

    // Dataset from month card
    const rent        = parseFloat(btn.dataset.rent || 0);
    const paid        = parseFloat(btn.dataset.paid || 0);
    const leaseStart  = btn.dataset.leaseStart;   // YYYY-MM-DD
    const monthKey    = btn.dataset.month;        // YYYY-MM
    const monthName   = btn.dataset.monthName;

    let rentDue = rent;
    let prorated = false;

    /* ===============================
       PRORATION (FIRST MONTH ONLY)
    =============================== */
    if (leaseStart && leaseStart.startsWith(monthKey)) {
      const startDate = new Date(leaseStart);
      const year  = startDate.getFullYear();
      const month = startDate.getMonth(); // 0-based

      const daysInMonth = new Date(year, month + 1, 0).getDate();
      const remainingDays = daysInMonth - startDate.getDate() + 1;

      rentDue = (remainingDays / daysInMonth) * rent;
      prorated = true;
    }

    const balance = Math.max(rentDue - paid, 0);

    // Title
    document.getElementById('rm_title').innerText =
      'Rent • ' + monthName;

    // Hidden fields
    document.getElementById('rm_month').value = monthKey;

    // Numbers
    document.getElementById('rm_rent').innerText    = rentDue.toFixed(2);
    document.getElementById('rm_paid').innerText    = paid.toFixed(2);
    document.getElementById('rm_balance').innerText = balance.toFixed(2);

    // Amount input
    const amountInput = document.getElementById('rm_amount');
    amountInput.value = balance > 0 ? balance.toFixed(2) : '';
    amountInput.max   = balance.toFixed(2);

    // Proration badge
    document.getElementById('rm_prorated').style.display =
      prorated ? 'block' : 'none';
  });

});
</script>

<div class="modal fade modal-apple" id="syncQboModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">

      <form method="POST"
            action="{{ route('users.kyc.update', $user) }}">
        @csrf
        @method('PUT')

        {{-- Force approval --}}
        <input type="hidden" name="kyc_status" value="approved">

        <div class="modal-body text-center p-4">

          {{-- ICON --}}
          <div style="
            width:64px;height:64px;border-radius:50%;
            background:linear-gradient(180deg,#0b0c0f,#000);
            color:#e1a422;
            display:flex;align-items:center;justify-content:center;
            font-size:28px;
            margin:0 auto 14px;">
            <i class="fas fa-cloud-upload-alt"></i>
          </div>

          <h5 class="fw-bold mb-2">Sync Tenant to QuickBooks</h5>

          <p class="text-muted small mb-4">
            This will approve KYC for <strong>{{ $user->name }}</strong>
            and attempt to sync their details to QuickBooks.
            <br><br>
            If the sync fails, you can retry at any time.
          </p>

          <div class="d-flex justify-content-center gap-2">
            <button type="button"
                    class="btn btn-light soft-btn"
                    data-bs-dismiss="modal">
              Cancel
            </button>

            <button type="submit" class="af-btn">
              <i class="fas fa-check me-1"></i> Confirm & Sync
            </button>
          </div>

        </div>
      </form>

    </div>
  </div>
</div>

<div class="modal fade modal-apple" id="assignLeaseModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:520px">
    <div class="modal-content">

      <div class="modal-header border-0">
        <h5 class="fw-bold mb-0">
          <i class="fas fa-file-signature me-2"></i>
          Assign Unit & Send Lease
        </h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="assignLeaseForm">
        <div class="modal-body">

          {{-- UNIT --}}
          <div class="mb-3">
            <label class="small fw-bold">Select Unit</label>
            <select id="leaseUnit"
                    class="af-input"
                    required>
              <option value="">-- Select Available Unit --</option>
              @foreach(
                \App\Models\Unit::where('property_id',$property->id)
                  ->where('status','available')
                  ->orderBy('code')
                  ->get() as $unit
              )
                <option value="{{ $unit->id }}">
                  {{ $unit->code }} — K{{ number_format($unit->rent_amount ?? 0,2) }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- START DATE --}}
          <div class="mb-3">
            <label class="small fw-bold">Start Date</label>
            <input type="date"
                   id="leaseStartDate"
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

  <div class="d-flex gap-2">
    <button type="button"
            id="copyLeaseLinkBtn"
            class="af-btn-outline d-none">
      <i class="fas fa-copy"></i>
      Copy Link
    </button>

    <button type="submit"
            id="saveLeaseBtn"
            class="af-btn">
      <i class="fas fa-save me-1"></i>
      Save & Generate Link
    </button>
  </div>
</div>


      </form>

    </div>
  </div>
</div>
<script>
let generatedLeaseLink = null;

document.getElementById('assignLeaseForm')
?.addEventListener('submit', async function (e) {
  e.preventDefault();

  const unitId    = document.getElementById('leaseUnit').value;
  const startDate = document.getElementById('leaseStartDate').value;

  if (!unitId || !startDate) {
    alert('Please select unit and start date');
    return;
  }

  const url = document
    .querySelector('meta[name="assign-route"]')
    .getAttribute('content');

  try {
const res = await fetch(url, {
  method: 'POST',
  credentials: 'same-origin', // 🔥 THIS FIXES 419
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': '{{ csrf_token() }}'
  },
  body: JSON.stringify({
    unit_id: unitId,
    start_date: startDate
  })
});


    if (!res.ok) {
      const text = await res.text();
      console.error('SERVER ERROR:', text);
      alert('Failed to save lease (see console)');
      return;
    }

    const data = await res.json();

    generatedLeaseLink = data.sign_url;

    // show copy button
    document.getElementById('copyLeaseLinkBtn')
      .classList.remove('d-none');

    // change button text
    document.getElementById('saveLeaseBtn').innerHTML =
      '<i class="fas fa-check me-1"></i> Lease Saved';

  } catch (err) {
    console.error(err);
    alert('Network or server error');
  }
});

// COPY LINK
document.getElementById('copyLeaseLinkBtn')
?.addEventListener('click', async () => {
  if (!generatedLeaseLink) return;

  await navigator.clipboard.writeText(generatedLeaseLink);

  const btn = document.getElementById('copyLeaseLinkBtn');
  btn.innerHTML = '<i class="fas fa-check"></i> Copied';
  setTimeout(() => {
    btn.innerHTML = '<i class="fas fa-copy"></i> Copy Link';
  }, 1200);
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const btn = document.getElementById('copyExistingLeaseBtn');
  if (!btn) return;

  btn.addEventListener('click', async () => {
    const link = btn.dataset.link;
    await navigator.clipboard.writeText(link);

    const original = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-check me-1"></i> Copied';
    setTimeout(() => btn.innerHTML = original, 1200);
  });
});
</script>
<div class="modal fade modal-apple" id="occupancyDatesModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content">

      <div class="modal-header border-0">
        <h5 class="fw-bold mb-0">Tenant Occupancy</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST"
            action="{{ route('property.users.occupancy-dates', [$property->slug, $user->slug]) }}">
        @csrf
        @method('PUT')

        <div class="modal-body">

          <div class="mb-3">
            <label class="small fw-bold">Actual Arrival Date</label>
            <input type="date"
                   name="arrived_date"
                   class="af-input"
                   value="{{ $user->arrived_date }}">
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Actual Leave Date</label>
            <input type="date"
                   name="leave_date"
                   class="af-input"
                   value="{{ $user->leave_date }}">
          </div>

          <p class="small text-muted mb-0">
            These dates represent when the tenant actually occupied the unit.
          </p>

        </div>

        <div class="modal-footer border-0 d-flex justify-content-between">
          <button type="button"
                  class="btn btn-light soft-btn"
                  data-bs-dismiss="modal">
            Cancel
          </button>

          <button type="submit" class="af-btn">
            <i class="fas fa-save me-1"></i>
            Save
          </button>
        </div>

      </form>
    </div>
  </div>
</div>



@endsection
