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

        .wp-header {
            text-align: center;
        }

        .wp-logo {
            width: 150px;
            margin-bottom: 15px;
        }

        label {
            font-weight: 600;
            color: #333;
            font-size: 13px;
            display: block;
            margin-bottom: 6px;
        }

        input, select {
            width: 100%;
            padding: 11px 14px;
            border-radius: 12px;
            border: 1px solid #d0d0d0;
            background: #fafafa;
            margin-bottom: 15px;
        }

        #cardFields {
            display: none;
            padding: 15px;
            border-radius: 16px;
            background: rgba(255,255,255,0.75);
            border: 1px solid #eee;
        }

        #payBtn {
            width: 100%;
            padding: 13px;
            border-radius: 14px;
            border: none;
            background: linear-gradient(135deg, #ff8c00, #042e72);
            color: white;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #payBtn .spinner {
            display: none;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.75s linear infinite;
            margin-left: 10px;
        }

        #payBtn.loading .spinner { display: inline-block; }

        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>

<body>

<div class="container">

    <div class="wp-header">
        <img src="/wirelogo.png" class="wp-logo">
    </div>

<form method="POST" action="{{ route('wp.process') }}">
@csrf

    <label>MSISDN</label>
    <input type="text" name="msisdn" value="{{ request('msisdn') }}" required>

    <label>Amount</label>
    <input type="number" name="amount" value="{{ request('amount') }}" required>

    <label>Payment Method</label>
    <select name="method" id="methodSelect" required>
        <option value="airtel" {{ request('method')=='airtel' ? 'selected' : '' }}>Airtel Money</option>
        <option value="mtn" {{ request('method')=='mtn' ? 'selected' : '' }}>MTN Money</option>
        <option value="card" {{ request('method')=='card' ? 'selected' : '' }}>Card</option>
    </select>

    <!-- Card Fields -->
    <div id="cardFields">
        <label>Card Number</label>
        <input type="text" name="pan" placeholder="2303 xxxx xxxx xxxx">

        <label>Expiry Date</label>
        <input type="month" name="expiry">

        <label>CVV</label>
        <input type="text" name="cvv" maxlength="4">

        <label>Cardholder Name</label>
        <input type="text" name="cardholderName" placeholder="John Doe">
    </div>

    <input type="hidden" name="property_id" value="{{ request('property_id') }}">

    <button id="payBtn">
        <span>Pay Now</span>
        <span class="spinner"></span>
    </button>

</form>

</div>

<script>
const select = document.getElementById('methodSelect');
const cardFields = document.getElementById('cardFields');

function toggleCard() {
    cardFields.style.display = select.value === 'card' ? 'block' : 'none';
}

select.addEventListener('change', toggleCard);
toggleCard(); // auto-show if method=card

document.querySelector("form").addEventListener("submit", function () {
    let btn = document.getElementById("payBtn");
    btn.classList.add("loading");
});
</script>

</body>
</html>
