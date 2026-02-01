@extends('layouts.app')

@section('content')
<style>
:root{
    --gold:#e19722;
    --ink:#0b0c0f;
    --muted:#6b7280;
    --border:#e6e8ef;
    --radius:22px;
}

/* ===============================
   PAGE
================================ */
.investor-page{
    min-height:100vh;
    background:#f5f6fa;
    padding:0 20px 60px;
}

/* ===============================
   HERO HEADER
================================ */
.inv-hero{
    max-width:1200px;
    margin:24px auto 40px;
    border-radius:28px;
    padding:42px;
    background:
        radial-gradient(1200px 400px at top right, rgba(225,151,34,.25), transparent),
        linear-gradient(135deg,#0b0c0f,#111827);
    color:#fff;
    box-shadow:0 40px 80px rgba(0,0,0,.35);
}
.inv-hero-title{
    font-size:32px;
    font-weight:900;
    letter-spacing:-.03em;
}
.inv-hero-sub{
    margin-top:10px;
    max-width:720px;
    font-size:15px;
    color:rgba(255,255,255,.75);
}
.inv-hero-name{
    color:var(--gold);
    font-weight:800;
}

/* ===============================
   STATS
================================ */
.inv-stats{
    max-width:1200px;
    margin:-60px auto 48px;
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(240px,1fr));
    gap:18px;
}
.stat-card{
    backdrop-filter: blur(14px);
    background:rgba(255,255,255,.85);
    border-radius:22px;
    padding:22px;
    border:1px solid var(--border);
    box-shadow:0 18px 40px rgba(0,0,0,.12);
}
.stat-label{
    font-size:12px;
    text-transform:uppercase;
    font-weight:800;
    color:#6b7280;
    letter-spacing:.04em;
}
.stat-value{
    margin-top:8px;
    font-size:26px;
    font-weight:900;
    color:#0b0c0f;
}
.stat-sub{
    margin-top:4px;
    font-size:12px;
    color:#9ca3af;
}

/* ===============================
   SECTION
================================ */
.section{
    max-width:1200px;
    margin:0 auto;
}
.section-title{
    font-size:20px;
    font-weight:900;
    letter-spacing:-.02em;
    margin-bottom:18px;
}

/* ===============================
   PROPERTY GRID
================================ */
.prop-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(300px,1fr));
    gap:22px;
}

/* ===============================
   PROPERTY CARD
================================ */
.prop-card{
    background:#fff;
    border-radius:22px;
    padding:22px;
    border:1px solid var(--border);
    box-shadow:0 14px 36px rgba(0,0,0,.08);
    display:flex;
    flex-direction:column;
    transition:.25s ease;
}
.prop-card:hover{
    transform:translateY(-4px);
    box-shadow:0 28px 60px rgba(0,0,0,.12);
}

.prop-name{
    font-size:18px;
    font-weight:900;
    letter-spacing:-.01em;
}

.prop-shares{
    margin-top:6px;
    font-size:13px;
    color:var(--muted);
}

/* Financial rows */
.prop-metric{
    display:flex;
    justify-content:space-between;
    margin-top:14px;
    font-size:14px;
}
.prop-metric span{
    font-weight:800;
}

/* CTA */
.inv-btn{
    margin-top:auto;
    margin-top:22px;
    width:100%;
    padding:12px;
    border-radius:14px;
    background:linear-gradient(135deg,#0b0c0f,#111827);
    color:#fff;
    font-weight:900;
    letter-spacing:.02em;
    border:none;
    /* cursor:not-allowed; */
    opacity:.65;
}

/* ===============================
   EMPTY
================================ */
.empty{
    background:#fff;
    border-radius:22px;
    border:1px dashed var(--border);
    padding:60px;
    text-align:center;
}
.empty h4{
    font-weight:900;
    margin-bottom:6px;
}
.empty p{
    color:var(--muted);
    font-size:14px;
}
.inv-btn.coming-soon {
    background: linear-gradient(135deg, #0b0c0f, #2a2a2a);
    color: #e19722;
    opacity: .9;
}

</style>

<div class="investor-page">

    {{-- HERO --}}
    <div class="inv-hero">
        <div class="inv-hero-title">
            Investor Dashboard
        </div>
        <div class="inv-hero-sub">
            Welcome back,
            <span class="inv-hero-name">{{ $user->name }}</span>.
            This is your private investment overview — track capital exposure,
            returns, and new opportunities synced from QuickBooks.
        </div>
    </div>

    {{-- STATS --}}
    <div class="inv-stats">
        <div class="stat-card">
            <div class="stat-label">Capital Invested</div>
            <div class="stat-value">
                ZMW {{ number_format($totalInvested ?? 0, 2) }}
            </div>
            <div class="stat-sub">Confirmed investments</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Returns Earned</div>
            <div class="stat-value">
                ZMW {{ number_format($totalReturns ?? 0, 2) }}
            </div>
            <div class="stat-sub">Distributed income</div>
        </div>

        <div class="stat-card">
            <div class="stat-label">Active Assets</div>
            <div class="stat-value">
                {{ $properties->count() }}
            </div>
            <div class="stat-sub">Available properties</div>
        </div>
    </div>

    {{-- PROPERTIES --}}
    <div class="section">
        <div class="section-title">Available Investment Opportunities</div>

        @if($properties->isEmpty())
            <div class="empty">
                <h4>No properties available</h4>
                <p>
                    Assets synced from QuickBooks will appear here once published.
                </p>
            </div>
        @else
            <div class="prop-grid">
                @foreach($properties as $property)
                    <div class="prop-card">

                        <div class="prop-name">
                            {{ $property->property_name ?? 'Unnamed Property' }}
                        </div>

                        <div class="prop-shares">
                            {{ $property->qbo_qty_on_hand ?? 0 }} shares available
                        </div>

                        <div class="prop-metric">
                            <div>Price per Share</div>
                            <span>
                                USD {{ number_format($property->qbo_unit_price ?? 0, 2) }}
                            </span>
                        </div>

                        <div class="prop-metric">
                            <div>Total Asset Value</div>
                            <span>
                                USD {{ number_format($property->bidding_price ?? 0, 2) }}
                            </span>
                        </div>

               <a href="{{ route('properties.show', $property->slug) }}"
   class="inv-btn">
    View Property
</a>

                    </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const page = document.querySelector('.investor-page');

    if (page.scrollHeight <= window.innerHeight + 10) {
        document.body.style.overflow = 'hidden';
    }
});
</script>

@endsection
