@extends('layouts.app')

@section('content')
    @include('admin.packages.style')
<div class="container-fluid">

<h4 class="fw-bold mb-4">Create Package</h4>

<div class="apple-card p-4 col-lg-6">

<form method="POST" action="{{ route('admin.packages.store') }}">
@csrf

<div class="mb-3">
  <label>Name</label>
  <input name="name" class="form-control apple-input" required>
</div>

<div class="mb-3">
  <label>Price (ZMW)</label>
  <input name="price" type="number" class="form-control apple-input" required>
</div>

<div class="row">
  <div class="col">
    <label>Max Institutions</label>
    <input name="max_institutions" type="number"
           class="form-control apple-input">
    <small class="text-muted">Leave empty = unlimited</small>
  </div>

  <div class="col">
    <label>Max Employees</label>
    <input name="max_employees" type="number"
           class="form-control apple-input">
  </div>
</div>

<div class="mt-3">
  <label>Duration (days)</label>
  <input name="duration_days" type="number"
         class="form-control apple-input">
</div>

<div class="form-check mt-3">
  <input class="form-check-input" type="checkbox"
         name="is_active" value="1" checked>
  <label class="form-check-label">Active</label>
</div>

<button class="btn btn-green mt-4 w-100">
  Save Package
</button>

</form>

</div>
</div>
@endsection
