<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan Downgraded to Starter - {{ $appName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 30px 32px 22px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                <span style="color: #f59e0b;">Affan</span>Hub
                            </h1>
                            <p style="margin: 6px 0 0; font-size: 13px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                ℹ️ Store Plan Downgraded
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 32px 24px;">
                            <h2 style="margin: 0 0 12px; font-size: 19px; font-weight: 700; color: #111827;">
                                Hi {{ $owner->name ?? 'Merchant' }},
                            </h2>
                            <p style="margin: 0 0 20px; font-size: 15px; line-height: 24px; color: #4b5563;">
                                Your <strong>{{ $oldPlan->name ?? 'Pro' }} Plan</strong> grace period for <strong>{{ $store->name ?? 'your store' }}</strong> has ended.
                            </p>

                            <!-- Notice Box -->
                            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; margin: 24px 0;">
                                <h3 style="margin: 0 0 8px; font-size: 14px; font-weight: 800; color: #1e293b; text-transform: uppercase; letter-spacing: 0.5px;">
                                    Transitioned to {{ $newPlan->name }} Plan
                                </h3>
                                <p style="margin: 0; font-size: 14px; color: #475569; line-height: 22px;">
                                    Your store remains online and active on our free <strong>{{ $newPlan->name }} Plan</strong>. Custom domain routing and Pro wholesale discounts have been paused.
                                </p>
                            </div>

                            <p style="margin: 0 0 24px; font-size: 14px; color: #4b5563; line-height: 22px;">
                                You can upgrade back to <strong>{{ $oldPlan->name ?? 'Pro' }}</strong> at any time to instantly restore your custom domain, premium rates, and full automation!
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0 10px;">
                                <a href="{{ $billingUrl }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; font-size: 15px; font-weight: 800; text-decoration: none; padding: 14px 34px; border-radius: 10px; box-shadow: 0 2px 4px rgba(15, 23, 42, 0.2);">
                                    Upgrade Back to {{ $oldPlan->name ?? 'Pro' }} &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 20px; background-color: #f8fafc; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                Automated Billing Notice &bull; {{ $appName }} Platform
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
