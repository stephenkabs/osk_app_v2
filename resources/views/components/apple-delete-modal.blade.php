<style>
    /* Apple-style modal */
.modal-apple .modal-content {
  border-radius: 18px;
  border: 1px solid #e6e8ef;
  box-shadow: 0 30px 80px rgba(0,0,0,.18);
  background: #fff;
}

/* Icon */
.danger-icon {
  width: 52px;
  height: 52px;
  border-radius: 14px;
  background: #fee2e2;
  color: #b91c1c;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
}

/* Buttons */
.soft-btn {
  font-weight: 700;
  border-radius: 12px;
  padding: 10px 18px;
  border: 1px solid #e6e8ef;
}

.soft-btn:hover {
  background: #f3f4f6;
}

.solid-danger {
  font-weight: 800;
  border-radius: 12px;
  padding: 10px 18px;
  background: #dc2626;
  border: none;
}

.solid-danger:hover {
  background: #b91c1c;
}

</style>
<div class="modal fade modal-apple" id="appleDeleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">

      <div class="modal-header border-0 text-center d-block pb-0">
        <div class="danger-icon mx-auto mb-2">
          <i class="fas fa-trash"></i>
        </div>
        <h5 class="modal-title fw-bold" id="deleteModalTitle">Delete Item</h5>
      </div>

      <div class="modal-body text-center pt-2">
        <p class="text-muted mb-0" id="deleteModalMessage">
          This action cannot be undone.
        </p>
      </div>

      <div class="modal-footer border-0 d-flex justify-content-between">
        <button type="button"
                class="btn btn-light soft-btn"
                data-bs-dismiss="modal">
          Cancel
        </button>

        <form method="POST" id="deleteModalForm">
          @csrf
          @method('DELETE')

          <button type="submit" class="btn btn-danger solid-danger">
            <i class="fas fa-trash me-1"></i> Delete
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = new bootstrap.Modal(
    document.getElementById('appleDeleteModal')
  );

  document.querySelectorAll('[data-delete]').forEach(btn => {
    btn.addEventListener('click', () => {

      document.getElementById('deleteModalTitle').textContent =
        btn.dataset.title || 'Delete Item';

      document.getElementById('deleteModalMessage').textContent =
        btn.dataset.message || 'This action cannot be undone.';

      document.getElementById('deleteModalForm').action =
        btn.dataset.action;

      modal.show();
    });
  });
});
</script>

