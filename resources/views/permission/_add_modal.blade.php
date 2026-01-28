<div class="modal fade" id="addPermissionModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content apple-card p-4">

            <h5 class="fw-bold mb-3">Add Permission</h5>

            <form method="POST" action="{{ route('permission.store') }}">
                @csrf

                <input type="text"
                       name="name"
                       class="form-control rounded-pill fw-semibold mb-3"
                       placeholder="Permission name"
                       required>

                <div class="text-end">
                    <button class="btn btn-light rounded-pill me-2"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>
                    <button class="btn apple-btn-danger rounded-pill">
                        Save
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
