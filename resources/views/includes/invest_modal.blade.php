
<!-- ===================== INVEST MODAL ===================== -->
<div class="modal fade" id="investModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content invest-card">

      <!-- Header -->
      <div class="modal-header border-0">
        {{-- <h4 class="modal-title fw-bold modal-title-text">Invest in Property</h4> --}}
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Body -->
      <div class="modal-body px-5">

<h3 id="investPropertyName"
    class="fw-bold mb-4 text-center"
    style="text-transform: uppercase; font-size: 17px; color: #505050;">
</h3>


        <!-- Info Box -->
        <div class="info-box mb-4">
          <div class="info-row">
            <span class="label">Unit Price (USD):</span>
            <span id="investUnitPrice" class="value highlight"></span>
          </div>

          <div class="info-row">
            <span class="label">Available Shares:</span>
            <span id="investQty" class="value highlight"></span>
          </div>
        </div>

        <!-- Mode Switch -->
        <div class="invest-mode-toggle mb-3">
          <button type="button" class="mode-btn active" data-mode="amount">Enter Amount</button>
          <button type="button" class="mode-btn" data-mode="shares">Enter Shares</button>
        </div>

        <!-- Amount & Shares Table -->
        <div class="invest-table mb-4">

          <div class="row-item">
            <div class="label">Amount (USD)</div>
            <div class="value">
              <input type="number" id="investAmount" min="1" step="0.01" class="table-input" placeholder="0.00">
            </div>
          </div>

          <div class="row-item">
            <div class="label">Estimated Shares</div>
            <div class="value">
              <input type="number" id="investSharesInput" min="0.01" step="0.01" class="table-input" placeholder="0.00">
            </div>
          </div>

        </div>

        <!-- Payment Method Logos -->
<!-- Payment Method Logos -->
<div class="payment-options mt-4">

    <div class="pay-wrapper" >
        <div class="pay-btn-option active" data-method="airtel" style="background-color: red">
            <img src="/airtel.webp" alt="Airtel">
        </div>
        <div class="pay-label" style="font-size: 10px">Airtel Money</div>
    </div>

    <div class="pay-wrapper">
        <div class="pay-btn-option" data-method="mtn" style="background-color:#fecd1b">
            <img src="/mtn.webp" alt="MTN">
        </div>
        <div class="pay-label">MTN Money</div>
    </div>

    <div class="pay-wrapper">
        <div class="pay-btn-option" data-method="card" style="background-color: rgb(0, 0, 0)">
            <img src="/card.webp" alt="Card">
        </div>
        <div class="pay-label">Card Payment</div>
    </div>

</div>


        <input type="hidden" id="investMethod" value="airtel">

      </div>

      <!-- Footer -->
      <div class="modal-footer border-0 justify-content-center pb-4">
        <form id="propertyInvestForm">
          @csrf
          <input type="hidden" id="inputPropertyId">
          <input type="hidden" id="inputAmount">
          <input type="hidden" id="inputMethod">

          <button type="submit" class="pay-btn">
            <i class="fas fa-lock"></i> Pay Securely
          </button>
        </form>
      </div>

    </div>
  </div>
</div>
<!-- ================= END INVEST MODAL ================= -->

<script>
document.addEventListener("DOMContentLoaded", function () {

    let price = 0;
    let qty   = 0;
    let mode  = "amount";

    // --- OPEN MODAL ---
    document.querySelectorAll(".js-invest-btn").forEach(btn => {
        btn.addEventListener("click", function () {

            price = parseFloat(this.dataset.price || 0);
            qty   = parseInt(this.dataset.qty || 0);

            document.getElementById("investPropertyName").textContent = this.dataset.property;
            document.getElementById("investUnitPrice").textContent     = price.toFixed(2);
            document.getElementById("investQty").textContent           = qty;
            document.getElementById("inputPropertyId").value           = this.dataset.id;

            // Reset inputs
            document.getElementById("investAmount").value = "";
            document.getElementById("investSharesInput").value = "";

            new bootstrap.Modal(document.getElementById("investModal")).show();
        });
    });

    // --- MODE SWITCH ---
    document.querySelectorAll(".mode-btn").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".mode-btn").forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            mode = this.dataset.mode;
        });
    });

    // --- AMOUNT → SHARES ---
    document.getElementById("investAmount").addEventListener("input", function () {
        if (mode !== "amount") return;

        const amount = parseFloat(this.value || 0);
        let shares = amount / price;

        if (shares > qty) {
            shares = qty;
            this.value = (qty * price).toFixed(2);
        }

        document.getElementById("investSharesInput").value = shares.toFixed(2);
        document.getElementById("inputAmount").value       = amount;
    });

    // --- SHARES → AMOUNT ---
    document.getElementById("investSharesInput").addEventListener("input", function () {
        if (mode !== "shares") return;

        const shares = parseFloat(this.value || 0);
        const amount = shares * price;

        document.getElementById("investAmount").value  = amount.toFixed(2);
        document.getElementById("inputAmount").value  = amount;
    });

    // --- PAYMENT OPTION CLICK ---
    document.querySelectorAll(".pay-btn-option").forEach(btn => {
        btn.addEventListener("click", function () {
            document.querySelectorAll(".pay-btn-option").forEach(b => b.classList.remove("active"));
            this.classList.add("active");

            document.getElementById("investMethod").value = this.dataset.method;
        });
    });

    // --- SUBMIT FORM → REDIRECT TO PAY PAGE ---
    document.getElementById("propertyInvestForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const amount      = document.getElementById("investAmount").value;
        const method      = document.getElementById("investMethod").value;
        const property_id = document.getElementById("inputPropertyId").value;
        const msisdn      = "{{ auth()->user()->phone ?? '0970000000' }}";

        if (!amount || amount <= 0) {
            alert("Please enter a valid amount.");
            return;
        }

        const url = new URL("{{ url('/pay') }}");

        url.searchParams.set("amount", amount);
        url.searchParams.set("method", method);
        url.searchParams.set("property_id", property_id);
        url.searchParams.set("msisdn", msisdn);

        window.location.href = url.toString();
    });

});
</script>

