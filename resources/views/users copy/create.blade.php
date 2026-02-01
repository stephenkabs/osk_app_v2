<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Create User • {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="/assets/css/app.min.css" rel="stylesheet"/>

    <!-- Icons -->
    <link href="/assets/css/icons.min.css" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <style>
        :root{
            --card:#fff; --ink:#0b0c0f; --muted:#5b5f6b; --ring:#0071e3;
            --border:#e6e8ef; --radius:14px;
        }

        body{
            font-family:-apple-system,BlinkMacSystemFont,"SF Pro Text",
            "Segoe UI",Roboto,Helvetica,Arial,sans-serif;
        }

        .apple-card{
            background:var(--card);
            border-radius:var(--radius);
            padding:24px;
            box-shadow:
                0 20px 50px rgba(0,0,0,.12),
                0 8px 20px rgba(0,0,0,.08);
        }

        .apple-header{
            display:flex;
            justify-content:space-between;
            margin-bottom:18px;
        }

        .apple-title{
            font-weight:800;
            font-size:20px;
            margin:0;
        }

        .apple-sub{
            color:var(--muted);
            font-size:11px;
            text-transform:uppercase;
            font-weight:600;
        }

        .grid{ display:grid; gap:14px }
        @media(min-width:720px){ .grid-2{ grid-template-columns:1fr 1fr } }

        label{ font-weight:700; font-size:13px }

        .af-input,.af-select{
            width:100%;
            border:1px solid var(--border);
            border-radius:12px;
            padding:10px 12px;
            font-weight:600;
            transition:.2s;
        }

        .af-input:focus,.af-select:focus{
            border-color:var(--ring);
            box-shadow:0 0 0 3px color-mix(in srgb, var(--ring) 18%, transparent);
            outline:none;
        }

        .af-btn{
            background:var(--ink);
            color:#fff;
            border:none;
            padding:10px 18px;
            border-radius:12px;
            font-weight:800;
            display:inline-flex;
            align-items:center;
            gap:8px;
            transition:.2s;
        }
        .af-btn.secondary{ background:#5b5f6b }
        .af-btn:hover{ transform:translateY(-1px); background:#000 }

        /* Tabs */
        .apple-tabs .nav-link{
            border-radius:999px;
            font-weight:700;
            font-size:13px;
            padding:8px 14px;
            color:#5b5f6b;
        }
        .apple-tabs .nav-link.active{
            background:#0b0c0f;
            color:#fff;
        }

        /* Skeleton */
        .skeleton{
            background:linear-gradient(
                90deg,#f1f2f6 25%,#e6e8ef 37%,#f1f2f6 63%);
            background-size:400% 100%;
            animation:shimmer 1.4s ease infinite;
            border-radius:12px;
        }
        .sk-title{height:22px;width:180px}
        .sk-input{height:44px}
        .sk-tab{height:32px;width:90px;border-radius:999px}
        .sk-btn{height:40px;width:140px}

        @keyframes shimmer{
            0%{background-position:100% 0}
            100%{background-position:-100% 0}
        }
    </style>
</head>

<body data-sidebar="dark">


@include('includes.header')
@include('includes.sidebar')

<div class="main-content">
<div class="page-content">
<div class="container-fluid">

<div class="col-lg-8 mx-auto">

    <!-- ================= SKELETON ================= -->
    <div id="createUserSkeleton" class="apple-card">
        <div class="sk-title skeleton mb-2"></div>
        <div class="skeleton sk-input mb-3"></div>

        <div class="d-flex gap-2 mb-4">
            <div class="sk-tab skeleton"></div>
            <div class="sk-tab skeleton"></div>
            <div class="sk-tab skeleton"></div>
            <div class="sk-tab skeleton"></div>
        </div>

        <div class="grid grid-2 mb-3">
            <div class="sk-input skeleton"></div>
            <div class="sk-input skeleton"></div>
        </div>

        <div class="sk-btn skeleton"></div>
    </div>

    <!-- ================= CONTENT ================= -->
    <div id="createUserContent" class="d-none apple-card">

        <div class="apple-header">
            <div>
                <h1 class="apple-title">Create User</h1>
                <div class="apple-sub">Administration • User Manager</div>
            </div>
        </div>

        <!-- Tabs -->
        <ul class="nav nav-pills apple-tabs mb-4">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-profile">Profile</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-org">Organization</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-access">Access</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-security">Security</button></li>
        </ul>

        <form action="{{ route('user.store') }}" method="POST">
            @csrf

            <div class="tab-content">

                <!-- PROFILE -->
                <div class="tab-pane fade show active" id="tab-profile">
                    <div class="grid grid-2">
                        <div>
                            <label>Full Names</label>
                            <input class="af-input" name="name" required placeholder="Enter Full Names">
                        </div>
                        <div>
                            <label>Address</label>
                            <input class="af-input" name="address" required placeholder="Enter Address">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Email</label>
                        <input class="af-input" name="email" type="email" required placeholder="Enter Email">
                    </div>
                </div>

                <!-- ORGANIZATION -->
                <div class="tab-pane fade" id="tab-org">
                    @php $isConsultant = auth()->user()->hasRole('loan_consultant'); @endphp

                    <label>Branch</label>

                    @if($isConsultant)
                        <input class="af-input bg-light"
                               value="{{ optional($branches->firstWhere('id', auth()->user()->branch_id))->name }}"
                               readonly>
                        <input type="hidden" name="branch_id" value="{{ auth()->user()->branch_id }}">
                    @else
                        <select name="branch_id" class="af-select" required>
                            <option value="">— Select Branch —</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>

                <!-- ACCESS -->
                <div class="tab-pane fade" id="tab-access">
                    @role('admin|super-admin')
                    <label>User Roles</label>
                    <select name="roles[]" class="af-select" multiple required>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </select>
                    @endrole

                    <input type="hidden" name="roles[]" value="user">
                </div>

                <!-- SECURITY -->
                <div class="tab-pane fade" id="tab-security">
                    <div class="grid grid-2">
                        <div>
                            <label>Password</label>
                            <input class="af-input" type="password" name="password" required>
                        </div>
                        <div>
                            <label>Confirm Password</label>
                            <input class="af-input" type="password" name="password_confirmation" required>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ACTIONS -->
            <div class="d-flex justify-content-end gap-2 mt-4">
                <button class="af-btn">
                    <i class="fas fa-user-plus"></i> Create User
                </button>
                <a href="{{ route('user.index') }}" class="af-btn secondary">Cancel</a>
            </div>
        </form>
    </div>

</div>
</div>
</div>
</div>

<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.getElementById('createUserSkeleton')?.classList.add('d-none');
        document.getElementById('createUserContent')?.classList.remove('d-none');
    }, 600);
});
</script>
</body>
</html>
