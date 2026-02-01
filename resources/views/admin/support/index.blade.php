@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-semibold mb-0">Help & Support</h4>
            <small class="text-muted">Manage FAQs and guides</small>
        </div>

        <a href="{{ route('admin.support.create') }}"
           class="btn btn-success rounded-pill px-4">
            <i class="fas fa-plus me-1"></i> New Article
        </a>
    </div>

    <div class="card border-0 rounded-4 shadow-sm">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="bg-light small text-uppercase text-muted">
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($articles as $article)
                    <tr>
                        <td>
                            <strong>{{ $article->title }}</strong>
                            <div class="small text-muted">{{ $article->slug }}</div>
                        </td>

                        <td>
                            <span class="badge bg-secondary">
                                {{ strtoupper(str_replace('_',' ', $article->type)) }}
                            </span>
                        </td>

                        <td>
                            @if($article->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </td>

                        <td class="text-end">
                            <a href="{{ route('admin.support.edit', $article) }}"
                               class="btn btn-light btn-sm rounded-circle">
                                <i class="fas fa-pen"></i>
                            </a>

                            <form method="POST"
                                  action="{{ route('admin.support.destroy', $article) }}"
                                  class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-light btn-sm rounded-circle"
                                        onclick="return confirm('Delete this article?')">
                                    <i class="fas fa-trash text-danger"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>

</div>
@endsection
