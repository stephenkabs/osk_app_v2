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

        .form-group {
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
            padding: 15px;
            border-radius: 16px;
            background: rgba(255,255,255,0.75);
            border: 1px solid #eee;
            margin-top: 10px;
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

<form method="POST" action="{{ route('wp.process') }}" class="wp-form">
    @csrf

    {{-- MSISDN --}}
    <div class="form-group">
        <label>MSISDN</label>
        <input
            type="text"
            name="msisdn"
            placeholder="097xxxxxxx"
            value="{{ old('msisdn', request('msisdn')) }}"
            required
        >
    </div>

    {{-- Amount --}}
    <div class="form-group">
        <label>Amount</label>
        <input
            type="number"
            name="amount"
            min="1"
            placeholder="1"
            value="{{ old('amount', request('amount')) }}"
            required
        >
    </div>

    {{-- Payment Method --}}
    <div class="form-group">
        <label>Payment Method</label>
        <select name="method" id="methodSelect" required>
            @php $m = old('method', request('method')); @endphp
            <option value="airtel" {{ $m === 'airtel' ? 'selected' : '' }}>Airtel Money</option>
            <option value="mtn"    {{ $m === 'mtn'    ? 'selected' : '' }}>MTN Money</option>
            <option value="card"   {{ $m === 'card'   ? 'selected' : '' }}>Card</option>
        </select>
    </div>

    <!-- Card fields (hidden unless method=card) -->
    <div id="cardFields" class="card-area">
        <div class="form-group">
            <label>Card Number (PAN)</label>
            <input type="text" name="pan" placeholder="2303 xxxx xxxx xxxx" value="{{ old('pan') }}">
        </div>

        <div class="form-group">
            <label>Expiry Date</label>
            <input type="month" name="expiry" value="{{ old('expiry') }}">
        </div>

        <div class="form-group">
            <label>CVV</label>
            <input type="text" name="cvv" maxlength="4" placeholder="123" value="{{ old('cvv') }}">
        </div>

        <div class="form-group">
            <label>Cardholder Name</label>
            <input type="text" name="cardholderName" placeholder="John Doe" value="{{ old('cardholderName') }}">
        </div>
    </div>

    {{-- from property modal --}}
    <input type="hidden" name="property_id" value="{{ request('property_id') }}">
<br>
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
</style>
    <div class="footer-note">
        🔒 Secure Transaction — Your details are encrypted using <strong>RSA-2048</strong>
        Wirepick ensures PCI-DSS compliant processing.s
    </div>
<script>
    const methodSelect = document.getElementById('methodSelect');
    const cardFields   = document.getElementById('cardFields');

    function toggleCardFields() {
        cardFields.style.display = methodSelect.value === 'card' ? 'block' : 'none';
    }

    methodSelect.addEventListener('change', toggleCardFields);
    toggleCardFields(); // initial state (e.g. if method=card came from modal)

    document.querySelector('.wp-form').addEventListener('submit', function () {
        const btn = document.getElementById('payBtn');
        btn.classList.add('loading');
        btn.disabled = true;
        btn.querySelector('.btn-text').innerText = 'Processing...';
    });
</script>

</div>

{{-- <script>
const select = document.getElementById('methodSelect');
const cardFields = document.getElementById('cardFields');

function toggleCard() {
    cardFields.style.display = select.value === 'card' ? 'block' : 'none';
}

select.addEventListener('change', toggleCard);
toggleCard();

document.querySelector("form").addEventListener("submit", function () {
    let btn = document.getElementById("payBtn");
    btn.classList.add("loading");
});
</script> --}}
<script>
    const methodSelect = document.getElementById('methodSelect');
    const cardFields   = document.getElementById('cardFields');

    function toggleCardFields() {
        cardFields.style.display = methodSelect.value === 'card' ? 'block' : 'none';
    }

    methodSelect.addEventListener('change', toggleCardFields);
    toggleCardFields(); // initial state (e.g. if method=card came from modal)

    document.querySelector('.wp-form').addEventListener('submit', function () {
        const btn = document.getElementById('payBtn');
        btn.classList.add('loading');
        btn.disabled = true;
        btn.querySelector('.btn-text').innerText = 'Processing...';
    });
</script>
</body>
</html>
