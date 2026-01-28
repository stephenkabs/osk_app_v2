@push('styles')
<style>
/* -----------------------------
   APPLE SYSTEM FONT
----------------------------- */
:root {
    --apple-font: -apple-system, BlinkMacSystemFont, "SF Pro Text",
                  "SF Pro Display", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
}

body {
    font-family: var(--apple-font);
    color: #111;
}

/* -----------------------------
   CARDS
----------------------------- */
.apple-card {
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 24px 55px rgba(0,0,0,.10);
}

/* -----------------------------
   HEADINGS
----------------------------- */
.apple-card h5,
.apple-card h6 {
    font-weight: 800;
    letter-spacing: -0.4px;
}

.apple-card h5 {
    font-size: 1.25rem;
}

.apple-card h6 {
    font-size: 1.05rem;
}

/* -----------------------------
   LABELS
----------------------------- */
.form-label {
    font-weight: 700;
    font-size: 0.85rem;
    letter-spacing: 0.3px;
    color: #111;
}

/* -----------------------------
   INPUTS
----------------------------- */
.apple-input {
    font-family: var(--apple-font);
    border-radius: 14px;
    padding: 12px 14px;
    font-size: 0.95rem;
    font-weight: 500;
    border: 1px solid #e5e7eb;
}

.apple-input::placeholder {
    color: #9ca3af;
    font-weight: 500;
}

.apple-input:focus {
    border-color: #9b0000;
    box-shadow: 0 0 0 3px rgba(155,0,0,.18);
}

/* -----------------------------
   CLIENT TYPE CHECKS
----------------------------- */
.apple-check {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 16px;
    border: 1px solid #e5e7eb;
    cursor: pointer;
    font-weight: 700;
    letter-spacing: 0.2px;
    transition: all .2s ease;
}

.apple-check:hover {
    background: #fafafa;
}

.apple-check input {
    accent-color: #9b0000;
}

/* -----------------------------
   BUTTONS
----------------------------- */
.btn {
    font-family: var(--apple-font);
    font-weight: 800;
    letter-spacing: 0.4px;
}

.btn-red {
    background: #9b0000;
    color: #fff;
    border: none;
}

.btn-red:hover {
    background: #7f0000;
}

.btn-white {
    background: #fff;
    border: 1px solid #e5e7eb;
    color: #111;
}

/* -----------------------------
   RIGHT PANEL CONTEXT
----------------------------- */
.context-card {
    display: none;
    animation: fadeSlide .25s ease;
}

.context-card.active {
    display: block;
}

.context-card p {
    font-size: 0.9rem;
    font-weight: 500;
    color: #6b7280;
}

