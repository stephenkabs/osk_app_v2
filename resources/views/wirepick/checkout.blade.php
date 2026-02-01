@extends('layouts.app')
@include('wirepick.style')



@section('content')
{{-- ================= SKELETON PRELOADER ================= --}}
<div id="skeleton-loader">

  <div class="invest-grid">

    {{-- LEFT CARD --}}
    <div class="apple-card skeleton-card">
      <div class="skeleton skel-title"></div>

      <div class="skeleton skel-row"></div>
      <div class="skeleton skel-row"></div>

      <div class="skeleton skel-toggle mt-3"></div>

      <div class="skeleton skel-box mt-3"></div>
      <div class="skeleton skel-box mt-3"></div>
    </div>

    {{-- RIGHT CARD --}}
    <div class="apple-card skeleton-card">
      <div class="skeleton skel-title"></div>

      <div class="d-flex justify-content-center gap-3 mt-4">
        <div class="skeleton" style="width:80px;height:50px;border-radius:14px"></div>
        <div class="skeleton" style="width:80px;height:50px;border-radius:14px"></div>
        <div class="skeleton" style="width:80px;height:50px;border-radius:14px"></div>
      </div>

      <div class="skeleton skel-box mt-4"></div>
      <div class="skeleton skel-button"></div>
    </div>

  </div>
</div>

<div id="real-content" style="display:none;">
<div class="container py-5">

  <div class="invest-grid">

    {{-- ================= LEFT: INVESTMENT ================= --}}
    <div class="apple-card">

      <h4 class="apple-title text-center mb-3"
          style="text-transform: uppercase;">
          {{ $property->property_name }}
      </h4>

      <div class="info-box mb-4">
        <div class="info-row">
          <span class="label">Unit Price (USD)</span>
          <span class="value">{{ number_format($property->qbo_unit_price,2) }}</span>
        </div>
        <div class="info-row">
          <span class="label">Available Shares</span>
          <span class="value">{{ $property->qbo_qty_on_hand }}</span>
        </div>
      </div>

      <div class="invest-mode-toggle mb-3">
        <button type="button" class="mode-btn active" data-mode="amount">Enter Amount</button>
        <button type="button" class="mode-btn" data-mode="shares">Enter Shares</button>
      </div>

      <div class="invest-table">
        <div class="row-item">
          <span class="label">Amount (USD)</span>
          <input type="number" id="investAmount" class="table-input" placeholder="0.00">
        </div>
        <div class="row-item">
          <span class="label">Estimated Shares</span>
          <input type="number" id="investSharesInput" class="table-input" placeholder="0.00">
        </div>
      </div>

    </div>

    {{-- ================= RIGHT: CHECKOUT ================= --}}
{{-- ================= RIGHT: CHECKOUT ================= --}}
<div class="apple-card">

  <h5 class="apple-title mb-3 text-center">Checkout</h5>

{{-- PAYMENT METHODS --}}
<div class="payment-options mb-4">

  <div class="pay-wrapper">
    <div class="pay-btn-option pay-airtel active" data-method="airtel">
      <img src="/airtel.webp" alt="Airtel Money">
    </div>
    <div class="pay-label">Airtel Money</div>
  </div>

  <div class="pay-wrapper">
    <div class="pay-btn-option pay-mtn" data-method="mtn">
      <img src="/mtn.webp" alt="MTN Money">
    </div>
    <div class="pay-label">MTN Money</div>
  </div>

  <div class="pay-wrapper">
    <div class="pay-btn-option pay-card" data-method="card">
      <img src="/card.webp" alt="Card Payment">
    </div>
    <div class="pay-label">Card</div>
  </div>

</div>


  {{-- CHECKOUT FORM --}}
  <form method="POST" action="{{ route('wp.process') }}" id="checkoutForm">
    @csrf

    <input type="hidden" name="property_id" value="{{ $property->id }}">
    <input type="hidden" name="method" id="investMethod" value="airtel">
    <input type="hidden" name="amount" id="inputAmount">

    {{-- MOBILE MONEY --}}
    <div id="mobileFields" class="checkout-fields">
      <div class="form-group">
        <label>Mobile Number (MSISDN)</label>
        <input type="text"
               name="msisdn"
               class="apple-input"
               placeholder="097xxxxxxx"
               value="{{ auth()->user()->phone ?? '' }}">
      </div>
    </div>

    {{-- CARD --}}
    <div id="cardFields" class="checkout-fields" style="display:none">
