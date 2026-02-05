{{-- RENT SUMMARY PARTIAL --}}

<style>/* ===============================
   🍎 LEASE ACTION BUTTONS
================================ */

.lease-btn{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 10px;
  font-size:12px;
  font-weight:600;
  border-radius:10px;
  width:auto;
}

/* Copy link */
.lease-btn-copy{
  border:1px solid #e5e7eb;
  background:#f9fafb;
  color:#111827;
}

.lease-btn-copy:hover{
  background:#eef2ff;
  border-color:#c7d2fe;
  color:#1e40af;
}

/* Send email */
.lease-btn-send{
  border:1px solid #dbeafe;
  background:#eff6ff;
  color:#1d4ed8;
}

.lease-btn-send:hover{
  background:#dbeafe;
  color:#1e3a8a;
}

/* Disabled */
.lease-btn:disabled{
  opacity:.55;
  cursor:not-allowed;
}
</style>

@if(isset($property, $rows, $totals, $month))

<div class="apple-card mt-4">

  {{-- HEADER --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">
      Rent Summary • {{ $property->property_name }}
    </h4>

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
      <div class="summary-card">
        <span>Lettable</span>
        <strong>K{{ number_format($totals['lettable']) }}</strong>
      </div>
    </div>

    <div class="col">
      <div class="summary-card">
        <span>Rent</span>
        <strong>K{{ number_format($totals['rent']) }}</strong>
      </div>
    </div>

    <div class="col">
      <div class="summary-card summary-success">
        <span>Paid</span>
        <strong>K{{ number_format($totals['paid']) }}</strong>
      </div>
    </div>

    <div class="col">
      <div class="summary-card summary-danger">
        <span>Overdue</span>
        <strong>K{{ number_format($totals['overdue']) }}</strong>
      </div>
    </div>
  </div>

  {{-- TABLE --}}
  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>Room</th>
          <th>Tenant</th>
          <th>Lease Copy</th>
          <th>Status</th>
          <th>Entry</th>
          <th class="text-end">Rent</th>
          <th class="text-end">Paid</th>
          <th class="text-end">Overdue</th>
          <th class="text-end">Balance</th>
        </tr>
      </thead>

      <tbody>
        @foreach($rows as $row)

          @php
            /** @var \App\Models\PropertyLeaseAgreement|null $lease */
            $lease = $row['lease'] ?? null;
          @endphp

          <tr>
            <td>{{ $row['room'] ?? '—' }}</td>
<td>
  @if(!empty($row['tenant']) && !empty($lease))
    <a href="{{ route('property.users.show', [$property->slug, $lease->tenant->slug]) }}"
       class="fw-bold text-decoration-none text-dark tenant-link">
      {{ $row['tenant'] }}
    </a>
  @else
    —
  @endif
</td>


{{-- COPY / SEND LEASE --}}
{{-- <td>
  @if($lease)
    @php
      $signUrl = route(
        'property.agreements.public.create',
        $property->slug
      ).'?lease='.$lease->slug;
    @endphp


<button
  class="lease-btn lease-btn-copy copy-lease-btn"
  data-link="{{ $signUrl }}"
  @disabled($lease->status === 'active')
>
  <i class="fas fa-link"></i> Copy
</button>



    @if($lease->status === 'pending' && $lease->tenant?->email)
<button
  class="lease-btn lease-btn-send send-lease-btn"
  data-url="{{ route('property.leases.send-email', [$property->slug, $lease->id]) }}">
  <i class="fas fa-envelope"></i> Send
</button>

    @endif


    <div class="small mt-1 {{ $lease->status === 'active' ? 'text-success fw-bold' : 'text-muted' }}">
      {{ $lease->status === 'active' ? 'Signed' : 'Pending signature' }}
    </div>
  @else
    —
  @endif
</td> --}}


{{-- COPY / SEND LEASE --}}
<td>
  @if($lease)
    @php
      $signUrl = route(
        'property.agreements.public.create',
        $property->slug
      ).'?lease='.$lease->slug;
    @endphp

    {{-- COPY LINK (ONLY IF PENDING) --}}
    @if($lease->status === 'pending')
      <button
        class="lease-btn lease-btn-copy copy-lease-btn"
        data-link="{{ $signUrl }}">
        <i class="fas fa-link"></i> Copy
      </button>
    @endif

    {{-- SEND EMAIL (ONLY IF PENDING + HAS EMAIL) --}}
    @if($lease->status === 'pending' && $lease->tenant?->email)
      <button
        class="lease-btn lease-btn-send send-lease-btn"
        data-url="{{ route('property.leases.send-email', [$property->slug, $lease->id]) }}">
        <i class="fas fa-envelope"></i> Send
      </button>
    @endif

    {{-- STATUS TEXT --}}
    <div class="small mt-1 {{ $lease->status === 'active' ? 'text-success fw-bold' : 'text-muted' }}">
      {{ $lease->status === 'active' ? 'Signed' : 'Pending signature' }}
    </div>
  @else
    —
  @endif
</td>



            {{-- LEASE STATUS --}}
            <td>
              @if($lease)
                <span class="badge
                  {{ $lease->status === 'active' ? 'bg-success' :
                     ($lease->status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                  {{ ucfirst($lease->status) }}
                </span>
              @else
                <span class="badge bg-secondary">No Lease</span>
              @endif
            </td>

            <td>
              {{ $row['entry_date']
                ? \Carbon\Carbon::parse($row['entry_date'])->format('d M Y')
                : '—' }}
            </td>

            <td class="text-end">K{{ number_format($row['rent']) }}</td>
            <td class="text-end text-success">K{{ number_format($row['paid']) }}</td>
            <td class="text-end text-danger">K{{ number_format($row['overdue']) }}</td>
            <td class="text-end fw-bold">K{{ number_format($row['balance']) }}</td>
          </tr>

        @endforeach
      </tbody>
    </table>
  </div>

</div>

{{-- COPY LINK SCRIPT --}}
<script>
document.addEventListener('click', function (e) {
  const btn = e.target.closest('.copy-lease-btn');
  if (!btn) return;

  const link = btn.dataset.link;

  navigator.clipboard.writeText(link).then(() => {
    btn.innerHTML = '<i class="fas fa-check me-1"></i> Copied';
    btn.classList.remove('btn-outline-dark');
    btn.classList.add('btn-success');

    setTimeout(() => {
      btn.innerHTML = '<i class="fas fa-link me-1"></i> Copy Lease Link';
      btn.classList.remove('btn-success');
      btn.classList.add('btn-outline-dark');
    }, 1500);
  });
});
</script>
<script>
document.addEventListener('click', async function (e) {
  const btn = e.target.closest('.send-lease-btn');
  if (!btn) return;

  btn.disabled = true;
  btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';

  const res = await fetch(btn.dataset.url, {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': '{{ csrf_token() }}',
      'Accept': 'application/json'
    }
  });

  const data = await res.json();

  if (!res.ok) {
    alert(data.error || 'Failed to send lease');
    btn.disabled = false;
    btn.innerHTML = '<i class="fas fa-envelope me-1"></i> Send Lease';
    return;
  }

  btn.classList.remove('btn-outline-primary');
  btn.classList.add('btn-success');
  btn.innerHTML = '<i class="fas fa-check me-1"></i> Sent';
});
</script>

@endif
