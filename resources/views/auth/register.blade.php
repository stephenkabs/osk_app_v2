<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://unpkg.com/alpinejs" defer></script>
</head>

<body class="min-h-screen bg-[#3a3a3a] flex items-center justify-center p-4 pb-16">
  <!-- Preloader -->
  <div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center bg-white transition-opacity duration-200">
    <div class="h-10 w-10 rounded-full border-2 border-[#313131] border-t-transparent animate-spin"></div>
  </div>
  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        const p = document.getElementById('preloader');
        p.style.opacity = '0';
        setTimeout(() => p.style.display = 'none', 200);
      }, 800);
    });
  </script>

  <!-- Two-column layout -->
  <div class="w-full max-w-5xl bg-white rounded-2xl shadow-xl ring-1 ring-black/5 grid grid-cols-1 md:grid-cols-2 overflow-hidden">

  <!-- Left side: Illustration with background image -->
<div class="hidden md:flex relative flex-col justify-center items-center p-6 bg-black">
  <!-- Background image -->
  <img src="/back.jpg" alt="Register"
       class="absolute inset-0 w-full h-full object-cover opacity-70">

  <!-- Dark overlay for readability -->
  <div class="absolute inset-0 bg-gradient-to-br from-black/70 via-black/60 to-black/70"></div>

  <!-- Foreground content -->
  <div class="relative z-10 text-center px-6">
    <h2 class="text-white text-3xl font-bold mb-2 drop-shadow-lg">
      Welcome to OSK App
    </h2>
    <p class="text-gray-200 text-base max-w-sm mx-auto leading-relaxed">
      Create your account to get started with investments, tenants & property management.
    </p>
  </div>
</div>

    <!-- Right side: Form -->
    <div class="p-6 md:p-10" x-data="registerForm()">

      <!-- Logo -->
      <div class="mb-5 text-center md:text-left">

          <img src="/logo.webp" alt="Logo" class="mx-auto md:mx-0" width="180">

      </div>

      <!-- Alerts -->
      @if (session('status'))
        <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
          {{ session('status') }}
        </div>
      @endif
      @if ($errors->any())
        <div class="mb-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
          <ul class="list-disc pl-5">
            @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
          </ul>
        </div>
      @endif

      <!-- Progress -->
      <div class="mb-4 flex items-center justify-between text-xs font-semibold text-gray-500">
        <div :class="{'text-gray-900': step===1}" class="flex items-center gap-2">
          <span :class="step===1 ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-700'}"
                class="inline-flex h-5 w-5 items-center justify-center rounded-full">1</span>
          <span>Account</span>
        </div>
        <div class="h-[2px] w-16 bg-gray-200 mx-1"></div>
        <div :class="{'text-gray-900': step===2}" class="flex items-center gap-2">
          <span :class="step===2 ? 'bg-gray-900 text-white' : 'bg-gray-200 text-gray-700'}"
                class="inline-flex h-5 w-5 items-center justify-center rounded-full">2</span>
          <span>KYC (Tenant)</span>
        </div>
      </div>

      <!-- Form -->
      <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" @submit="disableOnSubmit" class="space-y-4">
        @csrf

        <!-- Step 1 -->
        <div x-show="step===1" x-cloak class="space-y-3">
          <input type="text" name="name" placeholder="Full name" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-gray-800">
          <input type="email" name="email" placeholder="Email" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-gray-800">
          <input type="password" name="password" placeholder="Password" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-gray-800">
          <input type="password" name="password_confirmation" placeholder="Confirm password" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-gray-800">

          <select name="type" x-model="type" required class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5 text-sm focus:ring-2 focus:ring-gray-800">
            <option value="" disabled selected>Select Type</option>
            <option value="tenant">Tenant</option>
            <option value="investor">Investor</option>
          </select>
          <input type="hidden" name="roles[]" value="admin">
<input type="hidden" name="roles[]" value="user">


          <div class="grid grid-cols-1 gap-2">
            <button type="button" x-show="type==='tenant'" @click="step=2; $nextTick(()=>scrollToTop())" class="w-full rounded-xl bg-gray-800 px-4 py-3 text-white font-semibold hover:bg-black">
              Next
            </button>
            <button type="submit" x-show="type==='investor'" id="submit-btn-investor" class="w-full rounded-xl bg-gray-800 px-4 py-3 text-white font-semibold hover:bg-black">
              Register
            </button>
          </div>
        </div>

        <!-- Step 2: Tenant KYC -->
        <div x-show="step===2" x-cloak class="space-y-3">
          <h3 class="text-lg font-bold mb-2">Tenant KYC</h3>
          <input type="text" name="phone" placeholder="Phone" :disabled="!(type==='tenant' && step===2)" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5">
          <input type="text" name="nationality" placeholder="Nationality" :disabled="!(type==='tenant' && step===2)" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5">
          <select name="id_type" :disabled="!(type==='tenant' && step===2)" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5">
            <option value="">ID Type</option>
            <option value="NRC">NRC</option>
            <option value="Passport">Passport</option>
            <option value="DL">Driver’s License</option>
          </select>
          <input type="text" name="id_number" placeholder="ID Number" :disabled="!(type==='tenant' && step===2)" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5">
          {{-- <input type="date" name="id_expiry" :disabled="!(type==='tenant' && step===2)" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5"> --}}
          <textarea name="address" placeholder="Address" :disabled="!(type==='tenant' && step===2)" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5"></textarea>
          <div class="flex gap-3">
            <input type="text" name="city" placeholder="City" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5">
            <input type="text" name="country" placeholder="Country" class="w-full rounded-xl border border-gray-300 px-3.5 py-2.5">
          </div>
      <input type="hidden" name="status" value="approved">
          <!-- File Upload -->
          {{-- <div class="w-full">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Proof of Address</label>
            <input type="file" name="proof_of_address" accept=".jpg,.jpeg,.png,.pdf" class="w-full border-2 border-dashed border-gray-300 rounded-xl p-4">
          </div> --}}

          <div class="flex justify-between pt-2">
            <button type="button" @click="step=1; $nextTick(()=>scrollToTop())" class="rounded-xl bg-gray-200 text-gray-800 font-semibold px-5 py-2">
              Back
            </button>
            <button type="submit" id="submit-btn-tenant" class="rounded-xl bg-gray-800 px-5 py-2 text-white font-semibold hover:bg-black flex items-center gap-2">
              <span id="btn-text">Register</span>
              <span id="loading-spinner" class="hidden h-4 w-4 rounded-full border-2 border-white border-t-transparent animate-spin"></span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    function registerForm(){
      return {
        step: 1,
        type: '',
        disableOnSubmit(e){
          const form = e.target;
          const btn = form.querySelector('#submit-btn-tenant') || form.querySelector('#submit-btn-investor');
          if(!btn) return;
          const text = btn.querySelector('#btn-text');
          const spinner = btn.querySelector('#loading-spinner');
          if(text && spinner){ text.classList.add('hidden'); spinner.classList.remove('hidden'); }
          btn.disabled = true;
        },
        scrollToTop(){ window.scrollTo({top:0, behavior:'smooth'}); }
      }
    }
  </script>
</body>
</html>
