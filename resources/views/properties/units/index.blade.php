@extends('layouts.app')

@section('content')

<style>
  :root {
    --card:#fff;
    --border:#e6e8ef;
    --ink:#0b0c0f;
    --muted:#6b6b6b;
    --radius:18px;
  }

  body {
    font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif;
  }

  /* Header */
  .apple-title {
    font-weight: 800;
    font-size: 22px;
    letter-spacing: -0.02em;
  }
  .apple-sub {
    color: var(--muted);
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
  }

  /* Unit card */
  .unit-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    overflow: hidden;
    transition: .25s;
    box-shadow: 0 2px 8px rgba(0,0,0,.05);
  }

  .unit-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 28px rgba(0,0,0,.1);
  }

  .unit-photo {
    height: 180px;
    width: 100%;
    object-fit: cover;
    background: #f3f4f6;
    border-bottom: 1px solid #eee;
  }

  /* Status badges */
  .badge-status {
    font-size: 11px;
    font-weight: 700;
    padding: 4px 10px;
    border-radius: 999px;
    text-transform: uppercase;
  }
  .st-available { background:#e5f9e7; color:#0b9444; }
  .st-occupied  { background:#fef3c7; color:#92400e; }
  .st-maint     { background:#fee2e2; color:#991b1b; }

  /* Apple black buttons */
  .af-btn {
    background:#0b0c0f;
    color:#fff;
    border:none;
    border-radius:12px;
    padding:8px 14px;
    font-weight:700;
    font-size:13px;
    transition:.2s;
  }
  .af-btn:hover { background:#000; transform:translateY(-1px); }

  .af-btn-outline {
    border:1px solid #0b0c0f;
    color:#0b0c0f;
    background:transparent;
    border-radius:12px;
    padding:7px 12px;
    font-weight:700;
    font-size:13px;
    transition:.2s;
  }
  .af-btn-outline:hover {
    background:#0b0c0f;
    color:#fff;
  }

  /* ===============================
   Apple-like Filter Bar
================================ */
.apple-card {
    background: #ffffff;
    border: 1px solid #e6e8ef;
    border-radius: 16px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.04);
}

/* Labels */
.apple-card label {
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.02em;
    color: #374151;
    margin-bottom: 4px;
}

/* Inputs & selects */
.apple-card .form-control,
.apple-card .form-select {
    border-radius: 12px;
    border: 1px solid #d1d5db;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 12px;
    color: #111827;
    background-color: #fff;
    transition: all 0.2s ease;
}

/* Focus ring (Apple-like) */
.apple-card .form-control:focus,
.apple-card .form-select:focus {
    border-color: #0b0c0f;
    box-shadow: 0 0 0 3px rgba(11, 12, 15, 0.12);
}

/* Placeholder */
.apple-card ::placeholder {
    color: #9ca3af;
    font-weight: 500;
}

/* Compact button alignment */
.apple-card .btn {
    height: 36px;
    border-radius: 12px;
    font-weight: 700;
    font-size: 13px;
}

/* Outline button style */
.af-btn-outline {
    background: transparent;
    border: 1px solid #0b0c0f;
    color: #0b0c0f;
    transition: all 0.2s ease;
}

.af-btn-outline:hover {
    background: #0b0c0f;
    color: #ffffff;
    transform: translateY(-1px);
}

/* Mobile spacing improvement */
@media (max-width: 768px) {
    .apple-card .row > div {
        margin-bottom: 8px;
    }
}

/* ===============================
   Skeleton Loader (Apple-like)
================================ */
.skeleton {
    background: linear-gradient(
        90deg,
        #f0f0f0 25%,
        #e6e6e6 37%,
        #f0f0f0 63%
    );
    background-size: 400% 100%;
    animation: shimmer 1.4s ease infinite;
}

@keyframes shimmer {
    0% { background-position: -400px 0; }
    100% { background-position: 400px 0; }
}

.skeleton-card {
    border-radius: 18px;
    border: 1px solid #e6e8ef;
    overflow: hidden;
    background: #fff;
}

.skeleton-photo {
    height: 180px;
}

.skeleton-line {
    height: 14px;
    border-radius: 8px;
    margin-bottom: 10px;
}

.skeleton-line.sm { width: 40%; }
.skeleton-line.md { width: 60%; }
.skeleton-line.lg { width: 80%; }


</style>

<div class="container-fluid">
  <div class="col-lg-11 mx-auto">

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
      <div>
        <div class="apple-title">Units</div>
        <div class="apple-sub">{{ $property->property_name }}</div>
      </div>

      <div class="d-flex gap-2">
        <a href="{{ route('property.units.create', $property->slug) }}" class="af-btn">
          <i class="fas fa-plus me-1"></i> Add Unit
        </a>

        <a href="{{ route('property.users.index', $property->slug) }}" class="af-btn-outline">
          <i class="fas fa-users me-1"></i> Tenants
        </a>
      </div>
    </div>

    {{-- Filters --}}
    <div class="apple-card mb-3 p-3">
      <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4">
          <label class="small fw-bold">Search</label>
          <input type="text"
                 name="q"
                 value="{{ request('q') }}"
                 class="form-control form-control-sm"
                 placeholder="Code, type, status">
        </div>

        <div class="col-md-3">
          <label class="small fw-bold">Status</label>
          <select name="status"
                  class="form-select form-select-sm"
                  onchange="this.form.submit()">
            <option value="">Any</option>
            <option value="available" {{ request('status')==='available' ? 'selected':'' }}>Available</option>
            <option value="occupied" {{ request('status')==='occupied' ? 'selected':'' }}>Occupied</option>
            <option value="maintenance" {{ request('status')==='maintenance' ? 'selected':'' }}>Maintenance</option>
          </select>
        </div>

        <div class="col-md-3">
          <label class="small fw-bold">Type</label>
          <input name="type"
                 value="{{ request('type') }}"
                 class="form-control form-control-sm"
                 placeholder="Apartment, Office">
        </div>

        <div class="col-md-2 d-grid">
          <button class="af-btn-outline btn-sm">
            <i class="fas fa-filter me-1"></i> Apply
          </button>
        </div>
      </form>
    </div>
{{-- Skeleton Loader --}}
<div id="units-skeleton" class="row g-3">

  @for($i = 0; $i < 6; $i++)
    <div class="col-md-4">
      <div class="skeleton-card">
        <div class="skeleton skeleton-photo"></div>
        <div class="p-3">
          <div class="skeleton skeleton-line lg"></div>
          <div class="skeleton skeleton-line md"></div>
          <div class="skeleton skeleton-line sm"></div>
          <div class="skeleton skeleton-line md"></div>
        </div>
      </div>
    </div>
  @endfor

</div>

    {{-- Units Grid --}}
<div id="units-content" class="row g-3 d-none">

      @forelse($units as $unit)
        <div class="col-md-4">
          <div class="unit-card">

            @if($unit->photo_path)
              <img class="unit-photo"
                   src="{{ asset('storage/'.$unit->photo_path) }}"
                   alt="{{ $unit->code }}">
            @else
              <div class="unit-photo d-flex align-items-center justify-content-center text-muted">
                <i class="fas fa-door-open me-2"></i> No Photo
              </div>
            @endif

            <div class="p-3">
              <div class="d-flex justify-content-between align-items-center mb-1">
                <h5 class="mb-0 fw-bold">{{ $unit->code }}</h5>

                @php
                  $badge = $unit->status === 'occupied'
                    ? 'st-occupied'
                    : ($unit->status === 'maintenance' ? 'st-maint' : 'st-available');
                @endphp

                <span class="badge-status {{ $badge }}">
                  {{ ucfirst($unit->status) }}
                </span>
              </div>

              {{-- <div class="text-muted small mb-2">
                Type: {{ $unit->type ?? '—' }} • Floor: {{ $unit->floor ?? '—' }}
              </div>

              <div class="small mb-2">
                Rooms: {{ $unit->bedrooms ?? '—' }} bd /
                {{ $unit->bathrooms ?? '—' }} ba •
                {{ $unit->size_sq_m ?? '—' }} m²
              </div> --}}

              <div class="fw-bold mb-3">
                Rent: {{ $unit->rent_amount ? number_format($unit->rent_amount,2) : '—' }}
              </div>

              <div class="d-flex gap-2">
                <a href="{{ route('property.units.edit', [$property->slug, $unit->slug]) }}"
                   class="af-btn-outline">
                  <i class="fas fa-pen"></i> Edit
                </a>

                <form method="POST"
                      action="{{ route('property.units.destroy', [$property->slug, $unit->slug]) }}">
                  @csrf @method('DELETE')
                  <button class="af-btn-outline"
                          onclick="return confirm('Delete this unit?')">
                    <i class="fas fa-trash"></i> Delete
                  </button>
                </form>
              </div>
            </div>

          </div>
        </div>
      @empty
        <div class="col-12">
          <div class="apple-card text-muted p-4">
            No units found.
          </div>
        </div>
      @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-4">
      {{ $units->links() }}
    </div>

  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const skeleton = document.getElementById('units-skeleton');
    const content  = document.getElementById('units-content');

    // Small delay = smoother Apple-like feel
    setTimeout(() => {
        skeleton?.classList.add('d-none');
        content?.classList.remove('d-none');
    }, 350);
});
</script>

@endsection
