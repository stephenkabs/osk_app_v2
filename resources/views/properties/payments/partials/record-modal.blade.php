<!-- Record Payment Modal -->
<div class="modal fade" id="recordPaymentModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content apple-modal">

      <form method="POST" action="{{ route('property.payments.store', $property->slug) }}">
        @csrf

        <div class="modal-header">
          <h5 class="fw-bold">Record Rent Payment</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <input type="hidden" name="lease_agreement_id" id="leaseId">

          <div class="mb-3">
            <label>Payment Month</label>
            <input type="month" name="payment_month" class="af-input" required>
          </div>

          <div class="mb-3">
            <label>Amount Paid (K)</label>
            <input type="number" name="amount" class="af-input" required>
          </div>

          <div class="mb-3">
            <label>Payment Method</label>
            <select name="method" class="af-select">
              <option value="cash">Cash</option>
              <option value="bank">Bank</option>
              <option value="mobile_money">Mobile Money</option>
            </select>
          </div>

          <div class="mb-3">
            <label>Reference (optional)</label>
            <input name="reference" class="af-input">
          </div>

        </div>

        <div class="modal-footer">
          <button class="af-btn">Record Payment</button>
        </div>

      </form>

    </div>
  </div>
</div>
