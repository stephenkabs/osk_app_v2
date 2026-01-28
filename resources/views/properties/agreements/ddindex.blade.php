<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Lease Agreements • {{ $property->property_name }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="/assets/css/icons.min.css" rel="stylesheet">
  <link href="/assets/css/app.min.css" rel="stylesheet">
<style>
  :root{
    --ink:#0b0c0f; --muted:#6b7280; --ring:#0071e3; --line:#e6e8ef; --card:#fff;
    --radius:16px;
  }

  /* Container card */
  .apple-card {
    background: var(--card);
    border:1px solid var(--line);
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 6px 18px rgba(0,0,0,.06);
  }

  /* Individual lease card */
  .lease-card {
    background:#fff;
    border:1px solid var(--line);
    border-radius: 14px;
    padding: 16px;
    transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
  }
  .lease-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(16,24,40,.10);
    border-color:#dbe2ea;
  }

  /* Header row inside card */
  .lease-head {
    display:flex; align-items:center; justify-content:space-between; gap:10px;
    margin-bottom: 8px;
  }
  .lease-title {
    margin:0; font-weight:800; font-size:15px; letter-spacing:-.01em; color:var(--ink);
  }

  /* Status pill */
  .badge-status {
    font-weight:800; font-size:11px; padding:6px 10px; border-radius:999px; text-transform:capitalize;
    border:1px solid transparent; letter-spacing:.02em;
  }
  .st-active   { background:#e9f7ef; color:#156f3b; border-color:#c7ead5; }
  .st-pending  { background:#fff6e8; color:#8a6400; border-color:#f7e2b7; }
  .st-ended    { background:#fdecec; color:#8a1f1f; border-color:#f2c6c6; }

  /* Meta rows */
  .lease-meta { font-size:13px; color:var(--muted); margin: 0 0 8px; }
  .lease-meta strong { color:#111; font-weight:800; }

  /* Rent badge line */
  .rent-line { font-weight:800; margin: 6px 0 10px; }

  /* Button group (Apple-like black buttons) */
  .btn-bar { display:flex; gap:8px; flex-wrap:wrap; }
  .af-btn {
    background:#0b0c0f; color:#fff; border:none; padding:8px 12px; border-radius:12px;
    font-weight:800; font-size:13px; display:inline-flex; align-items:center; gap:8px;
    transition: transform .06s ease, box-shadow .18s ease, background .18s ease;
    text-decoration:none;
  }
  .af-btn:hover { transform:translateY(-1px); box-shadow:0 8px 16px rgba(0,0,0,.14); background:#000; }
  .af-btn-outline {
    background:#fff; color:#0b0c0f; border:1px solid var(--line);
  }
  .af-btn-outline:hover { background:#f7f7f7; box-shadow:0 4px 10px rgba(0,0,0,.08); }

  /* Icon sizing */
  .af-btn i { font-size:14px; }

  /* Small avatar (optional, if you want tenant initials/icon on each card) */
  .tenant-chip {
    display:flex; align-items:center; gap:8px; font-size:13px; color:#111; font-weight:700;
  }
  .tenant-avatar {
    width:26px; height:26px; border-radius:50%; flex:0 0 auto;
    background:#111; color:#fff; display:flex; align-items:center; justify-content:center;
    font-size:12px; font-weight:800;
  }

  /* Subtle separator line */
  .soft-line {
    height:1px; background: var(--line); margin: 10px 0;
  }
</style>
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

</head>
<body data-sidebar="dark">
@include('includes.preloader')
@include('includes.header')
@include('includes.sidebar')

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="col-lg-10 mx-auto">

        <div class="apple-card mb-3 d-flex justify-content-between align-items-center">
          <h3 class="fw-bold mb-0">Lease Agreements</h3>
          <a href="{{ route('property.agreements.create', $property->slug) }}" class="btn btn-dark">
            <i class="fas fa-file-signature me-1"></i> New Agreement
          </a>
        </div>

<div class="row g-3">
  @forelse($agreements as $agreement)
    @php
      $tenant = optional($agreement->tenant);
      $unit   = optional($agreement->unit);
      $title  = $agreement->lease_number ?? ('Lease-'.$agreement->id);
      $start  = \Carbon\Carbon::parse($agreement->start_date)->format('d M Y');
      $end    = $agreement->end_date ? \Carbon\Carbon::parse($agreement->end_date)->format('d M Y') : 'Ongoing';

      // Quick initials for optional avatar
      $parts = preg_split('/\s+/', trim($tenant->name ?? ''));
      $ini   = strtoupper(mb_substr($parts[0] ?? '', 0, 1).mb_substr($parts[1] ?? '', 0, 1));
      if ($ini === '') $ini = 'T';
    @endphp

    <div class="col-md-4">
      <div class="lease-card">
        <div class="lease-head">
          <h5 class="lease-title">{{ $title }}</h5>
          <span class="badge-status st-{{ $agreement->status }}">{{ $agreement->status }}</span>
        </div>

        <div class="tenant-chip lease-meta">
          <div class="tenant-avatar">{{ $ini }}</div>
          <div>{{ $tenant->name ?? 'No Tenant' }}</div>
        </div>

        <p class="lease-meta"><strong>Unit:</strong> {{ $unit->code ?? '—' }}</p>
        <p class="lease-meta"><strong>Term:</strong> {{ $start }} → {{ $end }}</p>

        <div class="rent-line">Rent: K{{ number_format($agreement->rent_amount, 2) }}</div>

        <div class="soft-line"></div>

        <div class="btn-bar">
          {{-- <a href="{{ route('property.agreements.show', [$property->slug, $agreement->slug]) }}" class="af-btn af-btn-outline" title="View details">
            <i class="fas fa-eye"></i> View
          </a>
          <a href="{{ route('property.agreements.edit', [$property->slug, $agreement->slug]) }}" class="af-btn af-btn-outline" title="Edit agreement">
            <i class="fas fa-pen"></i> Edit
          </a>
          <a href="{{ route('property.agreements.pdf', [$property->slug, $agreement->slug]) }}" class="af-btn af-btn-outline" title="Preview PDF">
            <i class="fas fa-file-pdf"></i> PDF
          </a> --}}
          <a href="{{ route('property.agreements.download', [$property->slug, $agreement->slug]) }}" class="af-btn" title="Download PDF">
            <i class="fas fa-download"></i> Download
          </a>

          <form method="POST" action="{{ route('property.agreements.destroy', [$property->slug, $agreement->slug]) }}" onsubmit="return confirm('Delete this agreement?')" class="ms-auto">
            @csrf
            @method('DELETE')
<button type="button"
        class="af-btn af-btn-outline"
        data-bs-toggle="modal"
        data-bs-target="#deleteModal"
        data-url="{{ route('property.agreements.destroy', [$property->slug, $agreement->slug]) }}">
  <i class="fas fa-trash"></i> Delete
</button>

          </form>
        </div>
      </div>
    </div>
  @empty
    <div class="col-12 text-center text-muted">No lease agreements found.</div>
  @endforelse
</div>


        <div class="mt-3">{{ $agreements->links() }}</div>
      </div>
    </div>
  </div>
</div>


<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
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
<script>
  const deleteModal = document.getElementById('deleteModal');
  const deleteForm = document.getElementById('deleteForm');

  deleteModal.addEventListener('show.bs.modal', event => {
    const button = event.relatedTarget;
    const url = button.getAttribute('data-url');
    deleteForm.setAttribute('action', url);
  });
</script>

</body>
</html>
