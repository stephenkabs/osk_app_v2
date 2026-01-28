@extends('layouts.app')

@section('content')

<style>
:root{
  --card:#fff; --ink:#0b0c0f; --muted:#6b7280; --ring:#0071e3; --border:#e6e8ef;
}
.apple-card{
  background:var(--card);
  border-radius:16px;
  padding:24px;
  border:1px solid var(--border);
  box-shadow:0 6px 20px rgba(0,0,0,.06);
}
.apple-header{
  display:flex;
  justify-content:space-between;
  align-items:flex-end;
  margin-bottom:22px;
}
.apple-title{
  font-size:20px;
  font-weight:800;
  letter-spacing:-.02em;
}
.apple-sub{
  font-size:12px;
  color:var(--muted);
  font-weight:600;
  text-transform:uppercase;
}
.af-input{
  width:100%;
  border:1px solid var(--border);
  border-radius:12px;
  padding:10px 12px;
  font-weight:600;
}
.af-input:focus{
  border-color:var(--ring);
  box-shadow:0 0 0 3px rgba(0,113,227,.15);
}
.profile-box{
  border:2px dashed var(--border);
  border-radius:14px;
  padding:18px;
  text-align:center;
  cursor:pointer;
  transition:.2s;
}
.profile-box:hover{
  border-color:var(--ring);
  color:var(--ring);
}
.af-btn{
  background:#0b0c0f;
  color:#fff;
  border:none;
  border-radius:12px;
  padding:10px 18px;
  font-weight:800;
}
.af-btn:hover{ background:#000; }
.preview img{
  max-width:140px;
  border-radius:12px;
  border:2px solid var(--border);
}
.current-avatar{
  max-width:120px;
  border-radius:14px;
  border:2px solid var(--border);
}
</style>

<div class="container-fluid">
  <div class="col-lg-9 mx-auto">

    <div class="apple-card">

      <div class="apple-header">
        <div>
          <h1 class="apple-title">Edit Tenant</h1>
          <div class="apple-sub">{{ $property->property_name }} • Tenants</div>
        </div>

        <a href="{{ route('property.users.show', [$property->slug, $user->slug]) }}"
           class="btn btn-light btn-sm rounded-pill">
          <i class="fas fa-arrow-left me-1"></i> Back
        </a>
      </div>

      <form method="POST"
            action="{{ route('property.users.update', [$property->slug, $user->slug]) }}"
            enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- BASIC INFO --}}
        <div class="row g-3">
          <div class="col-md-6">
            <label class="fw-semibold mb-1">Full Name</label>
            <input class="af-input"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   required>
          </div>

          <div class="col-md-6">
            <label class="fw-semibold mb-1">Email</label>
            <input type="email"
                   class="af-input"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required>
          </div>

          <div class="col-md-6">
            <label class="fw-semibold mb-1">New Password <small class="text-muted">(optional)</small></label>
            <input type="password"
                   class="af-input"
                   name="password">
          </div>

          <div class="col-md-6">
            <label class="fw-semibold mb-1">Confirm Password</label>
            <input type="password"
                   class="af-input"
                   name="password_confirmation">
          </div>
        </div>

        {{-- CONTACT --}}
        <div class="row g-3 mt-2">
          <div class="col-md-6">
            <label class="fw-semibold mb-1">WhatsApp Line</label>
            <input class="af-input"
                   name="whatsapp_line"
                   value="{{ old('whatsapp_line', $user->whatsapp_line) }}">
          </div>

          <div class="col-md-6">
            <label class="fw-semibold mb-1">Direct Phone</label>
            <input class="af-input"
                   name="whatsapp_phone"
                   value="{{ old('whatsapp_phone', $user->whatsapp_phone) }}">
          </div>
        </div>

        {{-- CURRENT IMAGE --}}
        @if($user->profile_image)
          <div class="mt-4">
            <label class="fw-semibold mb-2">Current Profile Image</label><br>
            <img src="{{ asset('storage/'.$user->profile_image) }}"
                 class="current-avatar">
          </div>
        @endif

        {{-- PROFILE IMAGE --}}
        <div class="mt-4">
          <label class="fw-semibold mb-2">Change Profile Image</label>

          <div class="d-flex gap-3 flex-wrap">
            <div class="profile-box" onclick="document.getElementById('uploadInput').click()">
              <i class="fas fa-upload fa-lg mb-2"></i>
              <div>Upload Image</div>
              <input type="file"
                     name="profile_image"
                     id="uploadInput"
                     class="d-none"
                     accept="image/*">
            </div>

            <div class="profile-box" onclick="openCameraModal()">
              <i class="fas fa-camera fa-lg mb-2"></i>
              <div>Take Photo</div>
            </div>
          </div>

          <div class="preview mt-3" id="preview"></div>
          <input type="hidden" name="camera_image_data" id="cameraImageData">
        </div>

        {{-- ACTION --}}
        <div class="mt-4 text-end">
          <button class="af-btn">
            <i class="fas fa-save me-1"></i> Save Changes
          </button>
        </div>

      </form>
    </div>
  </div>
</div>

{{-- CAMERA MODAL --}}
<div class="modal fade" id="cameraModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-white rounded-4">
      <div class="modal-header">
        <h5 class="modal-title">Take Photo</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <video id="cameraStream" autoplay playsinline class="w-100 rounded-3"></video>
        <canvas id="cameraCanvas" class="d-none"></canvas>
      </div>
      <div class="modal-footer">
        <button class="btn btn-light" onclick="capturePhoto()">Capture</button>
      </div>
    </div>
  </div>
</div>

<script>
const uploadInput = document.getElementById('uploadInput');
const preview = document.getElementById('preview');
const hiddenInput = document.getElementById('cameraImageData');
const cameraStream = document.getElementById('cameraStream');
const cameraCanvas = document.getElementById('cameraCanvas');
let stream;

uploadInput?.addEventListener('change', e => {
  const f = e.target.files[0];
  if (!f) return;
  const r = new FileReader();
  r.onload = e => preview.innerHTML = `<img src="${e.target.result}">`;
  r.readAsDataURL(f);
});

function openCameraModal(){
  const modal = new bootstrap.Modal('#cameraModal');
  modal.show();

  navigator.mediaDevices.getUserMedia({ video:true })
    .then(s => { stream = s; cameraStream.srcObject = s; });
}

function capturePhoto(){
  cameraCanvas.width = cameraStream.videoWidth;
  cameraCanvas.height = cameraStream.videoHeight;
  cameraCanvas.getContext('2d').drawImage(cameraStream,0,0);
  const data = cameraCanvas.toDataURL();
  preview.innerHTML = `<img src="${data}">`;
  hiddenInput.value = data;
}
</script>

@endsection
