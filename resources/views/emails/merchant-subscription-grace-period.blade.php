<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grace Period Started - {{ $appName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="padding: 30px 32px 22px; background: linear-gradient(135deg, #450a0a 0%, #1e293b 100%);">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                <span style="color: #f87171;">Affan</span>Hub
                            </h1>
                            <p style="margin: 6px 0 0; font-size: 13px; color: #fecaca; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                🚨 Grace Period Activated
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
                                Your <strong>{{ $plan->name ?? 'Pro' }} Plan</strong> subscription for <strong>{{ $store->name ?? 'your store' }}</strong> expired on 
                                <strong>{{ $subscription->ends_at?->format('F j, Y') }}</strong>.
                            </p>

                            <!-- Grace Period Alert -->
                            <div style="background-color: #fef2f2; border: 1.5px solid #f87171; border-radius: 12px; padding: 20px; margin: 24px 0; text-align: center;">
                                <div style="font-size: 13px; font-weight: 800; color: #991b1b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                                    {{ $graceDays }}-Day Grace Period Active
                                </div>
                                <div style="font-size: 15px; color: #7f1d1d; line-height: 22px;">
                                    To protect your store and prevent customer disruptions, your store will remain fully functional until 
                                    <strong>{{ $graceEndsAt?->format('F j, Y') }}</strong>.
                                </div>
                                <div style="font-size: 13px; color: #b91c1c; margin-top: 10px;">
                                    If not renewed by then, your store will automatically transition to the free Starter Plan.
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 30px 0 10px;">
                                <a href="{{ $billingUrl }}" style="display: inline-block; background-color: #dc2626; color: #ffffff; font-size: 15px; font-weight: 800; text-decoration: none; padding: 14px 34px; border-radius: 10px; box-shadow: 0 2px 4px rgba(220, 38, 38, 0.3);">
                                    Renew Subscription Now &rarr;
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
