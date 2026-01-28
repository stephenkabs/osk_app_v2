@extends('layouts.app')

@section('content')
@push('styles')
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
@endpush


{{-- 🍏 PAGE TITLE --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Properties</h4>

    @role('admin|super_admin')
        <button class="btn btn-outline-dark rounded-pill"
                id="open-sync-modal">
            <i class="fas fa-sync-alt me-1"></i> Sync from QBO
        </button>
    @endrole
</div>

{{-- 🍏 SKELETON --}}
<div id="properties-skeleton">
    <div class="row">
        @for($i = 0; $i < 6; $i++)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="skeleton-property-card">
                    <div class="skeleton-img"></div>
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

{{-- 🍏 REAL CONTENT --}}
<div id="properties-content" style="display:none;">
    <div class="row">

        @forelse($properties as $property)

            @php
                $imgs = is_array($property->images)
                    ? $property->images
                    : json_decode($property->images ?? '[]', true) ?? [];
            @endphp

            <div class="col-lg-4 col-md-6 mb-4">
                <a href="{{ route('properties.show', $property->slug) }}"
                   class="text-decoration-none text-dark">

                    <div class="property-card">

                        {{-- IMAGE --}}
                        <div class="property-media">
                            @if(count($imgs))
                                <img src="{{ asset('storage/'.$imgs[0]) }}"
                                     class="property-img"
                                     onerror="this.src='{{ asset('assets/images/placeholder.jpg') }}'">
                            @else
                                <img src="{{ asset('assets/images/placeholder.jpg') }}"
                                     class="property-img">
                            @endif
                        </div>

                        {{-- BODY --}}
                        <div class="property-body">
                            <h6 class="mb-1">{{ $property->property_name }}</h6>

                            <p class="prop-meta">
                                QTY {{ $property->qbo_qty_on_hand ?? '—' }}
                                • UNIT {{ $property->qbo_unit_price ?? '—' }}
                            </p>

                            <div class="d-flex gap-2 flex-wrap">
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
                <div class="alert alert-light border">
                    No properties found.
                </div>
            </div>
        @endforelse
    </div>

    {{ $properties->links() }}
</div>

{{-- 🍏 SYNC MODAL --}}
<div id="sync-modal" class="aw-modal" aria-hidden="true">
    <div class="aw-modal__sheet">
        <h3 class="aw-modal__title">Sync Property from QuickBooks</h3>
        <p class="aw-modal__text">Enter exact property name as in QBO.</p>

        <form method="POST" action="{{ route('properties.sync') }}">
            @csrf
            <input type="text"
                   name="name"
                   class="form-control mb-3"
                   placeholder="e.g. Kalingalinga Stock"
                   required>

            <div class="d-flex justify-content-end gap-2">
                <button type="button"
                        class="aw-btn aw-btn--secondary"
                        id="close-sync-modal">Cancel</button>

                <button class="aw-btn aw-btn--primary">Sync</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    setTimeout(() => {
        document.getElementById('properties-skeleton')?.remove();
        document.getElementById('properties-content').style.display = 'block';
    }, 400);

    document.getElementById('open-sync-modal')?.addEventListener('click', () => {
        document.getElementById('sync-modal').setAttribute('aria-hidden','false');
    });

    document.getElementById('close-sync-modal')?.addEventListener('click', () => {
        document.getElementById('sync-modal').setAttribute('aria-hidden','true');
    });

});
</script>
@endpush

@endsection
