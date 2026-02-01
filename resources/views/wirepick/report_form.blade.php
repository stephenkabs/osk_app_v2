
<div class="container">
    <h3 class="mb-3">Wirepick Payment Reports</h3>

    <form action="{{ route('wirepick.report.run') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Gateway</label>
            <select name="gateway" class="form-control" required>
                <option value="">Select</option>
                <option value="AIRTEL">Airtel</option>
                <option value="MTN">MTN</option>
                <option value="ZAMTEL">Zamtel</option>
                <option value="CARD">Card Payments</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Format</label>
            <select name="format" class="form-control">
                <option value="JSON">JSON</option>
                <option value="CSV">CSV</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Action</label>
            <select name="action" class="form-control" required>
                <option value="collect_report">Collection Report</option>
                <option value="disburse_report">Disbursement Report</option>
                <option value="general_report">General Report</option>
            </select>
        </div>

        <button class="btn btn-primary">Fetch Report</button>
    </form>
</div>
