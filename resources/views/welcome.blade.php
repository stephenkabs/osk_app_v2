<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8"/>
  <title>Rent App • Property & Rental Management System</title>

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap & Icons -->
  <link href="/assets/css/bootstrap.min.css" rel="stylesheet">
  <link rel="shortcut icon" href="/assets/images/favicon.png">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>
  @include('ui.websites.styles')
</head>

<body>

<!-- Page Loader -->
<div id="pageLoader" aria-hidden="true">
  <div class="loader-wrap">
    <div class="loader-ring"></div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  setTimeout(() => {
    const loader = document.getElementById('pageLoader');
    if (!loader) return;
    loader.classList.add('hidden');
    setTimeout(() => loader.style.display = 'none', 550);
  }, 300);
});
</script>

<header>
  <div class="brand">
    <img src="/logo.webp" alt="Rent App Logo" class="brand-logo">
  </div>

  <div class="nav-links d-none d-md-flex">
    <a href="#hero">Home</a>
    <a href="#about">About</a>
    <a href="#services">Features</a>
    <a href="#api">Developers</a>
    <a href="#footer">Contact</a>

    <a href="/login" class="nav-btn login-btn">Login</a>
  </div>
</header>

<!-- HERO -->
<section class="hero" id="hero">
  <div class="hero-left-overlay"></div>

  <div class="hero-content">
    <h1>Rent App</h1>

    <p>
      A modern property & rental management platform designed for
      <strong>landlords</strong>,
      <strong>property managers</strong>, and
      <strong>real estate businesses</strong>.
      Manage properties, tenants, leases, and rent payments — all in one system.
    </p>

    <div class="hero-buttons">
      <a href="/login" class="btn-primary">
        <i class="fas fa-sign-in-alt me-1"></i> Sign In
      </a>
      <a href="/register" class="btn-outline">
        <i class="fas fa-user-plus me-1"></i> Get Started
      </a>
    </div>
  </div>
</section>

<!-- ABOUT -->
<section class="about-section" id="about">
  <div class="about-container">

    <div class="about-text">
      <span class="about-badge">About the Platform</span>
      <h2>About Rent App</h2>

      <p>
        <strong>Rent App</strong> is a complete rental management system built to
        handle the full property lifecycle — from onboarding properties and units,
        managing tenants, signing leases, to tracking monthly rent and generating receipts.
      </p>

      <p>
        The system gives landlords and property managers real-time visibility
        into occupancy, rent status, lease agreements, and tenant activity,
        while keeping everything secure and auditable.
      </p>

      <ul class="about-list">
        <li><i class="fas fa-check-circle"></i> Property & unit management</li>
        <li><i class="fas fa-check-circle"></i> Tenant onboarding & approval workflow</li>
        <li><i class="fas fa-check-circle"></i> Digital lease agreements</li>
        <li><i class="fas fa-check-circle"></i> Monthly rent tracking & receipts</li>
        <li><i class="fas fa-check-circle"></i> Role-based access & audit logs</li>
      </ul>
    </div>

    <div class="about-visual">
      <div class="about-card">
        <div class="about-card-badge">
          <i class="fas fa-building"></i> Smart Property Control
        </div>
        <img src="/2.webp" alt="Rent App Dashboard">
      </div>
    </div>

  </div>
</section>

