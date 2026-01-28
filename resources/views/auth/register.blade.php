<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="min-h-screen bg-[#eaeaea] flex items-center justify-center p-6 pb-20">


  <!-- SKELETON PRELOADER -->
<div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center bg-[#eaeaea] transition-opacity duration-300">
  <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl overflow-hidden grid md:grid-cols-2 animate-pulse">

    <!-- LEFT IMAGE SKELETON -->
    <div class="hidden md:block bg-gray-300 relative">
      <div class="absolute inset-0 bg-gradient-to-r from-gray-300 via-gray-200 to-gray-300 skeleton"></div>
    </div>

    <!-- FORM SKELETON -->
    <div class="p-8 space-y-4">
      <!-- Logo -->
      <div class="h-8 w-40 bg-gray-300 rounded skeleton"></div>

      <!-- Steps -->
      <div class="flex gap-3 mt-4">
        <div class="h-5 w-5 bg-gray-300 rounded-full skeleton"></div>
        <div class="h-5 w-5 bg-gray-300 rounded-full skeleton"></div>
        <div class="h-5 w-5 bg-gray-300 rounded-full skeleton"></div>
      </div>

      <!-- Inputs -->
      <div class="h-11 bg-gray-300 rounded-xl skeleton"></div>
      <div class="h-11 bg-gray-300 rounded-xl skeleton"></div>
      <div class="h-11 bg-gray-300 rounded-xl skeleton"></div>
      <div class="h-11 bg-gray-300 rounded-xl skeleton"></div>

      <!-- Button -->
      <div class="h-12 bg-gray-400 rounded-xl mt-6 skeleton"></div>
    </div>

  </div>
</div>
<style>
  .skeleton {
    background-size: 200% 100%;
    animation: shimmer 1.4s ease-in-out infinite;
  }

  @keyframes shimmer {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
  }
</style>


  <!-- MAIN CARD -->
  <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl ring-1 ring-black/5 grid md:grid-cols-2 overflow-hidden">

<!-- LEFT SIDE IMAGE -->
<div class="hidden md:flex relative flex-col justify-between items-center p-6 bg-black">

  <!-- Background image -->
  <img src="/6.webp" class="absolute inset-0 w-full h-full object-cover opacity-100" />

  <!-- Dark overlay -->
  <div class="absolute inset-0 bg-gradient-to-br from-black/20 via-black/40 to-black/60"></div>

  <!-- Main welcome text (moved downward) -->
  <div class="relative z-10 text-center px-6 mt-auto mb-20">
    <h2 class="text-white text-3xl font-bold mb-2 drop-shadow-lg">Welcome to Rent App</h2>
    <p class="text-gray-200 text-base max-w-sm mx-auto leading-relaxed">
      Create your account to get started.
    </p>
  </div>

  <!-- Small warning text at bottom -->
  <div class="absolute bottom-4 px-4 text-center z-10">
    <p class="text-white/70 text-[9px] leading-tight">
      Please ensure all information is accurate.
      Your details help us keep your account secure.
    </p>
  </div>

</div>


    <!-- FORM SIDE -->
    <div class="p-6 md:p-10" x-data="registerForm()">

      <!-- LOGO -->
      <div class="mb-5 text-center md:text-left">
        <img src="/logo.webp" width="180" class="mx-auto md:mx-0">
      </div>

      <!-- ALERTS -->
      @if($errors->any())
      <div class="mb-3 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
        <ul class="list-disc pl-5">
          @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
      </div>
      @endif

      <!-- PROGRESS INDICATOR -->
      <div class="mb-5 flex items-center justify-between text-xs font-semibold text-gray-500">
        <div :class="{'text-[#122b50]': step===1}" class="flex items-center gap-2">
          <span :class="step===1 ? 'bg-[#122b50] text-white' : 'bg-gray-200 text-gray-700'"
            class="inline-flex h-5 w-5 items-center justify-center rounded-full">1</span>
          <span>Account</span>
        </div>

        <div class="h-[2px] w-12 bg-gray-300 mx-1"></div>

        <div :class="{'text-[#122b50]': step===2}" class="flex items-center gap-2">
          <span :class="step===2 ? 'bg-[#122b50] text-white' : 'bg-gray-200 text-gray-700'"
            class="inline-flex h-5 w-5 items-center justify-center rounded-full">2</span>
          <span>Contact</span>
        </div>

        <div class="h-[2px] w-12 bg-gray-300 mx-1"></div>

        <div :class="{'text-[#122b50]': step===3}" class="flex items-center gap-2">
          <span :class="step===3 ? 'bg-[#122b50] text-white' : 'bg-gray-200 text-gray-700'"
            class="inline-flex h-5 w-5 items-center justify-center rounded-full">3</span>
          <span>Verification</span>
        </div>
      </div>

      <!-- FORM -->
      <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data"
        @submit="disableOnSubmit" class="space-y-4">
        @csrf

        <!-- STEP 1 -->
        <div x-show="step===1" x-cloak class="space-y-3">

          <input type="text" name="name" placeholder="Full name" required
            class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm">

          <input type="email" name="email" placeholder="Email" required
            class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm">

          <input type="password" name="password" placeholder="Password" required
            class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm">


          <input type="hidden" name="roles[]" value="landlord">

          <input type="password" name="password_confirmation" placeholder="Confirm password" required
            class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm">

          <select name="type" x-model="type" required
            class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm">
            <option value="" disabled selected>Select account type</option>
            <option value="landlord">Landlord</option>
            {{-- <option value="developer"></option> --}}
          </select>

          <button type="button" @click="step=2; scrollToTop()"
            class="w-full rounded-xl bg-[#122b50] px-4 py-3 text-white font-semibold hover:bg-[#0e203f]">
            Next
          </button>
        </div>

        <!-- STEP 2 -->
        <div x-show="step===2" x-cloak class="space-y-3">

          {{-- <input type="text" name="whatsapp_line" placeholder="WhatsApp line"
            class="w-full rounded-xl border px-3.5 py-2.5"> --}}

          <input type="text" name="whatsapp_phone" placeholder="WhatsApp phone"
            class="w-full rounded-xl border px-3.5 py-2.5">

          <input type="text" name="address" placeholder="Address"
            class="w-full rounded-xl border px-3.5 py-2.5">
{{--
          <input type="text" name="occupation" placeholder="Occupation"
            class="w-full rounded-xl border px-3.5 py-2.5"> --}}

          <input type="text" name="nrc" placeholder="NRC number"
            class="w-full rounded-xl border px-3.5 py-2.5">

          {{-- <input type="text" name="country" placeholder="Country"
            class="w-full rounded-xl border px-3.5 py-2.5"> --}}

          <div class="flex justify-between pt-2">
            <button type="button" @click="step=1; scrollToTop()"
              class="rounded-xl bg-gray-200 text-gray-800 font-semibold px-5 py-2">
              Back
            </button>

            <button type="button" @click="step=3; scrollToTop()"
              class="rounded-xl bg-[#122b50] text-white font-semibold px-5 py-2 hover:bg-[#0e203f]">
              Next
            </button>
          </div>

        </div>

        <!-- STEP 3 — SELFIE -->
        <div x-show="step===3" x-cloak class="space-y-4">

          <h3 class="text-lg font-bold text-[#122b50]">Identity Verification</h3>

          <p class="text-sm text-gray-600">Take a clear selfie for verification.</p>

          <div class="flex gap-3 items-center mb-3">
            <video id="camera" autoplay playsinline class="w-32 h-32 bg-black rounded-xl"></video>
            <canvas id="snapshot" class="w-32 h-32 rounded-xl hidden"></canvas>

            <button type="button" onclick="takePhoto()"
              class="px-4 py-2 bg-[#122b50] text-white rounded-xl text-sm">
              Capture
            </button>
          </div>

          <input type="hidden" name="profile_image" id="profile_image">

          <div class="flex justify-between">
            <button type="button" @click="step=2; scrollToTop()"
              class="rounded-xl bg-gray-200 text-gray-800 font-semibold px-5 py-2">
              Back
            </button>

            <button type="submit" id="submit-btn-tenant"
              class="rounded-xl bg-[#122b50] px-5 py-2 text-white font-semibold hover:bg-[#0e203f] flex items-center gap-2">
              <span id="btn-text">Register</span>
              <span id="loading-spinner"
                class="hidden h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
            </button>
          </div>
        </div>

      </form>
    </div>
  </div>

  <!-- CAMERA SCRIPT -->
  <script>
    const video = document.getElementById('camera');
    const canvas = document.getElementById('snapshot');
    const hiddenInput = document.getElementById('profile_image');

    navigator.mediaDevices.getUserMedia({ video: true })
      .then(stream => video.srcObject = stream)
      .catch(() => console.log("Camera blocked"));

    function takePhoto() {
      canvas.classList.remove("hidden");
      const ctx = canvas.getContext("2d");
      ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
      hiddenInput.value = canvas.toDataURL("image/jpeg");
    }
  </script>

  <!-- FORM LOGIC -->
  <script>
    function registerForm(){
      return {
        step: 1,
        type: '',
        disableOnSubmit(e){
          const btn = document.querySelector('#submit-btn-tenant');
          if(!btn) return;
          const text = btn.querySelector('#btn-text');
          const spinner = btn.querySelector('#loading-spinner');
          if(text) text.classList.add('hidden');
          if(spinner) spinner.classList.remove('hidden');
          btn.disabled = true;
        },
        scrollToTop(){ window.scrollTo({ top: 0, behavior: 'smooth' }); }
      }
    }
  </script>

  <script>
  window.addEventListener('load', () => {
    setTimeout(() => {
      const p = document.getElementById('preloader');
      p.style.opacity = '0';
      setTimeout(() => p.style.display = 'none', 300);
    }, 800);
  });
</script>


</body>
</html>
