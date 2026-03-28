<!DOCTYPE html>
<html>
<head>
    <title>Wirepick Payment Result</title>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", Inter, sans-serif;
            background: #f2f2f7;
            margin: 0;
            padding: 40px;
        }

        .result-container {
            max-width: 480px;
            margin: auto;
            background: #ffffff;
            padding: 28px;
            border-radius: 24px;
            box-shadow: 0 12px 35px rgba(0,0,0,0.08);
            text-align: center;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo {
            width: 100px;
            margin-bottom: 8px;
        }

        .summary-box {
            margin-top: 10px;
            background: #f7f7fa;
            border-radius: 18px;
            padding: 18px;
            text-align: left;
            font-size: 15px;
        }

        .summary-box strong {
            font-weight: 700;
        }

        .toggle-btn {
            margin-top: 18px;
            padding: 14px;
            width: 100%;
            border-radius: 16px;
            border: none;
            background: linear-gradient(135deg, #ff8c00, #042e72);
            color: white;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.2s ease;
        }

        .toggle-btn:hover {
            opacity: 0.92;
        }

        #detailsArea {
            display: none;
            margin-top: 25px;
            text-align: left;
        }

        h3 {
            margin-bottom: 8px;
            margin-top: 20px;
            font-size: 15px;
            font-weight: 600;
        }

        pre {
            background: #f2f3f5;
            border-radius: 14px;
            padding: 14px;
            font-size: 13px;
            white-space: pre-wrap;
            border: 1px solid #e2e4e7;
        }

        a.back-link {
            display: inline-block;
            margin-top: 30px;
            color: #007aff;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="result-container">

    <img src="/wirelogo.png" class="logo" alt="Wirepick Logo">

    <h2 style="font-weight: 700; margin-bottom: 20px;">Payment Status</h2>

    <!-- Quick Summary -->
    <div class="summary-box">
        <div><strong>Code:</strong> {{ $response['code'] ?? 'N/A' }}</div>
        <div><strong>Status:</strong> {{ $response['status'] ?? 'N/A' }}</div>
        <div><strong>Message:</strong> {{ $response['message'] ?? 'N/A' }}</div>
    </div>

    <!-- Expand Button -->
    <button class="toggle-btn" id="btnMore">Check More</button>

    <!-- Hidden Detailed Area -->
    <div id="detailsArea">
        <h3>Request Sent</h3>
        <pre>{{ json_encode($payload, JSON_PRETTY_PRINT) }}</pre>

        <h3>Response Received</h3>
        <pre>{{ json_encode($response, JSON_PRETTY_PRINT) }}</pre>

        <a href="{{ route('wp.form') }}" class="back-link">← Back to Payment Form</a>
    </div>

</div>

<script>
    document.getElementById("btnMore").addEventListener("click", function () {
        const area = document.getElementById("detailsArea");

        if (area.style.display === "none") {
            area.style.display = "block";
            this.innerText = "Hide Details";
        } else {
            area.style.display = "none";
            this.innerText = "Check More";
        }
    });
</script>

</body>
</html>
