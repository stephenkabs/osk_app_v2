<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Edit User • {{ config('app.name') }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Bootstrap -->
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/assets/css/app.min.css" rel="stylesheet" />

  <!-- Icons -->
  <link href="/assets/css/icons.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <style>
    :root{
      --card:#fff; --ink:#0b0c0f; --muted:#5b5f6b; --ring:#0071e3;
      --border:#e6e8ef; --radius:14px;
    }
    body{ font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif; }

    .apple-card{ background:var(--card); border-radius:var(--radius); padding:24px; }
    .apple-header{ display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:20px; }
    .apple-title{ font-weight:800; font-size:20px; margin:0; }
    .apple-sub{ color:var(--muted); font-weight:600; font-size:11px; text-transform:uppercase; }

    .grid{ display:grid; gap:14px; grid-template-columns:1fr; }
    @media(min-width:720px){ .grid-2{ grid-template-columns:1fr 1fr; } }

    label{ font-weight:700; font-size:13px; margin-bottom:6px; display:block; }
    .af-input, .af-textarea, .af-select{
      width:100%; border:1px solid var(--border); border-radius:12px;
      padding:10px 12px; font-size:14px; font-weight:600; color:var(--ink);
      outline:none; transition:.2s;
    }
    .af-input:focus, .af-select:focus, .af-textarea:focus{
      border-color:var(--ring); box-shadow:0 0 0 3px color-mix(in srgb, var(--ring) 18%, transparent);
    }
    .af-textarea{ min-height:100px; resize:vertical; }

    .af-btn{
      background:var(--ink); color:#fff; border:none; padding:10px 18px;
      border-radius:12px; font-weight:800; font-size:14px; cursor:pointer;
      display:inline-flex; align-items:center; justify-content:center; gap:8px;
      transition:.2s;
    }
    .af-btn:hover{ background:#000; transform:translateY(-1px); }

    .apple-tabs .nav-link{
    border-radius:999px;
    font-weight:700;
    font-size:13px;
    padding:8px 14px;
    color:#5b5f6b;
}
.apple-tabs .nav-link.active{
    background:#0b0c0f;
    color:#fff;
}

/* =========================
   SKELETON PRELOADER
========================= */
.skeleton-wrap{
    animation: fadeIn .25s ease;
}

.skeleton{
    background: linear-gradient(
        90deg,
        #f1f2f6 25%,
        #e6e8ef 37%,
        #f1f2f6 63%
    );
    background-size: 400% 100%;
    animation: shimmer 1.4s ease infinite;
    border-radius: 12px;
}

/* Sizes */
.skeleton-title{ height:22px; width:180px; }
.skeleton-sub{ height:12px; width:220px; }

.skeleton-tab{
    height:32px;
    width:90px;
    border-radius:999px;
}

.skeleton-input{
    height:44px;
    width:100%;
}

.skeleton-btn{
    height:40px;
    width:140px;
}
.skeleton-btn.secondary{
    width:110px;
}

/* Animations */
@keyframes shimmer{
    0%{ background-position:100% 0 }
    100%{ background-position:-100% 0 }
}

@keyframes fadeIn{
    from{ opacity:0 }
    to{ opacity:1 }
}


  </style>
</head>
<body data-sidebar="dark">


@include('includes.header')
@include('includes.sidebar')


<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">
      <div class="col-lg-8 mx-auto">
<!-- SKELETON PRELOADER -->
<div id="userEditSkeleton" class="apple-card skeleton-wrap">

    <!-- Header -->
    <div class="skeleton skeleton-title mb-3"></div>
    <div class="skeleton skeleton-sub mb-4"></div>

    <!-- Tabs -->
    <div class="d-flex gap-2 mb-4">
        <div class="skeleton skeleton-tab"></div>
        <div class="skeleton skeleton-tab"></div>
        <div class="skeleton skeleton-tab"></div>
    </div>

    <!-- Form rows -->
    <div class="grid grid-2 mb-3">
        <div class="skeleton skeleton-input"></div>
        <div class="skeleton skeleton-input"></div>
    </div>

    <div class="grid grid-2 mb-3">
        <div class="skeleton skeleton-input"></div>
        <div class="skeleton skeleton-input"></div>
    </div>

    <div class="skeleton skeleton-input mb-4"></div>

    <!-- Buttons -->
    <div class="d-flex justify-content-end gap-2">
        <div class="skeleton skeleton-btn"></div>
        <div class="skeleton skeleton-btn secondary"></div>
    </div>

</div>
<div id="userEditContent" class="d-none">
        <div class="apple-card">


<div class="apple-header">
    <div>
        <h1 class="apple-title mb-1">Edit User</h1>
        <div class="apple-sub">Administration • User Manager</div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-pills apple-tabs mb-4" id="userEditTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active"
                data-bs-toggle="tab"
                data-bs-target="#tab-profile"
                type="button">
            <i class="fas fa-user me-1"></i> Profile
        </button>
    </li>

    <li class="nav-item" role="presentation">
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#tab-password"
                type="button">
            <i class="fas fa-lock me-1"></i> Password
        </button>
    </li>

    @role('admin|super-admin')
    <li class="nav-item" role="presentation">
        <button class="nav-link"
                data-bs-toggle="tab"
                data-bs-target="#tab-roles"
                type="button">
            <i class="fas fa-shield-alt me-1"></i> Roles
        </button>
    </li>
    @endrole
</ul>

<form action="{{ route('users.update', $user->slug) }}"
      method="POST">
    @csrf
    @method('PUT')

<div class="tab-content">

    <!-- ================= PROFILE TAB ================= -->
    <div class="tab-pane fade show active" id="tab-profile">

        <div class="grid grid-2">
            <div>
                <label>Full Name</label>
                <input type="text"
                       name="name"
                       class="af-input"
                       value="{{ old('name', $user->name) }}"
                       required>
            </div>

            <div>
                <label>Email</label>
       <input type="email"
       class="af-input"
       value="{{ $user->email }}"
       readonly>

<input type="hidden" name="email" value="{{ $user->email }}">

            </div>
        </div>




    </div>

    <!-- ================= PASSWORD TAB ================= -->
    <div class="tab-pane fade" id="tab-password">

        <div class="alert alert-light border small mb-4">
            <i class="fas fa-info-circle me-1"></i>
            Leave these fields empty if you don’t want to change the password.
        </div>

        <div class="grid grid-2">
            <div>
                <label>New Password</label>
                <input type="password"
                       name="password"
                       class="af-input"
                       placeholder="Enter new password">
            </div>

            <div>
                <label>Confirm Password</label>
                <input type="password"
                       name="password_confirmation"
                       class="af-input"
                       placeholder="Confirm new password">
            </div>
        </div>

    </div>

    <!-- ================= ROLES TAB ================= -->
    @role('admin|super-admin')
    <div class="tab-pane fade" id="tab-roles">

        <label>User Roles</label>
        <select name="roles[]" class="af-select" multiple>
            @foreach ($roles as $role)
                <option value="{{ $role }}"
                    {{ in_array($role, $userRoles) ? 'selected' : '' }}>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>

        <!-- fallback -->
        <input type="hidden" name="roles[]" value="user">
    </div>
    @endrole

</div>

<!-- ACTIONS -->
<div class="d-flex justify-content-end gap-2 mt-4">
    <button type="submit" class="af-btn">
        <i class="fas fa-save"></i> Save Changes
    </button>

    <a href="{{ route('users.index') }}"
       class="af-btn"
       style="background:#5b5f6b;">
        Cancel
    </a>
</div>

</form>



        </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>
@include('includes.modals.success')
@include('includes.modals.error')
<script>
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.getElementById('userEditSkeleton')?.classList.add('d-none');
        document.getElementById('userEditContent')?.classList.remove('d-none');
    }, 600); // subtle Apple-like delay
});
</script>

</body>
</html>
