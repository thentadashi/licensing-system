<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
    <table align="center" style="background: white; max-width: 600px; width: 100%; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="background: #2d3748; padding: 20px; text-align: center;">
                {{-- <img src="{{ asset('build/assets/images/logo.png') }}" alt="Logo" style="height: 50px;"> --}}
                <h1 style="color: white; margin: 0;">WCC Licensing System</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <h2>Hello, {{ $user->name }}</h2>
                <p>You requested to reset your password. Click the button below to proceed:</p>
                <p style="text-align: Left;">
                    <a href="{{ $url }}" style="background: #38a169; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px;">Reset Password</a>
                </p>
                <p>If you didn’t request this, you can safely ignore this email.</p>
                <p>Thanks,<br>WCC Licensing System Team</p>
            </td>
        </tr>
        <tr>
            <td style="background: #edf2f7; padding: 10px; text-align: center; font-size: 12px; color: #718096;">
                &copy; {{ date('Y') }} Licensing System. All rights reserved.
            </td>
        </tr>
    </table>
</body>
</html>
