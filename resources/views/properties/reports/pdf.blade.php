<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Property Report</title>

    <style>
        @page { margin: 26px 30px; }

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color:#111;
        }

        h1{
            font-size:20px;
            margin:0;
        }

        h3{
            margin:0 0 8px;
            font-size:14px;
        }

        .muted{ color:#6b7280; }
        .small{ font-size:11px; }

        /* Header */
        .header{
            display:flex;
            align-items:center;
            justify-content:space-between;
            margin-bottom:20px;
        }

        .logo{
            height:48px;
        }

        .header-right{
            text-align:right;
        }

        /* Cards */
        .card{
            border:1px solid #e6e8ef;
            border-radius:10px;
            padding:12px;
            margin-bottom:14px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th, td{
            padding:8px;
            border-bottom:1px solid #e5e7eb;
            text-align:left;
        }

        th{
            background:#f9fafb;
            font-weight:700;
        }

        .right{ text-align:right; }

        .total{
            font-weight:800;
            font-size:14px;
        }

        .highlight{
            background:#f3f4f6;
            font-weight:700;
        }

        .divider{
            height:1px;
            background:#e5e7eb;
            margin:16px 0;
        }
    </style>
</head>

<body>

{{-- ================= HEADER ================= --}}
<div class="header">
    <div>
        <img src="{{ public_path('logo.webp') }}" class="logo" alt="Logo">
    </div>

    <div class="header-right">
        <h1>{{ $property->property_name }}</h1>
        <div class="muted small">
            Property Performance Report<br>
            Year: <strong>{{ $year }}</strong><br>
            Generated on {{ now()->format('d M Y H:i') }}
        </div>
    </div>
</div>

{{-- ================= YEAR SUMMARY ================= --}}
<div class="card">
    <h3>Year Summary — {{ $year }}</h3>

    <table>
        <tr>
            <th>Total Rent Collected</th>
            <td class="right">K{{ number_format($totalPayments,2) }}</td>
        </tr>
        <tr>
            <th>Total Expenses</th>
            <td class="right">K{{ number_format($totalExpenses,2) }}</td>
        </tr>
        <tr class="highlight">
            <th>Net Income</th>
            <td class="right total">
                K{{ number_format($netIncome,2) }}
            </td>
        </tr>
    </table>
</div>

{{-- ================= OCCUPANCY ================= --}}
<div class="card">
    <h3>Occupancy Overview</h3>

    <table>
        <tr>
            <th>Total Units</th>
            <td class="right">{{ $totalUnits }}</td>
        </tr>
        <tr>
            <th>Occupied Units</th>
            <td class="right">{{ $occupiedUnits }}</td>
        </tr>
        <tr>
            <th>Vacant Units</th>
            <td class="right">{{ $vacantUnits }}</td>
        </tr>
    </table>
</div>

{{-- ================= TENANCY ================= --}}
<div class="card">
    <h3>Tenancy Information</h3>

    <table>
        <tr>
            <th>Active Tenants</th>
            <td class="right">{{ $totalTenants }}</td>
        </tr>
        <tr>
            <th>Total Lease Agreements</th>
            <td class="right">{{ $totalLeases }}</td>
        </tr>
    </table>
</div>

<div class="divider"></div>

<div class="muted small">
    This report is system-generated and valid without signature.
</div>

</body>
</html>
