<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Wirepick Report</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #222;
        }

        h1 {
            text-align: center;
            font-size: 20px;
            margin-bottom: 10px;
            color: #0d2543;
        }

        .subtitle {
            text-align: center;
            font-size: 12px;
            margin-bottom: 15px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #0d2543;
            color: white;
            padding: 6px;
            font-size: 12px;
            text-align: left;
        }

        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
        }

        .badge {
            padding: 3px 6px;
            border-radius: 4px;
            font-size: 11px;
            color: #fff;
        }

        .success { background: #28a745; }
        .failed { background: #dc3545; }
        .pending { background: #ffc107; color: #000; }
        .accepted { background: #0071e3; }

    </style>
</head>
<body>

    <h1>Wirepick Transaction Report</h1>
    <p class="subtitle">Generated: {{ now()->format('d M Y, H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Timestamp</th>
                <th>MSISDN</th>
                <th>Amount</th>
                <th>Reference</th>
                <th>Gateway</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @forelse($rows as $r)
                <tr>
                    <td>{{ $r['timestamp'] }}</td>
                    <td>{{ $r['msisdn'] }}</td>
                    <td>ZMW {{ number_format($r['amount'],2) }}</td>
                    <td>{{ $r['reference'] }}</td>
                    <td>{{ $r['gateway'] }}</td>
                    <td>
                        @php
                            $s = strtoupper($r['status']);
                            $class = match($s) {
                                'ACCEPTED' => 'accepted',
                                'SUCCESS','SUCCESSFUL' => 'success',
                                'FAILED','ERROR' => 'failed',
                                'PENDING' => 'pending',
                                default => 'pending'
                            };
                        @endphp

                        <span class="badge {{ $class }}">{{ $s }}</span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" align="center">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
