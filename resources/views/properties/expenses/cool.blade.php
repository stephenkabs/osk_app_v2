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

/* Inputs */
.af-input, .af-select{
  width:100%;
  border:1px solid var(--border);
  border-radius:12px;
  padding:10px 12px;
  font-weight:600;
}
.af-input:focus, .af-select:focus{
  outline:none;
  border-color:var(--ring);
  box-shadow:0 0 0 3px rgba(0,113,227,.15);
}

/* Expense row */
.expense-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
}

/* Modal */
.apple-modal{
  border-radius:20px;
  border:1px solid var(--border);
  box-shadow:0 20px 50px rgba(0,0,0,.18);
}

/* =========================
   ACTION BUTTONS (APPLE)
========================= */

/* Button row */
.expense-actions{
  display:flex;
  gap:8px;
  justify-content:flex-end;
  flex-wrap:wrap;
}

/* Base outline button */
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

  transition:all .18s ease;
  text-decoration:none;
  line-height:1;
}

/* Icons */
.af-btn-outline i{
  font-size:12px;
  opacity:.85;
}

/* Hover */
.af-btn-outline:hover{
  background:#f5f5f7;
  transform:translateY(-1px);
  box-shadow:0 6px 14px rgba(0,0,0,.08);
}

/* View */
.af-btn-view{
  color:#0b0c0f;
}

/* Edit */
.af-btn-edit{
  color:#0071e3;
  border-color:#dbeafe;
}
.af-btn-edit:hover{
  background:#eff6ff;
}

/* Delete */
.af-btn-danger{
  color:#b91c1c;
  border-color:#fee2e2;
}
.af-btn-danger:hover{
  background:#fef2f2;
  box-shadow:0 6px 16px rgba(185,28,28,.18);
}

/* =========================
   APPLE MODALS
========================= */
.apple-modal .modal-content{
  border-radius:20px;
  border:1px solid #e6e8ef;
  box-shadow:0 30px 70px rgba(0,0,0,.18);
}

.apple-modal .modal-header{
  border-bottom:none;
  padding:18px 22px 10px;
}

.apple-modal .modal-title{
  font-weight:800;
  letter-spacing:-.02em;
}

.apple-modal .modal-body{
  padding:16px 22px;
}

.apple-modal .modal-footer{
  border-top:none;
  padding:14px 22px 20px;
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
  color:#0b0c0f;
}

