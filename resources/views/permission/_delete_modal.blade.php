<div class="modal fade" id="deletePermissionModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content apple-card p-4 text-center">

            <h5 class="fw-bold text-danger mb-2">Delete Permission</h5>
            <p class="text-muted mb-4">
                This action cannot be undone.
            </p>

            <form method="POST">
                @csrf
                @method('DELETE')

                <button class="btn btn-light rounded-pill me-2"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <button class="btn apple-btn-danger rounded-pill">
                    Delete
                </button>
            </form>

        </div>
    </div>
</div>
