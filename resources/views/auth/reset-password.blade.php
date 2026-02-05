<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#e5e5e5] flex items-center justify-center p-4">

<div class="w-full max-w-[520px] bg-white/95 rounded-2xl shadow-2xl p-8">

  <div class="mb-6 text-center">
    <img src="/logo.webp" class="mx-auto" width="160">
  </div>

  <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <input type="email" name="email" required
      value="{{ old('email', $request->email) }}"
      placeholder="Email"
      class="w-full rounded-xl border px-4 py-3 text-sm">

    <input type="password" name="password" required
      placeholder="New password"
      class="w-full rounded-xl border px-4 py-3 text-sm">

    <input type="password" name="password_confirmation" required
      placeholder="Confirm password"
      class="w-full rounded-xl border px-4 py-3 text-sm">

    <button class="w-full rounded-xl bg-[#272727] py-3 text-sm font-semibold text-white">
      Reset Password
    </button>
  </form>
</div>

</body>
</html>
