<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Page Not Found</title>
    
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
            background: radial-gradient(circle, rgba(79, 70, 229, 0.08) 0%, rgba(99, 102, 241, 0.02) 50%, transparent 70%);
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
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
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
            background: #fef2f2;
            border: 1px solid #fee2e2;
            color: #dc2626;
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
            background-color: #ef4444;
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
            margin-bottom: 22px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 9px;
            width: 100%;
        }

        @media (min-width: 640px) {
            .btn-group {
                flex-direction: row;
            }
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 10px 18px;
            min-height: 44px;
            font-size: 13.5px;
            font-weight: 600;
            font-family: inherit;
            border-radius: 11px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.15s ease;
            flex: 1;
            white-space: nowrap;
        }

        .btn svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            border: 1px solid transparent;
            box-shadow: 0 3px 12px var(--primary-shadow);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .btn-secondary:hover {
            background-color: #f8fafc;
            border-color: #94a3b8;
        }
    </style>
</head>
<body>

    <div class="ambient-glow"></div>

    <div class="card">
        
        <div class="icon-circle">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <div class="status-pill">
            <span class="status-dot"></span>
            <span>404 · Page Not Found</span>
        </div>

        <h1>Page Not Found</h1>

        <p class="desc">
            The page you're looking for doesn't exist, has been moved, or is temporarily unavailable.
        </p>

        <div class="btn-group">
            <a href="/" class="btn btn-primary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Back to Home</span>
            </a>

            <button type="button" onclick="history.back()" class="btn btn-secondary">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Go Back</span>
            </button>
        </div>

    </div>

</body>
</html>
