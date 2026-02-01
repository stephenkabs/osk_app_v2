@extends('layouts.app')

@section('content')

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@500;600;700;800;900&display=swap');

:root {
  --black:#0b0c0f;
  --yellow:#e1a422;
  --yellow-soft:#f7e7b0;
  --muted:#6b7280;
  --card:#ffffff;
  --border:#e5e7eb;
  --radius:18px;
}

body {
  font-family: Inter, -apple-system, BlinkMacSystemFont,
               "SF Pro Text", "SF Pro Display",
               "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  color:var(--black);
}

/* ===============================
   HEADER
================================ */
.apple-header {
  background:linear-gradient(180deg,#fff,#fcfcfd);
  border:1px solid var(--border);
  border-radius:var(--radius);
  padding:20px 24px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:16px;
  box-shadow:0 8px 30px rgba(0,0,0,.05);
}

.apple-title {
  font-weight:900;
  font-size:22px;
  letter-spacing:-.03em;
  color:var(--black);
}

.apple-sub {
  font-size:11px;
  font-weight:800;
  letter-spacing:.12em;
  text-transform:uppercase;
  color:var(--yellow);
}

/* ===============================
   BUTTONS
================================ */
.af-btn,
.af-btn-outline {
  border-radius:14px;
  padding:9px 16px;
  font-weight:800;
  font-size:13px;
  display:inline-flex;
  align-items:center;
  gap:8px;
  transition:.25s cubic-bezier(.4,0,.2,1);
  text-decoration:none;
  white-space:nowrap;
}

/* Primary */
.af-btn {
  background:linear-gradient(180deg,var(--black),#000);
  color:#fff;
  box-shadow:0 6px 18px rgba(0,0,0,.25);
}

.af-btn i { color:var(--yellow); }

.af-btn:hover {
  transform:translateY(-2px);
  box-shadow:0 12px 28px rgba(0,0,0,.35);
}

/* Outline */
.af-btn-outline {
  background:#fff;
  border:1.5px solid var(--border);
  color:var(--black);
}

.af-btn-outline i {
  color:var(--yellow);
}

.af-btn-outline:hover {
  background:#fffbea;
  border-color:var(--yellow);
  transform:translateY(-1px);
}

/* ===============================
   CARDS
================================ */
.apple-card {
  background:#fff;
  border:1px solid var(--border);
  border-radius:var(--radius);
  padding:16px;
  box-shadow:0 4px 14px rgba(0,0,0,.05);
}

/* ===============================
   TENANT GRID
================================ */
.member-grid {
  display:grid;
  gap:20px;
  grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
}

.member-card {
  background:#fff;
  border:1px solid var(--border);
  border-radius:18px;
  padding:14px 16px;
  display:flex;
  gap:14px;
  align-items:center;
  transition:.3s cubic-bezier(.4,0,.2,1);
}

.member-card:hover {
  transform:translateY(-3px);
  border-color:var(--yellow);
  box-shadow:0 20px 40px rgba(0,0,0,.12);
}

/* Avatar */
.member-img,
.member-placeholder {
  width:56px;
  height:56px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:900;
  font-size:18px;
  background:linear-gradient(180deg,var(--yellow),#c79218);
  color:#000;
}

/* Info */
.member-info h5 {
  font-size:15px;
  font-weight:900;
  letter-spacing:-.02em;
  margin:0;
  color:var(--black);
}

.member-info p {
  font-size:12px;
  font-weight:600;
  color:var(--muted);
  margin:2px 0 0;
}

/* ===============================
   COPY FEEDBACK
================================ */
.copied {
  background:#fff7d6!important;
  border-color:var(--yellow)!important;
  color:#7a5a00!important;
}

/* ===============================
   APPLE-LIKE SKELETON
================================ */
.skeleton {
  position:relative;
  overflow:hidden;
  background:#e5e7eb;
  border-radius:14px;
}

.skeleton::after {
  content:"";
  position:absolute;
  inset:0;
  transform:translateX(-100%);
  background:linear-gradient(
    90deg,
    transparent,
    rgba(255,255,255,.7),
    transparent
  );
  animation:shimmer 1.4s infinite;
}

@keyframes shimmer {
  100% { transform:translateX(100%); }
}

.skeleton-circle {
  width:56px;
  height:56px;
  border-radius:50%;
}

.skeleton-line {
  height:12px;
  border-radius:8px;
}

.skeleton-line.sm { width:40%; }
.skeleton-line.md { width:65%; }
.skeleton-line.lg { width:85%; }

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
