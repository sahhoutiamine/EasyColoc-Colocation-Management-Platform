<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #0f172a;
            color: #f8fafc;
            padding: 40px;
        }
        .card {
            background-color: #1e293b;
            border-radius: 12px;
            padding: 32px;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #334155;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #6366f1;
            font-size: 24px;
            margin-bottom: 24px;
        }
        p {
            line-height: 1.6;
            margin-bottom: 24px;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            color: #ffffff !important;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            box-shadow: 0 4px 14px 0 rgba(99, 102, 241, 0.39);
        }
        .footer {
            margin-top: 32px;
            font-size: 14px;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>You're Invited!</h1>
        <p>Hello,</p>
        <p>You have been invited to join the colocation <strong>{{ $invitation->colocation->name }}</strong> on EasyColoc.</p>
        <p>Click the button below to view and respond to the invitation:</p>
        <a href="{{ route('invitations.show', $invitation->token) }}" class="btn">View Invitation</a>
        <p>If you don't have an account, you'll need to create one first to accept the invitation.</p>
        <div class="footer">
            &copy; {{ date('Y') }} EasyColoc. All rights reserved.
        </div>
    </div>
</body>
</html>
