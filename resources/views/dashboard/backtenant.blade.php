@extends('layouts.app')

@section('content')
<style>
:root{
  --ink:#0b0c0f; --muted:#6b7280; --card:#fff; --border:#e6e8ef;
  --ok:#065f46; --okbg:#ecfdf5;
  --warn:#92400e; --warnbg:#fffbeb;
}
.apple-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:18px;
  padding:20px;
  box-shadow:0 8px 22px rgba(0,0,0,.06);
}
.header{
  display:flex; justify-content:space-between; align-items:flex-end; gap:16px;
  margin-bottom:18px;
}
.title{
  font-size:22px; font-weight:800; letter-spacing:-.02em;
}
.sub{
  font-size:12px; color:var(--muted); font-weight:700; text-transform:uppercase;
}
.pill{
  display:inline-flex; align-items:center; gap:6px;
  padding:6px 12px; border-radius:999px;
  font-weight:800; font-size:12px; border:1px solid var(--border);
}
.pill.ok{ background:var(--okbg); color:var(--ok); border-color:#bbf7d0; }
.pill.warn{ background:var(--warnbg); color:var(--warn); border-color:#fde68a; }

.stat{
  border-radius:16px; border:1px solid var(--border);
  padding:14px; background:#fff;
}
.stat .k{ font-size:11px; color:var(--muted); font-weight:700; text-transform:uppercase; }
.stat .v{ font-size:18px; font-weight:800; }

.month{
  position:relative; border:1px solid var(--border);
  border-radius:14px; padding:12px; background:#fff;
  transition:.2s;
}
.month:hover{ transform:translateY(-2px); box-shadow:0 10px 18px rgba(0,0,0,.08); }
.month.paid{ background:#ecfdf5; border-color:#bbf7d0; }
.month.partial{ background:#fffbeb; border-color:#fde68a; }
.month.unpaid{ background:#fef2f2; border-color:#fecaca; }

.receipt{
  position:absolute; top:8px; right:8px;
  width:30px; height:30px; border-radius:10px;
  display:flex; align-items:center; justify-content:center;
  background:#fff; border:1px solid var(--border);
  box-shadow:0 4px 10px rgba(0,0,0,.12);
}
</style>

<div class="container-fluid">
  <div class="col-lg-10 mx-auto">

    {{-- HEADER --}}
    <div class="header">
      <div>
        <div class="title">My Home</div>
        <div class="sub">{{ $property->property_name }} • Tenant</div>
      </div>

      {{-- QBO STATUS (read-only) --}}
      @if($user->quickbooks_customer_id)
        <span class="pill ok">
          <i class="fas fa-check-circle"></i> QBO Synced
        </span>
      @else
        <span class="pill warn">
          <i class="fas fa-clock"></i> QBO Pending
        </span>
      @endif
    </div>

    {{-- TOP STATS --}}
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <div class="stat">
          <div class="k">Unit</div>
          <div class="v">
            @if($latestSignedLease->unit)
              <i class="fas fa-door-open me-1"></i> {{ $latestSignedLease->unit->code }}
            @else — @endif
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat">
          <div class="k">Lease Status</div>
          <div class="v">{{ ucfirst($latestSignedLease->status) }}</div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="stat">
          <div class="k">Monthly Rent</div>
          <div class="v">K{{ number_format($latestSignedLease->rent_amount,2) }}</div>
        </div>
      </div>
    </div>

    {{-- RENT CALENDAR --}}
    <div class="apple-card">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <strong>Rent Payments</strong>
        <small class="text-muted">{{ now()->year }}</small>
      </div>

      <div class="row g-2">
        @foreach($months as $m)
          @php
            $s = $rentSummary[$m['key']] ?? ['paid'=>0,'status'=>'unpaid'];
          @endphp

          <div class="col-md-3 col-6">
            <div class="month {{ $s['status'] }}">

              @if($s['paid'] > 0)
                <a href="{{ route('property.payments.receipt', [
                      $property->slug,
                      $latestSignedLease->id,
                      $m['key']
                ]) }}" class="receipt" title="Download receipt">
                  <i class="fas fa-file-pdf"></i>
                </a>
              @endif

              <div class="fw-bold">{{ $m['label'] }}</div>
              <small class="text-muted">
                {{ $s['paid'] > 0
                  ? 'Paid: K'.number_format($s['paid'],2)
                  : 'No payment' }}
              </small>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- SUPPORT --}}
    <div class="apple-card mt-3">
      <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
          <strong>Need help?</strong>
          <div class="text-muted small">Contact property management</div>
        </div>

        @if($user->whatsapp_phone)
          <a href="https://wa.me/{{ preg_replace('/\D/','',$user->whatsapp_phone) }}"
             target="_blank" class="pill">
            <i class="fab fa-whatsapp"></i> WhatsApp
          </a>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection
