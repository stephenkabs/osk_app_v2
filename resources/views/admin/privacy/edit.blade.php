@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px">

    <h4 class="fw-semibold mb-4">Edit Privacy Policy</h4>

    <form method="POST"
          action="{{ route('admin.privacy.update', $privacyPolicy) }}">
        @csrf
        @method('PUT')

        <div class="card border-0 rounded-4 shadow-sm p-4">

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title"
                       class="form-control rounded-pill"
                       value="{{ $privacyPolicy->title }}"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content"
                          rows="10"
                          class="form-control rounded-4"
                          required>{{ $privacyPolicy->content }}</textarea>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       value="1"
                       {{ $privacyPolicy->is_active ? 'checked' : '' }}>
                <label class="form-check-label">
                    Set as active policy
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.privacy.index') }}"
                   class="btn btn-light rounded-pill">
                    Cancel
                </a>

                <button class="btn btn-dark rounded-pill px-4">
                    Update Policy
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
