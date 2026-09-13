<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation Expired or Invalid | AffanHub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: #0b0f19;
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .card {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 20px;
            max-width: 460px;
            width: 100%;
            padding: 36px 32px;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.4);
        }
        .icon-circle {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.12);
            border: 1px solid rgba(239, 68, 68, 0.25);
            color: #ef4444;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 28px;
        }
        h1 { font-size: 20px; font-weight: 800; color: #ffffff; margin-bottom: 8px; }
        p { font-size: 14px; color: #9ca3af; line-height: 1.6; margin-bottom: 24px; }
        .btn {
            display: inline-block;
            background: #f59e0b;
            color: #0b0f19;
            font-weight: 800;
            font-size: 14px;
            padding: 12px 28px;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn:hover { background: #d97706; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-circle">✕</div>
        <h1>{{ $isExpired ? 'Invitation Expired' : 'Invalid Invitation Link' }}</h1>
        <p>
            {{ $isExpired 
                ? 'This staff invitation link has expired. Please ask the store owner to resend your invitation from their Staff Members dashboard.' 
                : 'This invitation link is not valid or has already been used. Please verify the URL or ask the store owner to send a new invitation.' }}
        </p>
        <a href="/" class="btn">Return to AffanHub</a>
    </div>
</body>
</html>
