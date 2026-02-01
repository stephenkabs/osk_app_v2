<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OneSquareK</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome (for install icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <!-- PWA Meta -->
    {{-- @include('laravelpwa::meta', ['config' => config('laravelpwa.manifest')]) --}}

    <style>
        body {
            background: linear-gradient(20deg, #2d2d2d, #111111);
            font-family: 'Segoe UI', system-ui, sans-serif;
        }

        #preloader {
            transition: opacity 0.4s ease;
        }

        .install-btn {
            display: none;
            align-items: center;
            gap: 8px;
            background: #d8d8d8;
            color: #242424;
            border: none;
            padding: 12px 22px;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all .2s ease;
        }

        .install-btn:hover {
            background: #16a34a;
            transform: translateY(-1px);
        }

        .install-btn:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }
    </style>
</head>

<body class="text-white min-h-screen flex flex-col items-center justify-center">

<!-- PRELOADER -->
<div id="preloader" class="fixed inset-0 z-50 flex items-center justify-center bg-white">
    <div class="animate-spin rounded-full h-14 w-14 border-4 border-black border-t-transparent"></div>
</div>

<!-- MAIN CARD -->
<div class="flex flex-col md:flex-row bg-black bg-opacity-40 max-w-4xl w-full mx-auto rounded-2xl overflow-hidden shadow-xl border border-white border-opacity-10">

    <!-- IMAGE -->
    <div class="w-full md:w-1/2 h-56 md:h-auto">
        <img src="/placeholder.webp"
             alt="OneSquareK Housing"
             class="object-cover h-full w-full">
    </div>

    <!-- CONTENT -->
    <div class="w-full md:w-1/2 flex flex-col justify-center p-6 md:p-10 bg-white text-gray-900 space-y-6">

<img src="/welcome/logo.webp"
     alt="OneSquareK Logo"
     class="h-auto max-w-[140px] md:max-w-[180px] mx-auto md:mx-0">


        <div class="space-y-3 text-center md:text-left">
            <h1 class="text-xl md:text-2xl font-bold tracking-tight">
                Compact Housing for Zambia’s Youth
            </h1>

            <p class="text-sm md:text-base text-gray-600 leading-relaxed">
                Founded in <strong>2015</strong>, OneSquareK is a Lusaka-headquartered
                property developer focused on the construction, ownership, and
                operation of <strong>affordable compact housing solutions</strong>
                for young people.
            </p>
        </div>

        <a href="{{ route('login', ['redirect_uri' => '/dashboard', 'state' => Str::random(32)]) }}"
           class="w-full md:w-48 text-center bg-black text-white py-3 rounded-full text-sm font-semibold hover:bg-gray-900 transition">
            Login to Platform
        </a>

    </div>
</div>

<!-- FOOTER -->
<div class="text-center mt-10 max-w-xl px-6 space-y-4">

    <h2 class="text-sm md:text-base font-semibold tracking-wide">
        OneSquareK Cloud Investment & Tenant Platform
    </h2>

    <p class="text-xs md:text-sm text-gray-300 leading-relaxed">
        OneSquareK (OSK) addresses the growing need for
        <strong>high-quality yet affordable accommodation</strong>
        for thousands of young people living and working in Lusaka.
    </p>

    <div class="flex justify-center">
        <button id="installAppBtn" class="install-btn">
            <i class="fa-solid fa-download"></i> Install App
        </button>
    </div>

    <hr class="my-4 border-white border-opacity-20">

    <p class="text-[10px] font-medium text-gray-400">
        Powered by Neurasofts Technologies · Version 1.0.0
    </p>
</div>

<!-- PRELOADER SCRIPT -->
<script>
    window.addEventListener('load', () => {
        const preloader = document.getElementById('preloader');
        setTimeout(() => {
            preloader.style.opacity = '0';
            setTimeout(() => preloader.style.display = 'none', 400);
        }, 1200);
    });
</script>

<!-- PWA INSTALL SCRIPT -->
{{-- <script>
    let deferredPrompt;
    const installBtn = document.getElementById('installAppBtn');
    installBtn.style.display = 'none';

    const isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);

    if (isSafari) {
        installBtn.style.display = 'inline-flex';
        installBtn.innerHTML = "<i class='fa-solid fa-plus'></i> Add to Home Screen";

        installBtn.addEventListener('click', () => {
            alert(
                "To install this app:\n\n" +
                "1. Tap the Share button (⬆️)\n" +
                "2. Select 'Add to Home Screen'"
            );
        });
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        installBtn.style.display = 'inline-flex';

        installBtn.addEventListener('click', async () => {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;

            if (outcome === 'accepted') {
                installBtn.innerHTML = "<i class='fa-solid fa-check'></i> Installed";
                installBtn.disabled = true;
            }

            deferredPrompt = null;
        });
    });

    window.addEventListener('appinstalled', () => {
        installBtn.innerHTML = "<i class='fa-solid fa-check'></i> Installed";
        installBtn.disabled = true;
    });
</script> --}}

<script>
let deferredPrompt;
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
});
function installApp() {
    if (deferredPrompt) {
        deferredPrompt.prompt();
        deferredPrompt.userChoice.then(() => deferredPrompt = null);
    }
}
</script>

<script>
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js');
    });
}
</script>

</body>
</html>
