<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Properties</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Property Management" name="description" />
    <meta content="Frameworx" name="author" />
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        .tile-link {
            text-decoration: none;
            display: block;
        }

        .tile {
            background: #fff;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 12px 16px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease, background-color .18s ease;
            will-change: transform;
        }

        .tile:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 10px 24px rgba(16, 24, 40, .12);
            border-color: #cfffbc;
            background: #f7fbff;
        }

        .icon-container {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            border-radius: 8px;
            transition: transform .18s ease, background-color .18s ease;
        }

        .tile:hover .icon-container {
            transform: scale(1.06) rotate(-1deg);
            background: #eef4ff;
        }

        .tile i {
            font-size: 24px;
            color: #555;
            transition: transform .18s ease, color .18s ease;
        }

        .tile:hover i {
            transform: scale(1.05);
            color: #5d8a1e;
        }

        .prop-meta {
            margin: 0;
            font-size: 10px;
            color: #6b7280;
        }

        .badge-chip {
            display: inline-block;
            border: 1px solid #e5e7eb;
            background: #f3f4f6;
            color: #374151;
            padding: 2px 8px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 700;
        }

        /* Modal Styles */
        .aw-modal {
            position: fixed;
            inset: 0;
            z-index: 1050;
            display: none
        }

        .aw-modal[aria-hidden="false"] {
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, .3);
            backdrop-filter: blur(6px)
        }

        .aw-modal__sheet {
            background: #fff;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, .15);
            width: min(90%, 400px)
        }

        .aw-modal__title {
            margin: 0 0 8px;
            font-weight: 800;
            font-size: 18px
        }

        .aw-modal__text {
            margin: 0 0 14px;
            color: #6b7280;
            font-size: 14px
        }

        .aw-btn {
            border-radius: 12px;
            padding: 8px 14px;
            border: 1px solid #e5e7eb;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer
        }

        .aw-btn--secondary {
            background: #f3f4f6;
            color: #111827
        }

        .aw-btn--primary {
            background: #111827;
            color: #fff
        }

        /* 🍏 PROPERTY CARD */
.property-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    border: 1px solid #e5e7eb;
    transition: all .25s ease;
}

.property-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 20px 40px rgba(0,0,0,.12);
}

/* 🍏 MEDIA */
.property-media {
    height: 180px;
    overflow: hidden;
    position: relative;
}

.property-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    transition: transform .6s ease;
}

.apple-carousel:hover .property-img {
    transform: scale(1.05);
}

/* 🍏 CONTROLS */
.apple-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(255,255,255,.9);
    backdrop-filter: blur(10px);
    border: none;
    font-size: 18px;
    font-weight: 700;
    color: #111;
    opacity: 0;
    transition: all .2s ease;
}

.apple-carousel:hover .apple-nav {
    opacity: 1;
}

.apple-nav.prev { left: 10px; }
.apple-nav.next { right: 10px; }

/* 🍏 BODY */
.property-body {
    padding: 12px 14px;
}


/* 🍏 Skeleton Animation */
@keyframes shimmer {
  0% { background-position:-300px 0; }
  100% { background-position:300px 0; }
}

.skeleton-property-card,
.skeleton-img,
.skeleton-line,
.skeleton-chip {
  background: linear-gradient(
    90deg,
    #f0f1f5 25%,
    #e5e7eb 37%,
    #f0f1f5 63%
  );
  background-size: 400% 100%;
  animation: shimmer 1.4s infinite ease;
  border-radius: 14px;
}

/* Card */
.skeleton-property-card {
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 18px;
  overflow: hidden;
}

/* Image */
.skeleton-img {
  height: 180px;
  border-radius: 0;
}

/* Text */
.skeleton-line {
  height: 12px;
}

.skeleton-chip {
  width: 48px;
  height: 18px;
  border-radius: 999px;
}

/* Width helpers */
.w-40 { width: 40%; }
.w-60 { width: 60%; }

    </style>
</head>

<body data-sidebar="dark">

    {{-- @include('includes.preloader') --}}

    <div id="layout-wrapper">
        @include('includes.header')
        @include('includes.sidebar')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
<!-- 🍏 Properties Skeleton -->
<div id="properties-skeleton">

  <div class="row">
    @for($i = 0; $i < 6; $i++)
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="skeleton-property-card">

          <!-- Image -->
          <div class="skeleton-img"></div>

          <!-- Body -->
          <div class="p-3">
            <div class="skeleton-line w-60 mb-2"></div>
            <div class="skeleton-line w-40 mb-3"></div>

            <div class="d-flex gap-2">
              <div class="skeleton-chip"></div>
              <div class="skeleton-chip"></div>
            </div>
          </div>

        </div>
      </div>
    @endfor
  </div>

</div>

