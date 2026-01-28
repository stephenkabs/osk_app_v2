<!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="changeStatusForm" method="POST">
      @csrf

      <div class="modal-content apple-card" style="border-radius:16px;">
        <div class="modal-header border-0 pb-0">
          <h5 class="modal-title fw-600" id="changeStatusLabel">Change User Status</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body pt-2">
          <p class="mb-2" id="statusUserName">
            <strong>User:</strong> —
          </p>

          <div class="apple-form-group mb-3">
            <label for="statusSelect" class="form-label fw-500">Select new status</label>
            <select class="form-select apple-select" id="statusSelect" name="status" required>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>

          <div class="alert alert-warning d-none" id="statusWarning">
            ⚠️ You’re changing the user’s status. Make sure this is intended.
          </div>
        </div>

        <div class="modal-footer border-0 pt-0">
          <button type="button" class="btn-apple btn-ghost" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-apple btn-amber">Update Status</button>
        </div>
      </div>
    </form>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal  = document.getElementById('changeStatusModal');
  const form   = document.getElementById('changeStatusForm');
  const select = document.getElementById('statusSelect');
  const nameEl = document.getElementById('statusUserName');
  const warn   = document.getElementById('statusWarning');

  modal.addEventListener('show.bs.modal', (e) => {
    const btn = e.relatedTarget;
    form.action = btn.getAttribute('data-action'); // critical
    nameEl.textContent = `User: ${btn.getAttribute('data-user-name')}`;
    const current = btn.getAttribute('data-current-status') || 'pending';
    [...select.options].forEach(o => o.selected = (o.value === current));

    // show warning if "rejected" is selected
    select.addEventListener('change', () => {
      if (select.value === 'rejected') warn.classList.remove('d-none');
      else warn.classList.add('d-none');
    }, { once: true });
  });
});
</script>
@endpush

@push('styles')
<style>
.apple-card {
  background: #fff;
  box-shadow: 0 8px 30px rgba(0,0,0,0.06);
  border: 1px solid rgba(0,0,0,0.06);
}

/* Buttons */
.btn-apple {
  border: 0;
  border-radius: 12px;
  padding: 10px 16px;
  font-weight: 600;
  transition: transform .05s ease, box-shadow .2s ease;
}
.btn-apple:active { transform: translateY(1px); }

.btn-success { background:#22c55e; color:#fff; }     /* Green */
.btn-danger-apple { background:#ef4444; color:#fff;} /* Red */
.btn-amber { background:#ffbf00; color:#fff; }       /* Amber/Orange */
.btn-ghost { background:#f3f4f6; color:#111827; }
.btn-ghost:hover { background:#e5e7eb; }

/* Form & select */
.apple-form-group label { font-weight:500; }
.apple-select { border-radius: 12px; padding:8px 12px; }
</style>
@endpush
