<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Complete Your Profile - AffanHub Merchant</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, ::after, ::before {
            box-sizing: border-box;
            border: 0 solid #e5e7eb;
        }
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 0;
            background-color: #f9fafb;
            color: #111827;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        @media (prefers-color-scheme: dark) {
            body {
                background-color: #09090b;
                color: #f4f4f5;
            }
        }
        .card {
            background-color: #ffffff;
            border-radius: 1rem;
            border: 1px solid #e5e7eb;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 440px;
            padding: 2.25rem;
        }
        @media (prefers-color-scheme: dark) {
            .card {
                background-color: #18181b;
                border-color: rgba(255, 255, 255, 0.1);
            }
        }
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.25rem 0.625rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            background-color: #fef3c7;
            color: #b45309;
        }
        @media (prefers-color-scheme: dark) {
            .badge {
                background-color: rgba(245, 158, 11, 0.15);
                color: #fbbf24;
            }
        }
        .user-pill {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.75rem;
            background-color: #f3f4f6;
            margin: 1.25rem 0;
        }
        @media (prefers-color-scheme: dark) {
            .user-pill {
                background-color: rgba(255, 255, 255, 0.05);
            }
        }
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 9999px;
            object-fit: cover;
            background-color: #d1d5db;
        }
        .input-group {
            margin-top: 1rem;
        }
        .label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
            color: #374151;
        }
        @media (prefers-color-scheme: dark) {
            .label {
                color: #d1d5db;
            }
        }
        .input-control {
            width: 100%;
            height: 42px;
            padding: 0 0.875rem;
            font-size: 0.9375rem;
            border-radius: 0.5rem;
            border: 1px solid #d1d5db;
            background-color: #ffffff;
            color: #111827;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }
        .input-control:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.15);
        }
        @media (prefers-color-scheme: dark) {
            .input-control {
                background-color: #27272a;
                border-color: rgba(255, 255, 255, 0.15);
                color: #ffffff;
            }
            .input-control:focus {
                border-color: #f59e0b;
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.25);
            }
        }
        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 42px;
            margin-top: 1.5rem;
            border-radius: 0.5rem;
            background-color: #d97706;
            color: #ffffff;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.15s ease;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }
        .btn-submit:hover {
            background-color: #b45309;
        }
        .error-msg {
            margin-top: 0.375rem;
            font-size: 0.8125rem;
            color: #dc2626;
            font-weight: 500;
        }
        .helper-text {
            margin-top: 0.375rem;
            font-size: 0.75rem;
            color: #6b7280;
        }
        @media (prefers-color-scheme: dark) {
            .helper-text {
                color: #9ca3af;
            }
        }
        .footer-link {
            display: block;
            text-align: center;
            margin-top: 1.25rem;
            font-size: 0.8125rem;
            color: #6b7280;
            text-decoration: none;
        }
        .footer-link:hover {
            color: #111827;
            text-decoration: underline;
        }
        @media (prefers-color-scheme: dark) {
            .footer-link {
                color: #9ca3af;
            }
            .footer-link:hover {
                color: #f4f4f5;
            }
        }
    </style>
</head>
<body>
    <div class="card">
        <div style="text-align: center;">
            <div class="badge">
                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 10v4h2v-4h-2zm0 6v2h2v-2h-2z"/>
                </svg>
                Action Required
            </div>
            <h1 style="font-size: 1.375rem; font-weight: 700; margin: 0.75rem 0 0.25rem 0;">
                One Last Step
            </h1>
            <p style="font-size: 0.875rem; color: #6b7280; margin: 0;" class="helper-text">
                Please enter your active phone number to complete your merchant account setup.
            </p>
        </div>

        <div class="user-pill">
            @if ($owner->avatar)
                <img src="{{ $owner->avatar }}" alt="{{ $owner->name }}" class="user-avatar">
            @else
                <div class="user-avatar" style="display: flex; align-items: center; justify-content: center; background-color: #f59e0b; color: #ffffff; font-weight: 700; font-size: 14px;">
                    {{ strtoupper(substr($owner->name, 0, 1)) }}
                </div>
            @endif
            <div style="overflow: hidden; line-height: 1.3;">
                <div style="font-weight: 600; font-size: 0.875rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $owner->name }}
                </div>
                <div style="font-size: 0.75rem; color: #6b7280;" class="helper-text">
                    {{ $owner->email }}
                </div>
            </div>
        </div>

        <form action="{{ route('merchant.phone.store') }}" method="POST">
            @csrf

            <div class="input-group">
                <label for="phone" class="label">Phone Number <span style="color: #dc2626;">*</span></label>
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value="{{ old('phone', $owner->phone) }}"
                    placeholder="e.g. 08012345678"
                    required
                    autofocus
                    class="input-control"
                >
                @error('phone')
                    <div class="error-msg">{{ $message }}</div>
                @else
                    <div class="helper-text">
                        Used for security alerts, withdrawal verification, and store notifications.
                    </div>
                @enderror
            </div>

            <button type="submit" class="btn-submit">
                Complete Registration & Continue →
            </button>
        </form>

        <form action="{{ route('filament.merchant.auth.logout') }}" method="POST" style="margin-top: 1rem;">
            @csrf
            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; width: 100%; text-align: center;" class="footer-link">
                Sign out and use another account
            </button>
        </form>
    </div>
</body>
</html>
