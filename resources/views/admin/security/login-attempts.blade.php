@extends('layouts.app')

@section('content')

@push('styles')
<style>
/* ===============================
   APPLE CARD BASE
================================ */
.apple-card {
    background: rgba(255,255,255,0.96);
    backdrop-filter: blur(14px);
    border-radius: 22px;
    border: 1px solid rgba(229,231,235,.9);
    box-shadow: 0 30px 70px rgba(0,0,0,.08);
}

/* ===============================
   CARD HEADERS
================================ */
.card-header-clean {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 12px;
    margin-bottom: 20px;
}

/* ===============================
   GRAPH CARD
================================ */
.graph-card {
    background: linear-gradient(
        180deg,
        #fff 0%,
        #fafafa 100%
    );
}

.graph-subtle {
    background: rgba(239,68,68,.08);
    border-radius: 14px;
    padding: 14px 16px;
    font-size: 13px;
    color: #7f1d1d;
    margin-bottom: 12px;
}

/* ===============================
   TABLE POLISH
================================ */
.table thead th {
    font-size: 11px;
    letter-spacing: .06em;
    text-transform: uppercase;
    border-bottom: 1px solid #e5e7eb;
}

.table tbody tr:hover {
    background: rgba(0,0,0,.025);
}

.table td {
    vertical-align: middle;
}

/* ===============================
   BADGES
================================ */
.badge {
    font-weight: 600;
    padding: 6px 10px;
}

.badge.bg-danger {
    background: linear-gradient(135deg, #ef4444, #b91c1c);
}

.badge.bg-success {
    background: linear-gradient(135deg, #22c55e, #15803d);
}
</style>
@endpush

<div class="container-fluid">

    {{-- ================= GRAPH CARD ================= --}}
    <div class="apple-card graph-card p-4 mb-4">

        <div class="card-header-clean">
            <h5 class="fw-bold mb-1">
                <i class="fas fa-chart-line text-danger me-2"></i>
                Failed Login Attempts
            </h5>
            <p class="text-muted small mb-0">
                Security activity over the last 7 days
            </p>
        </div>

        <div class="graph-subtle">
            <i class="fas fa-info-circle me-1"></i>
            Spikes may indicate brute-force or credential stuffing attempts
        </div>

        <canvas id="loginAttemptsChart" height="90"></canvas>
    </div>

    {{-- ================= TABLE CARD ================= --}}
    <div class="apple-card p-4">

        <div class="card-header-clean">
            <h5 class="fw-bold mb-1">
                <i class="fas fa-shield-alt text-danger me-2"></i>
                Login Security Monitor
            </h5>
            <p class="text-muted small mb-0">
                Failed login attempts grouped by email and IP
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="text-muted">
                    <tr>
                        <th>Email</th>
                        <th>IP Address</th>
                        <th>Attempts</th>
                        <th>Last Attempt</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td class="fw-semibold">
                            {{ $attempt->email ?? 'Unknown' }}
                        </td>

                        <td>
                            <span class="badge bg-light text-dark">
                                {{ $attempt->ip_address }}
                            </span>
                        </td>

                        <td class="fw-bold">
                            {{ $attempt->attempts }}
                        </td>

                        <td class="small text-muted">
                            {{ \Carbon\Carbon::parse($attempt->last_attempt)->diffForHumans() }}
                        </td>

                        <td>
                            @if($attempt->attempts >= 5)
                                <span class="badge bg-danger rounded-pill">
                                    🚩 Suspicious
                                </span>
                            @else
                                <span class="badge bg-success rounded-pill">
                                    Normal
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No failed login attempts recorded
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
new Chart(document.getElementById('loginAttemptsChart'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData->pluck('date')) !!},
        datasets: [{
            data: {!! json_encode($chartData->pluck('total')) !!},
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239,68,68,.18)',
            fill: true,
            tension: .35,
            pointRadius: 4,
            pointBackgroundColor: '#ef4444'
        }]
    },
    options: {
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});
</script>
@endpush

@endsection
