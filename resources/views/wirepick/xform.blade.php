{{-- <!DOCTYPE html>
<html>
<head>
    <title>Wirepick Test</title>
</head>
<body>

<h2>Wirepick Card Payment Test</h2>

@if(session('error'))
    <p style="color:red;">{{ session('error') }}</p>
@endif

<form method="POST" action="{{ route('wirepick.test') }}">
    @csrf

    <label>MSISDN:</label><br>
    <input type="text" name="msisdn" placeholder="097xxxxxxx" required><br><br>

    <label>Amount:</label><br>
    <input type="number" name="amount" value="1" required><br><br>

    <button type="submit">Run Test</button>

</form>

</body>
</html> --}}


<h2>Universal Wirepick Payment</h2>

<form method="POST" action="{{ route('wp.process') }}">
    @csrf

    <label>MSISDN:</label><br>
    <input type="text" name="msisdn" required><br><br>

    <label>Amount:</label><br>
    <input type="number" name="amount" value="1" required><br><br>

    <label>Payment Method:</label><br>
    <select name="method" required>
        <option value="card">Card</option>
        <option value="airtel">Airtel Money</option>
        <option value="mtn">MTN Money</option>
    </select><br><br>

    <button>Pay</button>
</form>


