<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Settlement Account - {{ $appName }}</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f3f4f6;">
        <tr>
            <td align="center" style="padding: 40px 16px;">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 580px; background-color: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); border: 1px solid #e5e7eb;">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="padding: 30px 32px 22px; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
                            <h1 style="margin: 0; font-size: 24px; font-weight: 800; color: #ffffff; letter-spacing: -0.5px;">
                                <span style="color: #f59e0b;">Affan</span>Hub Admin
                            </h1>
                            <p style="margin: 6px 0 0; font-size: 13px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">
                                🏦 Merchant Settlement Bank Verification Required
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 32px 32px 24px;">
                            <h2 style="margin: 0 0 12px; font-size: 19px; font-weight: 700; color: #111827;">
                                New Bank Account Submitted
                            </h2>
                            <p style="margin: 0 0 20px; font-size: 14px; line-height: 22px; color: #4b5563;">
                                A merchant has submitted Nigerian bank account details to receive profit payouts. Please review and approve or reject in the admin panel.
                            </p>

                            <!-- Bank Details Table -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #e5e7eb; border-radius: 10px; border-collapse: separate; overflow: hidden; margin-bottom: 24px;">
                                <tr style="background-color: #f9fafb;">
                                    <td colspan="2" style="padding: 10px 14px; font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #e5e7eb;">
                                        Submitted Account Details
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #f3f4f6; width: 40%;">Bank Name:</td>
                                    <td style="padding: 10px 14px; font-size: 13px; font-weight: 600; color: #111827; border-bottom: 1px solid #f3f4f6;">{{ $account->bank_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #f3f4f6;">Account Number:</td>
                                    <td style="padding: 10px 14px; font-size: 15px; font-weight: 800; color: #1e40af; font-family: monospace; border-bottom: 1px solid #f3f4f6;">{{ $account->account_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #f3f4f6;">Account Name:</td>
                                    <td style="padding: 10px 14px; font-size: 13px; font-weight: 600; color: #111827; border-bottom: 1px solid #f3f4f6;">{{ $account->account_name }}</td>
                                </tr>
                                <tr style="background-color: #f9fafb;">
                                    <td colspan="2" style="padding: 10px 14px; font-size: 12px; font-weight: 700; color: #374151; text-transform: uppercase; letter-spacing: 0.5px; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb;">
                                        Store &amp; Merchant Info
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #6b7280; border-bottom: 1px solid #f3f4f6;">Store Name:</td>
                                    <td style="padding: 10px 14px; font-size: 13px; font-weight: 600; color: #111827; border-bottom: 1px solid #f3f4f6;">{{ $store->name ?? 'Store #'.$account->store_id }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #6b7280;">Merchant:</td>
                                    <td style="padding: 10px 14px; font-size: 13px; color: #111827;">{{ $owner->name ?? 'Merchant' }} ({{ $owner->email ?? 'N/A' }})</td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin: 28px 0 10px;">
                                <a href="{{ $adminUrl }}" style="display: inline-block; background-color: #0f172a; color: #ffffff; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(15, 23, 42, 0.2);">
                                    Review &amp; Approve Account in Admin &rarr;
                                </a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td align="center" style="padding: 20px; background-color: #f8fafc; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                Automated Super Admin Alert &bull; {{ $appName }} Platform
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
