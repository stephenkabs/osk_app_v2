@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&display=swap');

:root {
  --black:#0b0c0f;
  --yellow:#e1a422;
  --yellow-soft:#fff7da;
  --muted:#6b7280;
  --border:#e5e7eb;
  --card:#ffffff;
  --radius:18px;
}

body {
  font-family: Inter, -apple-system, BlinkMacSystemFont,
               "SF Pro Text","SF Pro Display",
               "Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  -webkit-font-smoothing: antialiased;
  color:var(--black);
}

/* ===============================
   PAGE HEADER
================================ */
.card {
  border-radius:var(--radius);
}

.card h4 {
  font-weight:900;
  letter-spacing:-.03em;
}

.card small {
  font-weight:700;
}

/* ===============================
   SEARCH INPUT
================================ */
#userSearch {
  font-weight:700;
  font-size:15px;
  letter-spacing:-.01em;
  padding:14px 22px;
  border-radius:999px;
  border:1.5px solid var(--border);
}

#userSearch:focus {
  border-color:var(--yellow);
  box-shadow:0 0 0 4px rgba(225,164,34,.18);
}

/* ===============================
   TABLE
================================ */
.table thead th {
  font-size:11px;
  text-transform:uppercase;
  letter-spacing:.14em;
  font-weight:800;
  color:var(--muted);
  border-bottom:1px solid var(--border);
}

.table td {
  padding:14px 16px;
  vertical-align:middle;
}

.table-hover tbody tr:hover {
  background:#fffdf5;
}

/* ===============================
   USER INFO
================================ */
.user-name {
  font-weight:900;
  font-size:15px;
  letter-spacing:-.02em;
  color:var(--black);
}

.user-email {
  font-size:12px;
  font-weight:600;
  color:var(--muted);
}

.table img.rounded-circle {
  border:2px solid var(--yellow-soft);
}

/* ===============================
   STATUS PILLS
================================ */
.status-pill {
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:5px 12px;
  border-radius:999px;
  font-size:12px;
  font-weight:800;
}

.status-approved {
  background:#ecfdf3;
  color:#065f46;
}

.status-pending {
  background:var(--yellow-soft);
  color:#7a5a00;
}

.status-disabled {
  background:#f3f4f6;
  color:#374151;
}

/* ===============================
   ACTIVE BADGE
================================ */
.badge {
  font-weight:800;
  border-radius:999px;
  padding:6px 12px;
}