/* -----------------------------
   ANIMATION
----------------------------- */
@keyframes fadeSlide {
    from {
        opacity: 0;
        transform: translateY(8px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* index Css */

/* Table Head */
.apple-thead th {
    font-size: 0.75rem;
    font-weight: 800;
    letter-spacing: .5px;
    color: #6b7280;
    text-transform: uppercase;
}

/* Rows */
.apple-row {
    border-top: 1px solid #f1f1f1;
}

/* Initials Avatar */
.initials-circle {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: #111;
    color: #fff;
    font-weight: 800;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    justify-content: center;
    letter-spacing: 1px;
}

/* Status pill */
.status-pill {
    background: rgba(21, 155, 0, 0.1);
    color: #0f4403;
    font-weight: 700;
    font-size: 0.75rem;
    padding: 6px 14px;
    border-radius: 999px;
}

/* Badges */
.badge-dark {
    background: #111;
    color: #fff;
}

.badge-light {
    background: #f3f4f6;
    color: #111;
}

/* Black button */
.btn-black {
    background: #111;
    color: #fff;
}
.btn-black:hover {
    background: #000;
}

/* modals css */

.apple-modal {
    border-radius: 22px;
    box-shadow: 0 30px 70px rgba(0,0,0,.25);
    border: none;
}

/* Delete icon */
.delete-icon {
    width: 54px;
    height: 54px;
    border-radius: 50%;
    background: rgba(155,0,0,.12);
    color: #9b0000;
    font-size: 28px;
    font-weight: 900;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: auto;
}

.apple-modal-table th {
    width: 35%;
    font-weight: 700;
    font-size: 0.85rem;
    color: #111;
    border-bottom: 1px solid #f1f1f1;
    padding: 14px 16px;
    background: #fafafa;
}

.apple-modal-table td {
    font-size: 0.9rem;
    font-weight: 500;
    border-bottom: 1px solid #f1f1f1;
    padding: 14px 16px;
}

.apple-modal-table tr:last-child th,
.apple-modal-table tr:last-child td {
    border-bottom: none;
}

/* Section header */
.apple-modal-table .table-section th {
    background: #fff;
    color: #9b0000;
    font-size: 0.75rem;
    letter-spacing: 0.6px;
    text-transform: uppercase;
    border-top: 1px solid #e5e7eb;
}


/* NRC Copied */

.copy-nrc {
    cursor: pointer;
    font-weight: 600;
    color: #111;
    transition: color .15s ease;
}

.copy-nrc:hover {
    color: #9b0000;
    text-decoration: underline;
}

/* Branding CSS */
.apple-modal {
    border-radius: 22px;
    border: none;
    box-shadow: 0 30px 80px rgba(0,0,0,.25);
}

.modal-header h5 {
    font-weight: 800;
    letter-spacing: -0.4px;
}


/* ON Loans Index Buttons */
/* Compact search buttons */
.loan-search-actions .btn {
    padding: 6px 14px !important;
    font-size: 13px;
    border-radius: 999px;
    white-space: nowrap;
}


/* Show Modal CSS */
/* ===== Apple-like Buttons ===== */

.btn {
    font-weight: 600;
    font-size: 13.5px;
    border-radius: 999px;
    padding: 6px 14px;
    transition: all .18s ease;
}

/* Red (Primary action) */
.btn-red {
    background: #9b0000;
    color: #fff;
    border: none;
}
.btn-red:hover {
    background: #7f0000;
    transform: translateY(-1px);
}

/* White (Neutral) */
.btn-white {
    background: #fff;
    color: #111;
    border: 1px solid #e5e7eb;
}
.btn-white:hover {
    background: #f9fafb;
}

/* Dark (PDF / Secondary) */
.btn-dark {
    background: #111;
    color: #fff;
    border: none;
}
.btn-dark:hover {
    background: #000;
    transform: translateY(-1px);
}

/* Outline danger (Delete trigger) */
.btn-outline-danger {
    border: 1px solid #dc3545;
    color: #dc3545;
    background: transparent;
}
.btn-outline-danger:hover {
    background: #dc3545;
    color: #fff;
}

/* Disabled state */
.btn:disabled {
    opacity: .5;
    cursor: not-allowed;
    transform: none !important;
}

/* Small spacing fix inside modals */
.modal .btn {
    padding: 6px 16px;
}

/* Compact Apple-style buttons */
.btn-xs {
    padding: 4px 10px;
    font-size: 12px;
    font-weight: 600;
}

.loan-actions .btn {
    white-space: nowrap;
}

/* LOANS FILTER CSS */

.month-pill {
    border: 1px solid #ddd;
    background: #f8f9fa;
    padding: 8px 16px;
    border-radius: 999px;
    font-weight: 600;
    font-size: 13px;
    cursor: pointer;
    transition: all .2s ease;
}

.month-pill:hover {
    background: #000;
    color: #fff;
    border-color: #000;
}

.month-pill.active {
    background: #000;
    color: #fff;
    border-color: #000;
}


/* modal client css button */

.status-pill {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    background: #f1f1f1;
}

.status-pill:hover {
    background: #e6e6e6;
}


</style>

<style>
    /* ===============================
   Unified Small Control Size
   =============================== */

/* Inputs & selects */
.apple-input,
.form-control-sm,
.form-select-sm {
    height: 34px;
    padding: 6px 14px;
    font-size: 13px;
    border-radius: 999px; /* pill */
    line-height: 1.2;
}

/* Buttons */
.btn-sm {
    height: 34px;
    padding: 6px 16px;
    font-size: 13px;
    border-radius: 999px;
    line-height: 1.2;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* Apple red button */
.btn-red {
    background-color: #9b0000;
    color: #fff;
    border: none;
}

.btn-red:hover {
    background-color: #7f0000;
}

/* White / neutral button */
.btn-white {
    background-color: #ffffff;
    color: #333;
    border: 1px solid #e5e5e5;
}

.btn-white:hover {
    background-color: #f7f7f7;
}

/* Labels */
.form-label.small {
    font-size: 11px;
    letter-spacing: 0.02em;
    margin-bottom: 4px;
}

/* Optional: cleaner focus */
.apple-input:focus,
.form-control-sm:focus,
.form-select-sm:focus {
    box-shadow: 0 0 0 2px rgba(0,0,0,0.05);
    border-color: #ccc;
}

</style>
@endpush
