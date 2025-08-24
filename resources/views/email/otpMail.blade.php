<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $mailData['title'] }}</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #4F46E5; padding: 20px; text-align: center; color: #ffffff;">
                            <h1 style="margin: 0; font-size: 22px;">{{ $mailData['title'] }}</h1>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 30px; color: #333333; font-size: 16px; line-height: 1.6;">
                            <p>Hello,</p>
                            <p>This is your one-time password. It is valid for <strong>{{ env('OTP_EXPIRES_IN') }}
                                    minutes</strong>.</p>

                            <p style="text-align: center; margin: 30px 0;">
                                <span
                                    style="display: inline-block; font-size: 28px; font-weight: bold; color: #4F46E5; letter-spacing: 3px; padding: 12px 24px; border: 2px dashed #4F46E5; border-radius: 6px;">
                                    {{ $mailData['body'] }}
                                </span>
                            </p>

                            <p>If you did not request this code, please ignore this email.</p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background-color: #f9f9f9; padding: 15px; text-align: center; font-size: 12px; color: #666;">
                            &copy; {{ date('Y') }} {{ env('APP_NAME', 'DIHAS') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>

</html>
