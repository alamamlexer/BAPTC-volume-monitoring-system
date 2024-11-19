<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
        }
        .email-container {
            background-color: #fff;
            border-radius: 8px;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
        }
        p {
            font-size: 16px;
        }
        a {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Hello, {{ $staffName }}!</h1>
        <p>Thank you for registering with us. To activate your account, please click the link below:</p>
        <p>
            <a href="{{ $activationUrl }}" target="_blank">Login to You Account</a>
        </p>

        <p>Your username is: <strong>{{ $username }}</strong></p>
        <p>Your temporary password is: <strong>{{ $temporaryPassword }}</strong></p>

        <p>Once you activate your account, please log in and change your password immediately for security purposes.</p>

        <p>If you did not request this email, please ignore it. If you believe this to be an error, contact our support team immediately.</p>
    </div>
</body>
</html>
