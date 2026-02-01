{{-- RENT SUMMARY PARTIAL --}}

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
          <th>Lease</th>
          <th>Entry</th>
          <th class="text-end">Rent</th>
          <th class="text-end">Paid</th>
          <th class="text-end">Overdue</th>
          <th class="text-end">Balance</th>
        </tr>
      </thead>
      <tbody>
        @foreach($rows as $row)
        <tr>
          <td>{{ $row['room'] }}</td>
          <td>{{ $row['tenant'] }}</td>
          <td>
            <span class="badge {{ $row['lease']=='Yes' ? 'bg-success' : 'bg-secondary' }}">
              {{ $row['lease'] }}
            </span>
          </td>
          <td>{{ $row['entry_date'] ? \Carbon\Carbon::parse($row['entry_date'])->format('d M Y') : '—' }}</td>
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

@endif
