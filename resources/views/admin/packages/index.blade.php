@extends('layouts.app')

@section('content')
    @include('admin.packages.style')
<div class="container-fluid">

  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold">Packages</h4>

    <a href="{{ route('admin.packages.create') }}"
       class="btn btn-green">
        + New Package
    </a>
  </div>

  <div class="apple-card p-4">
    <table class="table align-middle">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Price (ZMW)</th>
          <th>Institutions</th>
          <th>Employees</th>
          <th>Status</th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        @foreach($packages as $package)
        <tr>
          <td class="fw-semibold">{{ $package->name }}</td>
          <td>K{{ number_format($package->price) }}</td>
          <td>{{ $package->max_institutions ?? '∞' }}</td>
          <td>{{ $package->max_employees ?? '∞' }}</td>
          <td>
            @if($package->is_active)
              <span class="badge badge-green">Active</span>
            @else
              <span class="badge bg-secondary">Disabled</span>
            @endif
          </td>
          <td class="text-end">
            <a href="{{ route('admin.packages.edit', $package) }}"
               class="btn btn-sm btn-outline-success rounded-pill">
                Edit
            </a>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

</div>
@endsection
