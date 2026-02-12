<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
      <link rel="shortcut icon" href="/assets/images/favicon.png">
</head>

<body class="min-h-screen bg-[#e5e5e5] flex items-center justify-center p-4 pb-16 relative">
{{--
<!-- Preloader (Red Spinner) -->
<div id="preloader"
     class="fixed inset-0 z-50 flex items-center justify-center bg-[#e5e5e5] transition-opacity duration-300">

  <div class="h-12 w-12 rounded-full
              border-4
              border-red-700/30
              border-t-red-700
              animate-spin">
  </div>

</div>


  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        const p = document.getElementById('preloader');
        p.style.opacity = '0';
        setTimeout(()=> p.style.display='none', 300);
      }, 800);
    });
  </script> --}}

  <!-- 🍎 Skeleton Preloader -->
<div id="skeleton"
     class="fixed inset-0 z-50 flex items-center justify-center bg-[#e5e5e5]">

  <div class="w-full max-w-[850px] bg-white/95 rounded-2xl shadow-2xl overflow-hidden
              ring-1 ring-black/10 flex flex-col md:flex-row animate-pulse">

    <!-- Left Image Skeleton -->
    <div class="hidden md:block md:w-1/2 bg-gray-300 relative">
      <div class="absolute inset-0 shimmer"></div>
    </div>

    <!-- Right Form Skeleton -->
    <div class="w-full md:w-1/2 p-8 md:p-10 space-y-5">

      <!-- Logo -->
      <div class="h-10 w-40 mx-auto rounded-lg bg-gray-300 shimmer"></div>

      <!-- Alerts -->
      <div class="h-10 rounded-xl bg-gray-200 shimmer"></div>

      <!-- Inputs -->
      <div class="h-12 rounded-xl bg-gray-300 shimmer"></div>
      <div class="h-12 rounded-xl bg-gray-300 shimmer"></div>

      <!-- Button -->
      <div class="h-12 rounded-xl bg-gray-400 shimmer"></div>

      <!-- Footer -->
      <div class="h-4 w-48 mx-auto rounded bg-gray-200 shimmer mt-6"></div>

    </div>
  </div>
</div>
<style>
/* Apple-like shimmer */
.shimmer{
  position: relative;
  overflow: hidden;
}
.shimmer::after{
  content:"";
  position:absolute;
  inset:0;
  background: linear-gradient(
    110deg,
    rgba(255,255,255,0) 30%,
    rgba(255,255,255,.55) 45%,
    rgba(255,255,255,0) 60%
  );
  animation: shimmer 1.4s infinite;
}

@keyframes shimmer{
  from { transform: translateX(-100%); }
  to   { transform: translateX(100%); }
}
</style>


  <!-- Card -->
  <div class="w-full max-w-[850px] bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/10 flex flex-col md:flex-row">

    <!-- Left Image Section -->
{{-- LEFT IMAGE SECTION --}}
<style>/* ===============================
   LOGIN HERO IMAGE CONTROL
================================ */
/* ===============================
   LOGIN HERO – MATCH CARD HEIGHT
================================ */
.login-hero {
    height: auto;        /* 👈 let card decide */
    overflow: hidden;
}

/* Image fills ONLY the card height */
.login-hero-img {
    width: 100%;
    height: 500px;
    object-fit: cover;      /* crop */
    object-position: top;   /* keep face visible */
}


</style>
<!-- Left Image Section -->
<div class="hidden md:block md:w-1/2 relative login-hero">

    <img src="{{ $hero && $hero->image
            ? Storage::disk('spaces')->url($hero->image)
            : '/placeholder.webp' }}"
         class="login-hero-img"
         alt="Login Illustration">

    <div class="absolute inset-0 bg-black/35"></div>

    <div class="absolute bottom-0 left-0 p-8 text-white">
        <h2 class="text-2xl font-bold leading-tight">
            {{ $hero->title ?? 'OSK App' }}
        </h2>

        <p class="mt-2 text-sm text-white/90 max-w-sm">
            {{ $hero->text ?? 'Secure. Fast. Reliable.' }}
        </p>

        <div class="mt-4 text-xs uppercase tracking-widest text-white/70">
            {{ $hero->tagline ?? 'Built for professionals' }}
        </div>
    </div>
</div>



    <!-- Right Form Section -->
    <div class="w-full md:w-1/2 p-8 md:p-10">
      <!-- Logo -->
      <div class="mb-6 text-center">
        <img src="/logo.webp" alt="Logo" class="mx-auto" width="190">
      </div>

      <!-- Alerts -->
      @if(session('status'))
        <div class="mb-3 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-sm text-emerald-700">
          {{ session('status') }}
        </div>
      @endif

      @if($errors->has('email'))
        <div class="mb-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-sm text-rose-700">
          {{ $errors->first('email') }}
        </div>
      @endif

      <!-- Form -->
      <form method="POST" action="{{ route('login') }}" onsubmit="showLoading(event)" class="space-y-4">
        @csrf

        <input type="email" name="email" placeholder="Email"
          class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium
          placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#353535]/80 focus:border-[#353535]" required>

        <input type="password" name="password" placeholder="Password"
          class="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm font-medium
          placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#353535]/80 focus:border-[#353535]" required>

        <button id="submit-btn" type="submit"
          class="w-full flex items-center justify-center gap-2 rounded-xl bg-[#353535] px-4 py-3 text-[15px] font-semibold text-white
          hover:bg-[#353535] transition disabled:opacity-60 disabled:cursor-not-allowed">
          <span id="btn-text">Login</span>
          <span id="loading-spinner" class="hidden h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
        </button>

        <input type="hidden" name="redirect_uri" value="{{ request('redirect_uri') }}">
        <input type="hidden" name="state" value="{{ request('state') }}">
      </form>
      <div class="mt-5 flex items-center justify-between text-sm">

    {{-- Forgot Password --}}
    @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}"
           class="font-medium text-[#353535] hover:text-[#191919] transition">
            Forgot password?
        </a>
    @endif

    {{-- Register --}}
    @if (Route::has('register'))
        <a href="{{ route('register') }}"
           class="font-semibold text-[#353535] hover:text-[#191919] transition">
            Don’t have an account?
            <span class="underline underline-offset-4">Register</span>
        </a>
    @endif

</div>


      <p class="mt-6 text-center text-[11px] text-gray-500">
        Powered by <span class="font-semibold">Neurasoft Technologies</span>
      </p>
    </div>
  </div>

  <!-- Submit Loader -->
  {{-- <script>
    function showLoading(e){
      e.preventDefault();
      const btn = document.getElementById('submit-btn');
      document.getElementById('btn-text').classList.add('hidden');
      document.getElementById('loading-spinner').classList.remove('hidden');
      btn.disabled = true;
      setTimeout(()=> e.target.submit(), 300);
    }
  </script>


 --}}

<script>
window.addEventListener('load', () => {
  const s = document.getElementById('skeleton');
  setTimeout(() => {
    s.style.opacity = '0';
    s.style.transition = 'opacity .35s ease';
    setTimeout(() => s.remove(), 350);
  }, 700); // Apple-like slight delay
});
</script>

</body>
</html>
