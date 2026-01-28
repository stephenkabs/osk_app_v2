@extends('layouts.app')

@section('content')
@include('ui.style')

<div class="container py-4">

    <div class="apple-card p-4">

        <h4 class="fw-extrabold mb-2">Rent App – Help & Support</h4>
        <p class="text-muted small mb-4">
            This guide explains how each part of the Rent App works.
            Use it whenever you are unsure what to do.
        </p>

        {{-- ======================
           OVERVIEW
        ====================== --}}
        <h6 class="fw-bold mt-4">1. System Overview</h6>
        <p class="small text-muted">
            Rent App is a modern property and rental management platform designed
            for landlords, property managers, and real estate businesses.
            It helps you manage properties, tenants, leases, and rent payments
            in a secure and organized way.
        </p>

        {{-- ======================
           PROPERTIES
        ====================== --}}
        <h6 class="fw-bold mt-4">2. Properties Management</h6>
        <ul class="small text-muted">
            <li>Add and manage properties and buildings.</li>
            <li>Each property can contain multiple units.</li>
            <li>Property details include address, type, and status.</li>
        </ul>

        {{-- ======================
           UNITS
        ====================== --}}
        <h6 class="fw-bold mt-4">3. Units Management</h6>
        <ul class="small text-muted">
            <li>Create and manage rental units under each property.</li>
            <li>Track unit status: <strong>Available</strong> or <strong>Occupied</strong>.</li>
            <li>Each unit can only be linked to one active lease at a time.</li>
        </ul>

        {{-- ======================
           TENANTS
        ====================== --}}
        <h6 class="fw-bold mt-4">4. Tenants Management</h6>
        <ul class="small text-muted">
            <li>Add and manage tenant profiles and contact details.</li>
            <li>Each tenant must be linked to a lease.</li>
            <li>Removing a tenant also affects related leases and payments.</li>
        </ul>

        {{-- ======================
           LEASES
        ====================== --}}
        <h6 class="fw-bold mt-4">5. Lease Agreements</h6>
        <ul class="small text-muted">
            <li>Create lease agreements with start and end dates.</li>
            <li>Define rent amount, billing cycle, and terms.</li>
            <li>Only active leases generate rent payments.</li>
        </ul>

        {{-- ======================
           RENT PAYMENTS
        ====================== --}}
        <h6 class="fw-bold mt-4">6. Rent Payments</h6>
        <ul class="small text-muted">
            <li>Track monthly rent payments per tenant.</li>
            <li>Payments can be marked as <strong>Paid</strong>, <strong>Pending</strong>, or <strong>Overdue</strong>.</li>
            <li>System prevents duplicate payments for the same month.</li>
        </ul>

        {{-- ======================
           EXPENSES
        ====================== --}}
        <h6 class="fw-bold mt-4">7. Property Expenses</h6>
        <ul class="small text-muted">
            <li>Record property-related expenses (maintenance, utilities, repairs).</li>
            <li>Expenses can be linked to specific properties or units.</li>
            <li>Helps track profitability per property.</li>
        </ul>

        {{-- ======================
           MAINTENANCE
        ====================== --}}
        <h6 class="fw-bold mt-4">8. Maintenance Requests</h6>
        <ul class="small text-muted">
            <li>Log maintenance and repair issues.</li>
            <li>Track status: <strong>Pending</strong>, <strong>In Progress</strong>, or <strong>Completed</strong>.</li>
            <li>Each request is linked to a property or unit.</li>
        </ul>

        {{-- ======================
           USER ROLES
        ====================== --}}
        <h6 class="fw-bold mt-4">9. User Roles & Access</h6>
        <ul class="small text-muted">
            <li><strong>Landlord:</strong> Full access to properties, tenants, and reports.</li>
            <li><strong>Property Manager:</strong> Manages daily operations.</li>
            <li>Access is controlled based on assigned role.</li>
        </ul>

        {{-- ======================
           REPORTS
        ====================== --}}
        <h6 class="fw-bold mt-4">10. Reports & Insights</h6>
        <ul class="small text-muted">
            <li>View rent collection summaries.</li>
            <li>Track outstanding balances and overdue tenants.</li>
            <li>Monitor income vs expenses per property.</li>
        </ul>

        {{-- ======================
           ACTIVITY LOGS
        ====================== --}}
        <h6 class="fw-bold mt-4">11. Activity Logs</h6>
        <ul class="small text-muted">
            <li>All important actions are logged automatically.</li>
            <li>Examples: property created, lease signed, payment recorded.</li>
            <li>Logs include user, timestamp, and action details.</li>
        </ul>

        {{-- ======================
           SUPPORT
        ====================== --}}
        <h6 class="fw-bold mt-4">12. When You Need Help</h6>
        <ul class="small text-muted">
            <li>Review this Help & Support page.</li>
            <li>Confirm your role permissions.</li>
            <li>Contact the system administrator if access is restricted.</li>
        </ul>

        <div class="alert alert-light small mt-4">
            <strong>Note:</strong> This help page is updated whenever new
            features are added to the Rent App.
        </div>

    </div>

</div>
@endsection
