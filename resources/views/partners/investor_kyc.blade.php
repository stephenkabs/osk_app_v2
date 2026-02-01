<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Partner KYC</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    :root{
      --bg:#fbfbfd; --ink:#0b0c0f; --muted:#6e6e73; --line:#e6e6ea; --ring:#0a84ff;
      --card:#ffffff; --shadow:0 1px 0 rgba(0,0,0,.04), 0 12px 24px rgba(0,0,0,.06);
      --ok:#34c759; --warn:#ff9f0a;
    }
    *{box-sizing:border-box}
    body{margin:0;background:var(--bg);color:var(--ink);font:14px/1.5 system-ui,-apple-system,Segoe UI,Roboto,Helvetica,Arial}
    .wrap{max-width:920px;margin:0 auto;padding:28px 16px 48px}
    .top{display:flex;justify-content:space-between;align-items:center;gap:12px}
    .h1{font-size:28px;font-weight:600;letter-spacing:-.2px;margin:0}
    .sub{color:var(--muted);margin-top:4px}
    .card{margin-top:18px;background:var(--card);border:1px solid var(--line);border-radius:16px;box-shadow:var(--shadow)}
    .card-hd{padding:14px 18px;border-bottom:1px solid #eee}
    .stepper{display:flex;gap:8px;align-items:center}
    .dot{width:8px;height:8px;border-radius:999px;background:#d2d2d7}
    .dot.active{background:var(--ring)}
    .card-bd{padding:18px}
    .grid{display:grid;gap:14px}
    @media(min-width:720px){.grid-2{grid-template-columns:1fr 1fr}}
    label{display:block;font:500 13px/1.2 system-ui;color:#1d1d1f;margin:0 0 6px}
    .input,.select,.file{
      width:100%;padding:10px 12px;border:1px solid #d2d2d7;border-radius:12px;background:#fff;
      color:var(--ink);font:14px;outline:none
    }
    .input:focus,.select:focus,.file:focus{border-color:#c2c2c7;box-shadow:0 0 0 3px rgba(10,132,255,.2)}
    .help{color:var(--muted);font-size:12px;margin-top:6px}
    .alert{border:1px solid #ffd7d7;background:#fff3f3;color:#9b1c1c;border-radius:12px;padding:10px 12px;margin-bottom:12px}
    .paper{background:#fafafa;border:1px solid var(--line);border-radius:12px;padding:12px;max-height:240px;overflow:auto}
    .checkbox{width:18px;height:18px;border:1px solid #c7c7cc;border-radius:6px;vertical-align:middle}
    .canvas{width:100%;height:180px;border:1px solid var(--line);border-radius:12px;background:#fff}
    .row{display:flex;gap:8px;flex-wrap:wrap;align-items:center}
    .btn{display:inline-flex;gap:8px;align-items:center;justify-content:center;border-radius:12px;
      padding:10px 14px;font:600 15px/1 system-ui;text-decoration:none;border:1px solid transparent;cursor:pointer}
    .btn-primary{background:#0a84ff;color:#fff}
    .btn-primary:hover{background:#0071e3}
    .btn-ghost{background:#fff;border-color:#d2d2d7;color:#1d1d1f}
    .actions{display:flex;justify-content:space-between;gap:8px;margin-top:6px}
    .hidden{display:none}
  </style>
</head>
<body>
  <div class="wrap">
    <div class="top">
      <div>
        <h1 class="h1">Partner KYC</h1>
        <div class="sub">Two quick steps: details → agreement & signature.</div>
      </div>
      <a href="{{ url()->previous() }}" class="btn btn-ghost">Close</a>
    </div>

    <div class="card">
      <div class="card-hd">
        <div class="stepper">
          <span class="dot dot-1 active"></span><span>Details</span>
          <span style="width:18px;height:1px;background:#d2d2d7;margin:0 8px"></span>
          <span class="dot dot-2"></span><span>Agreement</span>
        </div>
        <div class="sub" style="margin-top:6px">Fill your info, then review and sign.</div>
      </div>

      <div class="card-bd">
        @if ($errors->any())
          <div class="alert">
            <ul style="margin:0 0 0 18px">
              @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

<form action="{{ route('partners.store') }}" method="POST" id="partner-form" enctype="multipart/form-data" class="grid">
    @csrf

    {{-- Hidden input for logged in user email --}}
    {{-- <input type="hidden" name="user_email" value="{{ Auth::user()->email }}"> --}}

    {{-- STEP 1 --}}
    <div id="step-1" class="grid">
        <div class="grid grid-2">
            <div>
                <label for="name">Partner Name</label>
                <input type="text" name="name" id="name" class="input" placeholder="e.g. John Banda" required value="{{ old('name') }}">
            </div>

            <div>
                <label for="nrc_no">NRC No</label>
                <input type="text" name="nrc_no" id="nrc_no" class="input" placeholder="e.g. 123456/78/9" required value="{{ old('nrc_no') }}">
            </div>
        </div>

        <div class="grid grid-2">
            <div>
                <label for="phone_number">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" class="input" placeholder="e.g. 0977 000 000" value="{{ old('phone_number') }}">
            </div>

            <div>
                <label for="previous_address">Previous Address</label>
                <input type="text" name="previous_address" id="previous_address" class="input" placeholder="Street, City" value="{{ old('previous_address') }}">
            </div>
        </div>

        <div>
            <label for="nrc_image">NRC Image (jpg/png/pdf)</label>
            <input type="file" name="nrc_image" id="nrc_image" class="file" accept=".jpg,.jpeg,.png,.pdf">
            <div class="help">Optional. Max 5MB.</div>
        </div>

        <div class="actions">
            <span></span>
            <button type="button" id="to-step-2" class="btn btn-secondary">Next</button>
        </div>
    </div>

    {{-- STEP 2 --}}
    <div id="step-2" class="grid hidden">
        <div class="paper">
            <div style="font-weight:600;margin-bottom:6px">
                Partner Agreement ({{ config('partner_agreement.version', 'v1.0') }})
            </div>
            <pre style="margin:0;white-space:pre-wrap">{{ config('partner_agreement.text', 'Default partner agreement text goes here.') }}</pre>
        </div>

        <label class="row" style="align-items:center">
            <input type="checkbox" class="checkbox" id="agree_terms" name="agree_terms" value="1" required>
            <span>I have read and agree to the Partner Agreement.</span>
        </label>

        <div>
            <div style="font-weight:500;margin-bottom:6px">Sign below</div>
            <canvas id="sigpad" class="canvas"></canvas>
            <div class="row" style="margin-top:8px">
                <button type="button" id="clearSig" class="btn btn-ghost">Clear</button>
            </div>
            <input type="hidden" name="signature_data" id="signature_data">
        </div>

        <div class="actions">
            <button type="button" id="back-step-1" class="btn btn-ghost">Back</button>
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </div>
</form>

      </div>
    </div>

    <p style="text-align:center;margin-top:14px;color:#9e9ea2;font-size:12px">Your information is encrypted and kept private.</p>
  </div>

  {{-- Signature Pad --}}
  <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.5/dist/signature_pad.umd.min.js"></script>
  <script>
    (function(){
      const step1 = document.getElementById('step-1');
      const step2 = document.getElementById('step-2');
      const dot1  = document.querySelector('.dot-1');
      const dot2  = document.querySelector('.dot-2');

      const toStep2 = document.getElementById('to-step-2');
      const back1   = document.getElementById('back-step-1');

      function validateStep1(){
        const requiredIds = ['name','nrc_no'];
        for (const id of requiredIds){
          const el = document.getElementById(id);
          if (!el) continue;
          if (!el.value.trim()) return false;
        }
        return true;
      }

      function gotoStep2(){
        if (!validateStep1()){
          alert('Please complete Name and NRC No before continuing.');
          return;
        }
        step1.classList.add('hidden');
        step2.classList.remove('hidden');
        dot1.classList.remove('active');
        dot2.classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
        resizeCanvas();
      }

      function gotoStep1(){
        step2.classList.add('hidden');
        step1.classList.remove('hidden');
        dot2.classList.remove('active');
        dot1.classList.add('active');
        window.scrollTo({ top: 0, behavior: 'smooth' });
      }

      toStep2.addEventListener('click', gotoStep2);
      back1.addEventListener('click', gotoStep1);

      // Signature pad
      const canvas = document.getElementById('sigpad');
      let pad;
      function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        const rect = canvas.getBoundingClientRect();
        canvas.width = rect.width * ratio;
        canvas.height = 180 * ratio;
        const ctx = canvas.getContext('2d');
        ctx.scale(ratio, ratio);
      }
      resizeCanvas();
      window.addEventListener('resize', resizeCanvas);
      pad = new SignaturePad(canvas, { minWidth: .8, maxWidth: 2.5 });

      document.getElementById('clearSig').addEventListener('click', () => pad.clear());

      document.getElementById('partner-form').addEventListener('submit', (e) => {
        if (!step2 || step2.classList.contains('hidden')) {
          e.preventDefault();
          gotoStep2();
          return false;
        }
        if (pad.isEmpty()) {
          e.preventDefault();
          alert('Please provide your signature.');
          return false;
        }
        document.getElementById('signature_data').value = pad.toDataURL('image/png');
      });
    })();
  </script>
</body>
</html>
