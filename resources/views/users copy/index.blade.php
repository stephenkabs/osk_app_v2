@extends('layouts.app')

@section('content')

<style>
/* ===========================
   🍎 APPLE TABLE & TYPOGRAPHY
=========================== */

body {
  -webkit-font-smoothing: antialiased;
}

.table td, .table th {
  vertical-align: middle;
}

/* User name emphasis */
.user-name {
  font-weight: 700;
  font-size: 15px;
  color: #0b0c0f;
}

.user-email {
  font-size: 12px;
  color: #6b7280;
}

/* Status pills */
.status-pill {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 4px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 700;
}

.status-approved {
  background: #dcfce7;
  color: #166534;
}

.status-pending {
  background: #fef3c7;
  color: #92400e;
}

.status-disabled {
  background: #f3f4f6;
  color: #374151;
}

/* Approve button */
.btn-approve {
  background: linear-gradient(135deg, #16a34a, #22c55e);
  color: #fff;
  border: none;
  border-radius: 999px;
  padding: 6px 14px;
  font-weight: 800;
  font-size: 12px;
  /* box-shadow: 0 6px 18px rgba(34,197,94,.35); */
  transition: all .18s ease;
}

.btn-approve:hover {
  transform: translateY(-1px);
  box-shadow: 0 10px 28px rgba(34,197,94,.45);
}

/* Row hover */
.table-hover tbody tr:hover {
  background: #f9fafb;
}

/* Actions */
.action-stack {
  display: inline-flex;
  gap: 8px;
  align-items: center;
}

/* Apple modal */
.apple-modal .modal-content {
  border-radius: 22px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 30px 80px rgba(0,0,0,.25);
}

.apple-modal h5 {
  font-weight: 800;
  letter-spacing: -.02em;
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
         class="btn btn-primary rounded-pill px-4">
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
