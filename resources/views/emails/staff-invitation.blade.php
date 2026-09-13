<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation to join {{ $store->name }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 0;
            -webkit-text-size-adjust: none;
        }
        .wrapper {
            width: 100%;
            background-color: #f8fafc;
            padding: 40px 16px;
        }
        .card {
            max-width: 540px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        .header {
            padding: 32px 32px 24px;
            text-align: center;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%);
            color: #ffffff;
        }
        .brand {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            text-transform: uppercase;
        }
        .brand span {
            color: #f59e0b;
        }
        .badge {
            display: inline-block;
            margin-top: 14px;
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.25);
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .content {
            padding: 32px;
        }
        h1 {
            font-size: 20px;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 12px;
            letter-spacing: -0.3px;
        }
        p {
            font-size: 14px;
            line-height: 1.6;
            color: #475569;
            margin: 0 0 16px;
        }
        .invite-box {
            background-color: #f1f5f9;
            border-radius: 12px;
            padding: 16px;
            margin: 20px 0;
            border: 1px solid #e2e8f0;
        }
        .invite-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
        }
        .invite-row:last-child {
            margin-bottom: 0;
        }
        .label {
            color: #64748b;
            font-weight: 600;
        }
        .val {
            color: #0f172a;
            font-weight: 700;
        }
        .btn-wrapper {
            text-align: center;
            margin: 28px 0 20px;
        }
        .btn {
            display: inline-block;
            background-color: #f59e0b;
            color: #0f172a;
            font-size: 14px;
            font-weight: 800;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.35);
        }
        .footer {
            padding: 20px 32px 32px;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            font-size: 12px;
            color: #94a3b8;
        }
        .note {
            font-size: 12px;
            color: #64748b;
            margin-top: 16px;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            padding: 10px 14px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="header">
                <div class="brand">Affan<span>Hub</span></div>
                <div class="badge">Team Invitation</div>
            </div>

            <div class="content">
                <h1>You're invited to join {{ $store->name }}</h1>
                <p>
                    Hello,
                </p>
                <p>
                    <strong>{{ $inviterName }}</strong> has invited you to join the team at <strong>{{ $store->name }}</strong> on AffanHub.
                </p>

                <div class="invite-box">
                    <div class="invite-row">
                        <span class="label">Store:</span>
                        <span class="val">{{ $store->name }}</span>
                    </div>
                    <div class="invite-row">
                        <span class="label">Invited Role:</span>
                        <span class="val">{{ $role }}</span>
                    </div>
                    <div class="invite-row">
                        <span class="label">Email:</span>
                        <span class="val">{{ $invitation->email }}</span>
                    </div>
                    <div class="invite-row">
                        <span class="label">Expires:</span>
                        <span class="val">{{ $expiresAt }}</span>
                    </div>
                </div>

                <div class="btn-wrapper">
                    <a href="{{ $acceptUrl }}" class="btn" target="_blank">Accept Invitation & Join Team</a>
                </div>

                <div class="note">
                    <strong>Have an existing AffanHub account?</strong> Simply log in with your credentials to accept. If you're new, you'll be able to choose your own password and set up your profile.
                </div>

                <p style="margin-top: 24px; font-size: 12px; color: #94a3b8; word-break: break-all;">
                    Or copy and paste this link into your browser:<br>
                    <a href="{{ $acceptUrl }}" style="color: #6366f1;">{{ $acceptUrl }}</a>
                </p>
            </div>

            <div class="footer">
                &copy; {{ date('Y') }} AffanHub Technologies. All rights reserved.<br>
                If you did not expect this invitation, you can safely ignore this email.
            </div>
        </div>
    </div>
</body>
</html>
