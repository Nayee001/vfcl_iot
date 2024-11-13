<!-- resources/views/errors/mqtt_error.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection Error</title>
</head>
<body>
    <div style="text-align: center; padding: 20px;">
        <h1>Network Error</h1>
        <p>We're unable to connect to our IoT services at the moment. Please check your network connection and try again later.</p>
        <a href="{{ url()->previous() }}">Go Back</a>
    </div>
</body>
</html>
