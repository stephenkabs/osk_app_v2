<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>{{ $title ?? 'Rent App' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="/assets/images/favicon.png">
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" />
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" />

    @stack('styles')
</head>

<body data-sidebar="dark">

    {{-- Global Loader --}}
    {{-- @include('includes.preloader') --}}

    <div id="layout-wrapper">

        {{-- Top Header --}}
        @include('includes.header')

        {{-- Sidebar --}}
        @include('includes.sidebar')

        {{-- Validation Alerts --}}
        {{-- @include('includes.validation') --}}

        <!-- MAIN CONTENT -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    {{-- Inject page content --}}
                    @yield('content')

                </div>
            </div>

            {{-- @include('includes.footer') --}}
        </div>

    </div>

    <div class="rightbar-overlay"></div>

    <!-- JS -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/node-waves/waves.min.js"></script>
    <script src="/assets/js/app.js"></script>

    @stack('scripts')
  @include('includes.modals.success')
    @include('includes.modals.error')
<script>
document.addEventListener("DOMContentLoaded", () => {

    const spinner = document.querySelector(".header-spinner");
    const topLoader = document.getElementById("top-loader");
    const favicon = document.querySelector("link[rel='icon']");

    let originalFavicon = favicon?.href;
    let loadingFavicon = "/spinner.ico"; // 👈 create a small spinner favicon

    function startLoader() {
        spinner?.classList.remove("d-none");
        topLoader.style.width = "80%";

        // Change browser tab icon
        if (favicon && loadingFavicon) {
            favicon.href = loadingFavicon;
        }
    }

    function stopLoader() {
        topLoader.style.width = "100%";

        setTimeout(() => {
            spinner?.classList.add("d-none");
            topLoader.style.width = "0%";

            if (favicon && originalFavicon) {
                favicon.href = originalFavicon;
            }
        }, 400);
    }

    /* Page load */
    startLoader();
    window.addEventListener("load", stopLoader);

    /* All links */
    document.querySelectorAll("a:not([target='_blank'])").forEach(link => {
        link.addEventListener("click", e => {
            if (link.href && !link.href.includes("#")) {
                startLoader();
            }
        });
    });

    /* All forms */
    document.querySelectorAll("form").forEach(form => {
        form.addEventListener("submit", () => {
            startLoader();
        });
    });

    /* Optional: expose globally for AJAX */
    window.startGlobalLoader = startLoader;
    window.stopGlobalLoader = stopLoader;
});
</script>
</body>

</html>
