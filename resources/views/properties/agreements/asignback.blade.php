@extends('layouts.app')
@push('styles')
<style>
/* ===============================
   🍎 ASSIGN LEASE BOARD
================================ */

.apple-card{
  background:#fff;
  border-radius:22px;
  border:1px solid #e6e8ef;
  padding:22px;
  box-shadow:0 18px 45px rgba(0,0,0,.06);
}

/* HEADERS */
.assign-title{
  font-size:18px;
  font-weight:800;
  letter-spacing:-.02em;
}

/* ===============================
   👤 TENANTS (DRAGGABLE)
================================ */

#tenantList{
  display:flex;
  flex-direction:column;
  gap:10px;
  max-height:70vh;
  overflow:auto;
  padding-right:4px;
}

.drag-tenant{
  display:flex;
  align-items:center;
  gap:10px;
  padding:14px 16px;
  border-radius:14px;
  background:#f9fafb;
  border:1px solid #e5e7eb;
  font-weight:700;
  cursor:grab;
  transition:.18s ease;
  user-select:none;
}

.drag-tenant i{
  color:#2563eb;
}

.drag-tenant:hover{
  background:#eef2ff;
  border-color:#c7d2fe;
  transform:translateY(-2px);
  box-shadow:0 6px 18px rgba(0,0,0,.08);
}

.drag-tenant:active{
  cursor:grabbing;
  transform:scale(.98);
  opacity:.8;
}

/* ===============================
   🚪 UNITS (DROP TARGET)
================================ */

.drop-unit{
  background:#fff;
  border:2px dashed #e5e7eb;
  border-radius:18px;
  padding:20px;
  min-height:96px;
  text-align:center;
  font-weight:800;
  transition:.18s ease;
  display:flex;
  flex-direction:column;
  justify-content:center;
  gap:6px;
}

.drop-unit strong{
  font-size:16px;
}

.drop-unit small{
  color:#6b7280;
  font-weight:700;
}

/* Drag hover state */
.drop-unit.drag-over{
  border-color:#22c55e;
  background:#ecfdf5;
  box-shadow:0 10px 30px rgba(34,197,94,.25);
  transform:scale(1.02);
}

/* Assigned animation */
.drop-unit.assigned{
  border-style:solid;
  border-color:#2563eb;
  background:#eff6ff;
}

/* ===============================
   ✨ SCROLLBAR (SUBTLE)
================================ */

#tenantList::-webkit-scrollbar{
  width:6px;
}
#tenantList::-webkit-scrollbar-thumb{
  background:#d1d5db;
  border-radius:999px;
}

/* ===============================
   📱 MOBILE TWEAKS
================================ */

@media (max-width:768px){
  #tenantList{
    max-height:40vh;
  }
}

/* ===============================
   🍎 APPLE MODAL
================================ */

.modal-apple .modal-content{
  border-radius:18px;
  border:1px solid #e6e8ef;
  box-shadow:0 30px 80px rgba(0,0,0,.2);
}

