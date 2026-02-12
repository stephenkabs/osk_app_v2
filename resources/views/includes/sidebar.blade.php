<!-- ========== Left Sidebar Start ========== -->
<!-- ========== Left Sidebar Start ========== -->
<style>
  :root {
    --sb-bg: #2e2e2e;            /* deep blue background */
    --sb-hover-bg: #3d3d3d;      /* green hover */
    --sb-text: #ffffff;          /* white text */
    --sb-icon: #ffc400;          /* bright green icons */
    --sb-border: #303030;        /* dark blue border */
  }

  .vertical-menu {
    background: var(--sb-bg) !important;
    border-right: 1px solid var(--sb-border);
    width: 240px;
    padding: 20px 0;
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  }

  #sidebar-menu .metismenu {
    list-style: none;
    margin: 0;
    padding: 0 12px;
  }

  #sidebar-menu .metismenu > li {
    margin-bottom: 4px;
  }

  /* === Menu Links === */
  #sidebar-menu .metismenu > li > a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    color: var(--sb-text);
    font-weight: 600;
    font-size: 14px;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.2s ease;
  }

  #sidebar-menu .metismenu > li > a i {
    font-size: 16px;
    width: 18px;
    text-align: center;
    color: var(--sb-icon);
    transition: color 0.2s ease, transform 0.2s ease;
  }

  /* === Hover === */
  #sidebar-menu .metismenu > li > a:hover {
    background: var(--sb-hover-bg);
    color: #fff;
    transform: translateX(2px);
  }
  #sidebar-menu .metismenu > li > a:hover i {
    color: #fff;
    transform: scale(1.2);
  }

  /* === Active State === */
  #sidebar-menu .metismenu > li > a.active,
  #sidebar-menu .metismenu > li.mm-active > a {
    background: var(--sb-hover-bg);
    color: #fff;
    font-weight: 700;
  }
  #sidebar-menu .metismenu > li > a.active i,
  #sidebar-menu .metismenu > li.mm-active > a i {
    color: #fff;
  }

  /* === Cart Badge === */
  .cart-badge {
    margin-left: auto;
    background: var(--sb-hover-bg);
    color: #fff;
    font-weight: 700;
    font-size: 12px;
    padding: 0 8px;
    border-radius: 999px;
    line-height: 20px;
    min-width: 20px;
    text-align: center;
  }

  /* === User Section === */
  .sidebar-user {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px 14px 8px;
    padding: 8px 10px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.1);
  }

  .sidebar-user img {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
  }

  .sidebar-user .name {
    font-weight: 700;
    font-size: 14px;
    color: #fff;
  }

  /* === Footer === */
  .sidebar-footer {
    margin-top: 24px;
    border-top: 1px solid rgba(255, 255, 255, 0.15);
    padding: 16px 14px;
    font-size: 13px;
    color: #cbd5e1;
    display: flex;
    align-items: center;
    gap: 8px;
  }

  .sidebar-footer i {
    color: var(--sb-icon);
  }

  /* === Dropdown Button === */
  .sidebar-user-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    width: 100%;
    background: rgba(255, 255, 255, 0.1);
    border: none;
    border-radius: 12px;
    padding: 8px 10px;
    cursor: pointer;
    transition: background 0.2s ease;
  }

  .sidebar-user-btn:hover {
    background: var(--sb-hover-bg);
  }

  .sidebar-user-btn img {
    width: 28px;
    height: 28px;
    border-radius: 50%;
  }

  .sidebar-user-btn .name {
    font-weight: 700;
    font-size: 14px;
    color: #fff;
    flex-grow: 1;
  }

  .sidebar-user-btn i {
    font-size: 12px;
    color: var(--sb-icon);
  }

  /* Dropdown Menu */
  .sidebar-user-menu {
    display: none;
    flex-direction: column;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 12px;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    margin-top: 6px;
    z-index: 20;
    overflow: hidden;
  }

  .sidebar-user-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    color: #111827;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.2s ease;
  }

  .sidebar-user-menu a:hover {
    background: #f9fafb;
  }

  .sidebar-user-menu a i {
    color: #6b7280;
    font-size: 14px;
    width: 16px;
    text-align: center;
  }
</style>



<div class="vertical-menu">
    <div data-simplebar class="h-100">
        <div id="sidebar-menu">
<ul class="metismenu list-unstyled" id="side-menu">

    <!-- Properties -->

    <li>
        <a href="{{ route('dashboard.index') }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
      <i class="dripicons-device-desktop"></i>
            <span>Home</span>
        </a>
    </li>


@if(isset($property))
        <li>
        <a href="{{ route('property.lease.assign.board', $property->slug) }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
 <i class="fas fa-random"></i>
            <span>Assign Lease</span>
        </a>
    </li>
@endif


@if(isset($property))
        <li>
        <a href="{{ route('property.users.index', $property->slug) }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
            <i class="fas fa-users"></i>
            <span>Tenants</span>
        </a>
    </li>
@endif

@if(isset($property))
        <li>
        <a href="{{ route('property.units.index', $property->slug) }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
        <i class="fas fa-door-open"></i>
            <span>Units</span>
        </a>
    </li>
@endif

