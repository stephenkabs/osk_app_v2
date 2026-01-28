@extends('layouts.app')

@section('content')

<style>
:root {
  --ink:#0b0c0f;
  --muted:#6b7280;
  --card:#ffffff;
  --border:#e5e7eb;
  --radius:16px;
}

/* Header */
.apple-header {
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius);
  padding:18px 22px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:16px;
  box-shadow:0 4px 14px rgba(0,0,0,.04);
}
.apple-title {
  font-weight:800;
  font-size:20px;
  letter-spacing:-.02em;
}
.apple-sub {
  font-size:12px;
  font-weight:600;
  color:var(--muted);
  text-transform:uppercase;
}

/* Buttons */
.af-btn, .af-btn-outline {
  border-radius:12px;
  padding:8px 14px;
  font-weight:700;
  font-size:13px;
  display:inline-flex;
  align-items:center;
  gap:8px;
  transition:.2s;
  text-decoration:none;
}
.af-btn {
  background:var(--ink);
  color:#fff;
}
.af-btn:hover { background:#000; transform:translateY(-1px); }

.af-btn-outline {
  background:#fff;
  border:1px solid var(--border);
  color:var(--ink);
}
.af-btn-outline:hover { background:#f3f4f6; }

/* Cards */
.apple-card {
  background:var(--card);
  border:1px solid var(--border);
  border-radius:var(--radius);
  padding:16px;
  box-shadow:0 2px 6px rgba(0,0,0,.05);
}

/* Tenant grid */
.member-grid {
  display:grid;
  gap:18px;
  grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
}
.member-card {
  background:#fff;
  border:1px solid var(--border);
  border-radius:16px;
  padding:14px;
  display:flex;
  gap:14px;
  align-items:center;
  transition:.25s;
}
.member-card:hover {
  transform:translateY(-2px);
  box-shadow:0 10px 24px rgba(0,0,0,.08);
}
.member-img,.member-placeholder {
  width:56px;height:56px;border-radius:50%;
  display:flex;align-items:center;justify-content:center;
  font-weight:700;font-size:18px;
  background:#6b7280;color:#fff;
}
.member-info h5 {
  font-size:15px;
  font-weight:700;
  margin:0;
}
.member-info p {
  font-size:11px;
  color:var(--muted);
  margin:2px 0 0;
}

/* Copy feedback */
.copied {
  background:#e0f2fe!important;
  border-color:#93c5fd!important;
  color:#0369a1!important;
}

/* ===============================
   Apple-like Skeleton Loader
================================ */

.skeleton {
  position: relative;
  overflow: hidden;
  background: #e5e7eb;
  border-radius: 12px;
}

.skeleton::after {
  content: "";
  position: absolute;
  inset: 0;
  transform: translateX(-100%);
  background: linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.6),
    transparent
  );
  animation: shimmer 1.4s infinite;
}

@keyframes shimmer {
  100% { transform: translateX(100%); }
}

/* Sizes */
.skeleton-circle {
  width: 56px;
  height: 56px;
  border-radius: 50%;
}

.skeleton-line {
  height: 12px;
  border-radius: 6px;
}

.skeleton-line.sm { width: 40%; }
.skeleton-line.md { width: 65%; }
.skeleton-line.lg { width: 85%; }

</style>

<div class="container-fluid">
  <div class="col-lg-11 mx-auto">

    {{-- Header --}}
    <div class="apple-header mb-4">
      <div>
        <div class="apple-title">Tenants</div>
        <div class="apple-sub">{{ $property->property_name }}</div>
      </div>

      <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('property.users.create', $property->slug) }}" class="af-btn">
          <i class="fas fa-user-plus"></i> Add Tenant
        </a>

        <a href="{{ route('property.users.public.create', $property->slug) }}"
           target="_blank"
           class="af-btn-outline">
          <i class="fas fa-link"></i> Signup Link
        </a>

        <button id="copyLinkBtn" class="af-btn-outline">
          <i class="fas fa-copy"></i>
          <span class="btn-text">Copy</span>
        </button>
      </div>
    </div>

    {{-- Filters --}}
    <div class="apple-card mb-3">
      <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-5">
          <label class="small fw-bold">Search</label>
          <input name="q"
                 value="{{ request('q') }}"
                 class="form-control form-control-sm"
                 placeholder="Name or email">
        </div>

        <div class="col-md-4">
          <label class="small fw-bold">Range</label>
          <select name="range"
                  class="form-select form-select-sm"
                  onchange="this.form.submit()">
            <option value="all">All time</option>
            <option value="week"  {{ request('range')=='week'?'selected':'' }}>This week</option>
            <option value="month" {{ request('range')=='month'?'selected':'' }}>This month</option>
            <option value="year"  {{ request('range')=='year'?'selected':'' }}>This year</option>
          </select>
        </div>

        <div class="col-md-3 d-grid">
          <button class="af-btn-outline btn-sm">
            <i class="fas fa-filter"></i> Apply
          </button>
        </div>
      </form>
    </div>

    {{-- Stats --}}
    {{-- <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="apple-card">
          <div class="text-muted small fw-bold">TOTAL TENANTS</div>
          <div class="fs-3 fw-bold">{{ $totalMembers }}</div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="apple-card">
          <div class="text-muted small fw-bold">JOINED TODAY</div>
          <div class="fs-3 fw-bold">{{ $newToday }}</div>
        </div>
      </div>
    </div> --}}

    {{-- Skeleton loaders (shown while page loads) --}}
<div id="skeletonTenants" class="member-grid">
  @for ($i = 0; $i < 6; $i++)
    <div class="member-card">
      <div class="skeleton skeleton-circle"></div>

      <div class="flex-grow-1">
        <div class="skeleton skeleton-line lg mb-2"></div>
        <div class="skeleton skeleton-line sm"></div>
      </div>
    </div>
  @endfor
</div>


    {{-- Tenants --}}
    <div id="realTenants" style="display:none">
    <div class="member-grid">
      @forelse($users as $user)
        <a href="{{ route('property.users.show', [$property->slug, $user->slug]) }}"
           class="member-card text-decoration-none">
          @if($user->profile_image)
            <img src="{{ asset('storage/'.$user->profile_image) }}" class="member-img">
          @else
            <div class="member-placeholder">{{ strtoupper(substr($user->name,0,1)) }}</div>
          @endif

          <div class="member-info">
            <h5>{{ $user->name }}</h5>
            <p>{{ $user->whatsapp_phone ?? '—' }}</p>
          </div>
        </a>
      @empty
        <div class="apple-card text-muted">No tenants found.</div>
      @endforelse
    </div>
    </div>

    <div class="mt-4">
      {{ $users->links() }}
    </div>

  </div>
</div>

<script>
document.getElementById('copyLinkBtn')?.addEventListener('click', function () {
  const btn = this;
  const text = "{{ route('property.users.public.create', $property->slug) }}";
  navigator.clipboard.writeText(text).then(() => {
    btn.classList.add('copied');
    btn.querySelector('.btn-text').textContent = 'Copied ✓';
    setTimeout(() => {
      btn.classList.remove('copied');
      btn.querySelector('.btn-text').textContent = 'Copy';
    }, 1500);
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    const skeleton = document.getElementById('skeletonTenants');
    const real = document.getElementById('realTenants');

    if (skeleton && real) {
      skeleton.style.display = 'none';
      real.style.display = 'block';
    }
  }, 600); // subtle Apple-like delay
});
</script>


@endsection