.badge.bg-primary {
  background:linear-gradient(180deg,var(--yellow),#c79218)!important;
  color:#000;
}

.badge.bg-secondary {
  background:#f3f4f6!important;
  color:#374151;
}

/* ===============================
   ACTIONS
================================ */
.action-stack {
  display:inline-flex;
  gap:10px;
  align-items:center;
}

/* Approve button */
.btn-approve {
  background:linear-gradient(180deg,var(--black),#000);
  color:#fff;
  border:none;
  border-radius:999px;
  padding:7px 16px;
  font-weight:900;
  font-size:12px;
  box-shadow:0 8px 20px rgba(0,0,0,.25);
  transition:.25s cubic-bezier(.4,0,.2,1);
}

.btn-approve i {
  color:var(--yellow);
}

.btn-approve:hover {
  transform:translateY(-2px);
  box-shadow:0 14px 32px rgba(0,0,0,.35);
}

/* Dropdown */
.dropdown .btn-light {
  border-radius:999px;
  font-weight:700;
  border:1px solid var(--border);
  background:#fff;
}

.dropdown .btn-light:hover {
  background:#fffbea;
  border-color:var(--yellow);
}

/* ===============================
   MODAL (APPLE STYLE)
================================ */
.apple-modal .modal-content {
  border-radius:22px;
  border:1px solid var(--border);
  box-shadow:0 40px 90px rgba(0,0,0,.35);
}

.apple-modal h5 {
  font-weight:900;
  letter-spacing:-.03em;
}

.table-responsive {
  overflow: visible !important;
}


</style>

<!-- PAGE HEADER -->
<div class="card mb-4 border-0 shadow-sm" style="border-radius:16px;">
  <div class="card-body px-4 py-3">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <h4 class="fw-bold mb-0">Users</h4>
        <small class="text-muted">Manage system users & approvals</small>
      </div>

      <a href="{{ route('users.create') }}"
         class="btn btn-secondary rounded-pill px-4">
        <i class="bx bx-plus me-1"></i> Add User
      </a>
    </div>

    <input type="text"
           id="userSearch"
           class="form-control form-control-lg rounded-pill"
           placeholder="Search users…">
  </div>
</div>

<!-- USERS TABLE -->
<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" id="usersTable">
<thead class="table-light">
  <tr>
    <th>User</th>
    <th>Contact</th>
    <th>Roles</th>   {{-- 👈 NEW --}}
    <th>Status</th>
    <th>Active</th>
    <th class="text-end">Actions</th>
  </tr>
</thead>


      <tbody>
      @forelse($users as $user)
        <tr>

          {{-- USER --}}
          <td>
            <div class="d-flex align-items-center gap-2">
              <img src="{{ $user->profile_image
                    ? asset('storage/'.$user->profile_image)
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                   class="rounded-circle"
                   width="40"
                   height="40">
              <div>
                <div class="user-name">{{ $user->name }}</div>
                <div class="user-email">{{ $user->email }}</div>
              </div>
            </div>
          </td>

          {{-- CONTACT --}}
          <td>
            <div>{{ $user->whatsapp_phone ?? '—' }}</div>
            <small class="text-muted">{{ $user->country }}</small>
          </td>


          {{-- ROLES --}}
<td>
  <div class="d-flex flex-wrap gap-1">

    @forelse($user->roles as $role)
      <span class="role-chip role-{{ Str::slug($role->name) }}">
        {{ ucfirst(str_replace('_',' ',$role->name)) }}
      </span>
    @empty
      <span class="role-chip role-none">No role</span>
    @endforelse

  </div>
</td>


          {{-- STATUS --}}
          <td>
            @if($user->status === 'active')
              <span class="status-pill status-approved">
                <i class="bx bx-check"></i> Approved
              </span>
            @elseif($user->status === 'pending')
              <span class="status-pill status-pending">
                <i class="bx bx-time"></i> Pending
              </span>
            @else
              <span class="status-pill status-disabled">Disabled</span>
            @endif
          </td>

          {{-- ACTIVE --}}
          <td>
            <span class="badge bg-{{ $user->active ? 'primary' : 'secondary' }}">
              {{ $user->active ? 'Active' : 'Inactive' }}
            </span>
          </td>

          {{-- ACTIONS --}}
          <td class="text-end">
            <div class="action-stack justify-content-end">

              @if($user->status === 'pending')
              <button class="btn-approve"
                      data-bs-toggle="modal"
                      data-bs-target="#approveUser{{ $user->id }}">
                <i class="bx bx-check-circle"></i> Approve
              </button>
              @endif

              <div class="dropdown">
                <button class="btn btn-sm btn-light rounded-pill"
                        data-bs-toggle="dropdown">
                  More
                </button>

                <ul class="dropdown-menu dropdown-menu-end">
                  <li><a class="dropdown-item" href="{{ route('users.show', $user->slug) }}">View</a></li>
                  <li><a class="dropdown-item" href="{{ route('users.edit', $user->slug) }}">Edit</a></li>
                  <li>
                    <form method="POST" action="{{ route('users.destroy', $user->slug) }}">
                      @csrf @method('DELETE')
                      <button class="dropdown-item text-danger">Delete</button>
                    </form>
                  </li>
                </ul>
              </div>

            </div>
          </td>
        </tr>

        {{-- APPROVAL MODAL --}}
        @if($user->status === 'pending')
        <div class="modal fade apple-modal"
             id="approveUser{{ $user->id }}"
             tabindex="-1">
          <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
            <div class="modal-content">
              <form method="POST" action="{{ route('users.approve', $user) }}">
                @csrf @method('PUT')

                <div class="modal-body text-center p-4">
                  <div style="width:64px;height:64px;border-radius:50%;
                       background:linear-gradient(135deg,#16a34a,#22c55e);
                       color:#fff;display:flex;align-items:center;justify-content:center;
                       font-size:28px;margin:0 auto 14px;">
                    <i class="fas fa-check-circle"></i>
                  </div>

                  <h5>Approve {{ $user->name }}?</h5>
                  <p class="text-muted small mb-4">
                    This user will gain dashboard access.
                  </p>

                  <div class="d-flex justify-content-center gap-2">
                    <button type="button" class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-success rounded-pill px-4 fw-bold">
                      Approve User
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
        @endif

      @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-4">
            No users found
          </td>
        </tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('userSearch').addEventListener('keyup', function () {
  let v = this.value.toLowerCase();
  document.querySelectorAll('#usersTable tbody tr').forEach(row => {
    row.style.display = row.innerText.toLowerCase().includes(v) ? '' : 'none';
  });
});
</script>
@endpush
