<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temporary Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f4;
            padding: 20px;
            margin: 0;
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
            font-size: 24px;
        }
        p {
            font-size: 16px;
            line-height: 1.6;
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
        .footer {
            font-size: 12px;
            color: #888;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <h1>Hello, {{ $username }}!</h1>
        <p>We have received a request to reset your password. Below is your temporary password:</p>
        <p><strong>{{ $temporaryPassword }}</strong></p>

        <p>You can use this temporary password to log in. Once logged in, we strongly recommend that you change your password to something more secure.</p>

        <p>If you did not request this, please ignore this email.</p>

        <p>If you're ready to log in, you can go to the login page by clicking the button below:</p>

        <!-- Go to Login Button -->
        <a href="{{ $loginUrl }}" target="_blank">Go to Login</a>

        <div class="footer">
            <p>If you have any issues or did not request this password reset, please contact support immediately.</p>
        </div>
    </div>
</body>
</html>
