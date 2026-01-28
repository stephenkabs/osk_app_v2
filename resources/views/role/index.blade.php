@extends('layouts.app')

@section('content')

<style>
/* ===============================
   🍎 APPLE CARD
================================ */
.apple-card {
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 20px 45px rgba(0,0,0,.08);
}

/* ===============================
   🍎 TABLE
================================ */
.apple-table th {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .04em;
    color: #6b7280;
    border-bottom: 1px solid #e5e7eb !important;
}

.apple-table td {
    vertical-align: middle;
}

/* ===============================
   🍎 BUTTONS
================================ */
.apple-btn {
    border-radius: 999px;
    font-weight: 600;
    padding: 6px 14px;
}

.apple-btn-danger {
    background: linear-gradient(135deg,#9b0000,#7d0000);
    color: #fff;
    border: none;
}

.apple-btn-danger:hover {
    background: linear-gradient(135deg,#b00000,#8b0000);
}

.apple-btn-light {
    background: #f3f4f6;
    border: 1px solid #e5e7eb;
}

.apple-btn {
    border-radius: 999px;
    font-weight: 600;
    padding: 8px 18px;
}

.apple-btn-danger {
    background: linear-gradient(135deg,#9b0000,#7d0000);
    color: #fff;
    border: none;
    box-shadow: 0 10px 25px rgba(155,0,0,.35);
}

.apple-btn-danger:hover {
    background: linear-gradient(135deg,#b00000,#8b0000);
}

.apple-btn-light {
    background: #f3f4f6;
    border: none;
}

</style>

<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Roles Management</h3>
            <p class="text-muted mb-0">
                Manage system roles and permissions
            </p>
        </div>

        <div class="d-flex gap-2">
            <a href="/general_settings" class="btn apple-btn apple-btn-light">
                ← Settings
            </a>
    <button class="btn apple-btn apple-btn-danger btn-sm"
        data-bs-toggle="modal"
        data-bs-target="#addRoleModal">
    Add Role
</button>

        </div>
    </div>

    {{-- SUCCESS --}}
    {{-- @if(session('status'))
        <div class="alert alert-success rounded-4">
            {{ session('status') }}
        </div>
    @endif --}}

    {{-- ROLES TABLE --}}
    <div class="apple-card p-4">
        <div class="table-responsive">
            <table class="table apple-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $role)
                        <tr>
                            <td>
                                <span class="fw-semibold">
                                    {{ $role->name }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-2">

                                    <a href="{{ url('role/'.$role->id.'/add-permission') }}"
                                       class="btn btn-sm apple-btn apple-btn-light">
                                        Permissions
                                    </a>

        <button class="btn btn-sm apple-btn apple-btn-light"
        data-bs-toggle="modal"
        data-bs-target="#editRoleModal"
        data-id="{{ $role->id }}"
        data-name="{{ $role->name }}">
    Edit
</button>


                                    <button class="btn btn-sm apple-btn apple-btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteRoleModal"
                                            data-route="{{ route('role.destroy', $role->id) }}">
                                        Delete
                                    </button>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="text-center text-muted py-5">
                                No roles created yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ===============================
   DELETE MODAL
================================ --}}
<div class="modal fade" id="deleteRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content apple-card p-3">
            <div class="modal-header border-0">
                <h5 class="fw-bold">Delete Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <p class="text-muted mb-0">
                    Are you sure you want to delete this role?
                    This action cannot be undone.
                </p>
            </div>

            <div class="modal-footer border-0">
                <button type="button"
                        class="btn apple-btn apple-btn-light"
                        data-bs-dismiss="modal">
                    Cancel
                </button>

                <form id="deleteRoleForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn apple-btn apple-btn-danger">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- ===============================
   🍎 EDIT ROLE MODAL
================================ --}}
<div class="modal fade" id="editRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content apple-card p-3">
            <form id="editRoleForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-header border-0">
                    <h5 class="fw-bold mb-0">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Role Name
                        </label>

                        <input type="text"
                               name="name"
                               id="editRoleName"
                               class="form-control rounded-pill fw-semibold"
                               placeholder="Enter role name"
                               required>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn apple-btn apple-btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button class="btn apple-btn apple-btn-danger">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===============================
   🍎 ADD ROLE MODAL
================================ --}}
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content apple-card p-3">

            <form method="POST" action="{{ route('role.store') }}">
                @csrf

                <div class="modal-header border-0">
                    <h5 class="fw-bold mb-0">➕ Add New Role</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Role Name
                        </label>

                        <input type="text"
                               name="name"
                               class="form-control rounded-pill fw-semibold"
                               placeholder="e.g. Admin, Auditor, Officer"
                               required>
                    </div>
                </div>

                <div class="modal-footer border-0">
                    <button type="button"
                            class="btn apple-btn apple-btn-light"
                            data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button class="btn apple-btn apple-btn-danger">
                        Create Role
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>


@push('scripts')
<script>
document.getElementById('deleteRoleModal')
    .addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const route  = button.getAttribute('data-route');
        document.getElementById('deleteRoleForm').action = route;
    });
</script>
@endpush
<script>
document.getElementById('editRoleModal')
    .addEventListener('show.bs.modal', function (event) {

        const button = event.relatedTarget;

        const roleId   = button.getAttribute('data-id');
        const roleName = button.getAttribute('data-name');

        // Set form action
        document.getElementById('editRoleForm').action =
            `/role/${roleId}`;

        // Fill input
        document.getElementById('editRoleName').value = roleName;
    });
</script>

@endsection
