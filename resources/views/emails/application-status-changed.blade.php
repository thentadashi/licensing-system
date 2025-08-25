<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Status Changed</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px;">
    <table align="center" style="background: white; max-width: 600px; width: 100%; border-radius: 8px; overflow: hidden;">
        <tr>
            <td style="background: #2d3748; padding: 20px; text-align: center;">
                <h1 style="color: white; margin: 0;">WCC Licensing System</h1>
            </td>
        </tr>
        <tr>
            <td style="padding: 20px;">
                <h2>Hello, {{ $user->name }}</h2>
                <p>Your application <strong>#{{ $application->id }}</strong> has a status update:</p>
                <p>
                    <strong>From:</strong> {{ $old }} <br>
                    <strong>To:</strong> {{ $new }}
                </p>

		        @if($application->admin_notes)
                    <blockquote style="border-left: 4px solid #2d3748; padding-left: 10px; color: #4a5568; margin: 30px 0px;">
                        Admin Note: {{ $application->admin_notes }}
                    </blockquote>
		        @endif


                <p style="text-align: left;margin:30px 0px;">
                    <a href="{{ $url }}" style="background: #2d3748; color: white; padding: 12px 20px; text-decoration: none; border-radius: 5px;">
                        View Application
                    </a>
                </p>

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