<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f9f9f9; padding: 20px;">
    <div style="background: #ffffff; padding: 20px; border-radius: 6px; max-width: 600px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #2c3e50;">Hello, {{ $fullname }}</h2>

        <p>Your password has been reset by the administrator.</p>

        <p><strong>Email:</strong> {{ $email }}</p>
        <p><strong>New Password:</strong> {{ $password }}</p>


        <p style="margin-top: 20px;">Thank you,<br>SmartSched</p>
    </div>
</body>
</html>
