<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        .verification-code {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            text-align: center;
            padding: 20px;
            margin: 20px 0;
            background: #fff;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Email Verification</h2>
        <p>Hello,</p>
        <p>Your verification code is:</p>
        <div class="verification-code">
            {{ $verify_code }}
        </div>
        <p>Please use this code to verify your email address.</p>
        <p>If you didn't request this code, please ignore this email.</p>
        <p>Thank you!</p>
    </div>
</body>
</html>
