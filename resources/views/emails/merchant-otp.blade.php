<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $otp }} is your {{ $appName }} verification code</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 520px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03); border: 1px solid #e5e7eb;">
                    <!-- Brand Header -->
                    <tr>
                        <td align="center" style="padding: 32px 32px 20px; border-bottom: 1px solid #f3f4f6;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #111827; letter-spacing: -0.5px;">
                                <span style="color: #f59e0b;">Affan</span>Hub
                            </h1>
                            <p style="margin: 4px 0 0; font-size: 13px; color: #6b7280; font-weight: 500;">
                                Merchant Onboarding
                            </p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 32px;">
                            <h2 style="margin: 0 0 16px; font-size: 18px; font-weight: 700; color: #111827;">
                                Hello {{ $name }},
                            </h2>
                            <p style="margin: 0 0 24px; font-size: 15px; line-height: 24px; color: #4b5563;">
                                Welcome to {{ $appName }}! Use the 6-digit verification code below to verify your merchant email address:
                            </p>

                            <!-- 6-digit OTP Code Box -->
                            <div style="margin: 28px 0; text-align: center;">
                                <div style="display: inline-block; background-color: #fffbeb; border: 2px dashed #f59e0b; border-radius: 12px; padding: 16px 32px;">
                                    <span style="font-family: 'Courier New', Courier, monospace; font-size: 36px; font-weight: 800; letter-spacing: 10px; color: #b45309; display: block;">
                                        {{ $otp }}
                                    </span>
                                </div>
                            </div>

                            <p style="margin: 0 0 12px; font-size: 14px; line-height: 22px; color: #4b5563; text-align: center;">
                                Enter this code on your screen to complete your setup.
                            </p>
                            <p style="margin: 0; font-size: 13px; line-height: 20px; color: #9ca3af; text-align: center;">
                                This code will expire in <strong>15 minutes</strong>.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; background-color: #f9fafb; border-top: 1px solid #f3f4f6; text-align: center;">
                            <p style="margin: 0 0 8px; font-size: 12px; line-height: 18px; color: #9ca3af;">
                                If you did not create an account on {{ $appName }}, you can safely ignore this email.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                &copy; {{ date('Y') }} {{ $appName }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
