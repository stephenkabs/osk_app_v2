@extends('layouts.app')

@section('content')

@push('styles')
<style>
.permission-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    margin: 6px;
    border-radius: 999px;
    background: #f3f4f6;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all .15s ease;
    border: 1px solid #e5e7eb;
}

.permission-chip input {
    margin: 0;
}

.permission-chip:hover {
    background: #e5e7eb;
}

.permission-chip.active {
    background: linear-gradient(135deg,#ecfdf5,#d1fae5);
    color: #047857;
    border-color: #a7f3d0;
}

.permission-section {
    background: #fafafa;
    border-radius: 16px;
    padding: 16px;
    border: 1px dashed #e5e7eb;
}

/* ===============================
   🍎 APPLE PAGE HEADER
================================ */
.apple-page-header {
    background: linear-gradient(
        180deg,
        rgba(255,255,255,0.85),
        rgba(255,255,255,0.98)
    );
    backdrop-filter: blur(14px);
    border-radius: 20px;
    padding: 22px 26px;
    border: 1px solid #e5e7eb;
    box-shadow: 0 18px 40px rgba(0,0,0,.06);
}

/* Title */
.apple-page-header h4 {
    font-weight: 800;
    letter-spacing: -0.02em;
    margin-bottom: 4px;
}

/* Subtitle */
.apple-page-header p {
    font-size: 14px;
    color: #6b7280;
}

/* Back Button */
.apple-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;

    padding: 8px 20px;
    border-radius: 999px;

    font-weight: 600;
    font-size: 13px;

    background: #f9fafb;
    border: 1px solid #e5e7eb;
    color: #111827;

    transition: all .18s ease;
}

/* Hover / Active */
.apple-back-btn:hover {
    background: #f3f4f6;
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(0,0,0,.08);
}

.apple-back-btn:active {
    transform: translateY(0);
    box-shadow: inset 0 3px 6px rgba(0,0,0,.12);
}

</style>
@endpush

{{-- ===============================
   PAGE HEADER
================================ --}}
<div class="apple-page-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4>Role Permissions</h4>
        <p class="mb-0">
            Manage access rights for <strong>{{ $role->name }}</strong>
        </p>
    </div>

    <a href="{{ route('role.index') }}"
       class="apple-back-btn">
        ← Back
    </a>
</div>

{{-- ===============================
   CARD
================================ --}}
<div class="apple-card p-4">

    {{-- CARD HEADER --}}
    <div class="mb-4 border-bottom pb-3">
        <h5 class="fw-semibold mb-1">
            Permissions for "{{ $role->name }}"
        </h5>
        <p class="text-muted mb-0">
            Toggle permissions to control what this role can access
        </p>
    </div>

    {{-- ERROR --}}
    @error('permission')
        <div class="alert alert-danger rounded-4">
            {{ $message }}
        </div>
    @enderror

    {{-- FORM --}}
    <form method="POST"
          action="{{ url('role/'.$role->id.'/add-permission') }}">
        @csrf
        @method('PUT')

        {{-- SELECT ALL --}}
        <div class="mb-3">
            <label class="permission-chip">
                <input type="checkbox" id="selectAll">
                Select All Permissions
            </label>
        </div>

        {{-- PERMISSIONS --}}
        <div class="permission-section mb-4">
            <div class="d-flex flex-wrap">
                @foreach ($permission as $perm)
                    <label class="permission-chip {{ in_array($perm->id,$rolePermission) ? 'active' : '' }}">
                        <input type="checkbox"
                               class="permission-checkbox"
                               name="permission[]"
                               value="{{ $perm->name }}"
                               {{ in_array($perm->id,$rolePermission) ? 'checked' : '' }}>
                        {{ $perm->name }}
                    </label>
                @endforeach
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="d-flex justify-content-end gap-2">
            <a href="{{ route('role.index') }}"
               class="btn btn-light rounded-pill px-4">
                Cancel
            </a>

            <button class="btn btn-danger rounded-pill px-4">
                Save Changes
            </button>
        </div>

    </form>
</div>

@push('scripts')
<script>
document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.permission-checkbox').forEach(cb => {
        cb.checked = this.checked;
        cb.closest('.permission-chip')
            ?.classList.toggle('active', this.checked);
    });
});

document.querySelectorAll('.permission-checkbox').forEach(cb => {
    cb.addEventListener('change', function () {
        this.closest('.permission-chip')
            ?.classList.toggle('active', this.checked);
    });
});
</script>
@endpush

@endsection
