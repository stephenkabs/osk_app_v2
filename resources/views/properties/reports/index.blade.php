@extends('layouts.app')

@section('content')

<style>
:root{
  --ink:#0b0c0f;
  --muted:#6b7280;
  --border:#e6e8ef;
  --card:#ffffff;
  --green:#16a34a;
  --red:#dc2626;
  --blue:#2563eb;
}

.apple-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:18px;
  padding:22px;
  box-shadow:0 8px 26px rgba(0,0,0,.06);
}

.stat-card{
  display:flex;
  flex-direction:column;
  gap:6px;
}

.stat-label{
  font-size:12px;
  font-weight:700;
  text-transform:uppercase;
  color:var(--muted);
}

.stat-value{
  font-size:26px;
  font-weight:900;
  letter-spacing:-.02em;
}

.stat-sub{
  font-size:13px;
  color:var(--muted);
}

.badge-soft{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 12px;
  border-radius:999px;
  font-size:12px;
  font-weight:800;
}

.badge-green{ background:#ecfdf5; color:#065f46; }
.badge-red{ background:#fef2f2; color:#991b1b; }
.badge-blue{ background:#eff6ff; color:#1e40af; }

.section-title{
  font-size:18px;
  font-weight:900;
  margin-bottom:14px;
}

/* Apple-like pill (same height as buttons) */
.af-pill{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:10px 16px;
  border-radius:12px;
  font-weight:800;
  font-size:13px;
  line-height:1;
  cursor:default;
}

/* Outline version already matches af-btn height */
.af-btn-outline{
  background:#fff;
  border:1px solid #e6e8ef;
  color:#0b0c0f;
}

.af-btn-outline i{
  font-size:14px;
}

.af-btn{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding:10px 18px;
  border-radius:12px;
  font-weight:800;
  font-size:13px;
  text-decoration:none;
  border:none;
  cursor:pointer;
  transition:
    transform .12s ease,
    box-shadow .18s ease,
    background .18s ease,
    opacity .12s ease;
}

/* Primary dark button */
.af-btn{
  background:#0b0c0f;
  color:#fff;
}

/* Hover */
.af-btn:hover{
  background:#000;
  transform:translateY(-1px);
  box-shadow:0 8px 20px rgba(0,0,0,.18);
}

/* Active (press) */
.af-btn:active{
  transform:translateY(0);
  box-shadow:0 4px 10px rgba(0,0,0,.12);
}

/* Focus (accessibility) */
.af-btn:focus{
  outline:none;
  box-shadow:0 0 0 3px rgba(0,113,227,.35);
}

/* PDF-specific accent (optional) */
.af-btn-pdf i{
  color:#ff5a5f; /* subtle PDF hint */
}

/* Disabled (future-ready) */
.af-btn.disabled,
.af-btn[aria-disabled="true"]{
  opacity:.5;
  pointer-events:none;
}


</style>

<div class="container py-4">
  <div class="col-lg-11 mx-auto">

    {{-- HEADER --}}
    <div class="apple-card mb-4">
      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <h3 class="fw-bold mb-0">Property Report</h3>
          <p class="text-muted mb-0">{{ $property->property_name }}</p>
        </div>
<div class="d-flex gap-2 align-items-center">

  {{-- YEAR PILL --}}
  <div class="af-btn-outline af-pill">
    <i class="fas fa-calendar me-1"></i>
    {{ now()->year }}
  </div>

  {{-- PDF EXPORT --}}
<a href="{{ route('property.reports.pdf', $property->slug) }}"
   class="af-btn af-btn-pdf"
   role="button"
   aria-label="Export report as PDF">
  <i class="fas fa-file-pdf"></i>
  <span>Export PDF</span>
</a>


</div>

      </div>
    </div>

    {{-- FINANCIAL SUMMARY --}}
    <div class="row g-3 mb-4">

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Total Payments</div>
          <div class="stat-value text-success">
            K{{ number_format($totalPayments,2) }}
          </div>
          <div class="stat-sub">Income received</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Total Expenses</div>
          <div class="stat-value text-danger">
            K{{ number_format($totalExpenses,2) }}
          </div>
          <div class="stat-sub">Operational costs</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Net Balance</div>
          <div class="stat-value {{ $netBalance >= 0 ? 'text-success' : 'text-danger' }}">
            K{{ number_format($netBalance,2) }}
          </div>
          <div class="stat-sub">Profit / Loss</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Tenants</div>
          <div class="stat-value">{{ $totalTenants }}</div>
          <div class="stat-sub">Active & past</div>
        </div>
      </div>

    </div>

    {{-- OCCUPANCY --}}
    <div class="row g-3 mb-4">

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Total Units</div>
          <div class="stat-value">{{ $totalUnits }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Occupied Units</div>
          <div class="stat-value text-success">{{ $occupiedUnits }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Free Units</div>
          <div class="stat-value text-danger">{{ $freeUnits }}</div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="apple-card stat-card">
          <div class="stat-label">Occupancy Rate</div>
          <div class="stat-value">
            {{ $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100) : 0 }}%
          </div>
        </div>
      </div>

    </div>

    {{-- LEASES --}}
    <div class="row g-3 mb-4">

      <div class="col-md-6">
        <div class="apple-card stat-card">
          <div class="stat-label">Total Lease Agreements</div>
          <div class="stat-value">{{ $totalLeases }}</div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="apple-card stat-card">
          <div class="stat-label">Active Leases</div>
          <div class="stat-value text-success">{{ $activeLeases }}</div>
        </div>
      </div>

    </div>

    {{-- MONTHLY SUMMARY (READY FOR CHARTS) --}}
    <div class="apple-card">
      <div class="section-title">This Year Summary</div>

      <div class="row g-3">
        @foreach(range(1,12) as $m)
          @php
            $key = now()->year . '-' . str_pad($m,2,'0',STR_PAD_LEFT);
            $income = $monthlyPayments[$key] ?? 0;
            $expense = $monthlyExpenses[$key] ?? 0;
          @endphp

          <div class="col-md-3 col-6">
            <div class="border rounded-4 p-3">
              <div class="fw-bold">{{ \Carbon\Carbon::createFromDate(null,$m,1)->format('F') }}</div>
              <small class="text-muted d-block">Income: K{{ number_format($income,2) }}</small>
              <small class="text-muted d-block">Expenses: K{{ number_format($expense,2) }}</small>
            </div>
          </div>
        @endforeach
      </div>

    </div>

  </div>
</div>

@endsection
