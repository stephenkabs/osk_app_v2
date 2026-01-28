@extends('layouts.app')

@section('content')

<style>
/* Apple-like bulk form */
.bulk-card{
    background:#fff;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
    padding:24px;
}
.bulk-title{
    font-weight:800;
    font-size:20px;
    letter-spacing:-.02em;
}
.bulk-sub{
    color:#6b7280;
    font-size:13px;
}
.unit-row{
    border:1px solid #e5e7eb;
    border-radius:14px;
    padding:14px;
    margin-bottom:12px;
}
.unit-row h6{
    font-weight:700;
    font-size:14px;
    margin-bottom:10px;
}
.af-input{
    border-radius:12px;
    font-weight:600;
}
</style>

<div class="container-fluid">
    <div class="col-lg-10 mx-auto">

        <div class="bulk-card">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="bulk-title">Bulk Add Units</div>
                    <div class="bulk-sub">{{ $property->property_name }}</div>
                </div>

                <a href="{{ route('property.units.index', $property->slug) }}"
                   class="btn btn-light rounded-pill">
                    ← Back
                </a>
            </div>

            {{-- Unit count --}}
    <div class="row g-3 mb-4">

    <div class="col-md-3">
        <label class="fw-bold mb-1">Unit Code Prefix</label>
        <input type="text"
               id="unitPrefix"
               class="form-control af-input"
               placeholder="A, B, Shop, RM">
    </div>

    <div class="col-md-3">
        <label class="fw-bold mb-1">Number of Units</label>
        <input type="number"
               min="1"
               id="unitCount"
               class="form-control af-input"
               placeholder="e.g. 20">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <button type="button"
                onclick="generateUnits()"
                class="btn btn-dark rounded-pill px-4">
            Generate Units
        </button>
    </div>

</div>


            {{-- Form --}}
            <form method="POST"
                  enctype="multipart/form-data"
                  action="{{ route('property.units.store', $property->slug) }}">
                @csrf

                <div id="unitsWrapper"></div>

                <div class="text-end mt-4">
                    <button class="btn btn-primary rounded-pill px-5">
                        Save All Units
                    </button>
                </div>
            </form>

        </div>

    </div>
</div>

<script>
function generateUnits(){
    const count   = parseInt(document.getElementById('unitCount').value);
    const prefix  = document.getElementById('unitPrefix').value.trim();
    const wrapper = document.getElementById('unitsWrapper');

    wrapper.innerHTML = '';

    if(!count || count < 1){
        alert('Please enter number of units');
        return;
    }

    if(!prefix){
        alert('Please enter unit code prefix (e.g. A, Shop)');
        return;
    }

    const padLength = count >= 100 ? 3 : 2;

    for(let i = 1; i <= count; i++){
        const code = `${prefix}-${String(i).padStart(padLength, '0')}`;

        wrapper.innerHTML += `
            <div class="unit-row">
                <h6>Unit ${i}</h6>

                <div class="row g-3">
                    <div class="col-md-3">
                        <label>Code</label>
                        <input name="units[${i-1}][code]"
                               class="form-control af-input"
                               value="${code}"
                               required>
                    </div>

                    <div class="col-md-3">
                        <label>Rent Amount</label>
                        <input type="number"
                               step="0.01"
                               name="units[${i-1}][rent_amount]"
                               class="form-control af-input">
                    </div>

                    <div class="col-md-3">
                        <label>Deposit Amount</label>
                        <input type="number"
                               step="0.01"
                               name="units[${i-1}][deposit_amount]"
                               class="form-control af-input">
                    </div>

                    <div class="col-md-3">
                        <label>Photo</label>
                        <input type="file"
                               name="units[${i-1}][photo]"
                               accept="image/*"
                               class="form-control af-input">
                    </div>
                </div>
            </div>
        `;
    }
}
</script>



@endsection
