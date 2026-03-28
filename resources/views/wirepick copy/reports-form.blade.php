<h2>Wirepick Reports</h2>

<form method="POST" action="{{ route('wirepick.reports.fetch') }}">
    @csrf

    <label>Payment Channel:</label>
    <select name="gateway" required>
        <option value="AIRTEL">AIRTEL</option>
        <option value="MTN">MTN</option>
        <option value="ZAMTEL">ZAMTEL</option>
        <option value="CARD">CARD</option>
    </select><br><br>

    <label>Start Date:</label>
    <input type="date" name="start_date" required><br><br>

    <label>End Date:</label>
    <input type="date" name="end_date" required><br><br>

    <button type="submit">Fetch Reports</button>
</form>
