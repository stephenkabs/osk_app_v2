<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Partners</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/assets/css/icons.min.css" rel="stylesheet" />
  <link href="/assets/css/app.min.css" rel="stylesheet" />

  <style>
    body {
      font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
      background:#f5f5f7;
    }
    .aw-wrap { max-width:1200px; margin:0 auto; }
    .aw-header { display:flex; justify-content:space-between; align-items:center; margin:16px 0; }
    .aw-title { font-weight:900; font-size:20px; letter-spacing:-.02em; margin:0; }

    /* Table container */
    .aw-table-wrap {
      border: 1px solid #e6e8ef;
      border-radius: 16px;
      background: #fff;
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(0,0,0,.04);
    }
    table.aw-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
    }
    table.aw-table thead th {
      background: #f6f7fb;
      font-weight: 700;
      font-size: 13px;
      letter-spacing: .02em;
      text-transform: uppercase;
      color: #5b5f6b;
      padding: 12px 14px;
      border-bottom: 1px solid #e6e8ef;
    }
    table.aw-table tbody td {
      padding: 12px 14px;
      font-weight: 600;
      color:#0b0c0f;
      border-bottom: 1px solid #f0f1f5;
      vertical-align: middle;
    }
    table.aw-table tbody tr:nth-child(even) {
      background:#fbfbfd;
    }

    /* Tag */
    .tag {
      display:inline-block;
      padding:4px 8px;
      border-radius:999px;
      font-size:12px;
      font-weight:700;
    }
    .tag-green { background:#eaf8ef; color:#216e3a; border:1px solid #c9f0d7; }
    .tag-slate { background:#eef1f6; color:#3b4252; border:1px solid #dee2eb; }
    .tag-danger { background:#fdecea; color:#b71c1c; border:1px solid #facdcd; }

    /* Action buttons */
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
      text-decoration:none;
      color:#0b0c0f;
      transition:all .2s;
    }
    .chip-btn:hover {
      border-color:#0071e3;
      box-shadow:0 0 0 4px rgba(0,113,227,0.12);
    }
    .chip-danger { border-color:#f6b3b3; }
    .chip-danger:hover { border-color:#f56c6c; box-shadow:0 0 0 4px rgba(245,108,108,.18); }

    /* 🍏 Apple Modal Aesthetic */
    .apple-modal .modal-content {
      border-radius: 18px;
      background: rgba(255,255,255,0.85);
      backdrop-filter: blur(24px) saturate(180%);
      -webkit-backdrop-filter: blur(24px) saturate(180%);
      border: 1px solid rgba(255,255,255,0.4);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    .apple-modal .modal-header {
      border-bottom: none;
      padding: 16px 20px 0 20px;
    }
    .apple-modal .modal-title {
      font-weight: 800;
      font-size: 1.25rem;
      letter-spacing: -0.02em;
    }
    .apple-modal .modal-body { padding: 20px; }
    .apple-modal .modal-footer { border-top:none; padding: 16px 20px; gap:10px; }

    .af-select {
      width:100%;
      border:1px solid #e6e8ef;
      border-radius:12px;
      padding:10px 12px;
      font-weight:600;
      background:#fff;
      transition:all .15s ease;
    }
    .af-select:focus {
      border-color:#0071e3;
      box-shadow:0 0 0 4px rgba(0,113,227,.2);
      outline:none;
    }
    .btn-light.cancel-btn {
      border-radius:12px;
      background:#f5f5f7;
      border:1px solid #e6e8ef;
      font-weight:600;
    }
    .btn-light.cancel-btn:hover { background:#e5e5e7; }
    .af-btn {
      background:#0b0c0f;
      color:#fff;
      border-radius:12px;
      font-weight:700;
      padding:10px 16px;
    }
    .af-btn:hover { background:#000; }
  </style>
  <style>
/* 🍎 Skeleton base */
.sk-hidden { display:none !important; }

.skeleton {
  position: relative;
  overflow: hidden;
  background: #eef0f5;
  border-radius: 10px;
}
.skeleton::after {
  content: "";
  position: absolute;
  top: 0;
  left: -150px;
  width: 150px;
  height: 100%;
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.75),
    transparent
  );
  animation: shimmer 1.1s infinite;
}
@keyframes shimmer { 100% { left: 100%; } }

/* Sizes */
.sk-line      { height: 12px; border-radius: 999px; }
.sk-line.lg   { height: 16px; }
.sk-chip     { height: 26px; width: 90px; border-radius: 999px; }
.sk-btn      { height: 28px; width: 90px; border-radius: 10px; }

/* Table skeleton */
.sk-table-row td {
  padding: 12px 14px;
}
.sk-actions {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
}
</style>

</head>

<body data-sidebar="dark">
{{-- @include('includes.preloader') --}}
@include('includes.header')
@include('includes.sidebar')

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid aw-wrap">
        {{-- 🍎 PARTNERS TABLE SKELETON --}}
<div id="partnersSkeleton">

  {{-- Header skeleton --}}
  <div class="aw-header">
    <div class="skeleton sk-line lg" style="width:180px;"></div>
    <div class="skeleton sk-btn" style="width:140px;"></div>
  </div>

  {{-- Table skeleton --}}
  <div class="aw-table-wrap">
    <table class="aw-table">
      <thead>
        <tr>
          <th><div class="skeleton sk-line" style="width:80px"></div></th>
          <th><div class="skeleton sk-line" style="width:60px"></div></th>
          <th><div class="skeleton sk-line" style="width:70px"></div></th>
          <th><div class="skeleton sk-line" style="width:90px"></div></th>
          <th><div class="skeleton sk-line" style="width:70px"></div></th>
          <th><div class="skeleton sk-line" style="width:120px"></div></th>
        </tr>
      </thead>

      <tbody>
        @for ($i = 0; $i < 6; $i++)
        <tr class="sk-table-row">
          <td><div class="skeleton sk-line" style="width:140px"></div></td>
          <td><div class="skeleton sk-line" style="width:90px"></div></td>
          <td><div class="skeleton sk-line" style="width:110px"></div></td>
          <td><div class="skeleton sk-line" style="width:160px"></div></td>
          <td><div class="skeleton sk-chip"></div></td>
          <td>
            <div class="sk-actions">
              <div class="skeleton sk-btn"></div>
              <div class="skeleton sk-btn"></div>
              <div class="skeleton sk-btn"></div>
            </div>
          </td>
        </tr>
        @endfor
      </tbody>
    </table>
  </div>

</div>

<div id="partnersContent" class="sk-hidden">
      <div class="aw-header">
        <h2 class="aw-title">Partners</h2>
        {{-- <a href="{{ route('partners.create') }}" class="chip-btn" title="Add Partner">
          <i class="fas fa-plus"></i> Add Partner
        </a> --}}
      </div>

      <div class="aw-table-wrap">
        <table class="aw-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>NRC</th>
              <th>Phone</th>
              <th>Address</th>
              <th>Status</th>
              <th style="width:500px;">Actions</th>
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
                    @if($partner->status==='approved') tag-green
                    @elseif($partner->status==='rejected') tag-danger
                    @else tag-slate @endif">
                    {{ ucfirst($partner->status) }}
                  </span>
                </td>


                <td>
  <div class="d-flex flex-wrap gap-2">
    {{-- Existing Status Button --}}
    <button type="button" class="chip-btn js-open-approve"
            data-slug="{{ $partner->slug }}"
            data-name="{{ $partner->name }}"
            data-status="{{ $partner->status }}">
      Update Status
    </button>

    {{-- 🔄 Sync-to-QBO Button (only enabled when approved & not yet synced) --}}
    @if($partner->status === 'approved')
        @if(empty($partner->quickbooks_customer_id))
            <form action="{{ route('partners.sync-qbo', $partner->slug) }}" method="POST" class="d-inline">
              @csrf
              <button type="submit" class="chip-btn" style="background:#eef1f6; border-color:#d0d4dd;">
                <i class="fas fa-sync-alt"></i> Sync with QBO
              </button>
            </form>
        @else
            <button class="chip-btn" style="background:#eaf8ef; border-color:#c9f0d7;" disabled>
              <i class="fas fa-check-circle"></i> Synced
            </button>
        @endif
    @else
        <button class="chip-btn" style="background:#f6f7fb; border-color:#e0e0e0; opacity:.5;" disabled>
          <i class="fas fa-ban"></i> Sync Disabled
        </button>
    @endif

    <a href="{{ route('partners.edit',$partner->slug) }}" class="chip-btn">Edit</a>
    <a href="{{ route('partners.show',$partner->slug) }}" class="chip-btn">View</a>

    <form action="{{ route('partners.destroy',$partner->slug) }}" method="POST" class="d-inline">
      @csrf @method('DELETE')
      <button type="submit" class="chip-btn chip-danger">Delete</button>
    </form>
  </div>
</td>

              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-muted py-3">No partners found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

<div class="mt-3">
    {{ $partners->links('vendor.pagination.bootstrap-4') }}
</div>

      <br>
    </div>
  </div>
  </div>

</div>

<!-- 🍏 Apple-Style Approve/Reject Modal -->
<div class="modal fade apple-modal" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px;">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title fw-bold">Update Partner Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="statusForm" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="status" id="statusInput">
        <div class="modal-body text-center">
          <p id="statusPartnerName" class="mb-3 fw-semibold" style="font-size:14px; color:#555;"></p>
          <p class="text-muted" style="font-size:13px;">Choose an action for this partner.</p>
        </div>

        <div class="modal-footer border-0 d-flex justify-content-between">
          <button type="button" class="btn btn-light cancel-btn" data-bs-dismiss="modal">
            Cancel
          </button>
          <div class="d-flex gap-2">
            <button type="button" class="btn af-btn" style="background:#216e3a;"
                    onclick="submitStatus('approved')">Approve</button>
            <button type="button" class="btn af-btn" style="background:#b71c1c;"
                    onclick="submitStatus('rejected')">Reject</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
  /* Apple modal styles stay same as previous */
  .apple-modal .modal-content {
    border-radius: 18px;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(24px) saturate(180%);
    -webkit-backdrop-filter: blur(24px) saturate(180%);
    border: 1px solid rgba(255,255,255,0.4);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
  }
  .apple-modal .modal-header { padding: 16px 20px 0 20px; }
  .apple-modal .modal-title { font-weight: 800; font-size: 1.25rem; }
  .apple-modal .modal-footer { padding: 16px 20px; gap: 10px; }
  .btn.af-btn { border-radius:12px; color:#fff; font-weight:700; padding:10px 16px; border:none; }
  .btn.af-btn:hover { opacity:.9; }
  .btn-light.cancel-btn { border-radius:12px; background:#f5f5f7; border:1px solid #e6e8ef; font-weight:600; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
  const form = document.getElementById('statusForm');
  const partnerName = document.getElementById('statusPartnerName');
  const statusInput = document.getElementById('statusInput');

  document.querySelectorAll('.js-open-approve').forEach(btn => {
    btn.addEventListener('click', () => {
      const slug = btn.dataset.slug;
      const name = btn.dataset.name;
      partnerName.textContent = `Partner: ${name}`;
      form.action = `/partners/${slug}/status`;
      statusModal.show();
    });
  });

  window.submitStatus = function(status) {
    statusInput.value = status;
    form.submit();
  };
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    const skeleton = document.getElementById('partnersSkeleton');
    const content  = document.getElementById('partnersContent');

    if (skeleton) skeleton.remove();
    if (content)  content.classList.remove('sk-hidden');
  }, 350); // 🍏 subtle Apple-like delay
});
</script>


<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>




</body>
</html>
