@extends('layouts.app')

@section('content')
<div class="container-fluid" style="max-width: 900px">

    <h4 class="fw-semibold mb-4">Create Privacy Policy</h4>

    <form method="POST" action="{{ route('admin.privacy.store') }}">
        @csrf

        <div class="card border-0 rounded-4 shadow-sm p-4">

            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label fw-semibold">Title</label>
                <input name="title"
                       class="form-control rounded-pill"
                       value="Privacy Policy"
                       required>
            </div>

            {{-- CONTENT --}}
            <div class="mb-4">
                <label class="form-label fw-semibold">Content</label>
     <textarea name="content"
          id="summernote"
          class="form-control"
          required>
@include('admin.privacy.template')
</textarea>

            </div>

            {{-- ACTIVE --}}
            <div class="form-check form-switch mb-4">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       value="1"
                       checked>
                <label class="form-check-label">
                    Set as active policy
                </label>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.privacy.index') }}"
                   class="btn btn-light rounded-pill">
                    Cancel
                </a>

                <button class="btn btn-dark rounded-pill px-4">
                    Save Policy
                </button>
            </div>

        </div>
    </form>

</div>
@endsection

{{-- ================= STYLES ================= --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css"
      rel="stylesheet">

<style>
/* Apple-like editor feel */
.note-editor {
    border-radius: 16px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
}

.note-toolbar {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb;
}

.note-editable {
    min-height: 320px;
    font-size: 14px;
    line-height: 1.7;
    padding: 20px;
}
</style>
@endpush

{{-- ================= SCRIPTS ================= --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#summernote').summernote({
        height: 320,
        placeholder: 'Write your privacy policy here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>
@endpush
