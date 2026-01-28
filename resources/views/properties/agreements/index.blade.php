@extends('layouts.app')

@section('content')
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
/* ===============================
   SKELETON LOADER (APPLE STYLE)
================================ */
.skeleton {
  background: linear-gradient(
    90deg,
    #eee 25%,
    #f5f5f5 37%,
    #eee 63%
  );
  background-size: 400% 100%;
  animation: shimmer 1.4s ease infinite;
  border-radius: 12px;
}

@keyframes shimmer {
  0% { background-position: 100% 0; }
  100% { background-position: -100% 0; }
}

.skeleton-card {
  height: 220px;
  border-radius: 16px;
}

.skeleton-line {
  height: 14px;
  margin-bottom: 10px;
}

.skeleton-line.sm { width: 50%; }
.skeleton-line.md { width: 70%; }
.skeleton-line.lg { width: 90%; }

/* Hide real content until loaded */
.page-loaded .skeleton-wrap { display:none; }
.page-loaded .real-content { display:block; }
.real-content { display:none; }
</style>

<div class="container-fluid">

  {{-- ===============================
     HEADER
  ============================== --}}
  <div class="apple-card mb-3 d-flex justify-content-between align-items-center">
    <h3 class="fw-bold mb-0">Lease Agreements</h3>
    <a href="{{ route('property.agreements.create', $property->slug) }}" class="btn btn-dark">
      <i class="fas fa-file-signature me-1"></i> New Agreement
    </a>
  </div>

  {{-- ===============================
     SKELETON GRID
  ============================== --}}
  <div class="row g-3 skeleton-wrap">
    @for($i = 0; $i < 6; $i++)
      <div class="col-md-4">
        <div class="skeleton skeleton-card p-3">
          <div class="skeleton skeleton-line lg"></div>
          <div class="skeleton skeleton-line md"></div>
          <div class="skeleton skeleton-line sm"></div>
          <div class="skeleton skeleton-line lg mt-3"></div>
          <div class="skeleton skeleton-line md"></div>
        </div>
      </div>
    @endfor
  </div>

  {{-- ===============================
     REAL CONTENT
  ============================== --}}
  <div class="real-content">

    <div class="row g-3">
      @forelse($agreements as $agreement)
        @php
          $tenant = optional($agreement->tenant);
          $unit   = optional($agreement->unit);
          $title  = $agreement->lease_number ?? ('Lease-'.$agreement->id);
          $start  = \Carbon\Carbon::parse($agreement->start_date)->format('d M Y');
          $end    = $agreement->end_date
                    ? \Carbon\Carbon::parse($agreement->end_date)->format('d M Y')
                    : 'Ongoing';

          $parts = preg_split('/\s+/', trim($tenant->name ?? ''));
          $ini   = strtoupper(mb_substr($parts[0] ?? 'T',0,1).mb_substr($parts[1] ?? '',0,1));
        @endphp

        <div class="col-md-4">
          <div class="lease-card">

            <div class="lease-head">
              <h5 class="lease-title">{{ $title }}</h5>
              <span class="badge-status st-{{ $agreement->status }}">
                {{ $agreement->status }}
              </span>
            </div>

            <div class="tenant-chip lease-meta">
              <div class="tenant-avatar">{{ $ini }}</div>
              <div>{{ $tenant->name ?? 'No Tenant' }}</div>
            </div>

            <p class="lease-meta"><strong>Unit:</strong> {{ $unit->code ?? '—' }}</p>
            <p class="lease-meta"><strong>Term:</strong> {{ $start }} → {{ $end }}</p>

            <div class="rent-line">
              Rent: K{{ number_format($agreement->rent_amount,2) }}
            </div>

            <div class="soft-line"></div>

            <div class="btn-bar">
              <a href="{{ route('property.agreements.download', [$property->slug, $agreement->slug]) }}"
                 class="af-btn">
                <i class="fas fa-download"></i> Download
              </a>

              <button type="button"
                      class="af-btn af-btn-outline ms-auto"
                      data-bs-toggle="modal"
                      data-bs-target="#deleteModal"
                      data-url="{{ route('property.agreements.destroy', [$property->slug, $agreement->slug]) }}">
                <i class="fas fa-trash"></i> Delete
              </button>
            </div>

          </div>
        </div>
      @empty
        <div class="col-12 text-center text-muted">
          No lease agreements found.
        </div>
      @endforelse
    </div>

    <div class="mt-3">
      {{ $agreements->links() }}
    </div>

  </div>
</div>

{{-- ===============================
   DELETE MODAL (UNCHANGED)
============================== --}}
@include('properties.agreements.delete')

@endsection

@push('scripts')
<script>
  // Reveal content after load
  window.addEventListener('load', () => {
    document.body.classList.add('page-loaded');
  });

  // Delete modal binding
  const deleteModal = document.getElementById('deleteModal');
  const deleteForm  = document.getElementById('deleteForm');

  deleteModal?.addEventListener('show.bs.modal', e => {
    deleteForm.action = e.relatedTarget.getAttribute('data-url');
  });
</script>
@endpush
