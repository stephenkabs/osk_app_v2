<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>OSK App</title>

  <link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="/assets/css/app.min.css" rel="stylesheet" />
  <link href="/assets/css/icons.min.css" rel="stylesheet" />
  <link rel="shortcut icon" href="/assets/images/favicon.png">


  <style>
/* ===============================
   BASE
================================ */
body {
  background:#e8e8e8;
  font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif;
}

.apple-card {
  background:#fff;
  border-radius:16px;
  padding:28px;
  box-shadow:0 4px 20px rgba(0,0,0,.05);
  margin-bottom:24px;
}

/* ===============================
   🍏 SKELETON LOADER
================================ */
@keyframes shimmer {
  0% { background-position:-200px 0; }
  100% { background-position:200px 0; }
}

.skeleton-line,
.skeleton-icon,
.skeleton-circle,
.skeleton-row {
  background: linear-gradient(90deg,#f0f1f5 25%,#e4e6eb 37%,#f0f1f5 63%);
  background-size:400% 100%;
  animation: shimmer 1.4s infinite;
  border-radius:8px;
}

.skeleton-card {
  background:#fff;
  border-radius:14px;
  padding:20px;
  display:flex;
  gap:16px;
  align-items:center;
}

.skeleton-circle { width:64px;height:64px;border-radius:50%; }
.skeleton-line { height:12px; }
.w-50{width:50%} .w-30{width:30%}
.w-60{width:60%} .w-80{width:80%}

.skeleton-tile {
  background:#fff;
  border-radius:14px;
  padding:18px;
  display:flex;
  gap:14px;
}

.skeleton-icon { width:54px;height:54px;border-radius:12px }


/* new */

/* ===============================
   🍏 MATCHING ADMIN SKELETON
================================ */

.skeleton-admin-card {
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
}

.skeleton-admin-left {
  display: flex;
  align-items: center;
  gap: 16px;
  flex-grow: 1;
}

.skeleton-badge {
  width: 140px;
  height: 34px;
  border-radius: 999px;
  background: linear-gradient(
    90deg,
    #f0f1f5 25%,
    #e4e6eb 37%,
    #f0f1f5 63%
  );
  background-size: 400% 100%;
  animation: shimmer 1.4s infinite;
}

/* ===============================
   🍎 SETTINGS GRID SKELETON
================================ */

.skeleton-settings-card {
  background: #fff;
  border-radius: 22px;
  padding: 28px;
  box-shadow: 0 18px 45px rgba(0,0,0,.04);
}

.skeleton-settings-icon {
  width: 56px;
  height: 56px;
  border-radius: 16px;
  margin-bottom: 16px;
  background: linear-gradient(
    90deg,
    #f0f1f5 25%,
    #e4e6eb 37%,
    #f0f1f5 63%
  );
  background-size: 400% 100%;
  animation: shimmer 1.4s infinite;
}

/* reuse existing skeleton-line widths */
.w-70 { width: 70%; }
.w-90 { width: 90%; }


/* ===============================
   🌈 GRADIENT HEADER
================================ */
.gradient-animate {
   background: linear-gradient(120deg,#1c1c1c,#673d05,#6f4a05,#ca8d2b,#67450a);
  background-size:300% 300%;
  animation: gradientFlow 12s ease infinite;
}

@keyframes gradientFlow {
  0%{background-position:0% 50%}
  50%{background-position:100% 50%}
  100%{background-position:0% 50%}
}

/* ===============================
   🍎 SETTINGS GRID
================================ */
.settings-grid {
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
  gap:24px;
}

.settings-card {
  background:rgba(255,255,255,.96);
  border-radius:22px;
  border:1px solid #e5e7eb;
  padding:28px;
  box-shadow:0 18px 45px rgba(0,0,0,.08);
  transition:.25s;
  text-decoration:none;
  color:inherit;
}

.settings-card:hover {
  transform:translateY(-6px);
  box-shadow:0 28px 70px rgba(0,0,0,.14);
}

.settings-icon {
  width:56px;
  height:56px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:22px;
  color:#fff;
  margin-bottom:16px;
}

.bg-red{background:linear-gradient(135deg,#9b0000,#7d0000)}
.bg-blue{background:linear-gradient(135deg,#2563eb,#1d4ed8)}
.bg-green{background:linear-gradient(135deg,#16a34a,#22c55e)}
.bg-purple{background:linear-gradient(135deg,#7c3aed,#6d28d9)}
.bg-indigo{background:linear-gradient(135deg,#6366f1,#4f46e5)}
.bg-gray{background:linear-gradient(135deg,#374151,#1f2937)}
.bg-orange{background:linear-gradient(135deg,#722f06,#e48643)}
.bg-lightblue{background:linear-gradient(135deg,#043d45,#0896a3)}
.bg-steph{background:linear-gradient(135deg,#3e4504,#b4da0c)}
.bg-nice{background:linear-gradient(135deg,#2e0445,#620cda)}
.bg-good{background:linear-gradient(135deg,#04452e,#0cdac2)}

.settings-title{font-weight:700;font-size:16px}
.settings-desc{font-size:13px;color:#6b7280}

/* ===============================
   🍎 PENDING CLIENTS BADGE
================================ */

.pending-badge {
  position: absolute;
  top: 14px;
  right: 14px;
  min-width: 22px;
  height: 22px;
  padding: 0 6px;
  border-radius: 999px;
  background: linear-gradient(135deg, #f59e0b, #f97316);
  color: #fff;
  font-size: 12px;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 6px 18px rgba(249,115,22,.45);
}

/* Small helper text */
.pending-text {
  font-size: 11px;
  font-weight: 700;
  color: #f59e0b;
  background: #fffbeb;
  padding: 2px 8px;
  border-radius: 999px;
}

/* ===============================
   📊 RENT SUMMARY CARDS
================================ */

.summary-card{
  background:#ffffff;
  border:1px solid #e6e8ef;
  border-radius:18px;
  padding:18px 16px;
  text-align:center;
  font-weight:800;
  box-shadow:0 8px 28px rgba(0,0,0,.06);
  transition:.25s ease;
}

.summary-card:hover{
  transform:translateY(-4px);
  box-shadow:0 16px 45px rgba(0,0,0,.12);
}

/* Label */
.summary-card span{
  display:block;
  font-size:11px;
  text-transform:uppercase;
  letter-spacing:.08em;
  color:#6b7280;
  margin-bottom:6px;
}

/* Value */
.summary-card strong{
  font-size:20px;
  letter-spacing:-.02em;
  color:#0b0c0f;
}

/* Paid */
.summary-success strong{
  color:#16a34a;
}

/* Overdue */
.summary-danger strong{
  color:#dc2626;
}

/* ===============================
   📱 RESPONSIVE
================================ */

@media (max-width:768px){
  .summary-card{
    padding:14px 12px;
  }

  .summary-card strong{
    font-size:16px;
  }
}


  </style>
</head>

<body data-sidebar="dark">

@include('includes.header')
@include('includes.sidebar')

<div class="main-content">
<div class="page-content">
<div class="container-fluid">

<!-- 🍏 SKELETON (MATCHING ADMIN + GRID) -->
<div id="dashboard-skeleton">

  <!-- ADMIN / WELCOME CARD SKELETON -->
  <div class="skeleton-admin-card mb-4">
    <div class="skeleton-admin-left">
      <div class="skeleton-circle"></div>
      <div class="skeleton-lines">
        <div class="skeleton-line w-60"></div>
        <div class="skeleton-line w-80"></div>
      </div>
    </div>

    <div class="skeleton-badge"></div>
  </div>

  <!-- SETTINGS GRID SKELETON -->
  <div class="settings-grid">

    @for($i = 0; $i < 5; $i++)
      <div class="skeleton-settings-card">
        <div class="skeleton-settings-icon"></div>

        <div class="skeleton-lines">
          <div class="skeleton-line w-70"></div>
          <div class="skeleton-line w-90"></div>
        </div>
      </div>
    @endfor

  </div>
</div>


<!-- 🍏 CONTENT -->
<div id="dashboard-content" style="display:none;">


<!-- ADMIN / WELCOME CARD -->
<div class="apple-card gradient-animate text-white">
  <div class="d-flex align-items-center gap-3">
    <div class="bg-light text-secondary rounded-circle d-flex align-items-center justify-content-center"
         style="width:64px;height:64px;">
      <i class="fas fa-user-shield fa-lg"></i>
    </div>

    <div class="flex-grow-1">
      <h4 class="mb-1 fw-bold" style="color: white">OSK Admin Dashboard</h4>
      <small class="text-white-50">
        Centralized control for users, permissions, reports & system configuration
      </small>
    </div>

    <span class="badge bg-light text-dark rounded-pill px-3 py-2">
      Welcome, {{ $user->name }}
    </span>
  </div>


</div>

{{-- 📊 RENT SUMMARY --}}
@include('properties.rent-summary._summary')

</div>
</div>
</div>

<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/app.js"></script>

<script>
document.addEventListener('DOMContentLoaded',()=>{
  setTimeout(()=>{
    document.getElementById('dashboard-skeleton').style.display='none';
    document.getElementById('dashboard-content').style.display='block';
  },400);
});
</script>

</body>
</html>
