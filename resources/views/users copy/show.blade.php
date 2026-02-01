<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Profile • {{ $user->name }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/assets/css/app.min.css" rel="stylesheet" />

  <!-- Icons -->
  <link href="/assets/css/icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <style>
    :root{
      --card:#fff; --ink:#0b0c0f; --muted:#5b5f6b; --ring:#0071e3; --border:#e6e8ef; --radius:14px;
    }
    body{ font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif; }

    .apple-card{ background:var(--card); border-radius:var(--radius); padding:24px; }
    .apple-header{ display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:20px; }
    .apple-title{ font-weight:800; font-size:20px; letter-spacing:-0.02em; margin:0; }
    .apple-sub{ color:var(--muted); font-weight:600; font-size:11px; text-transform:uppercase; }

    .profile-section{ display:flex; align-items:center; gap:20px; margin-bottom:20px; }
    .profile-section img{
      width:100px; height:100px; object-fit:cover;
      border-radius:12px; border:2px solid var(--border);
    }

    .info-list{ margin:0; padding:0; list-style:none; }
    .info-list li{ margin-bottom:12px; font-size:14px; }
    .info-label{ font-weight:700; color:var(--muted); display:block; font-size:12px; text-transform:uppercase; }
    .info-value{ font-weight:600; color:var(--ink); }

    .af-btn{
      background:var(--ink); color:#fff; border:none; padding:10px 18px;
      border-radius:12px; font-weight:800; font-size:14px; cursor:pointer;
      display:inline-flex; align-items:center; justify-content:center; gap:8px;
      transition:.2s;
    }
    .af-btn:hover{ background:#000; transform:translateY(-1px); }
    /* 🍏 Skeleton Animation */
@keyframes shimmer {
  0% { background-position: -300px 0; }
  100% { background-position: 300px 0; }
}

.skeleton-line,
.skeleton-avatar,
.skeleton-info-card,
.skeleton-btn {
  background: linear-gradient(
    90deg,
    #f0f1f5 25%,
    #e5e7eb 37%,
    #f0f1f5 63%
  );
  background-size: 400% 100%;
  animation: shimmer 1.4s infinite ease;
  border-radius: 10px;
}

/* Header */
.skeleton-line {
  height: 12px;
}

.w-20 { width: 20%; }
.w-25 { width: 25%; }
.w-30 { width: 30%; }
.w-40 { width: 40%; }

/* Avatar */
.skeleton-avatar {
  width: 120px;
  height: 120px;
  border-radius: 12px;
}

/* Info Cards */
.skeleton-info-card {
  height: 86px;
  border-radius: 14px;
}

/* Buttons */
.skeleton-btn {
  width: 140px;
  height: 42px;
  border-radius: 12px;
}

  </style>
</head>
<body data-sidebar="dark">


@include('includes.header')
@include('includes.sidebar')
@include('includes.validation')

<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
        <!-- 🍏 Profile Skeleton -->
<div id="profile-skeleton" class="col-lg-9 mx-auto">

  <div class="apple-card">

    <!-- Header Skeleton -->
    <div class="d-flex justify-content-between align-items-end mb-4">
      <div>
        <div class="skeleton-line w-40 mb-2"></div>
        <div class="skeleton-line w-25"></div>
      </div>
      <div class="skeleton-btn"></div>
    </div>

    <!-- Avatar Section -->
    <div class="d-flex align-items-center gap-4 mb-4">
      <div class="skeleton-avatar"></div>
      <div class="flex-grow-1">
        <div class="skeleton-line w-30 mb-2"></div>
        <div class="skeleton-line w-20"></div>
      </div>
    </div>

    <!-- Info Cards -->
    <div class="row g-3">
      @for($i = 0; $i < 6; $i++)
        <div class="col-md-4">
          <div class="skeleton-info-card"></div>
        </div>
      @endfor
    </div>

    <!-- Action Buttons -->
    <div class="d-flex gap-3 mt-4">
      <div class="skeleton-btn"></div>
      <div class="skeleton-btn"></div>
    </div>

  </div>
</div>

<!-- 🔥 Real profile content -->
<div id="profile-content" style="display:none;">

      <div class="col-lg-9 mx-auto">
        <div class="apple-card">

          <!-- Header -->
          <div class="apple-header">
            <div>
           <h2 class="apple-title">User Details</h2>
              <div class="apple-sub">Profile for {{ $user->name }}</div>
            </div>
            <a href="{{ route('users.index') }}" class="af-btn">
              <i class="fas fa-arrow-left"></i> Back
            </a>

          </div>

          <!-- Profile Section -->
          <div class="profile-section">
      @if($user->profile_image)
  <img src="{{ asset('storage/' . $user->profile_image) }}"
       alt="Profile Image"
       style="width:120px;height:120px;object-fit:cover;object-position:top;border-radius:12px;border:2px solid #e6e8ef;">
@else
  <img src="/assets/images/user.jpg"
       alt="Default Avatar"
       style="width:120px;height:120px;object-fit:cover;object-position:top;border-radius:12px;border:2px solid #e6e8ef;">
@endif

            <div>
              <h4 class="mb-1">{{ $user->name }}</h4>
              <small class="text-muted">{{ implode(', ', $user->getRoleNames()->toArray()) ?: 'No Role Assigned' }}</small>
            </div>
          </div>

<!-- Info Cards -->
<div class="row g-3">
  <!-- Email -->
  <div class="col-md-4">
    <div class="info-card">
      <div class="info-label"><i class="fas fa-envelope me-1"></i> Email</div>
      <div class="info-value">{{ $user->email }}</div>
    </div>
  </div>

  <!-- Special Code -->
  <div class="col-md-4">
    <div class="info-card">
      <div class="info-label"><i class="fas fa-key me-1"></i> Security Code</div>
      <div class="info-value">{{ $user->special_code }}</div>
    </div>
  </div>
  <!-- Buttons -->


  <!-- Country -->
  {{-- <div class="col-md-4">
    <div class="info-card">
      <div class="info-label"><i class="fas fa-flag me-1"></i> Country</div>
      <div class="info-value">{{ $user->country ?? '—' }}</div>
    </div>
  </div> --}}

  <!-- City -->
  {{-- <div class="col-md-4">
    <div class="info-card">
      <div class="info-label"><i class="fas fa-city me-1"></i> City</div>
      <div class="info-value">{{ $user->city ?? '—' }}</div>
    </div>
  </div> --}}

  <!-- WhatsApp -->
  {{-- <div class="col-md-4">
    <div class="info-card">
      <div class="info-label"><i class="fab fa-whatsapp me-1"></i> WhatsApp</div>
      <div class="info-value">{{ $user->whatsapp_line ?? '—' }}</div>
    </div>
  </div> --}}

  <!-- Address -->
  {{-- <div class="col-md-4">
    <div class="info-card">
      <div class="info-label"><i class="fas fa-map-marker-alt me-1"></i> Address</div>
      <div class="info-value">{{ $user->address ?? '—' }}</div>
    </div>
  </div> --}}

  <!-- Joined -->
  <div class="col-md-4">
    <div class="info-card">
      <div class="info-label"><i class="fas fa-calendar me-1"></i> Joined</div>
      <div class="info-value">{{ $user->created_at->format('M d, Y') }}</div>
    </div>
  </div>




<style>
  .info-card {
    background: var(--card);
    border: 1px solid var(--border);
    border-radius: 14px;
    padding: 16px;
    transition: 0.2s;
  }
  .info-card:hover {
    border-color: var(--ring);
    box-shadow: 0 4px 14px rgba(0,0,0,0.05);
    transform: translateY(-2px);
  }
  .info-label {
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .info-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--ink);
  }
</style>

<!-- Actions -->
<div class="mt-4 d-flex gap-2 flex-wrap">
  <!-- Edit -->
  <a href="{{ route('users.edit', $user->slug) }}" class="af-btn">
    <i class="fas fa-edit"></i> Edit
  </a>

  <!-- Toggle Status -->

<!-- Status Button (Display Only) -->
@if($user->active)
  <button type="button" class="af-btn" style="background:#2e7d32;" disabled>
    <i class="fas fa-user-check"></i> Account Active
  </button>
@else
  <button type="button" class="af-btn" style="background:#f39c12;" disabled>
    <i class="fas fa-user-slash"></i> Inactive
  </button>
@endif



</div>


        {{-- </div>
@role('investor')
  <!-- ✅ New Accounts Card -->
  <div class="apple-card mt-4">
    <div class="apple-header">
      <div>
        <h2 class="apple-title">Linked Bank Details</h2>
        <div class="apple-sub">Bank Details for {{ $user->name }}</div>
      </div>
      <a href="{{ route('accounts.create') }}" class="af-btn">
        <i class="fas fa-plus"></i> Add Account
      </a>
    </div>

    @php
      $gradients = [
        'linear-gradient(135deg, #0071e3, #0b0c0f)',
        'linear-gradient(135deg, #ff5e3a, #ff2a68)',
        'linear-gradient(135deg, #34c759, #248a3d)',
        'linear-gradient(135deg, #5856d6, #242246)',
        'linear-gradient(135deg, #ff9500, #cc7a00)',
        'linear-gradient(135deg, #00c6ff, #0072ff)',
        'linear-gradient(135deg, #ff9a9e, #fad0c4)',
      ];
    @endphp

    <div class="row g-4">
      @forelse ($accounts as $index => $acc)
        @php $bg = $gradients[$index % count($gradients)]; @endphp
        <div class="col-md-6">
          <div class="bank-card" style="background: {{ $bg }};">
            <div class="d-flex justify-content-between align-items-center">
              <h2>{{ $acc->account_name }}</h2>
              <i class="fas fa-university fa-lg"></i>
            </div>
            <div class="bank-meta">{{ $acc->branch }}</div>

            <div class="bank-number">
              {{ chunk_split($acc->account_number, 4, ' ') }}
            </div>

            <div class="bank-footer">
              <div><span>Type:</span> {{ ucfirst($acc->type) }}</div>
              <div><span>Currency:</span> {{ strtoupper($acc->currency) }}</div>
            </div>

            <div class="bank-footer">
              <div><span>Swift:</span> {{ $acc->swift_code ?? '—' }}</div>
              <div><span>Sort:</span> {{ $acc->sort_code ?? '—' }}</div>
            </div>
          </div>
        </div>
      @empty
        <p class="text-muted">No accounts found for this member.</p>
      @endforelse
    </div>
  </div>
  @endrole
</div> --}}

<style>
.bank-card {
  color:#fff;
  border-radius:18px;
  padding:24px;
  box-shadow:0 6px 18px rgba(0,0,0,.15);
  position:relative;
  overflow:hidden;
  min-height:180px;
  transition:.2s;
}
.bank-card::before {
  content:"";
  position:absolute;
  top:-50px; right:-50px;
  width:200px; height:200px;
  background:rgba(255,255,255,.08);
  border-radius:50%;
}
.bank-card:hover { transform:translateY(-2px); box-shadow:0 8px 20px rgba(0,0,0,.25);}
.bank-number {
  font-size:18px; letter-spacing:3px; margin:20px 0; font-weight:600;
}
.bank-footer {
  display:flex; justify-content:space-between; align-items:center; margin-top:8px;
  font-size:14px;
}
.bank-footer span { font-weight:600; }
</style>

      </div>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    const skeleton = document.getElementById('profile-skeleton');
    const content  = document.getElementById('profile-content');

    if (skeleton) skeleton.remove();
    if (content) content.style.display = 'block';
  }, 350); // subtle Apple-like delay
});
</script>

<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>
@include('includes.modals.success')
@include('includes.modals.error')
</body>
</html>
