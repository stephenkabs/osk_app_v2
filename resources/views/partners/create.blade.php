@extends('layouts.app')

@section('content')
<style>
:root{
  --card:#fff; --ink:#0b0c0f; --muted:#6b7280;
  --border:#e6e8ef; --accent:#0071e3; --radius:20px;
}

/* Page */
.page-wrap{
  min-height:100vh;
  background:linear-gradient(180deg,#f9fafb,#eef1f5);
  padding:40px 16px;
}
.apple-card{
  max-width:820px;
  margin:auto;
  background:var(--card);
  border-radius:var(--radius);
  border:1px solid var(--border);
  box-shadow:0 30px 80px rgba(0,0,0,.08);
  padding:32px;
}

/* Header */
.apple-title{ font-size:26px;font-weight:800; }
.apple-sub{ color:var(--muted); font-size:13px; margin-top:4px }

/* Grid */
.grid{ display:grid; gap:18px }
.grid-2{ grid-template-columns:repeat(auto-fit,minmax(220px,1fr)) }

/* Inputs */
label{ font-size:13px;font-weight:700 }
.apple-input,.apple-file{
  width:100%; padding:12px 14px;
  border-radius:14px;
  border:1px solid var(--border);
  font-weight:600;
}
.apple-input:focus{
  outline:none;
  border-color:var(--accent);
  box-shadow:0 0 0 4px rgba(0,113,227,.15);
}
.help{ font-size:12px;color:var(--muted) }

/* Buttons */
.btn{
  border-radius:999px;
  padding:10px 22px;
  font-weight:800;
  border:none;
  cursor:pointer;
}
.btn-primary{ background:#0b0c0f;color:white }
.btn-secondary{ background:#f3f4f6;border:1px solid var(--border) }
.btn-ghost{ background:transparent;border:1px solid var(--border) }

.actions{ display:flex;justify-content:space-between }

/* Agreement */
.paper{
  background:#fafafa;
  border:1px solid var(--border);
  border-radius:16px;
  padding:18px;
  max-height:220px;
  overflow:auto;
  font-size:13px;
}

/* Checkbox (FIXED ALIGNMENT) */
.checkbox-row{
  display:flex;
  align-items:center;
  gap:10px;
  font-size:14px;
}
.checkbox-row input{
  width:18px;height:18px;
}

/* Signature */
.canvas{
  width:100%;
  height:160px;
  border-radius:14px;
  border:1px dashed #cbd5e1;
  background:#fff;
}

/* Steps */
.hidden{ display:none }

/* Step bar */
.step-indicator{ display:flex; gap:10px; margin-bottom:20px }
.step-dot{ flex:1;height:4px;background:#e5e7eb;border-radius:999px }
.step-dot.active{ background:#0b0c0f }
</style>

<div class="page-wrap">
<div class="apple-card">

  <div class="apple-title">Investor / Partner Registration</div>
  <div class="apple-sub">Complete your investor profile</div>

  <div class="step-indicator">
    <div id="dot-1" class="step-dot active"></div>
    <div id="dot-2" class="step-dot"></div>
  </div>

<form method="POST" action="{{ route('partners.store') }}" enctype="multipart/form-data" id="partnerForm">
@csrf

{{-- STEP 1 --}}
<div id="step-1" class="grid">
  <div class="grid grid-2">
    <div>
      <label>Partner Name</label>
      <input name="name" class="apple-input" required>
    </div>
    <div>
      <label>NRC No</label>
      <input name="nrc_no" class="apple-input" required>
    </div>
  </div>

  <div class="grid grid-2">
    <input name="phone_number" class="apple-input" placeholder="Phone">
    <input name="previous_address" class="apple-input" placeholder="Address">
  </div>

  <div>
    <label>NRC Image</label>
    <input type="file" name="nrc_image" class="apple-file">
  </div>

  <div class="actions">
    <span></span>
    <button type="button" id="toStep2" class="btn btn-secondary">Continue →</button>
  </div>
</div>

{{-- STEP 2 --}}
<div id="step-2" class="grid hidden">

  <div class="paper">
    <strong>Partner Agreement</strong>
    <pre>{{ config('partner_agreement.text') }}</pre>
  </div>

  <label class="checkbox-row">
    <input type="checkbox" name="agree_terms" id="agree" required>
    <span>I agree to the Partner Agreement</span>
  </label>

  <div>
    <strong>Signature</strong>
    <canvas id="sigpad" class="canvas" width="600" height="160"></canvas>

    <div style="margin-top:8px">
      <button type="button" id="clearSig" class="btn btn-ghost">Clear</button>
    </div>

    <input type="hidden" name="signature_data" id="signature_data">
  </div>

  <div class="actions">
    <button type="button" id="backStep1" class="btn btn-ghost">← Back</button>
    <button type="submit" class="btn btn-primary">Submit</button>
  </div>
</div>

</form>
</div>
</div>

{{-- JS --}}
<script>
const step1 = document.getElementById('step-1');
const step2 = document.getElementById('step-2');
const dot1 = document.getElementById('dot-1');
const dot2 = document.getElementById('dot-2');

document.getElementById('toStep2').onclick = () => {
  step1.classList.add('hidden');
  step2.classList.remove('hidden');
  dot1.classList.remove('active');
  dot2.classList.add('active');
  initSignature(); // 🔥 IMPORTANT
};

document.getElementById('backStep1').onclick = () => {
  step2.classList.add('hidden');
  step1.classList.remove('hidden');
  dot2.classList.remove('active');
  dot1.classList.add('active');
};

let initialized = false;
function initSignature(){
  if(initialized) return;
  initialized = true;

  const canvas = document.getElementById('sigpad');
  const ctx = canvas.getContext('2d');
  const input = document.getElementById('signature_data');

  let drawing=false;

  function pos(e){
    const r=canvas.getBoundingClientRect();
    const t=e.touches?e.touches[0]:e;
    return {x:t.clientX-r.left,y:t.clientY-r.top};
  }

  function start(e){ drawing=true; const p=pos(e); ctx.beginPath(); ctx.moveTo(p.x,p.y); e.preventDefault(); }
  function draw(e){ if(!drawing)return; const p=pos(e); ctx.lineTo(p.x,p.y); ctx.stroke(); }
  function end(){ drawing=false; input.value=canvas.toDataURL(); }

  ctx.lineWidth=2.5; ctx.lineCap='round';

  canvas.onmousedown=start;
  canvas.onmousemove=draw;
  canvas.onmouseup=end;
  canvas.ontouchstart=start;
  canvas.ontouchmove=draw;
  canvas.ontouchend=end;

  document.getElementById('clearSig').onclick=()=>{
    ctx.clearRect(0,0,canvas.width,canvas.height);
    input.value='';
  };
}
</script>
@endsection
