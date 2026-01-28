@extends('layouts.app')

@section('content')

<style>
:root{
  --ink:#0b0c0f;
  --muted:#6b7280;
  --border:#e6e8ef;
  --ring:#0071e3;
  --card:#ffffff;
}

/* Card */
.apple-card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:16px;
  padding:20px;
  box-shadow:0 6px 18px rgba(0,0,0,.06);
}

/* Buttons */
.af-btn{
  background:#0b0c0f;
  color:#fff;
  border:none;
  padding:10px 16px;
  border-radius:12px;
  font-weight:800;
  display:inline-flex;
  align-items:center;
  gap:6px;
}
.af-btn:hover{ background:#000; }

/* Outline buttons */
.af-btn-outline{
  display:inline-flex;
  align-items:center;
  gap:6px;
  padding:6px 14px;
  font-size:12px;
  font-weight:700;
  border-radius:12px;
  border:1px solid #e5e7eb;
  background:#fff;
  color:#0b0c0f;
}
.af-btn-outline:hover{
  background:#f5f5f7;
  box-shadow:0 6px 14px rgba(0,0,0,.08);
}

/* Modals */
.apple-modal .modal-content{
  border-radius:20px;
  border:1px solid var(--border);
  box-shadow:0 30px 70px rgba(0,0,0,.18);
}

.apple-kv{
  font-size:13px;
  margin-bottom:10px;
}
.apple-kv span{
  color:#6b7280;
  font-weight:600;
}
.apple-kv strong{
  display:block;
  font-weight:800;
}

/* ============================
   🍎 APPLE-STYLE MODAL INPUTS
   (SAFE: inputs only)
============================ */

/* Base input */
.apple-modal .af-input {
  width: 100%;
  border-radius: 12px;
  border: 1px solid #e6e8ef;
  padding: 10px 12px;
  font-size: 13px;
  font-weight: 600;
  color: #0b0c0f;
  background: #ffffff;
  transition: border-color .15s ease, box-shadow .15s ease;
}

/* Focus */
.apple-modal .af-input:focus {
  outline: none;
  border-color: #0071e3;
  box-shadow: 0 0 0 3px rgba(0,113,227,.15);
}

/* Textarea */
.apple-modal textarea.af-input {
  resize: none;
  min-height: 70px;
}

/* Select dropdown */
.apple-modal select.af-input {
  appearance: none;
  background-image:
    linear-gradient(45deg, transparent 50%, #6b7280 50%),
    linear-gradient(135deg, #6b7280 50%, transparent 50%);
  background-position:
    calc(100% - 16px) calc(50% - 3px),
    calc(100% - 12px) calc(50% - 3px);
  background-size: 4px 4px, 4px 4px;
  background-repeat: no-repeat;
  padding-right: 32px;
}

/* Labels */
.apple-modal label {
  display: block;
  margin-bottom: 4px;
  font-size: 12px;
  font-weight: 700;
  color: #0b0c0f;
}

/* Spacing consistency */
.apple-modal .mb-3 {
  margin-bottom: 14px !important;
}
.apple-modal .mb-2 {
  margin-bottom: 10px !important;
}

</style>

<div class="container py-4">
  <div class="col-lg-10 mx-auto">

    {{-- HEADER --}}
    <div class="apple-card mb-3 d-flex justify-content-between align-items-center">
      <div>
        <h4 class="fw-bold mb-0">Property Expenses</h4>
        <p class="text-muted mb-0">{{ $property->property_name }}</p>
      </div>

      <button class="af-btn" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
        <i class="fas fa-plus"></i> Add Expense
      </button>
    </div>

    {{-- EXPENSE LIST --}}
    @forelse($expenses as $expense)

    <div class="apple-card mb-2">
      <div class="d-flex justify-content-between align-items-center">
        <div>
          <strong>{{ $expense->title }}</strong><br>
          <span class="text-muted small">
            {{ ucfirst($expense->category) }}
            @if($expense->unit)
              • Unit {{ $expense->unit->code }}
            @endif
          </span>
        </div>

        <div class="text-end">
          <strong>K{{ number_format($expense->amount,2) }}</strong><br>
          <span class="text-muted small">
            {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
          </span>
        </div>
      </div>

      {{-- ACTIONS --}}
      <div class="d-flex gap-2 justify-content-end mt-3">
        <button class="af-btn-outline" data-bs-toggle="modal" data-bs-target="#showExpense{{ $expense->id }}">View</button>
        <button class="af-btn-outline" data-bs-toggle="modal" data-bs-target="#editExpense{{ $expense->id }}">Edit</button>
        <button class="af-btn-outline text-danger" data-bs-toggle="modal" data-bs-target="#deleteExpense{{ $expense->id }}">Delete</button>
      </div>
    </div>

    {{-- SHOW MODAL --}}
    <div class="modal fade apple-modal" id="showExpense{{ $expense->id }}">
      <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Expense Details</h5>
            <button class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="apple-kv"><span>Title</span><strong>{{ $expense->title }}</strong></div>
            <div class="apple-kv"><span>Category</span><strong>{{ ucfirst($expense->category) }}</strong></div>
            <div class="apple-kv"><span>Unit</span><strong>{{ $expense->unit->code ?? 'General' }}</strong></div>
            <div class="apple-kv"><span>Amount</span><strong>K{{ number_format($expense->amount,2) }}</strong></div>
            <div class="apple-kv"><span>Date</span><strong>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</strong></div>
            @if($expense->description)
              <div class="apple-kv"><span>Description</span><strong>{{ $expense->description }}</strong></div>
            @endif
          </div>
        </div>
      </div>
    </div>

{{-- EDIT EXPENSE MODAL --}}
<div class="modal fade apple-modal"
     id="editExpense{{ $expense->id }}"
     tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">

      <form method="POST"
            action="{{ route('property.expenses.update', [$property->slug, $expense->id]) }}">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title fw-bold">Edit Expense</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          {{-- TITLE --}}
          <div class="mb-3">
            <label class="small fw-bold">Title</label>
            <input
              name="title"
              value="{{ $expense->title }}"
              class="af-input"
              required>
          </div>

          {{-- CATEGORY --}}
          <div class="mb-3">
            <label class="small fw-bold">Category</label>
            <select name="category" class="af-input" required>
              @foreach(['maintenance','utilities','repairs','management','other'] as $cat)
                <option value="{{ $cat }}" {{ $expense->category === $cat ? 'selected' : '' }}>
                  {{ ucfirst($cat) }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- UNIT --}}
          <div class="mb-3">
            <label class="small fw-bold">Unit (optional)</label>
            <select name="unit_id" class="af-input">
              <option value="">General</option>
              @foreach($units as $unit)
                <option value="{{ $unit->id }}"
                  {{ $expense->unit_id == $unit->id ? 'selected' : '' }}>
                  {{ $unit->code }}
                </option>
              @endforeach
            </select>
          </div>

          {{-- AMOUNT --}}
          <div class="mb-3">
            <label class="small fw-bold">Amount (K)</label>
            <input
              type="number"
              step="0.01"
              name="amount"
              value="{{ $expense->amount }}"
              class="af-input"
              required>
          </div>

          {{-- DATE --}}
          <div class="mb-3">
            <label class="small fw-bold">Expense Date</label>
            <input
              type="date"
              name="expense_date"
              value="{{ $expense->expense_date }}"
              class="af-input"
              required>
          </div>

          {{-- DESCRIPTION --}}
          <div class="mb-2">
            <label class="small fw-bold">Description</label>
            <textarea
              name="description"
              rows="2"
              class="af-input">{{ $expense->description }}</textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button"
                  class="af-btn-outline"
                  data-bs-dismiss="modal">
            Cancel
          </button>
          <button class="af-btn">
            <i class="fas fa-save me-1"></i> Update
          </button>
        </div>

      </form>

    </div>
  </div>
</div>


    {{-- DELETE MODAL --}}
    <div class="modal fade apple-modal" id="deleteExpense{{ $expense->id }}">
      <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
        <div class="modal-content">
          <form method="POST" action="{{ route('property.expenses.destroy', [$property->slug, $expense->id]) }}">
            @csrf
            @method('DELETE')

            <div class="modal-body text-center">
              <h5 class="fw-bold">Delete Expense?</h5>
              <p class="text-muted">{{ $expense->title }}</p>

              <div class="d-flex justify-content-center gap-2">
                <button type="button" class="af-btn-outline" data-bs-dismiss="modal">Cancel</button>
                <button class="af-btn text-danger">Delete</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    @empty
      <div class="text-center text-muted">No expenses recorded.</div>
    @endforelse

    {{ $expenses->links() }}

  </div>
</div>

{{-- ADD EXPENSE MODAL --}}
<div class="modal fade apple-modal"
     id="addExpenseModal"
     tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">

      <form method="POST"
            action="{{ route('property.expenses.store', $property->slug) }}">
        @csrf

        <div class="modal-header">
          <h5 class="modal-title fw-bold">Add Expense</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          {{-- TITLE --}}
          <div class="mb-3">
            <label class="small fw-bold">Title</label>
            <input
              name="title"
              class="af-input"
              placeholder="e.g. Plumbing repair"
              required>
          </div>

          {{-- CATEGORY --}}
          <div class="mb-3">
            <label class="small fw-bold">Category</label>
            <select name="category" class="af-input" required>
              <option value="">Select category</option>
              <option value="maintenance">Maintenance</option>
              <option value="utilities">Utilities</option>
              <option value="repairs">Repairs</option>
              <option value="management">Management</option>
              <option value="other">Other</option>
            </select>
          </div>

          {{-- UNIT --}}
          <div class="mb-3">
            <label class="small fw-bold">Unit (optional)</label>
            <select name="unit_id" class="af-input">
              <option value="">General</option>
              @foreach($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->code }}</option>
              @endforeach
            </select>
          </div>

          {{-- AMOUNT --}}
          <div class="mb-3">
            <label class="small fw-bold">Amount (K)</label>
            <input
              type="number"
              step="0.01"
              name="amount"
              class="af-input"
              placeholder="0.00"
              required>
          </div>

          {{-- DATE --}}
          <div class="mb-3">
            <label class="small fw-bold">Expense Date</label>
            <input
              type="date"
              name="expense_date"
              value="{{ now()->toDateString() }}"
              class="af-input"
              required>
          </div>

          {{-- DESCRIPTION --}}
          <div class="mb-2">
            <label class="small fw-bold">Description</label>
            <textarea
              name="description"
              rows="2"
              class="af-input"
              placeholder="Optional notes..."></textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button"
                  class="af-btn-outline"
                  data-bs-dismiss="modal">
            Cancel
          </button>

          <button class="af-btn">
            <i class="fas fa-check me-1"></i> Save Expense
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection
