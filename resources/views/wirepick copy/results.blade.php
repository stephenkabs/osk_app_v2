<!DOCTYPE html>
<html>
<head>
    <title>Wirepick Response</title>
</head>
<body>

<h2>Wirepick Test Response</h2>

<h3>Request Payload</h3>
<pre>{{ json_encode($payload, JSON_PRETTY_PRINT) }}</pre>

<h3>Response</h3>
<pre>{{ json_encode($parsed, JSON_PRETTY_PRINT) }}</pre>

<a href="/wirepick">Run Another Test</a>

</body>
</html>
