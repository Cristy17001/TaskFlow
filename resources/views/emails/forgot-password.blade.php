<!DOCTYPE html>
<html>

<head>
    <title>Password Reset</title>
    <style>
        .link {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <h3>Hi {{ $mailData['email'] }},</h3>
    <p>You have requested to reset your password.</p>
    <p>Please click the following link to reset your password:</p>
    <a href="{{ route('password.reset', ['token' => $mailData['token']]) }}" class="link">Reset Password</a>
    <hr>
    <p>If you didn't request this, you can safely ignore this email.</p>
    <p>Best regards,</p>
    <p>Your Website Team</p>
</body>

</html>
