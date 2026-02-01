@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px">

    <h4 class="fw-semibold mb-4">Edit Help Article</h4>

    <form method="POST"
          action="{{ route('admin.support.update', $article) }}">
        @csrf
        @method('PUT')

        <div class="card border-0 rounded-4 shadow-sm p-4">

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title"
                       value="{{ $article->title }}"
                       class="form-control rounded-pill"
                       required>
            </div>

            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type"
                        class="form-select rounded-pill">
                    <option value="faq" @selected($article->type === 'faq')>FAQ</option>
                    <option value="how_it_works" @selected($article->type === 'how_it_works')>How It Works</option>
                    <option value="guide" @selected($article->type === 'guide')>Guide</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content"
                          rows="8"
                          class="form-control rounded-4"
                          required>{!! $article->content !!}</textarea>
            </div>

            <div class="form-check form-switch mb-4">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       value="1"
                       @checked($article->is_active)>
                <label class="form-check-label">
                    Active
                </label>
            </div>

            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.support.index') }}"
                   class="btn btn-light rounded-pill">
                    Cancel
                </a>

                <button class="btn btn-success rounded-pill px-4">
                    Update Article
                </button>
            </div>

        </div>
    </form>

</div>
@endsection