@if(isset($property))
        <li>
        <a href="{{ route('property.agreements.index', $property->slug) }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
           <i class="fas fa-file-signature"></i>
            <span>Lease Agreements</span>
        </a>
    </li>
@endif


@if(isset($property))
        <li>
        <a href="{{ route('property.lease-template.edit', $property->slug) }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
             <i class="fas fa-file-alt"></i>
            <span>Lease Templates </span>
        </a>
    </li>
@endif



@if(isset($property))
        <li>
        <a href="{{ route('property.payments.index', $property->slug) }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
               <i class="fas fa-credit-card"></i>
            <span>Payments</span>
        </a>
    </li>
@endif

@if(isset($property))
        <li>
        <a href="{{ route('property.reports.index', $property->slug) }}" class="{{ request()->is('dashboard.index') ? 'active' : '' }}">
               <i class="fas fa-chart-bar"></i>
            <span>OSK Report</span>
        </a>
    </li>
@endif




        <!-- Properties -->
    <li>
        <a href="/properties" class="{{ request()->is('wallet') ? 'active' : '' }}">
            <i class="fas fa-building"></i>
            <span>OSK Building</span>
        </a>
    </li>

    <!-- Support / Help -->
    <li>
        <a href="/support" class="{{ request()->is('support') ? 'active' : '' }}">
            <i class="fas fa-life-ring"></i>
            <span>Support & Help</span>
        </a>
    </li>

</ul>


            @php
                $userName = auth()->user()->name ?? 'User';
                $avatarUrl = auth()->user()->profile_image
                    ? asset('storage/' . auth()->user()->profile_image)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($userName) . '&color=FFFFFF&background=da890f';
            @endphp

            <!-- USER DROPDOWN -->
            <div class="sidebar-user-dropdown">
                <!-- Trigger -->
                <button class="sidebar-user-btn" id="sidebarUserToggle">
                    <img src="{{ $avatarUrl }}" alt="User">
                    <span class="name">{{ $userName }}</span>
                    <i class="fas fa-chevron-down"></i>
                </button>

                <!-- Dropdown Menu -->
                <div class="sidebar-user-menu" id="sidebarUserMenu">
                    <a href="{{ route('users.show', auth()->user()->slug ?? '') }}">
                        <i class="fas fa-user"></i> My Profile
                    </a>
                    <a href="/admin/system-settings">
      <i class="fas fa-cog"></i> Settings
    </a>
                    <a href="#"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-power-off text-danger"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('dashboard.logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>

            <style>
                /* Container */
                .sidebar-user-dropdown {
                    position: relative;
                    margin: 20px 14px;
                }

                /* Trigger Button */
                .sidebar-user-btn {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 10px;
                    width: 100%;
                    background: #f9fafb;
                    border: none;
                    border-radius: 12px;
                    padding: 8px 10px;
                    cursor: pointer;
                    font-family: inherit;
                    transition: background 0.2s ease;
                }

                .sidebar-user-btn:hover {
                    background: #f3f4f6;
                }

                .sidebar-user-btn img {
                    width: 28px;
                    height: 28px;
                    border-radius: 50%;
                    object-fit: cover;
                }

                .sidebar-user-btn .name {
                    font-weight: 700;
                    font-size: 14px;
                    color: #111827;
                    flex-grow: 1;
                    text-align: left;
                }

                .sidebar-user-btn i {
                    font-size: 12px;
                    color: #6b7280;
                    transition: transform 0.2s ease;
                }

                /* Dropdown Menu */
                .sidebar-user-menu {
                    display: none;
                    flex-direction: column;
                    background: #fff;
                    border: 1px solid #e5e7eb;
                    border-radius: 12px;
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
                    position: absolute;
                    top: 100%;
                    left: 0;
                    right: 0;
                    margin-top: 6px;
                    z-index: 20;
                    overflow: hidden;
                }

                .sidebar-user-menu a {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    padding: 10px 14px;
                    color: #111827;
                    text-decoration: none;
                    font-size: 14px;
                    transition: background 0.2s ease;
                }

                .sidebar-user-menu a:hover {
                    background: #f9fafb;
                }

                .sidebar-user-menu a i {
                    color: #6b7280;
                    font-size: 14px;
                    width: 16px;
                    text-align: center;
                }

                /* Active state (rotate chevron) */
                .sidebar-user-btn.open i {
                    transform: rotate(180deg);
                }
            </style>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const toggleBtn = document.getElementById("sidebarUserToggle");
                    const menu = document.getElementById("sidebarUserMenu");

                    toggleBtn.addEventListener("click", function(e) {
                        e.stopPropagation();
                        toggleBtn.classList.toggle("open");
                        menu.style.display = menu.style.display === "flex" ? "none" : "flex";
                    });

                    // Close menu if clicked outside
                    document.addEventListener("click", function(e) {
                        if (!toggleBtn.contains(e.target) && !menu.contains(e.target)) {
                            menu.style.display = "none";
                            toggleBtn.classList.remove("open");
                        }
                    });
                });
            </script>


            <div class="sidebar-footer">
                <i class="fas fa-question-circle"></i> Help and support
            </div>
        </div>
    </div>
</div>
<!-- Left Sidebar End -->
