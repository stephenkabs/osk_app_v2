@extends('layouts.app')
<style>
:root{
  --card-bg:#ffffff;
  --card-border:#e6e8ef;
  --ink:#0b0c0f;
  --muted:#6b7280;
  --success:#16a34a;
  --danger:#dc2626;
  --warning:#f59e0b;
}

/* ===============================
   🍎 APPLE CARD
================================ */
.apple-card{
  background:var(--card-bg);
  border:1px solid var(--card-border);
  border-radius:18px;
  padding:18px;
  box-shadow:0 8px 30px rgba(0,0,0,.06);
}

/* ===============================
   🔢 SUMMARY CARDS
================================ */
.summary-card{
  text-align:center;
  padding:20px 14px;
  border-radius:18px;
  font-weight:800;
}

.summary-card span{
  display:block;
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:.04em;
  color:var(--muted);
  margin-bottom:6px;
}

.summary-card strong{
  font-size:20px;
  letter-spacing:-.02em;
}

.summary-success{ color:var(--success); }
.summary-danger{ color:var(--danger); }

/* ===============================
   📋 TABLE
================================ */
.table{
  font-size:14px;
}

.table thead th{
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:.04em;
  color:#374151;
  background:#f9fafb;
  border-bottom:2px solid var(--card-border);
}

.table tbody tr{
  transition:.15s ease;
}

.table tbody tr:hover{
  background:#f9fafb;
}

/* Numeric columns */
.table td.text-end{
  font-variant-numeric: tabular-nums;
}

/* ===============================
   🏷 BADGES
================================ */
.badge{
  padding:6px 10px;
  border-radius:999px;
  font-size:11px;
  font-weight:800;
}

.bg-success{
  background:#ecfdf5 !important;
  color:#065f46 !important;
}

.bg-secondary{
  background:#f3f4f6 !important;
  color:#374151 !important;
}

/* ===============================
   📅 MONTH SELECT
================================ */
input[type="month"]{
  border-radius:12px;
  border:1px solid var(--card-border);
  font-weight:700;
}

/* ===============================
   📱 MOBILE
================================ */
@media(max-width:768px){
  .summary-card strong{
    font-size:16px;
  }

  table{
    font-size:13px;
  }
}
</style>

@section('content')
<div class="container-fluid col-lg-11 mx-auto">

  {{-- HEADER --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="fw-bold">
      Rent Summary • {{ $property->property_name }}
    </h3>

    {{-- MONTH SELECTOR --}}
    <form method="GET">
      <input type="month"
             name="month"
             value="{{ $month }}"
             class="form-control"
             onchange="this.form.submit()">
    </form>
  </div>

  {{-- SUMMARY CARDS --}}
<div class="row g-3 mb-3">
  <div class="col">
    <div class="apple-card summary-card">
      <span>Lettable Space</span>
      <strong>K{{ number_format($totals['lettable']) }}</strong>
    </div>
  </div>

  <div class="col">
    <div class="apple-card summary-card">
      <span>Current Month</span>
      <strong>{{ \Carbon\Carbon::parse($month)->format('M Y') }}</strong>
    </div>
  </div>

  <div class="col">
    <div class="apple-card summary-card">
      <span>Rent</span>
      <strong>K{{ number_format($totals['rent']) }}</strong>
    </div>
  </div>

  <div class="col">
    <div class="apple-card summary-card summary-success">
      <span>Cash Paid</span>
      <strong>K{{ number_format($totals['paid']) }}</strong>
    </div>
  </div>

  <div class="col">
    <div class="apple-card summary-card summary-danger">
      <span>Overdue</span>
      <strong>K{{ number_format($totals['overdue']) }}</strong>
    </div>
  </div>
</div>


  {{-- TABLE --}}
  <div class="apple-card">
    <table class="table table-bordered table-sm align-middle">
      <thead class="table-light text-center">
        <tr>
          <th>Room</th>
          <th>Tenant</th>
          <th>Lease</th>
          <th>Entry Date</th>
          <th>Rent</th>
          <th>Cash Paid</th>
          <th>Overdue</th>
          <th>Balance</th>
        </tr>
      </thead>

      <tbody>
        @foreach($rows as $row)
        <tr>
          <td>{{ $row['room'] }}</td>

          <td>{{ $row['tenant'] }}</td>

          <td class="text-center">
            <span class="badge {{ $row['lease']=='Yes' ? 'bg-success' : 'bg-secondary' }}">
              {{ $row['lease'] }}
            </span>
          </td>

          <td>
            {{ $row['entry_date']
              ? \Carbon\Carbon::parse($row['entry_date'])->format('d-m-Y')
              : '—' }}
          </td>

          <td class="text-end">
            K{{ number_format($row['rent']) }}
          </td>

          <td class="text-end text-success">
            K{{ number_format($row['paid']) }}
          </td>

          <td class="text-end text-danger">
            K{{ number_format($row['overdue']) }}
          </td>

          <td class="text-end fw-bold">
            K{{ number_format($row['balance']) }}
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
@endsection
