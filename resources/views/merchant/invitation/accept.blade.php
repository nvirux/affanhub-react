<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join {{ $store->name }} | AffanHub Merchant Invitation</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #090d16;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 16px;
        }
        .container {
            max-width: 480px;
            width: 100%;
        }
        .brand-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .brand-logo {
            font-size: 24px;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: #ffffff;
            text-transform: uppercase;
        }
        .brand-logo span { color: #f59e0b; }
        .brand-subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 4px;
            font-weight: 500;
        }
        .card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 20px;
            padding: 32px 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .store-banner {
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.2);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .store-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: #f59e0b;
            color: #0b0f19;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            shrink-0;
        }
        .store-info { flex: 1; min-width: 0; }
        .store-name { font-size: 16px; font-weight: 800; color: #ffffff; truncate; }
        .store-role-badge {
            display: inline-block;
            margin-top: 3px;
            background: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #cbd5e1;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        input {
            width: 100%;
            background: #1f2937;
            border: 1px solid #374151;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 14px;
            color: #ffffff;
            outline: none;
            transition: border-color 0.15s;
            font-family: inherit;
        }
        input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.2);
        }
        input:disabled, input[readonly] {
            background: #182234;
            color: #94a3b8;
            cursor: not-allowed;
            border-color: #26334d;
        }
        .error-msg {
            color: #f87171;
            font-size: 12px;
            font-weight: 600;
            margin-top: 5px;
        }
        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
            padding: 12px 14px;
            border-radius: 10px;
            font-size: 13px;
            margin-bottom: 18px;
        }
        .btn {
            width: 100%;
            background: #f59e0b;
            color: #0b0f19;
            font-weight: 800;
            font-size: 14px;
            padding: 14px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            transition: background 0.15s ease, transform 0.05s ease;
            box-shadow: 0 4px 14px rgba(245, 158, 11, 0.3);
            margin-top: 6px;
        }
        .btn:hover { background: #d97706; }
        .btn:active { transform: scale(0.99); }
        .helper-text {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
            line-height: 1.4;
        }
        .footer-note {
            text-align: center;
            margin-top: 20px;
            font-size: 12px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="brand-header">
            <div class="brand-logo">Affan<span>Hub</span></div>
            <div class="brand-subtitle">Merchant Team Onboarding</div>
        </div>

        <div class="card">
            <div class="store-banner">
                <div class="store-icon">🏪</div>
                <div class="store-info">
                    <div class="store-name">{{ $store->name }}</div>
                    <span class="store-role-badge">Role: {{ ucfirst($invitation->role) }}</span>
                </div>
            </div>

            @if (isset($errors) && $errors->any())
                <div class="alert-error">
                    @foreach ($errors->all() as $error)
                        <div>• {{ $error }}</div>
                    @endforeach
                </div>
            @endif

            {{-- CASE 1: ALREADY LOGGED IN AS THE INVITED ACCOUNT --}}
            @if ($currentUser && strtolower($currentUser->email) === strtolower($invitation->email))
                <div style="text-align: center; margin-bottom: 24px;">
                    <div style="font-size: 15px; font-weight: 700; color: #ffffff; margin-bottom: 6px;">
                        Signed in as {{ $currentUser->name }}
                    </div>
                    <div style="font-size: 13px; color: #94a3b8;">
                        Click below to accept this invitation and immediately access {{ $store->name }} from your merchant dashboard.
                    </div>
                </div>

                <form method="POST" action="{{ route('merchant.invitation.accept-existing', ['token' => $invitation->token]) }}">
                    @csrf
                    <button type="submit" class="btn">Accept Invitation & Enter Store</button>
                </form>

            {{-- CASE 2: EXISTING AFFANHUB ACCOUNT (NOT LOGGED IN) --}}
            @elseif ($existingOwner)
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 16px; font-weight: 800; color: #ffffff; margin-bottom: 6px;">
                        Welcome back, {{ $existingOwner->name }}!
                    </div>
                    <div style="font-size: 13px; color: #94a3b8; line-height: 1.5;">
                        You already have an AffanHub account. Enter your password to accept this invitation and add <strong>{{ $store->name }}</strong> to your dashboard.
                    </div>
                </div>

                <form method="POST" action="{{ route('merchant.invitation.accept-existing', ['token' => $invitation->token]) }}">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" value="{{ $invitation->email }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Your Account Password</label>
                        <input type="password" name="password" required placeholder="Enter your current password" autofocus>
                        @error('password')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn">Log In & Accept Invitation</button>
                </form>

            {{-- CASE 3: BRAND NEW USER (SELF-REGISTRATION) --}}
            @else
                <div style="margin-bottom: 20px;">
                    <div style="font-size: 16px; font-weight: 800; color: #ffffff; margin-bottom: 6px;">
                        Create Your Staff Account
                    </div>
                    <div style="font-size: 13px; color: #94a3b8; line-height: 1.5;">
                        Complete your details below to set up your account and access the <strong>{{ $store->name }}</strong> merchant portal.
                    </div>
                </div>

                <form method="POST" action="{{ route('merchant.invitation.register', ['token' => $invitation->token]) }}">
                    @csrf
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" value="{{ $invitation->email }}" readonly>
                        <div class="helper-text">Assigned to this invitation</div>
                    </div>

                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="e.g. Jane Doe" autofocus>
                        @error('name')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Phone Number (Optional)</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="e.g. 08012345678">
                        @error('phone')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Create Password</label>
                        <input type="password" name="password" required placeholder="Minimum 8 characters">
                        @error('password')
                            <div class="error-msg">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" required placeholder="Re-enter password">
                    </div>

                    <button type="submit" class="btn">Create Account & Join Store</button>
                </form>
            @endif
        </div>

        <div class="footer-note">
            Invitation expires on {{ $invitation->expires_at->format('M d, Y') }} • Powered by AffanHub
        </div>
    </div>
</body>
</html>
