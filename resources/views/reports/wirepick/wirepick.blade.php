@extends('layouts.app')

@section('content')

<style>
body {
    background: #f5f7fa;
}

.glass-card {
    background: rgba(255,255,255,0.75);
    backdrop-filter: blur(12px);
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.08);
}

.apple-title {
    font-weight: 900;
    font-size: 28px;
    color: #122b50;
    letter-spacing: -0.5px;
}

.table thead {
    background: #122b50;
    color: white;
    border-radius: 12px;
}

.export-btn {
    border-radius: 14px;
    font-weight: 700;
}

.pagination-btn {
    padding: 6px 14px;
    border-radius: 10px;
    background: #122b50;
    color: white;
    cursor: pointer;
}
.pagination-btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.chart-card {
    padding: 20px;
    border-radius: 18px;
    background: white;
    margin-bottom: 20px;
}
</style>

<div class="container mt-4">

    <h2 class="apple-title mb-3">📊 Wirepick Transaction Reports</h2>

    <!-- Filters -->
    <div class="glass-card mb-4">
        <form id="reportForm">
            @csrf

            <div class="row gy-3">
                <div class="col-md-3">
                    <label class="fw-bold">Start Date</label>
                    <input type="date" class="form-control" name="start_date" required>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold">End Date</label>
                    <input type="date" class="form-control" name="end_date" required>
                </div>

                <div class="col-md-3">
                    <label class="fw-bold">Gateway</label>
                    <select class="form-control" name="gateway">
                        <option value="ALL">ALL</option>
                        <option value="AIRTEL">AIRTEL</option>
                        <option value="MTN">MTN</option>
                        <option value="ZAMTEL">ZAMTEL</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-dark w-100" id="btnFetch">
                        🔍 Fetch Report
                    </button>
                </div>
            </div>
        </form>

        <!-- Export Buttons -->
        <div class="mt-3 d-flex gap-2">
            <button class="btn btn-success export-btn" id="exportCsv">📥 Export CSV</button>
            <button class="btn btn-danger export-btn" id="exportPdf">📄 Export PDF</button>
        </div>
    </div>

    <!-- Charts -->
    <div class="chart-card">
        <h5 class="fw-bold">📈 Transactions Overview</h5>
        <canvas id="chart"></canvas>
    </div>

    <!-- Results Table -->
    <div class="glass-card">
        <h4 class="fw-bold">Results</h4>

        <table class="table table-hover mt-3">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>MSISDN</th>
                    <th>Amount</th>
                    <th>Reference</th>
                    <th>Gateway</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="tableBody"></tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-between mt-3">
            <button id="prevPage" class="pagination-btn">⬅ Previous</button>
            <button id="nextPage" class="pagination-btn">Next ➡</button>
        </div>
    </div>

</div>

<!-- CHART.JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let fullData = [];
let page = 0;
const perPage = 10;

// Load & paginate data
function loadTable() {
    let start = page * perPage;
    let slice = fullData.slice(start, start + perPage);

    let body = document.getElementById("tableBody");
    body.innerHTML = "";

    slice.forEach(r => {
        body.innerHTML += `
            <tr>
                <td>${r.timestamp}</td>
                <td>${r.msisdn}</td>
                <td>${r.amount}</td>
                <td>${r.reference}</td>
                <td>${r.gateway}</td>
                <td><span class="badge bg-${r.status === 'ACCEPTED' ? 'success' : 'danger'}">${r.status}</span></td>
            </tr>
        `;
    });
}

// Fetch report
document.getElementById("reportForm").addEventListener("submit", function(e) {
    e.preventDefault();

    let btn = document.getElementById("btnFetch");
    btn.innerText = "Loading...";
    btn.disabled = true;

    fetch("{{ route('reports.wirepick.fetch') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            start_date: document.querySelector('input[name="start_date"]').value,
            end_date: document.querySelector('input[name="end_date"]').value,
            gateway: document.querySelector('select[name="gateway"]').value
        })
    })
    .then(res => res.json())
    .then(data => {
        fullData = data.data;
        page = 0;
        loadTable();
        drawChart(fullData);
        btn.innerText = "Fetch Report";
        btn.disabled = false;
    });
});

// Charts
let chart;
function drawChart(rows) {
    if (chart) chart.destroy();

    const labels = rows.map(r => r.timestamp);
    const amounts = rows.map(r => r.amount);

    chart = new Chart(document.getElementById("chart"), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: "Amounts",
                data: amounts,
                borderColor: "#122b50",
                backgroundColor: "rgba(18,43,80,0.2)",
                borderWidth: 2,
                tension: .4
            }]
        }
    });
}

// Pagination
document.getElementById("prevPage").onclick = () => page > 0 ? (page--, loadTable()) : null;
document.getElementById("nextPage").onclick = () => (page + 1) * perPage < fullData.length ? (page++, loadTable()) : null;

// Export CSV
document.getElementById("exportCsv").onclick = () => {
    let form = new FormData(document.getElementById("reportForm"));
    fetch("{{ route('reports.wirepick.csv') }}", {
        method: "POST",
        body: form
    }).then(res => res.blob()).then(file => {
        let link = document.createElement("a");
        link.href = URL.createObjectURL(file);
        link.download = "wirepick_report.csv";
        link.click();
    });
};

// Export PDF
document.getElementById("exportPdf").onclick = () => {
    let form = new FormData(document.getElementById("reportForm"));
    fetch("{{ route('reports.wirepick.pdf') }}", {
        method: "POST",
        body: form
    }).then(res => res.blob()).then(file => {
        let link = document.createElement("a");
        link.href = URL.createObjectURL(file);
        link.download = "wirepick_report.pdf";
        link.click();
    });
};
</script>

@endsection
