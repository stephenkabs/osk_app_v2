<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 | Forbidden</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #2e3a46;
            color: #ffffff;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        h1 {
            font-size: 4rem;
        }
        p {
            font-size: 1.5rem;
            margin: 20px 0;
        }
        a {
            color: #61dafb;
            text-decoration: none;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div>
        <h1>419</h1>
        <p>Sorry, the page you are looking is Expired.</p>
        <a href="{{ url('/login') }}">Return to Login</a>
    </div>
</body>
</html>
