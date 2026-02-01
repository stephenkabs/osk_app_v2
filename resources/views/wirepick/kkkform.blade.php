<!DOCTYPE html>
<html>
<head>
    <title>Wirepick Payment</title>

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Display", "Inter", sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 380px;
            margin: 70px auto;
            background: #ffffff;
            padding: 28px;
            border-radius: 22px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.10);
            animation: fadeIn 0.5s ease;
        }

        h2 {
            text-align: center;
            font-weight: 700;
            color: #111;
            margin-bottom: 20px;
            letter-spacing: -0.3px;
            font-size: 22px;
        }

        label {
            font-weight: 600;
            color: #333;
            font-size: 13px;
            margin-bottom: 5px;
            display: block;
        }

        input, select {
            width: 100%;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid #d0d0d0;
            font-size: 14px;
            background: #fafafa;
            transition: 0.15s ease-in-out;
        }

        input:focus, select:focus {
            border-color: #007aff;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(0,122,255,0.18);
            outline: none;
        }

        #cardFields {
            display: none;
            margin-top: 12px;
            padding: 15px;
            border-radius: 16px;
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(8px);
            border: 1px solid #eee;
            animation: fadeIn 0.3s ease;
        }

        button {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, #ff8c00, #042e72);
            color: white;
            font-size: 15px;
            font-weight: 600;
            border: none;
            border-radius: 14px;
            margin-top: 10px;
            box-shadow: 0 6px 18px rgba(0,122,255,0.25);
            cursor: pointer;
            transition: 0.2s ease;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,122,255,0.30);
        }

        button:active {
            transform: translateY(0);
        }

        .footer-note {
            text-align: center;
            font-size: 11px;
            color: #666;
            margin-top: 18px;
            line-height: 1.5;
        }

        .footer-note strong {
            color: #000;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
            .wp-header {
        text-align: center;
        padding-bottom: 10px;
    }

    .wp-logo {
        width: 150px;
        height: auto;
        margin-bottom: 8px;
        /* filter: drop-shadow(0px 2px 4px rgba(0,0,0,0.1)); */
    }

    .wp-header h2 {
        font-weight: 700;
        font-size: 20px;
        letter-spacing: -0.3px;
        color: #1a1a1a;
        margin: 0;
    }

       #payBtn {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #payBtn .spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(255,255,255,0.4);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.75s linear infinite;
        margin-left: 8px;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    #payBtn.loading {
        opacity: 0.85;
        cursor: not-allowed;
    }

    #payBtn.loading .spinner {
        display: inline-block;
    }

    #payBtn.loading .btn-text {
        opacity: 0.75;
    }
    </style>
</head>

<body>

<div class="container">

<div class="wp-header">
    <img src="/wirelogo.png" class="wp-logo" alt="Wirepick Logo">
    {{-- <h2>Wirepick Payment</h2> --}}
</div>


<form method="POST" action="{{ route('wp.process') }}" class="wp-form">
    @csrf

    <div class="form-group">
        <label>MSISDN</label>
        <input type="text" name="msisdn" placeholder="097xxxxxxx" required>
    </div>

    <div class="form-group">
        <label>Amount</label>
        <input type="number" name="amount" min="1" placeholder="1" required>
    </div>

    <div class="form-group">
        <label>Payment Method</label>
        <select name="method" id="methodSelect" required>
            <option value="airtel">Airtel Money</option>
            <option value="mtn">MTN Money</option>
            <option value="card">Card</option>
        </select>
    </div>

    <!-- Card fields -->
    <div id="cardFields" class="card-area">
        <div class="form-group">
            <label>Card Number (PAN)</label>
            <input type="text" name="pan" placeholder="2303 xxxx xxxx xxxx">
        </div>

        <div class="form-group">
            <label>Expiry Date</label>
            <input type="month" name="expiry">
        </div>

        <div class="form-group">
            <label>CVV</label>
            <input type="text" name="cvv" maxlength="4" placeholder="123">
        </div>

        <div class="form-group">
            <label>Cardholder Name</label>
            <input type="text" name="cardholderName" placeholder="John Doe">
        </div>
    </div>

<button type="submit" id="payBtn">
    <span class="btn-text">Pay Now</span>
    <span class="spinner"></span>
</button>

</form>
<style>
    .wp-form .form-group {
        margin-bottom: 15px;
    }

    .wp-form label {
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 6px;
        display: block;
        color: #333;
    }

    .wp-form input,
    .wp-form select {
        width: 100%;
        padding: 11px 14px;
        border-radius: 12px;
        border: 1px solid #d0d0d0;
        background: #fafafa;
        font-size: 14px;
        transition: 0.2s ease;
        box-sizing: border-box;
    }

    .card-area {
        display: none;
        background: rgba(255,255,255,0.65);
        padding: 15px;
        border-radius: 16px;
        border: 1px solid #eee;
        margin-top: 8px;
    }
</style>


    <div class="footer-note">
        🔒 Secure Transaction — Your details are encrypted using <strong>RSA-2048</strong>
        Wirepick ensures PCI-DSS compliant processing.
    </div>

</div>

<script>
document.getElementById('methodSelect').addEventListener('change', function () {
    let show = this.value === 'card';
    document.getElementById('cardFields').style.display = show ? 'block' : 'none';
});
</script>
<script>
document.querySelector("form").addEventListener("submit", function () {
    let btn = document.getElementById("payBtn");
    btn.classList.add("loading");
    btn.disabled = true;
    btn.querySelector(".btn-text").innerText = "Processing...";
});
</script>

</body>
</html>