<!-- 🔥 REAL PROPERTIES CONTENT -->
<div id="properties-content" style="display:none;">

            @role('admin|super_admin')
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- 🔄 Sync Button -->
                    <button class="tile-link" id="open-sync-modal" style="background:none;border:none;padding:0">
                        <div class="tile">
                            <div class="icon-container me-2">
                                <i class="fas fa-sync-alt"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Sync from QBO</h6>
                                <p class="prop-meta">Fetch property data from QuickBooks</p>
                            </div>
                        </div>
                    </button>
                </div>
            @endrole

            <!-- 🍏 PROPERTY GRID -->
            <div class="row">
                @forelse($properties as $property)

                    @php
                        // ✅ Safe for array / JSON / null
                        $imgs = is_array($property->images)
                            ? $property->images
                            : json_decode($property->images ?? '[]', true);

                        $imgs = $imgs ?? [];
                    @endphp

                    <div class="col-lg-4 col-md-6 mb-4">

                        <!-- ✅ WHOLE CARD CLICKABLE -->
                        <a href="{{ route('properties.show', $property->slug) }}"
                           class="text-decoration-none text-dark">

                            <div class="property-card">

                                <!-- 🍏 IMAGE CAROUSEL -->
                                <div class="property-media">
                                    @if(count($imgs))
                                        <div id="prop-carousel-{{ $property->id }}"
                                             class="carousel slide apple-carousel"
                                             data-bs-ride="carousel"
                                             data-bs-interval="4500">

                                            <div class="carousel-inner">
                                                @foreach($imgs as $i => $img)
                                                    <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                                        <img
                                                            src="{{ asset('storage/'.$img) }}"
                                                            class="property-img"
                                                            alt="{{ $property->property_name }}"
                                                            onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                                                    </div>
                                                @endforeach
                                            </div>

                                            @if(count($imgs) > 1)
                                                <button class="apple-nav prev"
                                                        data-bs-target="#prop-carousel-{{ $property->id }}"
                                                        data-bs-slide="prev">‹</button>

                                                <button class="apple-nav next"
                                                        data-bs-target="#prop-carousel-{{ $property->id }}"
                                                        data-bs-slide="next">›</button>
                                            @endif
                                        </div>
                                    @else
                                        <img src="{{ asset('assets/images/placeholder.jpg') }}"
                                             class="property-img">
                                    @endif
                                </div>

                                <!-- 🍏 CONTENT -->
                                <div class="property-body">
                                    <h6 class="mb-1">{{ $property->property_name }}</h6>

                                    <p class="prop-meta">
                                        QTY {{ $property->qbo_qty_on_hand ?? '—' }}
                                        • UNIT PRICE {{ $property->qbo_unit_price ?? '—' }}
                                    </p>

                                    <div class="d-flex gap-2 flex-wrap">
                                        @if($property->lat && $property->lng)
                                            <span class="badge-chip">Lat {{ $property->lat }}</span>
                                            <span class="badge-chip">Lng {{ $property->lng }}</span>
                                        @endif

                                        @if($property->radius_m)
                                            <span class="badge-chip">{{ $property->radius_m }} m</span>
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </a>
                    </div>

                @empty
                    <div class="col-12">
                        <div class="tile">
                            <div class="icon-container me-3">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">No Properties Found</h6>
                                <p class="prop-meta">Click “Add Property” to create your first one.</p>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">
                {{ $properties->links() }}
            </div>

        </div>
    </div>
</div>



    </div>

    <!-- Modal -->
    <div id="sync-modal" class="aw-modal" aria-hidden="true">
        <div class="aw-modal__backdrop"></div>
        <div class="aw-modal__sheet">
            <h3 class="aw-modal__title">Sync Property from QuickBooks</h3>
            <p class="aw-modal__text">Enter the exact property name as it appears in QBO.</p>
            <form method="POST" action="{{ route('properties.sync') }}" class="d-flex flex-column gap-3">
                @csrf
                <input type="text" name="name" class="form-control" placeholder="e.g., Kalingalinga Stock"
                    required>
                <div class="d-flex justify-content-end gap-2 mt-3">
                    <button type="button" class="aw-btn aw-btn--secondary" id="close-sync-modal">Cancel</button>
                    <button type="submit" class="aw-btn aw-btn--primary">Sync Now</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('open-sync-modal').addEventListener('click', () => {
            document.getElementById('sync-modal').setAttribute('aria-hidden', 'false');
        });

        document.getElementById('close-sync-modal').addEventListener('click', () => {
            document.getElementById('sync-modal').setAttribute('aria-hidden', 'true');
        });
    </script>

    <!-- JS -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/node-waves/waves.min.js"></script>
    <script src="/assets/js/app.js"></script>
@include('includes.modals.success')
@include('includes.modals.error')
        @include('includes.validation')

        <script>
document.addEventListener('DOMContentLoaded', () => {
  setTimeout(() => {
    document.getElementById('properties-skeleton')?.remove();
    document.getElementById('properties-content').style.display = 'block';
  }, 400); // Apple-like delay
});
</script>

</body>

</html>