.af-input{
  width:100%;
  border:1px solid #e6e8ef;
  border-radius:12px;
  padding:12px 14px;
  font-weight:700;
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
.drag-tenant.bg-light{
  background:#f0fdf4;
  border-color:#bbf7d0;
}

.drag-tenant.bg-warning-subtle{
  background:#fffbeb;
  border-color:#fde68a;
}

/* ===============================
   👤 TENANT – COMPACT MODE
================================ */

.drag-tenant.compact{
  padding:8px 12px;          /* ⬇ reduce height */
  border-radius:12px;
  font-size:14px;
  gap:8px;
}

.drag-tenant.compact i{
  font-size:13px;
}

.drag-tenant .tenant-meta{
  font-size:12px;
  line-height:1.2;
  color:#6b7280;
}


</style>
@endpush

@section('content')

<div class="container-fluid">
  <h3 class="fw-bold mb-4">
    Assign Lease • {{ $property->property_name }}
  </h3>

  <div class="row g-4">
<div id="tenantList">
@foreach($tenants as $tenant)

@php
  $lease = $tenant->leases->first();
@endphp

<div class="drag-tenant compact
  {{ $lease && $lease->unit ? 'bg-light' : '' }}
  {{ $lease && !$lease->unit ? 'bg-warning-subtle' : '' }}"
  {{ $lease ? 'draggable=false style=cursor:not-allowed' : 'draggable=true data-user='.$tenant->id }}>

  {{-- ICON --}}
  <i class="fas
    {{ $lease && $lease->unit ? 'fa-home text-success' : '' }}
    {{ $lease && !$lease->unit ? 'fa-clock text-warning' : '' }}
    {{ !$lease ? 'fa-user text-primary' : '' }}
  "></i>

  {{-- NAME + META --}}
  <div class="flex-grow-1">
    <div class="fw-semibold">{{ $tenant->name }}</div>

    @if($lease && $lease->unit)
      <div class="tenant-meta">Unit {{ $lease->unit->code }}</div>
    @elseif($lease)
      <div class="tenant-meta">Pending</div>
    @endif
  </div>

</div>

@endforeach

</div>


    {{-- UNITS --}}
    <div class="col-md-7">
      <div class="apple-card">
        <h6 class="fw-bold mb-3">Available Units</h6>

        <div class="row g-3">
          @foreach($units as $unit)
            <div class="col-md-6">
              <div class="drop-unit"
                   data-unit="{{ $unit->id }}">
                <strong>{{ $unit->code }}</strong><br>
                <small>K{{ number_format($unit->rent_amount,2) }}</small>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    </div>

  </div>
</div>

{{-- ASSIGN LEASE MODAL --}}
<div class="modal fade modal-apple" id="assignLeaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content">

      <div class="modal-header border-0">
        <h5 class="fw-bold mb-0">
          <i class="fas fa-file-signature me-2"></i>
          Assign Lease
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-0">
        <p class="text-muted small mb-3">
          Select the lease start date to continue.
        </p>

        <input type="date"
               id="leaseStartDate"
               class="af-input"
               required>
      </div>

      <div class="modal-footer border-0 d-flex justify-content-between">
        <button type="button"
                class="btn btn-light soft-btn"
                data-bs-dismiss="modal">
          Cancel
        </button>

        <button type="button"
                id="confirmAssignBtn"
                class="af-btn">
          <i class="fas fa-check me-1"></i>
          Assign & Generate
        </button>
      </div>

    </div>
  </div>
</div>
{{-- SUCCESS MODAL --}}
<div class="modal fade modal-apple" id="leaseSuccessModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">

      <div class="modal-header border-0">
        <h5 class="fw-bold mb-0">
          <i class="fas fa-check-circle text-success me-2"></i>
          Lease Assigned
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body pt-0">
        <p class="text-muted small">
          Share this link with the tenant to review and sign the lease.
        </p>

        <div class="d-flex gap-2">
          <input type="text"
                 id="leaseLinkInput"
                 class="af-input"
                 readonly>
          <button class="af-btn"
                  id="copyLeaseBtn">
            Copy
          </button>
        </div>

        <a id="whatsappLeaseBtn"
           target="_blank"
           class="btn btn-success w-100 mt-3 fw-bold rounded-3">
          <i class="fab fa-whatsapp me-2"></i>
          Send via WhatsApp
        </a>
      </div>

      <div class="modal-footer border-0">
        <button class="btn soft-btn w-100"
                data-bs-dismiss="modal">
          Done
        </button>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

  let draggedUser = null;
  let targetUnit  = null;

  const modalEl = document.getElementById('assignLeaseModal');
  const modal   = new bootstrap.Modal(modalEl);

  document.querySelectorAll('.drag-tenant').forEach(el => {
    el.addEventListener('dragstart', () => {
      draggedUser = el.dataset.user;
      el.style.opacity = '0.5';
    });

    el.addEventListener('dragend', () => {
      el.style.opacity = '1';
    });
  });

  document.querySelectorAll('.drop-unit').forEach(unit => {

    unit.addEventListener('dragover', e => {
      e.preventDefault();
      unit.classList.add('drag-over');
    });

    unit.addEventListener('dragleave', () => {
      unit.classList.remove('drag-over');
    });

    unit.addEventListener('drop', () => {
      unit.classList.remove('drag-over');

      if (!draggedUser) return;

      targetUnit = unit;
      document.getElementById('leaseStartDate').value = '';

      modal.show();
    });
  });

  document.getElementById('confirmAssignBtn')
    .addEventListener('click', async () => {

      const startDate = document.getElementById('leaseStartDate').value;
      if (!startDate || !draggedUser || !targetUnit) return;

      targetUnit.classList.add('assigned');

      try {
        const res = await fetch(
          "{{ route('property.lease.assign.api', $property->slug) }}",
          {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              user_id: draggedUser,
              unit_id: targetUnit.dataset.unit,
              start_date: startDate
            })
          }
        );

        const data = await res.json();

if (!res.ok) {
  console.error('FULL RESPONSE', data);
  alert(JSON.stringify(data, null, 2));
  unit.classList.remove('assigned');
  return;
}


 modal.hide();

const successModalEl = document.getElementById('leaseSuccessModal');
const successModal   = new bootstrap.Modal(successModalEl);

document.getElementById('leaseLinkInput').value = data.sign_url;

// Copy button
document.getElementById('copyLeaseBtn').onclick = () => {
  navigator.clipboard.writeText(data.sign_url);
};

// WhatsApp button
document.getElementById('whatsappLeaseBtn').href =
  `https://wa.me/?text=${encodeURIComponent(
    'Please review and sign your lease using this link:\n\n' + data.sign_url
  )}`;

successModal.show();


      } catch (e) {
        console.error(e);
        alert('Network error');
        targetUnit.classList.remove('assigned');
      }
    });

});
</script>
@endpush

@endsection

