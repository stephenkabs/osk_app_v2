<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Units</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="/assets/images/favicon.png">

    <!-- Responsive Table css -->
    <link href="/assets/libs/admin-resources/rwd-table/rwd-table.min.css" rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="/assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />

    <!-- Icons Css -->
    <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="/assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />


</head>

<body data-sidebar="dark">

    <!-- Loader -->
    @include('includes.preloader')



    <!-- Delete Confirmation Modal -->
    <div id="aw-modal" class="aw-modal" aria-hidden="true">
        <div class="aw-modal__backdrop"></div>
        <div class="aw-modal__sheet" role="dialog" aria-modal="true" aria-labelledby="aw-modal-title">
            <div class="aw-modal__icon">⚠️</div>
            <h3 id="aw-modal-title" class="aw-modal__title">Delete this item?</h3>
            <p class="aw-modal__text">
                You’re about to delete <span id="aw-modal-item" class="aw-modal__item"></span>. This action can’t be
                undone.
            </p>
            <div class="aw-modal__actions">
                <button type="button" class="aw-btn aw-btn--secondary" id="aw-cancel">Cancel</button>
                <button type="button" class="aw-btn aw-btn--destructive" id="aw-confirm">Delete</button>
            </div>
        </div>
    </div>
    <style>
        .aw-modal {
            position: fixed;
            inset: 0;
            z-index: 10000;
            display: none;
        }

        .aw-modal[aria-hidden="false"] {
            display: block;
        }

        .aw-modal__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, .28);
            backdrop-filter: blur(6px);
        }

        .aw-modal__sheet {
            position: absolute;
            left: 50%;
            top: 18%;
            transform: translateX(-50%);
            width: min(92vw, 480px);
            background: #fff;
            border-radius: 18px;
            box-shadow: 0 30px 60px rgba(0, 0, 0, .2);
            padding: 22px;
            border: 1px solid #e6e8ef;
            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        .aw-modal__icon {
            font-size: 32px;
            line-height: 1;
            margin-bottom: 8px;
        }

        .aw-modal__title {
            margin: 0 0 6px;
            font-size: 18px;
            font-weight: 800;
            letter-spacing: -.01em;
            color: #0b0c0f;
        }

        .aw-modal__text {
            margin: 0 0 16px;
            color: #5b5f6b;
            font-weight: 600;
            font-size: 13px;
        }

        .aw-modal__item {
            color: #0b0c0f;
            font-weight: 800;
        }

        .aw-modal__actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .aw-btn {
            border-radius: 12px;
            border: 1px solid #e6e8ef;
            background: #fff;
            color: #0b0c0f;
            padding: 9px 14px;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            transition: box-shadow .2s, border-color .2s, transform .06s;
        }

        .aw-btn:hover {
            border-color: #0071e3;
            box-shadow: 0 0 0 4px color-mix(in srgb, #0071e3 16%, transparent);
        }

        .aw-btn--secondary {
            background: #f6f7fb;
        }

        .aw-btn--destructive {
            background: #f56c6c;
            color: #fff;
            border-color: #f56c6c;
        }

        .aw-btn--destructive:hover {
            box-shadow: 0 0 0 4px color-mix(in srgb, #f56c6c 22%, transparent);
        }
    </style>
    <script>
        (() => {
            const modal = document.getElementById('aw-modal');
            const itemEl = document.getElementById('aw-modal-item');
            const btnCancel = document.getElementById('aw-cancel');
            const btnConfirm = document.getElementById('aw-confirm');
            let pendingForm = null;

            // Open modal on any delete button
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.js-open-delete');
                if (!btn) return;
                e.preventDefault();
                pendingForm = btn.closest('.js-delete-form');
                itemEl.textContent = btn.getAttribute('data-item') || 'this item';
                openModal();
            });

            function openModal() {
                modal.setAttribute('aria-hidden', 'false');
                // close on Esc / backdrop
                document.addEventListener('keydown', escClose);
                modal.addEventListener('click', backdropClose);
            }

            function closeModal() {
                modal.setAttribute('aria-hidden', 'true');
                document.removeEventListener('keydown', escClose);
                modal.removeEventListener('click', backdropClose);
                pendingForm = null;
            }

            function escClose(e) {
                if (e.key === 'Escape') closeModal();
            }

            function backdropClose(e) {
                if (e.target === modal || e.target.classList.contains('aw-modal__backdrop')) closeModal();
            }

            btnCancel.addEventListener('click', closeModal);
            btnConfirm.addEventListener('click', () => {
                if (pendingForm) pendingForm.submit();
                closeModal();
            });
        })();
    </script>


    @include('toast.success_toast')
    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('includes.header')

        @include('includes.sidebar')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <style>
                        /* Layout: left align (no Bootstrap centering) */
                        .aw-wrap {
                            max-width: 100%;
                            padding-left: 0;
                            padding-right: 12px;
                        }

                        /* Header + Add */
                        .aw-header {
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            gap: 12px;
                            margin-bottom: 8px;
                            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                        }

                        .aw-title {
                            font-weight: 800;
                            letter-spacing: -.02em;
                            font-size: 20px;
                            margin: 0;
                        }

                        .aw-add-btn {
                            display: inline-flex;
                            align-items: center;
                            justify-content: center;
                            width: 38px;
                            height: 38px;
                            border-radius: 999px;
                            border: 1px solid #e6e8ef;
                            background: #fff;
                            font-size: 20px;
                            font-weight: 900;
                            line-height: 1;
                            color: #0b0c0f;
                            text-decoration: none;
                            transition: box-shadow .2s, transform .06s, border-color .2s;
                        }

                        .aw-add-btn:hover {
                            border-color: #0071e3;
                            box-shadow: 0 0 0 4px color-mix(in srgb, #0071e3 18%, transparent);
                        }

                        /* Filter bar */
                        .aw-filters {
                            display: flex;
                            gap: 10px;
                            flex-wrap: wrap;
                            margin: 8px 0 12px;
                        }

                        .af-input,
                        .af-select {
                            border: 1px solid #e6e8ef;
                            border-radius: 10px;
                            padding: 8px 10px;
                            font-size: 13px;
                            font-weight: 600;
                            background: #fff;
                            outline: none;
                            transition: box-shadow .2s, border-color .2s;
                        }

                        .af-input:focus,
                        .af-select:focus {
                            border-color: #0071e3;
                            box-shadow: 0 0 0 3px color-mix(in srgb, #0071e3 14%, transparent);
                        }

                        /* Scroll wrapper for sticky header */
                        .aw-table-wrap {
                            max-height: 70vh;
                            overflow: auto;
                            border-radius: 14px;
                            border: 1px solid #e6e8ef;
                            background: #fff;
                        }

                        /* Table */
                        .aw-table {
                            width: 100%;
                            border-collapse: separate;
                            border-spacing: 0;
                            font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
                        }

                        .aw-table thead th {
                            position: sticky;
                            top: 0;
                            z-index: 2;
                            text-align: left;
                            font-weight: 800;
                            font-size: 13px;
                            letter-spacing: .02em;
                            text-transform: uppercase;
                            color: #5b5f6b;
                            background: #f6f7fb;
                            padding: 12px 14px;
                            border-bottom: 1px solid #e6e8ef;
                        }

                        .aw-table tbody td {
                            padding: 12px 14px;
                            font-size: 14px;
                            font-weight: 600;
                            color: #0b0c0f;
                            border-bottom: 1px solid #f0f1f5;
                            vertical-align: middle;
                        }

                        .aw-table tbody tr:nth-child(even) {
                            background: #fbfbfd;
                        }

                        .aw-table tbody tr:hover {
                            background: #f3f6ff;
                        }

                        .aw-thumb {
                            width: 44px;
                            height: 44px;
                            object-fit: cover;
                            border-radius: 10px;
                            border: 1px solid #e6e8ef;
                        }

                        .mono {
                            font-variant-numeric: tabular-nums;
                        }

                        .tag {
                            display: inline-block;
                            padding: .2rem .5rem;
                            border-radius: 999px;
                            font-size: 12px;
                            font-weight: 800;
                        }

                        .tag-green {
                            background: #eaf8ef;
                            color: #216e3a;
                            border: 1px solid #c9f0d7;
                        }

                        .tag-slate {
                            background: #eef1f6;
                            color: #3b4252;
                            border: 1px solid #dee2eb;
                        }

                        .aw-actions {
                            display: flex;
                            gap: 6px;
                            flex-wrap: wrap;
                        }

                        .chip-btn {
                            display: inline-flex;
                            align-items: center;
                            gap: 6px;
                            padding: 8px 10px;
                            border-radius: 10px;
                            border: 1px solid #e6e8ef;
                            background: #fff;
                            font-size: 12.5px;
                            font-weight: 800;
                            color: #0b0c0f;
                            text-decoration: none;
                            transition: border-color .2s, box-shadow .2s, transform .06s;
                        }

                        .chip-btn:hover {
                            border-color: #0071e3;
                            box-shadow: 0 0 0 4px color-mix(in srgb, #0071e3 14%, transparent);
                        }

                        .chip-warn {
                            border-color: #ffd2a1;
                        }

                        .chip-warn:hover {
                            border-color: #ff9500;
                            box-shadow: 0 0 0 4px color-mix(in srgb, #ff9500 18%, transparent);
                        }

                        .chip-danger {
                            border-color: #f6b3b3;
                        }

                        .chip-danger:hover {
                            border-color: #f56c6c;
                            box-shadow: 0 0 0 4px color-mix(in srgb, #f56c6c 18%, transparent);
                        }

                        .chip-primary {
                            border-color: #bad7ff;
                        }

                        .chip-primary:hover {
                            border-color: #0071e3;
                            box-shadow: 0 0 0 4px color-mix(in srgb, #0071e3 18%, transparent);
                        }
                    </style>

                    <!-- Approve/Reject Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="border-radius:16px">
      <div class="modal-header">
        <h5 class="modal-title">Update Partner Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="statusForm" method="POST">
        @csrf
        @method('PATCH')
        <div class="modal-body">
          <p id="statusPartnerName" style="font-weight:600"></p>
          <div class="mb-3">
            <label for="status" class="form-label">Select Status</label>
            <select name="status" id="status" class="form-select" required>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary">Save Status</button>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    const form = document.getElementById('statusForm');
    const partnerName = document.getElementById('statusPartnerName');
    const statusSelect = document.getElementById('status');

    document.querySelectorAll('.js-open-approve').forEach(btn => {
        btn.addEventListener('click', () => {
            const slug = btn.getAttribute('data-slug');
            const name = btn.getAttribute('data-name');
            const status = btn.getAttribute('data-status');

            // Update modal content
            partnerName.textContent = "Partner: " + name;
            statusSelect.value = status;

            // Update form action → using slug
            form.action = `/partners/${slug}/status`;

            statusModal.show();
        });
    });
});

</script>


<div class="aw-wrap">
    <div class="aw-header">
        <h2 class="aw-title">Partners</h2>
        <a href="{{ route('partners.create') }}" class="aw-add-btn" title="Add Partner">+</a>
    </div>

    <!-- Quick Search / Filters -->
    <div class="aw-filters">
        <input id="flt-search" class="af-input" type="text" placeholder="Search (name, NRC, phone…)" />
        <select id="flt-status" class="af-select">
            <option value="">All Status</option>
            <option value="pending">Pending</option>
            <option value="approved">Approved</option>
            <option value="rejected">Rejected</option>
        </select>
        <button id="flt-clear" class="chip-btn" type="button">Reset</button>
    </div>

    <!-- Table -->
    <div class="aw-table-wrap">
        <table class="aw-table" id="partners-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>NRC No</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Status</th>
                    <th style="width:330px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partners as $partner)
                    <tr>
                        <td data-col="name">{{ $partner->name }}</td>
                        <td data-col="nrc_no" class="mono">{{ $partner->nrc_no }}</td>
                        <td data-col="phone">{{ $partner->phone_number }}</td>
                        <td data-col="address">{{ $partner->previous_address }}</td>
                        <td data-col="status">
                            <span class="tag
                                @if($partner->status === 'approved') tag-green
                                @elseif($partner->status === 'rejected') tag-danger
                                @else tag-slate @endif">
                                {{ ucfirst($partner->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="aw-actions">
<button type="button" class="chip-btn chip-primary js-open-approve"
    data-slug="{{ $partner->slug }}"
    data-name="{{ $partner->name }}"
    data-status="{{ $partner->status }}"
    title="Update Status">
    Update Status
</button>


                                <a href="{{ route('partners.edit', $partner->slug) }}"
                                    class="chip-btn chip-warn" title="Edit">Edit</a>
                                <a href="{{ route('partners.show', $partner->slug) }}"
                                    class="chip-btn" title="View">View</a>
                                <form action="{{ route('partners.destroy', $partner->slug) }}"
                                    method="POST" class="js-delete-form" style="display:inline">
                                    @csrf @method('DELETE')
                                    <button type="button" class="chip-btn chip-danger js-open-delete"
                                        data-item="{{ $partner->name }} • {{ $partner->nrc_no }}"
                                        title="Delete">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    // Simple client-side filter
    (function () {
        const q = id => document.getElementById(id);
        const search = q('flt-search');
        const statusSel = q('flt-status');
        const clear = q('flt-clear');
        const rows = Array.from(document.querySelectorAll('#partners-table tbody tr'));

        function normalize(txt) {
            return (txt || '').toString().toLowerCase().trim();
        }

        function apply() {
            const s = normalize(search.value);
            const st = statusSel.value;

            rows.forEach(tr => {
                const name = tr.querySelector('[data-col="name"]')?.textContent || '';
                const nrc = tr.querySelector('[data-col="nrc_no"]')?.textContent || '';
                const phone = tr.querySelector('[data-col="phone"]')?.textContent || '';
                const status = tr.querySelector('[data-col="status"]')?.textContent.toLowerCase() || '';

                let ok = true;

                // search text across name, NRC, phone
                if (s) {
                    const blob = (name + ' ' + nrc + ' ' + phone).toLowerCase();
                    ok = blob.includes(s);
                }

                // status filter
                if (ok && st) {
                    ok = status.trim() === st;
                }

                tr.style.display = ok ? '' : 'none';
            });
        }

        [search, statusSel].forEach(el => el.addEventListener('input', apply));
        clear.addEventListener('click', () => {
            search.value = '';
            statusSel.value = '';
            apply();
        });

        apply();
    })();
</script>


                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('includes.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="/assets/libs/jquery/jquery.min.js"></script>
    <script src="/assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="/assets/libs/simplebar/simplebar.min.js"></script>
    <script src="/assets/libs/node-waves/waves.min.js"></script>

    <!-- Responsive Table js -->
    <script src="/assets/libs/admin-resources/rwd-table/rwd-table.min.js"></script>

    <!-- Init js -->
    <script src="/assets/js/pages/table-responsive.init.js"></script>

    <script src="/assets/js/app.js"></script>

    <script>
        // Event listener for delete button click
        $('#deleteMinistryModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var route = button.data('route'); // Extract route from data-* attribute
            var modal = $(this);

            // Update the form action with the correct route
            modal.find('#deleteMinistryForm').attr('action', route);
        });
    </script>
    @include('toast.error_success_js')


</body>

</html>
