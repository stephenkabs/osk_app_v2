<div class="modal fade" id="addExpenseModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
    <div class="modal-content apple-modal">

      <form method="POST" action="{{ route('property.expenses.store', $property->slug) }}">
        @csrf

        <div class="modal-header border-0">
          <h5 class="fw-bold">Add Expense</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-2">
            <label class="small fw-bold">Category</label>
            <select name="category" class="af-input" required>
              <option value="maintenance">Maintenance</option>
              <option value="utilities">Utilities</option>
              <option value="security">Security</option>
              <option value="admin">Administration</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Title</label>
            <input name="title" class="af-input" required>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Amount (K)</label>
            <input type="number" step="0.01" name="amount" class="af-input" required>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Expense Date</label>
            <input type="date" name="expense_date" class="af-input"
                   value="{{ now()->toDateString() }}" required>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Description</label>
            <textarea name="description" class="af-input"></textarea>
          </div>

        </div>

        <div class="modal-footer border-0">
          <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
            Cancel
          </button>
          <button class="af-btn">
            <i class="fas fa-save"></i> Save Expense
          </button>
        </div>

      </form>

    </div>
  </div>
</div>
