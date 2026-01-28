<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Create Property</title>

  <!-- Tailwind -->
  <script src="https://cdn.tailwindcss.com"></script>

  <style>
    body {
      background: linear-gradient(135deg, #f4f6f8, #eaeaea);
    }

    /* ===============================
       🍎 SKELETON LOADER
    =============================== */
    .skeleton {
      position: relative;
      overflow: hidden;
      background: #e5e7eb;
      border-radius: 12px;
    }

    .skeleton::after {
      content: "";
      position: absolute;
      inset: 0;
      transform: translateX(-100%);
      background: linear-gradient(
        90deg,
        transparent,
        rgba(255,255,255,0.6),
        transparent
      );
      animation: shimmer 1.2s infinite;
    }

    @keyframes shimmer {
      100% {
        transform: translateX(100%);
      }
    }
  </style>
</head>

<body class="min-h-screen flex items-center justify-center p-6">

  <!-- PAGE WRAPPER -->
  <div class="w-full max-w-3xl">

    <!-- LOGO (OUTSIDE CARD) -->
    <div class="flex justify-center mb-6">
      <img src="/logo.webp"
           alt="Logo"
           class="h-14 drop-shadow-sm">
    </div>

    <!-- CARD -->
    <div id="contentCard"
         class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 overflow-hidden hidden">

      <!-- HEADER -->
      <div class="px-6 py-5 bg-[#122b50]">
        <h1 class="text-xl font-bold text-white">
          Create Building / Property
        </h1>
        <p class="text-sm text-blue-100 mt-1">
          Add your first property to start managing units and tenants.
        </p>
      </div>

      <!-- FORM -->
      <form
        method="POST"
        action="{{ route('properties.store') }}"
        enctype="multipart/form-data"
        class="p-6 space-y-5"
      >
        @csrf

        <!-- PROPERTY NAME -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Property Name *
          </label>
          <input
            type="text"
            name="property_name"
            value="{{ old('property_name') }}"
            required
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm
                   focus:outline-none focus:ring-2 focus:ring-[#122b50]"
            placeholder="e.g. Sunset Apartments"
          >
          @error('property_name')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
          @enderror
        </div>

        <!-- ADDRESS -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Address
          </label>
          <input
            type="text"
            name="address"
            value="{{ old('address') }}"
            class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
            placeholder="Plot No, Area, City"
          >
        </div>

        <!-- CONTACT + EMAIL -->
        <div class="grid md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
              Contact Phone
            </label>
            <input
              type="text"
              name="property_contact"
              value="{{ old('property_contact') }}"
              class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
              placeholder="+260 97..."
            >
          </div>

          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">
              Contact Email
            </label>
            <input
              type="email"
              name="property_email"
              value="{{ old('property_email') }}"
              class="w-full rounded-xl border border-gray-300 px-4 py-3 text-sm"
              placeholder="info@property.com"
            >
          </div>
        </div>

        <!-- LOGO -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">
            Property Logo
          </label>
          <input
            type="file"
            name="logo"
            accept="image/*"
            class="w-full text-sm file:mr-4 file:py-2 file:px-4
                   file:rounded-lg file:border-0
                   file:bg-gray-100 file:text-gray-700
                   hover:file:bg-gray-200"
          >
        </div>

        <!-- ACTIONS -->
        <div class="flex justify-end gap-3 pt-4 border-t">
          <a
            href="{{ url()->previous() }}"
            class="px-5 py-2.5 rounded-xl bg-gray-100 text-gray-700 font-semibold"
          >
            Cancel
          </a>

          <button
            type="submit"
            class="px-6 py-2.5 rounded-xl bg-[#122b50] text-white font-semibold
                   hover:bg-[#0e203f]"
          >
            Create Property
          </button>
        </div>

      </form>
    </div>

    <!-- SKELETON (INITIAL LOAD) -->
    <div id="skeletonCard"
         class="bg-white rounded-2xl shadow-xl ring-1 ring-black/5 p-6 space-y-4">

      <div class="skeleton h-6 w-1/2"></div>
      <div class="skeleton h-4 w-3/4"></div>

      <div class="skeleton h-12 w-full mt-4"></div>
      <div class="skeleton h-12 w-full"></div>
      <div class="skeleton h-12 w-full"></div>

      <div class="flex justify-end gap-3 pt-4">
        <div class="skeleton h-10 w-24"></div>
        <div class="skeleton h-10 w-32"></div>
      </div>
    </div>

  </div>

  <!-- JS: SKELETON TO CONTENT -->
  <script>
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('skeletonCard').style.display = 'none';
        document.getElementById('contentCard').classList.remove('hidden');
      }, 600); // subtle Apple-like delay
    });
  </script>

</body>
</html>