<div class="form-group">
  <label>Card Number</label>
  <input type="text"
         id="panInput"
         name="pan"
         class="apple-input"
         placeholder="4111 1111 1111 1111"
         inputmode="numeric"
         autocomplete="cc-number">
</div>

      <div class="form-group">
        <label>Expiry</label>
        <input type="month" name="expiry" class="apple-input">
      </div>

      <div class="form-group">
        <label>CVV</label>
        <input type="text" name="cvv" class="apple-input" placeholder="123">
      </div>

      <div class="form-group">
        <label>Cardholder Name</label>
        <input type="text" name="cardholderName" class="apple-input" placeholder="John Doe">
      </div>

    </div>

    <button class="pay-btn mt-3">
      <i class="fas fa-lock"></i> Pay Securely
    </button>

    <p class="text-center text-muted small mt-3">
      🔒 Encrypted & PCI-DSS compliant
    </p>
  </form>

</div>


  </div>
</div>
</div>
@include('wirepick.spinner_modal')
@endsection

<script>
document.addEventListener("DOMContentLoaded", function () {

    let price = {{ $property->qbo_unit_price }};
    let qty   = {{ $property->qbo_qty_on_hand }};
    let mode  = "amount";

    // MODE SWITCH
    document.querySelectorAll(".mode-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".mode-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            mode = this.dataset.mode;
        });
    });

    // AMOUNT → SHARES
    document.getElementById("investAmount").addEventListener("input", function () {
        if (mode !== "amount") return;

        let amount = parseFloat(this.value || 0);
        let shares = amount / price;

        if (shares > qty) {
            shares = qty;
            amount = qty * price;
            this.value = amount.toFixed(2);
        }

        document.getElementById("investSharesInput").value = shares.toFixed(2);
        document.getElementById("inputAmount").value = amount;
    });

    // SHARES → AMOUNT
    document.getElementById("investSharesInput").addEventListener("input", function () {
        if (mode !== "shares") return;

        let shares = parseFloat(this.value || 0);
        let amount = shares * price;

        document.getElementById("investAmount").value = amount.toFixed(2);
        document.getElementById("inputAmount").value = amount;
    });

    // PAYMENT METHOD
    document.querySelectorAll(".pay-btn-option").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".pay-btn-option").forEach(b => b.classList.remove("active"));
            this.classList.add("active");
            document.getElementById("investMethod").value = this.dataset.method;
        });
    });

});
</script>
<script>
document.addEventListener("DOMContentLoaded", () => {

  const cardFields = document.getElementById("cardFields");

  const pan     = document.getElementById("panInput");
  const expiry  = document.getElementById("expiryInput");
  const cvv     = document.getElementById("cvvInput");
  const name    = document.getElementById("nameInput");

  document.querySelectorAll(".pay-btn-option").forEach(btn => {
    btn.addEventListener("click", function () {

      const method = this.dataset.method;
      document.getElementById("investMethod").value = method;

      if (method === "card") {
        // Show card fields
        cardFields.style.display = "block";

        // Card required
        pan.required    = true;
        expiry.required = true;
        cvv.required    = true;
        name.required   = true;

      } else {
        // Hide card fields
        cardFields.style.display = "none";

        pan.required    = false;
        expiry.required = false;
        cvv.required    = false;
        name.required   = false;
      }
    });
  });

});
</script>


<script>
document.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    const skel = document.getElementById("skeleton-loader");
    const real = document.getElementById("real-content");

    if (skel) skel.remove();
    if (real) real.style.display = "block";
  }, 450); // subtle Apple-like delay
});
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {

  const panInput = document.getElementById("panInput");
  if (!panInput) return;

  panInput.addEventListener("input", (e) => {
    // Remove all non-digits
    let value = e.target.value.replace(/\D/g, "");

    // Limit to 19 digits (Visa/MC safe)
    value = value.substring(0, 19);

    // Add spaces every 4 digits
    const formatted = value.match(/.{1,4}/g)?.join(" ") || "";

    e.target.value = formatted;
  });

});
</script>





