@extends('layouts.app')

@section('content')

<style>
    body {
        background: #f4f6fa;
        font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", Arial;
    }

    .heading {
        font-size: 26px;
        font-weight: 800;
        color: #0d2543;
        margin-bottom: 20px;
    }

    .filters {
        display: flex;
        gap: 12px;
        margin-bottom: 25px;
    }

    .filter-btn {
        padding: 10px 18px;
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #d1d5db;
        cursor: pointer;
        font-weight: 600;
        transition: .2s;
        text-decoration: none;
        color: #0d2543;
    }

    .filter-btn:hover {
        background: #eef2f7;
    }

    .active {
        background: #122b50 !important;
        color: white !important;
        border-color: #122b50 !important;
    }

    .card-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }

    .card-report {
        background: #ffffff;
        padding: 22px;
        border-radius: 18px;
        box-shadow: 0 12px 22px rgba(0,0,0,.08);
        transition: .2s;
    }

    .card-report:hover {
        transform: translateY(-4px);
    }

    .label {
        color: #6b7280;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .value {
        font-size: 26px;
        font-weight: 800;
        color: #0d2543;
        margin-top: 6px;
    }
</style>

<div class="container-fluid">

    <div class="heading">Payment Reports</div>

    {{-- FILTERS --}}
    <div class="filters">
        @php $f = $data['filter']; @endphp

        <a href="?filter=today" class="filter-btn {{ $f=='today' ? 'active':'' }}">Today</a>
        <a href="?filter=week" class="filter-btn {{ $f=='week' ? 'active':'' }}">This Week</a>
        <a href="?filter=month" class="filter-btn {{ $f=='month' ? 'active':'' }}">This Month</a>
        <a href="?filter=year" class="filter-btn {{ $f=='year' ? 'active':'' }}">This Year</a>
    </div>
    <div class="d-flex justify-content-end mb-3">
    <a href="{{ route('payments.reports.csv') }}" class="btn btn-sm btn-outline-primary me-2">
        <i class="fas fa-file-csv"></i> Export CSV
    </a>

    <a href="{{ route('payments.reports.pdf') }}" class="btn btn-sm btn-outline-danger">
        <i class="fas fa-file-pdf"></i> Export PDF
    </a>
</div>


    {{-- STATISTICS --}}
    <div class="card-container">

        <div class="card-report">
            <div class="label">Total Transactions</div>
            <div class="value">{{ $data['total'] }}</div>
        </div>

        <div class="card-report">
            <div class="label">Successful</div>
            <div class="value" style="color:#16a34a">{{ $data['successful'] }}</div>
        </div>

        <div class="card-report">
            <div class="label">Pending</div>
            <div class="value" style="color:#2563eb">{{ $data['pending'] }}</div>
        </div>

        <div class="card-report">
            <div class="label">Failed</div>
            <div class="value" style="color:#dc2626">{{ $data['failed'] }}</div>
        </div>

        <div class="card-report">
            <div class="label">System Errors</div>
            <div class="value" style="color:#c2410c">{{ $data['errors'] }}</div>
        </div>

        <div class="card-report">
            <div class="label">Total Amount</div>
            <div class="value">ZMW {{ number_format($data['amount'], 2) }}</div>
        </div>

    </div>
    <!-- ABOUT PAYMENT REPORTS (Apple Style) -->
<div class="row mt-4">
    <div class="col-lg-8">

        <div class="apple-card" style="
            background:#ffffff;
            border-radius:18px;
            padding:22px 24px;
            margin-top:10px;
            box-shadow:0 12px 22px rgba(0,0,0,.07);
        ">

            <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                <h2 style="font-weight:800; font-size:20px; margin:0; color:#0d2543;">
                    About Payment Reports
                </h2>

                <span style="
                    font-size:11px;
                    text-transform:uppercase;
                    color:#6b7280;
                    font-weight:600;">
                    OnPay • Analytics
                </span>
            </div>

            <p style="
                margin-top:14px;
                font-size:13px;
                line-height:1.55;
                color:#4b5563;
                font-weight:500;">
                Payment Reports give you a complete overview of all transactions processed through
                your OnPay merchant account. Use the filters above to analyze performance by
                <strong>day, week, month, or year</strong>.
            </p>

            <ul style="margin:0; padding-left:18px; color:#4b5563; font-size:13px;">
                <li>Track total payments, successes, pending and failed attempts.</li>
                <li>Identify system errors or integration issues quickly.</li>
                <li>Monitor revenue growth using total transaction amounts.</li>
                <li>Export your reports in <strong>CSV</strong> or <strong>PDF</strong> formats for accounting or audit purposes.</li>
            </ul>

            <p style="
                margin-top:14px;
                font-size:12px;
                color:#6b7280;
                font-weight:600;">
                These insights help you optimize merchant performance and improve customer
                payment completion rates.
            </p>

        </div>

    </div>
</div>


</div>

@endsection
