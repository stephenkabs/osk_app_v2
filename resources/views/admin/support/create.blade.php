@extends('layouts.app')

@section('content')
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .note-editor.note-frame {
        border-radius: 18px;
        border: 1px solid #e5e7eb;
    }
</style>
@endpush

<div class="container-fluid" style="max-width: 900px">

    <h4 class="fw-semibold mb-4">Create Help Article</h4>

    <form method="POST" action="{{ route('admin.support.store') }}">
        @csrf

        <div class="card border-0 rounded-4 shadow-sm p-4">

            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input name="title"
                       class="form-control rounded-pill"
                       placeholder="e.g. How Payroll Works"
                       required>
            </div>

            {{-- TYPE --}}
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type"
                        class="form-select rounded-pill">
                    <option value="faq">FAQ</option>
                    <option value="how_it_works">How It Works</option>
                    <option value="guide">Guide</option>
                </select>
            </div>

            {{-- CONTENT --}}
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea id="summernote"
                          name="content"
                          required>
<h4>Overview</h4>
<p>
This article explains how this feature works in the HRM system.
Follow the steps below to understand the process clearly.
</p>

<h4>Steps</h4>
<ol>
    <li>Navigate to the relevant module from the dashboard.</li>
    <li>Select the employee or record you want to manage.</li>
    <li>Fill in the required information.</li>
    <li>Save or submit to complete the action.</li>
</ol>

<h4>Important Notes</h4>
<ul>
    <li>Only authorized users can perform this action.</li>
    <li>All changes are logged for audit purposes.</li>
</ul>

<h4>Need Help?</h4>
<p>
If you experience any issues, contact your system administrator
or reach out to support.
</p>
                </textarea>
            </div>

            {{-- STATUS --}}
            <div class="form-check form-switch mb-4">
                <input class="form-check-input"
                       type="checkbox"
                       name="is_active"
                       value="1"
                       checked>
                <label class="form-check-label">
                    Publish immediately
                </label>
            </div>

            {{-- ACTIONS --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('admin.support.index') }}"
                   class="btn btn-light rounded-pill">
                    Cancel
                </a>

                <button class="btn btn-success rounded-pill px-4">
                    Save Article
                </button>
            </div>

        </div>
    </form>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>

<script>
$(document).ready(function () {
    $('#summernote').summernote({
        height: 320,
        placeholder: 'Write help content here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview']]
        ]
    });
});
</script>
@endpush

@endsection