<!-- FEATURES -->
<section class="ms-features" id="services">
  <div class="ms-features-inner">

    <div class="ms-features-header">
      <span class="section-badge">Core Features</span>
      <h2>What Rent App Delivers</h2>

      <p>
        Everything you need to manage rental properties efficiently,
        transparently, and professionally.
      </p>
    </div>

    <div class="ms-feature-grid">

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-building"></i></div>
        <h5>Property & Unit Management</h5>
        <p>
          Create properties, define units, assign rent amounts, and
          track availability across buildings.
        </p>
        <span class="ms-feature-chip">FOUNDATION</span>
      </div>

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-user-check"></i></div>
        <h5>Tenant Registration & Approval</h5>
        <p>
          Tenants register and remain pending until approved by an admin
          or landlord before accessing the system.
        </p>
        <span class="ms-feature-chip">CONTROLLED ACCESS</span>
      </div>

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-file-signature"></i></div>
        <h5>Digital Lease Agreements</h5>
        <p>
          Create, sign, activate, and manage lease agreements
          with status tracking (pending, active, ended).
        </p>
        <span class="ms-feature-chip">LEASES</span>
      </div>

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-calendar-alt"></i></div>
        <h5>Rent Calendar (Jan–Dec)</h5>
        <p>
          Visual monthly rent calendar showing paid, partial,
          and unpaid months for each tenant.
        </p>
        <span class="ms-feature-chip">VISUAL</span>
      </div>

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-money-bill-wave"></i></div>
        <h5>Rent Payments & Partial Payments</h5>
        <p>
          Record rent payments per month with support for partial payments,
          proration, and balance tracking.
        </p>
        <span class="ms-feature-chip">FLEXIBLE</span>
      </div>

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-file-pdf"></i></div>
        <h5>PDF Receipts</h5>
        <p>
          Automatically generate downloadable PDF receipts
          per tenant, per month.
        </p>
        <span class="ms-feature-chip">PROFESSIONAL</span>
      </div>

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-users-cog"></i></div>
        <h5>Role-Based Access</h5>
        <p>
          Separate access for admins, landlords, tenants, and staff
          with strict permission control.
        </p>
        <span class="ms-feature-chip">SECURE</span>
      </div>

      <div class="ms-feature-card">
        <div class="ms-feature-icon"><i class="fas fa-shield-alt"></i></div>
        <h5>Audit Logs & Security</h5>
        <p>
          Every action — approvals, payments, updates — is logged
          with timestamps and user accountability.
        </p>
        <span class="ms-feature-chip">TRUSTED</span>
      </div>

    </div>

  </div>
</section>

<!-- API -->
<section class="api-section" id="api">
  <div class="api-container">

    <div class="api-text">
      <span class="api-badge">For Developers</span>
      <h2>APIs & Integrations</h2>

      <p>
        <strong>Rent App</strong> exposes secure APIs for integration with
        mobile apps, payment gateways, accounting systems, and third-party tools.
      </p>

      <ul class="api-list">
        <li><i class="fas fa-check-circle"></i> Tenant & lease management APIs</li>
        <li><i class="fas fa-check-circle"></i> Rent payment posting endpoints</li>
        <li><i class="fas fa-check-circle"></i> Token-based authentication</li>
        <li><i class="fas fa-check-circle"></i> Receipt & reporting endpoints</li>
      </ul>

      <div class="api-actions">
        <a href="/documentation" class="btn-dark">
          <i class="fas fa-book me-1"></i> API Docs
        </a>
      </div>
    </div>

    <div class="api-code">
<pre><code><span class="comment">// Example: Record rent payment</span>
POST /api/v1/rent-payments

{
  "tenant_id": 7,
  "lease_id": 12,
  "payment_month": "2026-01",
  "amount": 950
}

<span class="comment">// Response</span>
{
  "status": "paid",
  "receipt_id": "RNT-2026-0012"
}
</code></pre>
    </div>

  </div>
</section>

<!-- FOOTER -->
<footer class="ms-footer" id="footer">
  <div class="ms-footer-container">

    <div class="ms-footer-brand">
      <img src="/logo_white.webp" alt="Rent App Logo">
      <p>
        <strong>Rent App</strong> is a modern rental management platform
        built to simplify property operations, tenant management,
        and rent tracking.
      </p>
    </div>

    <div class="ms-footer-links">
      <h4>Platform</h4>
      <a href="/">Home</a>
      <a href="#about">About</a>
      <a href="#services">Features</a>
      <a href="#api">Developers</a>
    </div>

    <div class="ms-footer-links">
      <h4>Resources</h4>
      <a href="/documentation">Documentation</a>
      <a href="/login">Login</a>
      <a href="/register">Get Started</a>
    </div>

    <div class="ms-footer-links">
      <h4>Contact</h4>
      <div class="ms-footer-contact">
        <i class="fas fa-envelope"></i>
        <span>support@neurasofts.com</span>
      </div>
      <div class="ms-footer-contact">
        <i class="fas fa-phone"></i>
        <span>+260 773 360 664</span>
      </div>
      <div class="ms-footer-contact">
        <i class="fas fa-map-marker-alt"></i>
        <span>Lusaka, Zambia</span>
      </div>
    </div>

  </div>

  <div class="ms-footer-bottom">
    © {{ now()->year }} Rent App • Built by Neurasoft Technologies Inc.
  </div>
</footer>

<script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
