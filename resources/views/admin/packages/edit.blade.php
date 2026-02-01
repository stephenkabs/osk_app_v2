@extends('layouts.app')

@section('content')
<div class="container-fluid">
    @include('admin.packages.style')

<h4 class="fw-bold mb-4">Edit Package</h4>

<div class="apple-card p-4 col-lg-6">

<form method="POST"
      action="{{ route('admin.packages.update', $package) }}">
@csrf
@method('PUT')

@include('admin.packages.form')

<button class="btn btn-green mt-4 w-100">
  Update Package
</button>

</form>

</div>
</div>
@endsection
