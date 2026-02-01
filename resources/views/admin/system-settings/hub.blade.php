@extends('layouts.app')

@section('content')

@push('styles')
<style>
/* ===============================
   🍎 SETTINGS HUB
================================ */
.settings-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
    gap: 24px;
}

.settings-card {
    background: rgba(255,255,255,.96);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid #e5e7eb;
    padding: 28px;
    box-shadow: 0 18px 45px rgba(0,0,0,.08);
    transition: transform .25s ease, box-shadow .25s ease;
    text-decoration: none;
    color: inherit;
}

.settings-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 28px 70px rgba(0,0,0,.14);
}

.settings-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    color: #fff;
    margin-bottom: 16px;
}

.bg-red    { background: linear-gradient(135deg,#9b0000,#7d0000); }
.bg-blue   { background: linear-gradient(135deg,#2563eb,#1d4ed8); }
.bg-green  { background: linear-gradient(135deg,#16a34a,#22c55e); }
.bg-purple { background: linear-gradient(135deg,#7c3aed,#6d28d9); }
.bg-orange { background: linear-gradient(135deg,#f97316,#ea580c); }
.bg-indigo {
    background: linear-gradient(135deg,#6366f1,#4f46e5);
    color: #fff;
}

.bg-gray {
    background: linear-gradient(135deg,#374151,#1f2937);
    color: #fff;
}


.settings-title {
    font-weight: 700;
    font-size: 16px;
    margin-bottom: 4px;
}

.settings-desc {
    font-size: 13px;
    color: #6b7280;
}

/* 🔒 LOCKED STATE */
.settings-locked {
    position: relative;
    pointer-events: none;
    filter: blur(1.5px) grayscale(30%);
    opacity: .75;
}

.settings-locked::after {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,0.45);
    z-index: 5;
}

</style>
@endpush

{{-- HEADER --}}
<div class="mb-4">
    <h3 class="fw-bold mb-1">System Settings</h3>
    <p class="text-muted mb-0">
        Manage access, security, and core system configuration
    </p>
</div>
@php($isSuperAdmin = auth()->user()?->hasRole('admin') ?? false)


<div class="settings-wrapper {{ !$isSuperAdmin ? 'settings-locked' : '' }}">
{{-- SETTINGS GRID --}}
<div class="settings-grid">

    {{-- ROLES --}}
    <a href="{{ route('role.index') }}" class="settings-card">
        <div class="settings-icon bg-purple">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="settings-title">Roles</div>
        <div class="settings-desc">
            Manage system roles and access levels
        </div>
    </a>

    {{-- PERMISSIONS --}}
    <a href="{{ route('permission.index') }}" class="settings-card">
        <div class="settings-icon bg-blue">
            <i class="fas fa-key"></i>
        </div>
        <div class="settings-title">Permissions</div>
        <div class="settings-desc">
            Control granular permissions
        </div>
    </a>

    {{-- SYSTEM USERS --}}
    <a href="{{ route('users.index') }}" class="settings-card">
        <div class="settings-icon bg-green">
            <i class="fas fa-users-cog"></i>
        </div>
        <div class="settings-title">System Users</div>
        <div class="settings-desc">
            Administrators and internal users
        </div>
    </a>

    {{-- GENERAL SYSTEM --}}
    <a href="{{ route('admin.settings.index') }}" class="settings-card">
        <div class="settings-icon bg-orange">
            <i class="fas fa-sliders-h"></i>
        </div>
        <div class="settings-title">General System</div>
        <div class="settings-desc">
            Global system behavior & limits
        </div>
    </a>

    {{-- API TOKENS --}}
    {{-- <a href="/api-tokens" class="settings-card">
        <div class="settings-icon bg-red">
            <i class="fas fa-plug"></i>
        </div>
        <div class="settings-title">API Tokens</div>
        <div class="settings-desc">
            Manage API access & integrations
        </div>
    </a> --}}



{{-- LOGIN SECURITY MONITOR --}}
<a href="{{ route('admin.login-attempts') }}" class="settings-card">
    <div class="settings-icon bg-red">
        <i class="fas fa-shield-alt"></i>
    </div>

    <div class="settings-title">
        Login Security
    </div>

    <div class="settings-desc">
        Monitor failed login attempts, suspicious activity, and potential threats
    </div>
</a>


{{-- LOGIN SCREEN HERO --}}
<a href="{{ route('login-hero.edit') }}" class="settings-card">
    <div class="settings-icon bg-indigo">
        <i class="fas fa-image"></i>
    </div>

    <div class="settings-title">
        Login Screen
    </div>

    <div class="settings-desc">
        Customize left-side image, title and text on login page
    </div>
</a>


{{-- PACKAGES MANAGEMENT --}}
{{-- <a href="{{ route('admin.packages.index') }}" class="settings-card">
    <div class="settings-icon bg-success">
        <i class="fas fa-box-open"></i>
    </div>

    <div class="settings-title">
        Packages
    </div>

    <div class="settings-desc">
        Create, update and manage subscription packages & limits
    </div>
</a> --}}


{{-- PRIVACY --}}
<a href="{{ route('admin.privacy.index') }}" class="settings-card">
    <div class="settings-icon bg-dark">
        <i class="fas fa-user-lock"></i>
    </div>

    <div class="settings-title">Privacy Policy</div>

    <div class="settings-desc">
        Data protection, retention & compliance
    </div>
</a>

{{-- ADMIN • HELP & SUPPORT --}}
{{-- <a href="{{ route('admin.support.index') }}" class="settings-card">
    <div class="settings-icon bg-green">
        <i class="fas fa-life-ring"></i>
    </div>

    <div class="settings-title">Help & Support</div>

    <div class="settings-desc">
        Manage FAQs, guides, and how the HRM system works
    </div>
</a> --}}



{{-- EMPLOYEE DATABASE --}}
{{-- <a href="/developer/loans-database" class="settings-card">
    <div class="settings-icon bg-indigo">
<i class="fas fa-database"></i>
    </div>
    <div class="settings-title">Loans Database</div>
    <div class="settings-desc">
        Backup Archived Loans Database
    </div>
</a> --}}

{{-- ACTIVITY LOGS --}}
<a href="/activity-logs" class="settings-card">
    <div class="settings-icon bg-gray">
        <i class="fas fa-clipboard-list"></i>
    </div>
    <div class="settings-title">Activity Logs</div>
    <div class="settings-desc">
        Track system actions & user activities
    </div>
</a>


{{-- LOGIN SCREEN HERO --}}
{{-- <a href="{{ route('login-hero.edit') }}" class="settings-card">
    <div class="settings-icon bg-indigo">
        <i class="fas fa-image"></i>
    </div>

    <div class="settings-title">
        Login Screen
    </div>

    <div class="settings-desc">
        Customize left-side image, title and text on login page
    </div>
</a> --}}


{{-- DATABASE BACKUP --}}
<a href="{{ route('admin.backup.index') }}" class="settings-card">
    <div class="settings-icon bg-blue">
        <i class="fas fa-database"></i>
    </div>

    <div class="settings-title">
        Database Backup
    </div>

    <div class="settings-desc">
        Manually export and store the system database securely in DigitalOcean Spaces
    </div>
</a>


{{-- LOGIN SECURITY MONITOR --}}
{{-- <a href="{{ route('admin.login-attempts') }}" class="settings-card">
    <div class="settings-icon bg-red">
        <i class="fas fa-shield-alt"></i>
    </div>

    <div class="settings-title">
        Login Security
    </div>

    <div class="settings-desc">
        Monitor failed login attempts, suspicious activity, and potential threats
    </div>
</a> --}}





{{-- DOCUMENTATION --}}
<a href="/support" class="settings-card">
    <div class="settings-icon bg-indigo">
        <i class="fas fa-id-badge"></i>
    </div>
    <div class="settings-title">Help & Support</div>
    <div class="settings-desc">
        Select a Help & Support to view usage instructions
    </div>
</a>

</div>
</div>

@if(!$isSuperAdmin)
<div class="modal fade" id="superAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4">

      <div class="modal-body p-4 text-center">

        <div class="mb-3">
          <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-inline-flex align-items-center justify-content-center"
               style="width:64px;height:64px;">
            <i class="fas fa-user-shield fa-lg"></i>
          </div>
        </div>

        <h5 class="fw-bold mb-2">Restricted Access</h5>

        <p class="text-muted mb-4">
          You are not logged in as a <strong>Super Admin</strong>.<br>
          Please login with a Super Admin account to manage system settings.
        </p>

        <div class="d-flex justify-content-center gap-2">
          <a href="{{ route('logout') }}"
             onclick="event.preventDefault();document.getElementById('logout-form').submit();"
             class="btn btn-danger rounded-pill px-4">
            Switch Account
          </a>

          <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Close
          </button>
        </div>

      </div>
    </div>
  </div>
</div>
@endif
@if(!$isSuperAdmin)
<script>
document.addEventListener("DOMContentLoaded", () => {
    setTimeout(() => {
        const modal = new bootstrap.Modal(
            document.getElementById('superAdminModal'),
            { backdrop: 'static', keyboard: false }
        );
        modal.show();
    }, 0000); // ⏱️ 5 seconds
});
</script>
@endif


@endsection
