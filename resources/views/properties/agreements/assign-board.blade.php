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

/* ===============================
   👤 TENANTS GRID (2 COLUMNS)
================================ */

#tenantList{
  display:grid;
  grid-template-columns:repeat(2, minmax(0,1fr));
  gap:10px;
  max-height:70vh;
  overflow:auto;
  padding-right:6px;
}

.drag-tenant{
  display:flex;
  align-items:center;
  gap:8px;
  padding:8px 12px;
  border-radius:12px;
  background:#f9fafb;
  border:1px solid #e5e7eb;
  font-size:14px;
  font-weight:600;
  cursor:grab;
  transition:.18s ease;
  user-select:none;
}

.drag-tenant i{
  font-size:13px;
}

.drag-tenant:hover{
  background:#eef2ff;
  border-color:#c7d2fe;
}

.drag-tenant:active{
  cursor:grabbing;
  opacity:.8;
}

.drag-tenant.bg-light{
  background:#f0fdf4;
  border-color:#bbf7d0;
  cursor:not-allowed;
}

.drag-tenant.bg-warning-subtle{
  background:#fffbeb;
  border-color:#fde68a;
  cursor:not-allowed;
}

.tenant-meta{
  font-size:12px;
  color:#6b7280;
  line-height:1.2;
}

/* ===============================
   🚪 UNITS (DROP TARGETS)
================================ */

.drop-unit{
  background:#fff;
  border:2px dashed #e5e7eb;
  border-radius:18px;
  padding:18px;
  min-height:90px;
  text-align:center;
  font-weight:800;
  transition:.18s ease;
  display:flex;
  flex-direction:column;
  justify-content:center;
}

.drop-unit small{
  color:#6b7280;
  font-weight:700;
}

.drop-unit.drag-over{
  border-color:#22c55e;
  background:#ecfdf5;
  transform:scale(1.02);
}

.drop-unit.assigned{
  border-style:solid;
  border-color:#2563eb;
  background:#eff6ff;
}

/* ===============================
   🍎 MODALS
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

.af-btn{
  background:#0b0c0f;
  color:#fff;
  border:none;
  border-radius:12px;
  padding:10px 16px;
  font-weight:800;
}

.soft-btn{
  border-radius:12px;
  padding:10px 16px;
  border:1px solid #e6e8ef;
  font-weight:700;
}

.af-btn.copied {
  background: #16a34a !important; /* green */
  transform: scale(1.02);
}

.af-btn.copied .copy-text {
  content: 'Copied ✓';
}

</style>
@endpush

@section('content')

<div class="container-fluid">
  <h3 class="fw-bold mb-4">
    Assign Lease • {{ $property->property_name }}
  </h3>

  <div class="row g-4">

    {{-- LEFT: TENANTS --}}
    <div class="col-md-5">
      <div class="apple-card">
        <h6 class="fw-bold mb-3">Tenants</h6>

        <div id="tenantList">
          @foreach($tenants as $tenant)

            @php
              $lease = $tenant->leases->first();
            @endphp

            {{-- TENANT WITH UNIT --}}
            @if($lease && $lease->unit)
              <div class="drag-tenant bg-light" draggable="false">
                <i class="fas fa-home text-success"></i>
                <div class="flex-grow-1">
                  <div>{{ $tenant->name }}</div>
                  <div class="tenant-meta">Unit {{ $lease->unit->code }}</div>
                </div>
              </div>

            {{-- TENANT PENDING --}}
            @elseif($lease)
              <div class="drag-tenant bg-warning-subtle" draggable="false">
                <i class="fas fa-clock text-warning"></i>
                <div class="flex-grow-1">
                  <div>{{ $tenant->name }}</div>
                  <div class="tenant-meta">Pending</div>
                </div>
              </div>

            {{-- FREE TENANT --}}
            @else
              <div class="drag-tenant"
                   draggable="true"
                   data-user="{{ $tenant->id }}">
                <i class="fas fa-user text-primary"></i>
                <div class="flex-grow-1">
                  <div>{{ $tenant->name }}</div>
                </div>
              </div>
            @endif

          @endforeach
        </div>
      </div>
    </div>

    {{-- RIGHT: UNITS --}}
    <div class="col-md-7">
      <div class="apple-card">
        <h6 class="fw-bold mb-3">Available Units</h6>

        <div class="row g-3">
          @foreach($units as $unit)
            <div class="col-md-6">
              <div class="drop-unit"
                   data-unit="{{ $unit->id }}">
                <strong>{{ $unit->code }}</strong>
                <small>K{{ number_format($unit->rent_amount,2) }}</small>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>
