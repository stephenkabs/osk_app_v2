@extends('layouts.app')

@section('content')

@push('styles')
<style>
.apple-card {
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(14px);
    border-radius: 20px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 18px 40px rgba(0,0,0,.08);
}

.apple-table th {
    font-size: 12px;
    text-transform: uppercase;
    color: #6b7280;
    letter-spacing: .08em;
}

.apple-table td {
    vertical-align: middle;
}

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

.permission-chip {
    padding: 6px 14px;
    border-radius: 999px;
    background: #eff6ff;
    color: #1d4ed8;
    font-weight: 600;
    font-size: 12px;
}
</style>
@endpush

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Permissions</h4>
        <p class="text-muted mb-0">
            Manage system access permissions
        </p>
    </div>

    <button class="btn apple-btn apple-btn-danger"
            data-bs-toggle="modal"
            data-bs-target="#addPermissionModal">
        + Add Permission
    </button>
</div>

{{-- CARD --}}
<div class="apple-card p-4">

    {{-- SEARCH --}}
    <input type="text"
           id="permission-search"
           class="form-control rounded-pill mb-3 fw-semibold"
           placeholder="Search permissions…">

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table apple-table align-middle">
            <thead>
                <tr>
                    <th>Permission</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody id="permission-table-body">
                @foreach ($permissions as $permission)
                    <tr>
                        <td>
                            <span class="permission-chip">
                                {{ $permission->name }}
                            </span>
                        </td>
                        <td class="text-end">

                            <button class="btn btn-light apple-btn me-1"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editPermissionModal"
                                    data-id="{{ $permission->id }}"
                                    data-name="{{ $permission->name }}">
                                Edit
                            </button>

                            <button class="btn btn-light apple-btn text-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deletePermissionModal"
                                    data-route="{{ route('permission.destroy',$permission->id) }}">
                                Delete
                            </button>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- MODALS --}}
@include('permission._add_modal')
@include('permission._edit_modal')
@include('permission._delete_modal')

@push('scripts')
<script>
/* SEARCH */
document.getElementById('permission-search').addEventListener('input', function () {
    const q = this.value.toLowerCase();
    document.querySelectorAll('#permission-table-body tr').forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(q) ? '' : 'none';
    });
});

/* EDIT MODAL */
document.getElementById('editPermissionModal').addEventListener('show.bs.modal', function (e) {
    const btn = e.relatedTarget;
    this.querySelector('input[name=name]').value = btn.dataset.name;
    this.querySelector('form').action = `/permission/${btn.dataset.id}`;
});

/* DELETE MODAL */
document.getElementById('deletePermissionModal').addEventListener('show.bs.modal', function (e) {
    this.querySelector('form').action = e.relatedTarget.dataset.route;
});
</script>
@endpush

@endsection
