<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .header {
            background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .footer {
            padding: 24px;
            text-align: center;
            font-size: 14px;
            color: #64748b;
            background: #f1f5f9;
        }
        .button {
            display: inline-block;
            padding: 14px 32px;
            background: #6366f1;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            margin: 24px 0;
            transition: background 0.2s;
        }
        .colocation-name {
            font-size: 24px;
            font-weight: 800;
            color: #1e293b;
            margin: 8px 0;
        }
        .expiry {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 16px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="margin: 0; font-size: 28px; font-weight: 800;">EasyColoc</h1>
            <p style="margin: 8px 0 0 0; opacity: 0.9;">Smart Shared Living</p>
        </div>
        <div class="content">
            <h2 style="margin-top: 0; color: #1e293b;">You're invited!</h2>
            <p>Someone wants you to join their colocation on <strong>EasyColoc</strong>.</p>
            
            <div style="margin: 32px 0; padding: 24px; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <p style="text-transform: uppercase; font-size: 11px; letter-spacing: 0.05em; color: #64748b; margin-bottom: 8px;">Colocation Name</p>
                <div class="colocation-name">{{ $invitation->colocation->name }}</div>
            </div>

            <p>Ready to manage expenses and simplify your shared life?</p>
            <a href="{{ route('invitations.show', $invitation->token) }}" class="button">View Invitation</a>
            
            <p class="expiry">This invitation expires on {{ $invitation->expires_at->format('F j, Y') }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} EasyColoc. All rights reserved.
        </div>
    </div>
</body>
</html>