</div>

{{-- ASSIGN MODAL --}}
<div class="modal fade modal-apple" id="assignLeaseModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="fw-bold mb-0">Assign Lease</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="date" id="leaseStartDate" class="af-input" required>
      </div>
      <div class="modal-footer border-0">
        <button class="soft-btn" data-bs-dismiss="modal">Cancel</button>
        <button class="af-btn" id="confirmAssignBtn">Assign</button>
      </div>
    </div>
  </div>
</div>

{{-- SUCCESS MODAL --}}
<div class="modal fade modal-apple" id="leaseSuccessModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="fw-bold mb-0">Lease Assigned</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="text" id="leaseLinkInput" class="af-input mb-2" readonly>
        <button class="af-btn w-100 mb-2" id="copyLeaseBtn">
      <span class="copy-text">Copy Link</span>
        </button>

        <a id="whatsappLeaseBtn" target="_blank"
           class="btn btn-success w-100 fw-bold rounded-3">
          <i class="fab fa-whatsapp me-2"></i> Send via WhatsApp
        </a>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

  let draggedUser = null;
  let targetUnit  = null;

  const assignModal  = new bootstrap.Modal(document.getElementById('assignLeaseModal'));
  const successModal = new bootstrap.Modal(document.getElementById('leaseSuccessModal'));

  document.querySelectorAll('.drag-tenant[draggable="true"]').forEach(el => {
    el.addEventListener('dragstart', () => draggedUser = el.dataset.user);
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
      assignModal.show();
    });
  });

  document.getElementById('confirmAssignBtn').onclick = async () => {
    const startDate = document.getElementById('leaseStartDate').value;
    if (!startDate || !draggedUser || !targetUnit) return;

    const res = await fetch("{{ route('property.lease.assign.api', $property->slug) }}", {
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':'{{ csrf_token() }}',
        'Accept':'application/json'
      },
      body:JSON.stringify({
        user_id:draggedUser,
        unit_id:targetUnit.dataset.unit,
        start_date:startDate
      })
    });

    const data = await res.json();
    if (!res.ok) return alert(data.error || 'Failed');

    assignModal.hide();
    document.getElementById('leaseLinkInput').value = data.sign_url;

    // document.getElementById('copyLeaseBtn').onclick = () =>
    //   navigator.clipboard.writeText(data.sign_url);
document.getElementById('copyLeaseBtn').onclick = () => {
  const btn = document.getElementById('copyLeaseBtn');
  const textEl = btn.querySelector('.copy-text');

  navigator.clipboard.writeText(document.getElementById('leaseLinkInput').value)
    .then(() => {
      // ✅ visual feedback
      textEl.textContent = 'Copied ✓';
      btn.classList.add('copied');

      // ✅ after short delay → close modal + reload
      setTimeout(() => {
        const modalEl = document.getElementById('leaseSuccessModal');
        const modal = bootstrap.Modal.getInstance(modalEl);

        modal.hide(); // close modal

        // reload after modal animation
        setTimeout(() => {
          window.location.reload();
        }, 300);

      }, 800);
    });
};



    document.getElementById('whatsappLeaseBtn').href =
      `https://wa.me/?text=${encodeURIComponent('Please sign your lease:\n\n'+data.sign_url)}`;

    successModal.show();
  };

});
</script>
@endpush

@endsection