/* Delete icon */
.delete-icon{
  width:64px;
  height:64px;
  border-radius:50%;
  background:#fee2e2;
  color:#b91c1c;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:26px;
  margin:0 auto 12px;
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

      <button class="af-btn"
              data-bs-toggle="modal"
              data-bs-target="#addExpenseModal">
        <i class="fas fa-plus"></i> Add Expense
      </button>
    </div>

    {{-- LIST --}}
@forelse($expenses as $expense)
  <div class="apple-card mb-2">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

      {{-- LEFT --}}
      <div>
        <strong>{{ $expense->title }}</strong><br>
        <span class="text-muted small">
          {{ ucfirst($expense->category) }}
          @if($expense->unit)
            • Unit {{ $expense->unit->code }}
          @endif
        </span>
      </div>

      {{-- RIGHT --}}
      <div class="text-end">
        <strong>K{{ number_format($expense->amount,2) }}</strong><br>
        <span class="text-muted small">
          {{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}
        </span>
      </div>

    </div>

    {{-- ACTION BUTTONS --}}
<div class="expense-actions mt-3">

<button class="af-btn-outline af-btn-view"
        data-bs-toggle="modal"
        data-bs-target="#showExpenseModal{{ $expense->id }}">
View
</button>


<button class="af-btn-outline af-btn-edit"
        data-bs-toggle="modal"
        data-bs-target="#editExpenseModal{{ $expense->id }}">
   Edit
</button>



<button class="af-btn-outline af-btn-danger"
        data-bs-toggle="modal"
        data-bs-target="#deleteExpenseModal{{ $expense->id }}">
  Delete
</button>


</div>

  </div>
@empty
  <div class="text-center text-muted">No expenses recorded.</div>
@endforelse


    {{ $expenses->links() }}

  </div>
</div>

{{-- ADD EXPENSE MODAL --}}
<div class="modal fade" id="addExpenseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:440px;">
    <div class="modal-content apple-modal">

      <form method="POST"
            action="{{ route('property.expenses.store', $property->slug) }}">
        @csrf

        <div class="modal-header border-0">
          <h5 class="fw-bold mb-0">Add Expense</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="small fw-bold">Title</label>
            <input name="title" class="af-input" required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Category</label>
            <select name="category" class="af-select" required>
              <option value="maintenance">Maintenance</option>
              <option value="utilities">Utilities</option>
              <option value="repairs">Repairs</option>
              <option value="management">Management</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Unit (optional)</label>
            <select name="unit_id" class="af-select">
              <option value="">— Not unit specific —</option>
              @foreach($units as $unit)
                <option value="{{ $unit->id }}">{{ $unit->code }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Amount (K)</label>
            <input type="number" step="0.01" name="amount" class="af-input" required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Expense Date</label>
            <input type="date"
                   name="expense_date"
                   value="{{ now()->toDateString() }}"
                   class="af-input"
                   required>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Notes</label>
            <textarea name="notes" class="af-input"
                      placeholder="Optional description..."></textarea>
          </div>

        </div>

        <div class="modal-footer border-0">
          <button type="button"
                  class="btn btn-light rounded-pill px-4"
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

<div class="modal fade apple-modal"
     id="showExpenseModal{{ $expense->id }}"
     tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:420px">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">Expense Details</h5>
        <button class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <div class="apple-kv">
          <span>Title</span>
          <strong>{{ $expense->title }}</strong>
        </div>

        <div class="apple-kv">
          <span>Category</span>
          <strong>{{ ucfirst($expense->category) }}</strong>
        </div>

        <div class="apple-kv">
          <span>Unit</span>
          <strong>{{ $expense->unit->code ?? 'General' }}</strong>
        </div>

        <div class="apple-kv">
          <span>Amount</span>
          <strong>K{{ number_format($expense->amount,2) }}</strong>
        </div>

        <div class="apple-kv">
          <span>Date</span>
          <strong>{{ \Carbon\Carbon::parse($expense->expense_date)->format('d M Y') }}</strong>
        </div>

        @if($expense->description)
          <div class="apple-kv">
            <span>Description</span>
            <strong>{{ $expense->description }}</strong>
          </div>
        @endif
      </div>

      <div class="modal-footer">
        <button class="af-btn-outline" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
<div class="modal fade apple-modal"
     id="editExpenseModal{{ $expense->id }}"
     tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">

      <form method="POST"
            action="{{ route('property.expenses.update', [$property->slug, $expense->id]) }}">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Expense</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="small fw-bold">Title</label>
            <input name="title"
                   value="{{ $expense->title }}"
                   class="af-input"
                   required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Category</label>
            <input name="category"
                   value="{{ $expense->category }}"
                   class="af-input"
                   required>
          </div>

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

          <div class="mb-3">
            <label class="small fw-bold">Amount (K)</label>
            <input type="number"
                   step="0.01"
                   name="amount"
                   value="{{ $expense->amount }}"
                   class="af-input"
                   required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Date</label>
            <input type="date"
                   name="expense_date"
                   value="{{ $expense->expense_date }}"
                   class="af-input"
                   required>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Description</label>
            <textarea name="description"
                      class="af-input"
                      rows="2">{{ $expense->description }}</textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button"
                  class="af-btn-outline"
                  data-bs-dismiss="modal">
            Cancel
          </button>
          <button class="af-btn">
            <i class="fas fa-save"></i> Update
          </button>
        </div>

      </form>

    </div>
  </div>
</div>

<div class="modal fade apple-modal"
     id="deleteExpenseModal{{ $expense->id }}"
     tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:380px">
    <div class="modal-content">

      <div class="modal-body text-center">
        <div class="delete-icon">
          <i class="fas fa-trash"></i>
        </div>

        <h5 class="fw-bold mb-2">Delete Expense?</h5>
        <p class="text-muted small mb-3">
          This will permanently delete<br>
          <strong>{{ $expense->title }}</strong>
        </p>

        <form method="POST"
              action="{{ route('property.expenses.destroy', [$property->slug, $expense->id]) }}">
          @csrf
          @method('DELETE')

          <div class="d-flex justify-content-center gap-2">
            <button type="button"
                    class="af-btn-outline"
                    data-bs-dismiss="modal">
              Cancel
            </button>

            <button class="af-btn af-btn-danger">
              Delete
            </button>
          </div>
        </form>

      </div>

    </div>
  </div>
</div>
<div class="modal fade apple-modal"
     id="editExpenseModal{{ $expense->id }}"
     tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" style="max-width:460px">
    <div class="modal-content">

      <form method="POST"
            action="{{ route('property.expenses.update', [$property->slug, $expense->id]) }}">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Expense</h5>
          <button class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body">

          <div class="mb-3">
            <label class="small fw-bold">Title</label>
            <input name="title"
                   value="{{ $expense->title }}"
                   class="af-input"
                   required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Category</label>
            <input name="category"
                   value="{{ $expense->category }}"
                   class="af-input"
                   required>
          </div>

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

          <div class="mb-3">
            <label class="small fw-bold">Amount (K)</label>
            <input type="number"
                   step="0.01"
                   name="amount"
                   value="{{ $expense->amount }}"
                   class="af-input"
                   required>
          </div>

          <div class="mb-3">
            <label class="small fw-bold">Date</label>
            <input type="date"
                   name="expense_date"
                   value="{{ $expense->expense_date }}"
                   class="af-input"
                   required>
          </div>

          <div class="mb-2">
            <label class="small fw-bold">Description</label>
            <textarea name="description"
                      class="af-input"
                      rows="2">{{ $expense->description }}</textarea>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button"
                  class="af-btn-outline"
                  data-bs-dismiss="modal">
            Cancel
          </button>
          <button class="af-btn">
            <i class="fas fa-save"></i> Update
          </button>
        </div>

      </form>

    </div>
  </div>
</div>


@endsection
