<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Device Verification</title>
</head>
<body>
    <p>Hello {{ $name }},</p>
    <p>Please verify your device by clicking on the button below:</p>
    <a href="{{ $verifyUrl }}" style="background-color: #4CAF50; color: white; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; font-size: 16px; margin: 4px 2px; cursor: pointer; border-radius: 12px;">Verify Device</a>
    <footer style="text-align: center; margin-top: 20px; padding: 10px; font-size: 14px; color: #666;">
        <p>Team vFCL</p>
    </footer>
</body>
</html>
