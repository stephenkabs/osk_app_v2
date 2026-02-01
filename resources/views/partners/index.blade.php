@extends('layouts.app')

@section('content')

<style>
/* ===============================
   🍏 Apple-like Partner Listing
================================ */
.aw-wrap { max-width:1200px; margin:0 auto; }

.aw-header {
  display:flex;
  justify-content:space-between;
  align-items:center;
  margin:16px 0;
}

.aw-title {
  font-weight:900;
  font-size:20px;
  letter-spacing:-.02em;
  margin:0;
}

/* Table */
.aw-table-wrap {
  border:1px solid #e6e8ef;
  border-radius:16px;
  background:#fff;
  overflow:hidden;
  box-shadow:0 4px 20px rgba(0,0,0,.04);
}

table.aw-table {
  width:100%;
  border-collapse:separate;
  border-spacing:0;
}

table.aw-table thead th {
  background:#f6f7fb;
  font-weight:700;
  font-size:13px;
  text-transform:uppercase;
  color:#5b5f6b;
  padding:12px 14px;
  border-bottom:1px solid #e6e8ef;
}

table.aw-table tbody td {
  padding:12px 14px;
  font-weight:600;
  color:#0b0c0f;
  border-bottom:1px solid #f0f1f5;
  vertical-align:middle;
}

table.aw-table tbody tr:nth-child(even) {
  background:#fbfbfd;
}

/* Status tags */
.tag {
  display:inline-block;
  padding:4px 10px;
  border-radius:999px;
  font-size:12px;
  font-weight:700;
}
.tag-green  { background:#eaf8ef; color:#216e3a; border:1px solid #c9f0d7; }
.tag-slate  { background:#eef1f6; color:#3b4252; border:1px solid #dee2eb; }
.tag-danger { background:#fdecea; color:#b71c1c; border:1px solid #facdcd; }

/* Buttons */
.chip-btn {
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  border-radius:10px;
  border:1px solid #e6e8ef;
  background:#fff;
  font-size:12.5px;
  font-weight:700;
  color:#0b0c0f;
  text-decoration:none;
  transition:all .2s;
}
.chip-btn:hover {
  border-color:#0071e3;
  box-shadow:0 0 0 4px rgba(0,113,227,.12);
}
.chip-danger { border-color:#f6b3b3; }
.chip-danger:hover {
  border-color:#f56c6c;
  box-shadow:0 0 0 4px rgba(245,108,108,.18);
}

/* 🍎 Apple Modal */
.apple-modal .modal-content {
  border-radius:18px;
  background:rgba(255,255,255,.88);
  backdrop-filter:blur(24px);
  border:1px solid rgba(255,255,255,.4);
  box-shadow:0 20px 40px rgba(0,0,0,.15);
}
</style>

<div class="container-fluid aw-wrap">

  {{-- HEADER --}}
  <div class="aw-header">
    <h2 class="aw-title">Registered Partners</h2>
  </div>

  {{-- TABLE --}}
  <div class="aw-table-wrap">
    <table class="aw-table">
      <thead>
        <tr>
          <th>Partner Name</th>
          <th>NRC</th>
          <th>Phone</th>
          <th>Address</th>
          <th>Status</th>
          <th style="width:480px;">Actions</th>
        </tr>
      </thead>

      <tbody>
        @forelse($partners as $partner)
          <tr>
            <td>{{ $partner->name }}</td>
            <td>{{ $partner->nrc_no }}</td>
            <td>{{ $partner->phone_number }}</td>
            <td>{{ $partner->previous_address }}</td>

            <td>
              <span class="tag
                @if($partner->status === 'approved') tag-green
                @elseif($partner->status === 'rejected') tag-danger
                @else tag-slate @endif">
                {{ ucfirst($partner->status) }}
              </span>
            </td>

            <td>
              <div class="d-flex flex-wrap gap-2">

                {{-- Status --}}
                <button type="button"
                        class="chip-btn js-open-status"
                        data-slug="{{ $partner->slug }}"
                        data-name="{{ $partner->name }}">
                  Update Status
                </button>

                {{-- QBO Sync --}}
                @if($partner->status === 'approved')
                  @if(empty($partner->quickbooks_customer_id))
                    <form action="{{ route('partners.sync-qbo',$partner->slug) }}"
                          method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="chip-btn">
                        <i class="fas fa-sync-alt"></i> Sync to QBO
                      </button>
                    </form>
                  @else
                    <button class="chip-btn" disabled
                            style="background:#eaf8ef;border-color:#c9f0d7;">
                      <i class="fas fa-check-circle"></i> Synced
                    </button>
                  @endif
                @else
                  <button class="chip-btn" disabled style="opacity:.5;">
                    Sync Disabled
                  </button>
                @endif

                <a href="{{ route('partners.show',$partner->slug) }}" class="chip-btn">
                  View
                </a>

                <a href="{{ route('partners.edit',$partner->slug) }}" class="chip-btn">
                  Edit
                </a>

                <form action="{{ route('partners.destroy',$partner->slug) }}"
                      method="POST" class="d-inline">
                  @csrf @method('DELETE')
                  <button type="submit" class="chip-btn chip-danger">
                    Delete
                  </button>
                </form>

              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              No partners found.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">
    {{ $partners->links('vendor.pagination.bootstrap-4') }}
  </div>

</div>

{{-- STATUS MODAL --}}
<div class="modal fade apple-modal" id="statusModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">Update Partner Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="statusForm" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">

        <div class="modal-body text-center">
          <p id="statusPartnerName" class="fw-semibold mb-2"></p>
          <p class="text-muted small">Choose the appropriate action.</p>
        </div>

        <div class="modal-footer border-0 justify-content-between">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">
            Cancel
          </button>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-success"
                    onclick="submitStatus('approved')">Approve</button>
            <button type="button" class="btn btn-danger"
                    onclick="submitStatus('rejected')">Reject</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const modal = new bootstrap.Modal(document.getElementById('statusModal'));
  const form = document.getElementById('statusForm');

  document.querySelectorAll('.js-open-status').forEach(btn => {
    btn.addEventListener('click', () => {
      document.getElementById('statusPartnerName').textContent =
        `Partner: ${btn.dataset.name}`;
      form.action = `/partners/${btn.dataset.slug}/status`;
      modal.show();
    });
  });

  window.submitStatus = status => {
    document.getElementById('statusInput').value = status;
    form.submit();
  };
});
</script>

@endsection
