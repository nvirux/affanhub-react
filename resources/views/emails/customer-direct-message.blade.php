<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message from {{ $storeName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Brand Header -->
                    <tr>
                        <td align="center" style="padding: 28px 32px 18px; border-bottom: 1px solid #f3f4f6; background-color: #faf5ff;">
                            <h1 style="margin: 0; font-size: 22px; font-weight: 800; color: #581c87; letter-spacing: -0.5px;">
                                {{ $storeName }}
                            </h1>
                            <p style="margin: 4px 0 0; font-size: 13px; color: #7e22ce; font-weight: 500;">
                                Customer Support &amp; Updates
                            </p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 32px 32px 24px;">
                            <h2 style="margin: 0 0 16px; font-size: 18px; font-weight: 700; color: #111827;">
                                Hello {{ $name }},
                            </h2>

                            <!-- Custom Message Body -->
                            <div style="font-size: 15px; line-height: 24px; color: #374151; white-space: pre-line; margin-bottom: 28px;">
                                {{ $bodyContent }}
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 28px 0 16px;">
                                <a href="{{ $portalUrl }}" style="display: inline-block; background-color: #7e22ce; color: #ffffff; padding: 13px 28px; border-radius: 10px; font-size: 14px; font-weight: 700; text-decoration: none;">
                                    Go to Customer Portal &rarr;
                                </a>
                            </div>

                            <p style="margin: 24px 0 0; font-size: 14px; color: #4b5563;">
                                Best regards,<br>
                                <strong>{{ $storeName }} Team</strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 32px; background-color: #f9fafb; border-top: 1px solid #f3f4f6; text-align: center;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                &copy; {{ date('Y') }} {{ $storeName }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
