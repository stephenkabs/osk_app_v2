
<header id="page-topbar" style="background: linear-gradient(to right, #ffffff, #ffffff); box-shadow: 0 1px 1px rgba(0,0,0,0.1);">

    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
<div class="navbar-brand-box-co">
    <a href="/dashboard" class="logo logo-dark">

        <span class="logo-lg">
            <img src="/logo.webp" alt="" height="40">
        </span>
    </a>



    <a href="/dashboard" class="logo logo-light">
        <span class="logo-lg">
            <img src="/logo.webp" alt="" height="40">
        </span>
    </a>

</div>

<style>
.navbar-brand-box-co {
    background-color: #ffffff; /* solid black */
    display: flex;
    align-items: center;
    padding: 0 16px;
    padding-left: 24px; /* move logo to the right */
}

.logo-lg img {
    display: block;
    height: 50px;
}
</style>

<style>
/* ===============================
   HEADER SPINNER
================================ */
.header-spinner {
    width: 18px;
    height: 18px;
    border: 2px solid rgba(255, 0, 0, 0.15);
    border-top-color: #ff0000;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    display: inline-block;
}

/* ===============================
   TOP LOADING BAR
================================ */
#top-loader {
    position: fixed;
    top: 0;
    left: 0;
    height: 3px;
    width: 0%;
    background: linear-gradient(90deg, #00a6ff, #043a5e);
    z-index: 9999;
    transition: width 0.25s ease;
}

/* ===============================
   ANIMATION
================================ */
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>



            <div class="d-none d-sm-block ms-2">
                {{-- <h4 class="page-title font-size-18">{{ $user->company_name }} Dashboard</h4> --}}
            </div>

        </div>



        <div class="d-flex">


            <div class="dropdown d-none d-md-block ms-2">
                <button type="button" class="btn header-item waves-effect" data-bs-toggle="modal" data-bs-target="#lockScreenModal"
                    aria-haspopup="true" aria-expanded="false">
                    {{-- <img class="me-2" src="/assets/images/flags/us_flag.jpg" alt="Header Language" height="16"> English --}}
                    {{-- <span class="mdi mdi-chevron-down"></span> --}}
                </button>

            </div>



            <div class="dropdown d-inline-block ms-2">
                <!-- TOP GLOBAL LOADER BAR -->
<div id="top-loader"></div>

<!-- GLOBAL LOADER ICON (Header) -->
<div class="d-inline-block ms-2">
    <button type="button"
            class="btn header-item waves-effect"
            id="globalLoaderBtn"
            style="pointer-events:none;">
        <span class="header-spinner d-none"></span>
    </button>
</div>

                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                {{-- <h5 class="m-0 font-size-16"> Notification ({{ number_format($document->count() ) }}) </h5> --}}
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <a href="" class="text-reset notification-item">
                            <div class="media d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-success rounded-circle font-size-16">
                                        <i class="mdi mdi-message-text-outline"></i>
                                    </span>
                                </div>
                                {{-- @if ($document->count() != 0) --}}


                                <div class="flex-1">
                                    {{-- <h6 class="mt-0 font-size-15 mb-1">({{ number_format($document->count() ) }})New Mail received</h6> --}}
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">New mail has been created Successfully</p>
                                    </div>
                                </div>
                                {{-- @endif --}}
                            </div>
                        </a>



                        <a href="" class="text-reset notification-item">
                            <div class="media d-flex">
                                <div class="avatar-xs me-3">
                                    <span class="avatar-title bg-warning rounded-circle font-size-16">
                                        <i class="fas fa-smile"></i>
                                    </span>
                                </div>
                                {{-- @if ($users->count() != 0) --}}


                                <div class="flex-1">
                                    {{-- <h6 class="mt-0 font-size-15 mb-1">({{ number_format($users->count() ) }})New User Registered</h6> --}}
                                    <div class="font-size-12 text-muted">
                                        <p class="mb-1">New User has been created Successfully</p>
                                    </div>
                                </div>
                                {{-- @endif --}}
                            </div>
                        </a>

                    </div>
                    <div class="p-2 border-top text-center">
                        <a class="btn btn-sm btn-link font-size-14 w-100" href="javascript:void(0)">
                            View all
                        </a>
                    </div>
                </div>
            </div>


        </div>
    </div>


</header>
