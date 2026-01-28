<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Create Property</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <style>
      :root {
        --card-bg: #fff;
        --border: #e6e8ef;
        --radius: 14px;
        --shadow: 0 8px 20px rgba(0,0,0,0.06);
        --ink: #0b0c0f;
        --muted: #5b5f6b;
        --ring: #0071e3;
      }

      .apple-card {
        background:var(--card-bg);
        border-radius:var(--radius);
        padding:28px;
        box-shadow:var(--shadow);
        border:1px solid var(--border);
      }

      .apple-header {
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        margin-bottom:24px;
      }

      .apple-title {
        font-weight:800;
        font-size:22px;
        margin:0;
        letter-spacing:-0.02em;
        color:var(--ink);
      }

      .apple-sub {
        color:var(--muted);
        font-weight:600;
        text-transform:uppercase;
        font-size:11px;
      }

      .apple-card label {
        font-weight:700;
        font-size:13px;
        margin-bottom:6px;
        color:var(--ink);
      }

      .apple-card .form-control {
        border-radius:12px;
        border:1px solid var(--border);
        padding:10px 12px;
        font-weight:600;
        transition:border-color .2s, box-shadow .2s;
      }
      .apple-card .form-control:focus {
        border-color:var(--ring);
        box-shadow:0 0 0 3px rgba(0,113,227,0.2);
      }

      .apple-card button.btn-primary {
        border-radius:12px;
        font-weight:700;
        padding:10px 20px;
        background:var(--ink);
        border:none;
        transition:transform .06s, box-shadow .2s;
      }
      .apple-card button.btn-primary:hover {
        transform:translateY(-1px);
        box-shadow:0 6px 14px rgba(0,0,0,0.1);
      }

      #map {
        border-radius:14px;
        border:1px solid var(--border);
        box-shadow:inset 0 2px 6px rgba(0,0,0,0.05);
      }
    </style>
</head>

<body data-sidebar="dark">
@include('includes.preloader')

<div id="layout-wrapper">
    @include('includes.header')
    @include('includes.sidebar')
    @include('includes.validation')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-lg-9">
                        <div class="apple-card">
                          <div class="apple-header">
                            <h1 class="apple-title">Create New Property</h1>
                            <span class="apple-sub">App • Properties</span>
                          </div>

                          <form action="{{ route('properties.store') }}" method="POST" enctype="multipart/form-data">
                              @csrf
                              @include('properties._form')
                          </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- Scripts -->
<script src="/assets/libs/jquery/jquery.min.js"></script>
<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/libs/metismenu/metisMenu.min.js"></script>
<script src="/assets/libs/simplebar/simplebar.min.js"></script>
<script src="/assets/libs/node-waves/waves.min.js"></script>
<script src="/assets/libs/parsleyjs/parsley.min.js"></script>
<script src="/assets/js/pages/form-validation.init.js"></script>
<script src="/assets/js/app.js"></script>

@stack('scripts')
</body>
</html>
