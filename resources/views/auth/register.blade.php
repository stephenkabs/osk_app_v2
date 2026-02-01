<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Tenant Onboarding • Property Management</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
</head>

<body class="min-h-screen bg-gray-100 flex items-start justify-center p-6">

  <div class="w-full max-w-5xl">

    <!-- TOP IMAGE CARD -->
    <div class="relative rounded-2xl overflow-hidden shadow-lg mb-8">
      <img src="/6.webp"
           alt="Tenant Onboarding"
           class="w-full h-[280px] object-cover">

      <div class="absolute inset-0 bg-black/55"></div>

      <div class="absolute inset-0 flex items-center justify-center text-center px-6">
        <div>
          <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-3">
            Tenant Onboarding
          </h1>
          <p class="text-gray-200 max-w-xl mx-auto text-sm leading-relaxed">
            To ensure safety, trust, and proper lease management, all tenants
            must complete identity verification before accessing their accounts.
          </p>
        </div>
      </div>
    </div>

    <!-- INFO CARDS -->
    <div class="grid md:grid-cols-2 gap-6">

      <!-- CARD 1: TENANT VERIFICATION -->
      <div class="bg-white rounded-2xl shadow-md ring-1 ring-black/5 p-6 md:p-8">

        <div class="flex items-center gap-3 mb-4">
          <div class="h-10 w-10 rounded-xl bg-red-100 text-red-700 flex items-center justify-center">
            <i class="fas fa-user-check"></i>
          </div>
          <h2 class="text-xl font-bold text-gray-900">
            Tenant Verification (KYC)
          </h2>
        </div>

        <p class="text-sm text-gray-600 leading-relaxed mb-4">
          All tenants are required to complete a
          <strong>Know Your Customer (KYC)</strong> process.
          This helps protect landlords, property managers, and fellow tenants
          by ensuring accurate and verified records.
        </p>

        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 mb-4">
          Tenant registration is <strong>reviewed manually</strong>.
          Your account will be activated only after successful verification.
        </div>

        <h4 class="text-sm font-semibold text-gray-900 mb-2">
          Information Required from Tenants
        </h4>

        <ul class="space-y-2 text-sm text-gray-700">
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-red-600 mt-1"></i>
            Full legal name and contact details
          </li>
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-red-600 mt-1"></i>
            National ID (NRC) or Passport number
          </li>
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-red-600 mt-1"></i>
            Residential address and location
          </li>
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-red-600 mt-1"></i>
            Emergency contact information
          </li>
        </ul>
      </div>

      <!-- CARD 2: ACCOUNT & LEASE ACCESS -->
      <div class="bg-white rounded-2xl shadow-md ring-1 ring-black/5 p-6 md:p-8">

        <div class="flex items-center gap-3 mb-4">
          <div class="h-10 w-10 rounded-xl bg-gray-900 text-white flex items-center justify-center">
            <i class="fas fa-key"></i>
          </div>
          <h2 class="text-xl font-bold text-gray-900">
            Account Activation & Lease Access
          </h2>
        </div>

        <p class="text-sm text-gray-600 leading-relaxed mb-4">
          Once your tenant profile is approved, your account will be activated
          and linked to your assigned unit or lease agreement.
        </p>

        <h4 class="text-sm font-semibold text-gray-900 mb-2">
          After Approval
        </h4>

        <ul class="space-y-2 text-sm text-gray-700 mb-4">
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-gray-900 mt-1"></i>
            Secure tenant login access
          </li>
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-gray-900 mt-1"></i>
            Lease agreement viewing and signing
          </li>
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-gray-900 mt-1"></i>
            Rent payment history and billing records
          </li>
          <li class="flex gap-2">
            <i class="fas fa-check-circle text-gray-900 mt-1"></i>
            Maintenance requests and communication tools
          </li>
        </ul>

        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 text-sm text-gray-700">
          <strong>Why Verification Matters:</strong><br>
          Tenant verification helps prevent fraud, ensures accurate records,
          and builds trust between tenants, landlords, and property managers.
        </div>
      </div>

    </div>

    <!-- ACTIONS -->
    <div class="mt-8 flex flex-wrap gap-3 justify-center">
      <a href="/contact"
         class="rounded-xl bg-red-700 px-6 py-3 text-white text-sm font-semibold hover:bg-red-800 transition">
        Contact Property Management
      </a>

      <a href="/login"
         class="rounded-xl border border-gray-300 px-6 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
        Back to Login
      </a>
    </div>

    <!-- FOOTNOTE -->
    <p class="mt-6 text-center text-xs text-gray-500">
      Tenant information is handled securely and used strictly for verification,
      leasing, and property management purposes.
    </p>

  </div>

</body>
</html>
