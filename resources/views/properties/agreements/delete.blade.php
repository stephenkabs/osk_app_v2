<style>
  .apple-modal {
    background:#fff;
    border-radius:20px;
    border:1px solid #e5e7eb;
    box-shadow:0 16px 40px rgba(0,0,0,0.15);
    animation: modalPop .3s ease;
  }

  @keyframes modalPop {
    0% { transform:scale(0.9); opacity:0; }
    100% { transform:scale(1); opacity:1; }
  }

  .apple-modal .icon-circle {
    width:60px; height:60px;
    border-radius:50%;
    background:#fce9e9;
    color:#d32f2f;
    font-size:24px;
    display:flex; align-items:center; justify-content:center;
    margin:0 auto;
  }

  .apple-modal h5 { font-weight:800; letter-spacing:-.02em; }
  .apple-modal p { line-height:1.5; color:#6b7280; }

  .cancel-btn {
    background:#f5f5f7;
    border:none;
    border-radius:10px;
    font-weight:700;
    transition:.2s;
  }
  .cancel-btn:hover { background:#e4e4e7; }

  .delete-btn {
    background:#d32f2f;
    border:none;
    border-radius:10px;
    font-weight:700;
    transition:.2s;
  }
  .delete-btn:hover { background:#b71c1c; }
</style>

<!-- Apple-Style Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:380px;">
    <div class="modal-content apple-modal">
      <div class="modal-body text-center">
        <div class="icon-circle mb-3">
          <i class="fas fa-trash-alt"></i>
        </div>
        <h5 class="fw-bold mb-2">Delete Agreement?</h5>
        <p class="text-muted mb-4" style="font-size:14px;">
          Are you sure you want to delete this lease agreement? <br>
          This action <strong>cannot be undone.</strong>
        </p>
        <div class="d-flex justify-content-center gap-2">
          <button type="button" class="btn btn-light cancel-btn px-4 py-2" data-bs-dismiss="modal">
            Cancel
          </button>
          <form id="deleteForm" method="POST" class="m-0">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger delete-btn px-4 py-2">
              Delete
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
