landlord

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

/* ===============================
   🦴 SKELETON LOADER
================================ */
.skeleton {
    position: relative;
    overflow: hidden;
    background: #e5e7eb;
}

.skeleton::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(
        90deg,
        rgba(229,231,235,0) 0%,
        rgba(243,244,246,.8) 50%,
        rgba(229,231,235,0) 100%
    );
    animation: shimmer 1.4s infinite;
}

@keyframes shimmer {
    from { transform: translateX(-100%); }
    to   { transform: translateX(100%); }
}

.skeleton-card {
    border-radius: 22px;
    padding: 28px;
    height: 170px;
}

.skeleton-icon {
    width: 56px;
    height: 56px;
    border-radius: 16px;
    margin-bottom: 16px;
}

.skeleton-title {
    height: 16px;
    width: 60%;
    border-radius: 6px;
    margin-bottom: 8px;
}

.skeleton-desc {
    height: 12px;
    width: 90%;
    border-radius: 6px;
}

</style>
@endpush

{{-- HEADER --}}
{{-- <div class="mb-4">
    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
        <i class="fas fa-user-shield text-danger"></i>
        Administration Panel
    </h3>

    <p class="text-muted mb-0 small">
        Centralized control for users, permissions, security, and system configuration
    </p>
</div> --}}


{{-- SKELETON LOADER --}}
<div id="landlord-skeleton" class="settings-grid">

    @for ($i = 0; $i < 8; $i++)
        <div class="skeleton skeleton-card">
            <div class="skeleton skeleton-icon"></div>
            <div class="skeleton skeleton-title"></div>
            <div class="skeleton skeleton-desc"></div>
            <div class="skeleton skeleton-desc mt-2"></div>
        </div>
    @endfor

</div>


{{-- SETTINGS GRID --}}
<div id="landlord-content" class="settings-grid d-none">
<div class="settings-grid">

    {{-- LEASE AGREEMENTS --}}
    <a href="{{ route('property.agreements.index', $property->slug) }}" class="settings-card">
        <div class="settings-icon bg-purple">
            <i class="fas fa-file-signature"></i>
        </div>
        <div class="settings-title">Lease Agreements</div>
        <div class="settings-desc">
            Create, manage and track property lease agreements
        </div>
    </a>

    {{-- LEASE TEMPLATE --}}
<a href="{{ route('property.lease-template.edit', $property->slug) }}"
   class="settings-card">

    <div class="settings-icon bg-indigo">
        <i class="fas fa-file-alt"></i>
    </div>

    <div class="settings-title">Lease Template</div>

    <div class="settings-desc">
        Edit and customize the legal wording used in all lease agreements
    </div>

</a>




    {{-- LEASE UNITS --}}
    <a href="{{ route('property.units.index', $property->slug) }}" class="settings-card">
        <div class="settings-icon bg-purple">
            <i class="fas fa-file-signature"></i>
        </div>
        <div class="settings-title">Units</div>
        <div class="settings-desc">
            Create, manage and track property lease agreements
        </div>
    </a>


    {{-- TENANTS --}}
    <a href="{{ route('property.users.index', $property->slug) }}" class="settings-card">
        <div class="settings-icon bg-blue">
            <i class="fas fa-users"></i>
        </div>
        <div class="settings-title">Tenants</div>
        <div class="settings-desc">
            View and manage all registered tenants
        </div>
    </a>
{{-- PAYMENTS --}}
<a href="{{ route('property.payments.index', $property->slug) }}" class="settings-card">
    <div class="settings-icon bg-green">
        <i class="fas fa-credit-card"></i>
    </div>
    <div class="settings-title">Payments</div>
    <div class="settings-desc">
        Track rent payments and payment history
    </div>
</a>




    {{-- EXPENSES --}}
{{-- EXPENSES --}}
<a href="{{ route('property.expenses.index', $property->slug) }}" class="settings-card">
    <div class="settings-icon bg-red">
        <i class="fas fa-receipt"></i>
    </div>
    <div class="settings-title">Expenses</div>
    <div class="settings-desc">
        Track operational and property-related expenses
    </div>
</a>


{{-- PROPERTY REPORTS --}}
<a href="{{ route('property.reports.index', $property->slug) }}" class="settings-card">
    <div class="settings-icon bg-dark">
        <i class="fas fa-chart-bar"></i>
    </div>
    <div class="settings-title">Property Reports</div>
    <div class="settings-desc">
        View financial, occupancy and performance reports
    </div>
</a>


</div>
</div>
@push('scripts')
<script>
    window.addEventListener('load', () => {
        setTimeout(() => {
            document.getElementById('landlord-skeleton')?.remove();
            document.getElementById('landlord-content')?.classList.remove('d-none');
        }, 700); // smooth Apple-like delay
    });
</script>
@endpush


@endsection
