@extends('layouts.app')

@section('content')
@push('styles')
<style>
/* ===============================
   APPLE CARD BASE
================================ */
.apple-card {
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid rgba(229, 231, 235, 0.9);
    box-shadow:
        0 20px 40px rgba(0, 0, 0, 0.08),
        0 4px 12px rgba(0, 0, 0, 0.04);
}

/* ===============================
   ICON CIRCLE (TOP CARD)
================================ */
.apple-delete-icon {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 26px;
    margin: 0 auto;
}

/* ===============================
   TABLE STYLING
================================ */
.apple-card table {
    margin: 0;
}

.apple-card thead th {
    font-size: 13px;
    font-weight: 600;
    color: #6b7280;
    text-transform: uppercase;
    border-bottom: 1px solid #e5e7eb;
    padding: 14px 18px;
}

.apple-card tbody td {
    padding: 16px 18px;
    border-top: 1px solid #f1f5f9;
    font-size: 14px;
}

.apple-card tbody tr {
    transition: background 0.2s ease;
}

.apple-card tbody tr:hover {
    background: rgba(243, 244, 246, 0.6);
}

/* ===============================
   DOWNLOAD BUTTON
================================ */
.apple-card .btn-outline-dark {
    border-radius: 999px;
    font-size: 13px;
    padding: 6px 14px;
}

/* ===============================
   ALERTS
================================ */
.alert {
    border-radius: 14px;
    font-size: 13px;
}

/* ===============================
   PAGE SPACING
================================ */
.container-fluid {
    padding-top: 24px;
}

/* ===============================
   MOBILE TWEAKS
================================ */
@media (max-width: 768px) {
    .apple-card {
        border-radius: 18px;
    }

    .apple-card tbody td {
        font-size: 13px;
        padding: 14px;
    }
}
</style>
@endpush

<div class="row justify-content-center">
    <div class="col-lg-6">

        <div class="apple-card p-4 text-center">

            <div class="mb-3">
                <div class="apple-delete-icon" style="background: rgba(59,130,246,.1); color:#3b82f6;">
                    <i class="fas fa-database"></i>
                </div>
            </div>

            <h4 class="fw-bold mb-1">Database Backup</h4>
            <p class="text-muted small mb-4">
                Manually export and store the system database
                securely in DigitalOcean Spaces.
            </p>

            {{-- STATUS --}}
            @if(session('success'))
                <div class="alert alert-success rounded-3 small">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger rounded-3 small">
                    {{ session('error') }}
                </div>
            @endif

            {{-- ACTION --}}
            <form method="POST" action="{{ route('admin.backup.run') }}">
                @csrf

                <button type="submit"
                        class="btn btn-dark rounded-pill px-5">
                    <i class="fas fa-cloud-upload-alt me-1"></i>
                    Run Backup Now
                </button>
            </form>

            <p class="text-muted small mt-3 mb-0">
                Last backup is stored securely in DigitalOcean Spaces.
            </p>

        </div>

    </div>
</div>

{{-- ================= BACKUP LIST ================= --}}
<div class="row justify-content-center mt-4">
    <div class="col-lg-10">

        <div class="apple-card p-0 overflow-hidden">

            <div class="p-4 border-bottom">
                <h6 class="fw-bold mb-0">Available Backups</h6>
                <p class="text-muted small mb-0">
                    Stored securely in DigitalOcean Spaces
                </p>
            </div>

            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>File</th>
                            <th>Size</th>
                            <th>Created</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($backups as $backup)
                            <tr>
                                <td class="fw-medium">
                                    <i class="fas fa-file-archive text-muted me-2"></i>
                                    {{ $backup['name'] }}
                                </td>

                                <td class="text-muted small">
                                    {{ number_format($backup['size'] / 1024, 1) }} KB
                                </td>

                                <td class="text-muted small">
                                    {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->diffForHumans() }}
                                </td>

                                <td class="text-end">
                                    <a href="{{ $backup['url'] }}"
                                       target="_blank"
                                       class="btn btn-outline-dark btn-sm rounded-pill">
                                        <i class="fas fa-download me-1"></i>
                                        Download
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No backups available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

@endsection
