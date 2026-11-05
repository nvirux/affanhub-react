<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ $appName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 560px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Brand Header -->
                    <tr>
                        <td align="center" style="padding: 32px 32px 20px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            <h1 style="margin: 0; font-size: 26px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                <span style="color: #f59e0b;">Affan</span>Hub
                            </h1>
                            <p style="margin: 6px 0 0; font-size: 14px; color: #cbd5e1; font-weight: 500;">
                                The Modern Telecom &amp; VTU Cloud Infrastructure
                            </p>
                        </td>
                    </tr>

                    <!-- Main Content -->
                    <tr>
                        <td style="padding: 32px 32px 24px;">
                            <h2 style="margin: 0 0 14px; font-size: 20px; font-weight: 700; color: #111827;">
                                Welcome aboard, {{ $name }}! 🚀
                            </h2>
                            <p style="margin: 0 0 20px; font-size: 15px; line-height: 24px; color: #4b5563;">
                                Congratulations on creating your <strong>{{ $appName }}</strong> merchant account! You're now equipped to run a fully automated VTU &amp; data vending business under your own brand name.
                            </p>

                            <!-- Quick 3-Step Guide -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 24px 0;">
                                <h3 style="margin: 0 0 14px; font-size: 14px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: 0.5px;">
                                    3 Steps to Start Earning Today:
                                </h3>

                                <div style="display: flex; margin-bottom: 12px;">
                                    <span style="font-weight: 800; color: #2563eb; margin-right: 8px;">1.</span>
                                    <span style="font-size: 14px; color: #334155; line-height: 20px;">
                                        <strong>Create your Storefront:</strong> Pick your store name, subdomain, and logo.
                                    </span>
                                </div>

                                <div style="display: flex; margin-bottom: 12px;">
                                    <span style="font-weight: 800; color: #2563eb; margin-right: 8px;">2.</span>
                                    <span style="font-size: 14px; color: #334155; line-height: 20px;">
                                        <strong>Fund Wholesale Capital:</strong> Top up your main capital wallet to fulfill customer orders at wholesale rates.
                                    </span>
                                </div>

                                <div style="display: flex;">
                                    <span style="font-weight: 800; color: #2563eb; margin-right: 8px;">3.</span>
                                    <span style="font-size: 14px; color: #334155; line-height: 20px;">
                                        <strong>Share your Link or APK:</strong> Connect a custom domain or share your branded Android APK with customers.
                                    </span>
                                </div>
                            </div>

                            <!-- CTA Buttons -->
                            <div style="text-align: center; margin: 28px 0 16px;">
                                <a href="{{ $loginUrl }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; padding: 14px 32px; border-radius: 10px; font-size: 15px; font-weight: 700; text-decoration: none; box-shadow: 0 4px 6px rgba(15, 23, 42, 0.15);">
                                    Open Merchant Dashboard &rarr;
                                </a>
                            </div>

                            <!-- WhatsApp Support Box -->
                            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 10px; padding: 14px 18px; margin-top: 24px; text-align: center;">
                                <p style="margin: 0; font-size: 13px; color: #166534; line-height: 20px;">
                                    💬 <strong>Need help launching?</strong> Our onboarding team is available on WhatsApp to walk you through setup.
                                </p>
                                <a href="https://wa.me/2348000000000?text={{ urlencode('Hello AffanHub Admin, I just registered as a merchant (' . $name . ') and would like some help setting up my store.') }}" style="display: inline-block; margin-top: 8px; font-size: 13px; font-weight: 700; color: #15803d; text-decoration: underline;">
                                    Chat with Onboarding Support on WhatsApp &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 24px 32px; background-color: #f9fafb; border-top: 1px solid #f3f4f6; text-align: center;">
                            <p style="margin: 0 0 6px; font-size: 12px; color: #64748b;">
                                You received this email because you registered on {{ $appName }}.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
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
