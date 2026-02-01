@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-1">Privacy Policy</h4>
            <small class="text-muted">Manage privacy policy versions</small>
        </div>

        <a href="{{ route('admin.privacy.create') }}"
           class="btn btn-dark rounded-pill px-4">
            <i class="fas fa-plus me-1"></i> New Policy
        </a>
    </div>

    <div class="card border-0 rounded-4 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light text-uppercase small text-muted">
                    <tr>
                        <th class="ps-4">Title</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($policies as $policy)
                    <tr>
                        <td class="ps-4 fw-semibold">
                            {{ $policy->title }}
                        </td>

                        <td>
                            @if($policy->is_active)
                                <span class="badge bg-success-subtle text-success rounded-pill">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-muted rounded-pill">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <td class="small text-muted">
                            {{ $policy->updated_at->diffForHumans() }}
                        </td>

                        <td class="text-end pe-4">
                            <a href="{{ route('admin.privacy.edit', $policy) }}"
                               class="btn btn-light btn-sm rounded-circle">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.privacy.destroy', $policy) }}"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button onclick="return confirm('Delete this policy?')"
                                        class="btn btn-light btn-sm rounded-circle">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="text-center py-5 text-muted">
                            No privacy policy created yet
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $policies->links() }}
    </div>

</div>
@endsection
