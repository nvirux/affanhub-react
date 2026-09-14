<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Under Maintenance</title>

    {{-- Google Fonts matching AffanHub Design System --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400..700;1,400..700&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-shadow: rgba(79, 70, 229, 0.22);
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            height: 100%;
            height: 100dvh;
            overflow: hidden;
        }

        body {
            font-family: 'Instrument Sans', 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
            -webkit-font-smoothing: antialiased;
            position: relative;
        }

        .ambient-glow {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 450px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, rgba(79, 70, 229, 0.03) 50%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .card {
            position: relative;
            z-index: 1;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            padding: 30px 20px;
            max-width: 400px;
            width: 100%;
            text-align: center;
            box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.05), 0 0 0 1px rgba(0, 0, 0, 0.02);
        }

        @media (min-width: 640px) {
            .card {
                padding: 38px 28px;
            }
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            color: #d97706;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 14px;
        }

        .icon-circle svg {
            width: 24px;
            height: 24px;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 3px 10px;
            background: #fffbeb;
            border: 1px solid #fef3c7;
            color: #b45309;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.02em;
            text-transform: uppercase;
            margin-bottom: 12px;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #f59e0b;
            animation: pulse-slow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse-slow {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.35; transform: scale(0.9); }
        }

        h1 {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.02em;
            line-height: 1.2;
            margin-bottom: 8px;
        }

        @media (min-width: 640px) {
            h1 {
                font-size: 26px;
            }
        }

        p.desc {
            font-size: 13.5px;
            line-height: 1.5;
            color: #64748b;
            margin-bottom: 16px;
        }

        .reassurance-box {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 12px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 11.5px;
            color: #475569;
            text-align: left;
            line-height: 1.35;
            margin-bottom: 20px;
        }

        .reassurance-box svg {
            width: 15px;
            height: 15px;
            color: #059669;
            flex-shrink: 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            width: 100%;
            padding: 10px 18px;
            min-height: 44px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: inherit;
            border-radius: 11px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            background-color: var(--primary);
            color: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0 3px 12px var(--primary-shadow);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-primary svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }
    </style>
</head>
<body>

    <div class="ambient-glow"></div>

    <div class="card">
        
        <div class="icon-circle">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div class="status-pill">
            <span class="status-dot"></span>
            <span>503 · Scheduled Maintenance</span>
        </div>

        <h1>We'll be back shortly</h1>

        <p class="desc">
            We're currently performing scheduled system updates to improve speed and reliability. Services will resume in a few minutes.
        </p>

        <div class="reassurance-box">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
            <span>All account balances, wallets, and transactions remain completely safe.</span>
        </div>

        <div>
            <button type="button" onclick="window.location.reload()" class="btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span>Check Again / Refresh</span>
            </button>
        </div>

    </div>

</body>
</html>
