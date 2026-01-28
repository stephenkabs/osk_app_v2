<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Apply as Tenant • {{ $property->property_name }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="/assets/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="/assets/css/app.min.css" rel="stylesheet"/>
  <link href="/assets/css/icons.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

  <style>
    :root{ --card:#fff; --ink:#0b0c0f; --muted:#5b5f6b; --ring:#0071e3; --border:#e6e8ef; --radius:14px; }
    body{ background:#f5f6f8; font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text","Segoe UI",Roboto,Helvetica,Arial,sans-serif; }
    .wrap{ max-width:760px; margin:40px auto; padding:0 16px; }
    .apple-card{ background:var(--card); border-radius:var(--radius); padding:24px; border:1px solid var(--border); box-shadow:0 8px 24px rgba(0,0,0,.05);}
    .apple-header{ display:flex; align-items:flex-end; justify-content:space-between; margin-bottom:18px; }
    .apple-title{ font-weight:800; font-size:20px; margin:0; letter-spacing:-.02em; }
    .apple-sub{ color:var(--muted); font-weight:600; font-size:11px; text-transform:uppercase; }

    label{ font-weight:700; font-size:13px; margin-bottom:6px; display:block; }
    .af-input, .af-select, .af-textarea{
      width:100%; border:1px solid var(--border); border-radius:12px; padding:10px 12px;
      font-size:14px; font-weight:600; color:var(--ink); outline:none; transition:.2s;
    }
    .af-input:focus, .af-select:focus, .af-textarea:focus{
      border-color:var(--ring); box-shadow:0 0 0 3px rgba(0,113,227,.18);
    }
    .grid{ display:grid; gap:14px; grid-template-columns:1fr; }
    @media(min-width:720px){ .grid-2{ grid-template-columns:1fr 1fr; } }

    .checkbox-group{ display:flex; gap:16px; flex-wrap:wrap; }
    .af-btn{
      background:#0b0c0f; color:#fff; border:none; padding:10px 16px; border-radius:12px;
      font-weight:800; letter-spacing:.01em; display:inline-flex; gap:8px; align-items:center;
      transition:transform .06s, box-shadow .2s;
    }
    .af-btn:hover{ transform:translateY(-1px); box-shadow:0 6px 14px rgba(0,0,0,.12); }

    /* Profile capture boxes */
    .profile-options{ display:flex; gap:16px; flex-wrap:wrap; }
    .profile-box{
      flex:1; min-width:180px; padding:18px; border:2px dashed var(--border);
      border-radius:12px; text-align:center; cursor:pointer; transition:.2s;
    }
    .profile-box:hover{ border-color:var(--ring); color:var(--ring); }
    .profile-box i{ font-size:28px; margin-bottom:8px; display:block; }

    .preview{ margin-top:12px; }
    .preview img{ max-width:160px; border-radius:12px; border:2px solid var(--border); }
    .modal-content{ background:#111; color:#fff; border-radius:14px; }
    .btn-close-white{ filter: invert(1); }
  </style>
</head>
<body>

<div class="wrap">
  <div class="apple-card">
<div class="apple-header d-flex align-items-center justify-content-between mb-3">
  <div class="d-flex align-items-center gap-3">
    @php
      $logo = $property->logo_path ? asset('storage/'.$property->logo_path) : null;
      $initials = collect(explode(' ', $property->property_name))
                      ->map(fn($w) => mb_substr($w, 0, 1))
                      ->take(2)
                      ->implode('');
    @endphp

    @if($logo)
      <img src="{{ $logo }}" alt="Logo" style="height:40px;width:auto;border-radius:10px;object-fit:cover;">
    @else
      <div style="height:40px;width:40px;border-radius:10px;background:#0b0c0f;color:#fff;
                  font-weight:800;display:flex;align-items:center;justify-content:center;
                  font-size:16px;">
        {{ strtoupper($initials) }}
      </div>
    @endif

    <div>
      <h1 class="apple-title m-0" style="font-size:18px;">Apply as Tenant</h1>
      <span class="apple-sub" style="font-size:12px;">{{ $property->property_name }}</span>
    </div>
  </div>
</div>


    {{-- validation / flash --}}
    {{-- @include('includes.validation') --}}

<form action="{{ route('property.users.public.store', $property->slug) }}" method="POST" enctype="multipart/form-data">
  @csrf

  <div class="grid grid-2">
    <div>
      <label>Full Name</label>
      <input type="text" name="name" class="af-input" value="{{ old('name') }}" required>
    </div>
    <div>
      <label>Email</label>
      <input type="email" name="email" class="af-input" value="{{ old('email') }}" required>
    </div>
  </div>

  <!-- Hidden default password -->
  <input type="hidden" name="password" value="password">
  <input type="hidden" name="password_confirmation" value="password">

  <div class="grid grid-2">
    <div>
      <label>WhatsApp Line</label>
      <input type="text" name="whatsapp_phone" class="af-input" value="{{ old('whatsapp_phone') }}" placeholder="+260 ..." required>
    </div>
        <div>
      <label>Address</label>
      <input type="text" name="address" class="af-input" value="{{ old('address') }}" placeholder="Home address">
    </div>
  </div>
{{--
  <div class="grid grid-2">
    <div>
      <label>Address</label>
      <input type="text" name="address" class="af-input" value="{{ old('address') }}" placeholder="Home address">
    </div>
  </div> --}}
<br>
  <div class="grid">
    <label>Occupation</label>
    <div class="checkbox-group">
      @php $oldTypes = (array) old('type', []); @endphp
      <label><input type="checkbox" name="type[]" value="Businessman"   {{ in_array('Businessman', $oldTypes) ? 'checked':'' }}> Businessman</label>
      <label><input type="checkbox" name="type[]" value="Working Class" {{ in_array('Working Class', $oldTypes) ? 'checked':'' }}> Working Class</label>
      <label><input type="checkbox" name="type[]" value="Student"       {{ in_array('Student', $oldTypes) ? 'checked':'' }}> Student</label>
      <label><input type="checkbox" name="type[]" value="Agent"         {{ in_array('Agent', $oldTypes) ? 'checked':'' }}> Agent</label>
      <label><input type="checkbox" name="type[]" value="Landlord"      {{ in_array('Landlord', $oldTypes) ? 'checked':'' }}> Landlord</label>
    </div>
  </div>

  <!-- Profile Image (Upload or Camera) -->
  <div class="grid" style="margin-top:6px">
    <label>Profile Image (optional)</label>

    <div class="profile-options">
      <!-- Upload -->
      <label class="profile-box" onclick="document.getElementById('uploadInput').click()">
        <i class="fas fa-upload"></i>
        <span>Upload Image</span>
        <input type="file" id="uploadInput" name="profile_image" accept="image/*" capture="environment" class="d-none">
      </label>

      <!-- Camera -->
      <div class="profile-box" onclick="openCameraModal()">
        <i class="fas fa-camera"></i>
        <span>Take Photo</span>
      </div>
    </div>

    <div class="preview" id="preview"></div>
    <input type="hidden" name="camera_image_data" id="cameraImageData">
  </div>

  <div style="margin-top:16px; display:flex; justify-content:flex-end;">
    <button class="af-btn"><i class="fas fa-user-plus"></i> Submit Application</button>
  </div>
</form>

  </div>

  <p class="text-center text-muted mt-3" style="font-size:12px;">
    Already have an account? <a href="{{ route('login') }}">Sign in</a>
  </p>
</div>

<!-- Camera Modal -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Take a Photo</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <video id="cameraStream" autoplay playsinline style="width:100%;border-radius:12px;"></video>
        <canvas id="cameraCanvas" class="d-none"></canvas>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" onclick="capturePhoto()">Capture</button>
        <button type="button" class="btn btn-success d-none" id="savePhotoBtn" data-bs-dismiss="modal">Use Photo</button>
      </div>
    </div>
  </div>
</div>

<script>
  // ===== Upload preview =====
  const uploadInput = document.getElementById('uploadInput');
  const preview     = document.getElementById('preview');
  const hiddenInput = document.getElementById('cameraImageData');
  let stream;

  uploadInput.addEventListener('change', e => {
    const file = e.target.files && e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = ev => {
      preview.innerHTML = `<img src="${ev.target.result}" alt="Preview">`;
      // If user uploads a file, clear camera base64 to avoid double sources
      hiddenInput.value = '';
    };
    reader.readAsDataURL(file);
  });

  // ===== Camera modal logic =====
  const cameraStream = document.getElementById('cameraStream');
  const cameraCanvas = document.getElementById('cameraCanvas');
  const savePhotoBtn = document.getElementById('savePhotoBtn');

  function openCameraModal(){
    // Bootstrap modal
    const modal = new bootstrap.Modal(document.getElementById('cameraModal'));
    modal.show();

    // Ask for camera
    navigator.mediaDevices.getUserMedia({ video: true })
      .then(s => { stream = s; cameraStream.srcObject = s; })
      .catch(err => alert("Camera access denied: " + err));
  }

  function capturePhoto(){
    const ctx = cameraCanvas.getContext('2d');
    cameraCanvas.width  = cameraStream.videoWidth;
    cameraCanvas.height = cameraStream.videoHeight;
    ctx.drawImage(cameraStream, 0, 0, cameraCanvas.width, cameraCanvas.height);

    const dataURL = cameraCanvas.toDataURL('image/png');
    preview.innerHTML = `<img src="${dataURL}" alt="Preview">`;
    hiddenInput.value = dataURL; // <- this is what your controller reads
    savePhotoBtn.classList.remove('d-none');
  }

  // Stop camera when modal closes
  document.getElementById('cameraModal').addEventListener('hidden.bs.modal', () => {
    if(stream){ stream.getTracks().forEach(track => track.stop()); }
  });
</script>
@include('includes.modals.error')
@include('includes.modals.success')
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>
</html>
