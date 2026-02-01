<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Forgot Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="shortcut icon" href="/assets/images/favicon.png">
</head>

<body class="min-h-screen bg-[#e5e5e5] flex items-center justify-center p-4 relative">

{{-- 🍎 Skeleton --}}
<div id="skeleton" class="fixed inset-0 z-50 flex items-center justify-center bg-[#e5e5e5]">
  <div class="w-full max-w-[850px] bg-white rounded-2xl shadow-2xl overflow-hidden flex animate-pulse">
    <div class="hidden md:block md:w-1/2 bg-gray-300"></div>
    <div class="w-full md:w-1/2 p-10 space-y-5">
      <div class="h-10 w-40 mx-auto bg-gray-300 rounded"></div>
      <div class="h-12 bg-gray-300 rounded"></div>
      <div class="h-12 bg-gray-400 rounded"></div>
    </div>
  </div>
</div>

{{-- Card --}}
<div class="w-full max-w-[850px] bg-white/95 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden ring-1 ring-black/10 flex flex-col md:flex-row">

  {{-- LEFT IMAGE --}}
  <div class="hidden md:block md:w-1/2 relative">
    <img src="/6.webp" class="h-[500px] w-full object-cover">
    <div class="absolute inset-0 bg-black/35"></div>
    <div class="absolute bottom-0 p-8 text-white">
      <h2 class="text-2xl font-bold">Reset Access</h2>
      <p class="text-sm text-white/90 mt-2">We’ll help you get back in</p>
    </div>
  </div>

  {{-- FORM --}}
  <div class="w-full md:w-1/2 p-8 md:p-10">
    <div class="mb-6 text-center">
      <img src="/logo.webp" class="mx-auto" width="180">
    </div>

    <p class="text-sm text-gray-600 mb-4 text-center">
      Enter your email and we’ll send you a password reset link.
    </p>

    @if(session('status'))
      <div class="mb-3 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-2 text-sm text-emerald-700">
        {{ session('status') }}
      </div>
    @endif

    @if($errors->has('email'))
      <div class="mb-3 rounded-lg bg-rose-50 border border-rose-200 px-3 py-2 text-sm text-rose-700">
        {{ $errors->first('email') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
      @csrf

      <input type="email" name="email" required
        placeholder="Email address"
        class="w-full rounded-xl border px-4 py-3 text-sm focus:ring-2 focus:ring-[#155012]/80">

      <button class="w-full rounded-xl bg-[#097204] py-3 text-sm font-semibold text-white hover:bg-[#0b5a08]">
        Send Reset Link
      </button>
    </form>

    <div class="mt-5 text-center">
      <a href="{{ route('login') }}" class="text-sm font-semibold text-[#155012] hover:underline">
        Back to login
      </a>
    </div>
  </div>
</div>

<script>
window.addEventListener('load', () => {
  const s = document.getElementById('skeleton');
  setTimeout(() => {
    s.style.opacity = '0';
    setTimeout(() => s.remove(), 350);
  }, 700);
});
</script>

</body>
</html>
