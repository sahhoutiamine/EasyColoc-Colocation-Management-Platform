<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EasyColoc — Smart Colocation Management</title>
    <meta name="description" content="EasyColoc helps you manage shared housing effortlessly — track expenses, split costs, and live together stress-free.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { background: var(--bg-dark); overflow-x: hidden; }

        .hero-title {
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 900;
            letter-spacing: -0.04em;
            line-height: 1.05;
        }

        .feature-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.375rem;
            margin-bottom: 1rem;
        }

        .cta-glow {
            box-shadow: 0 0 50px rgba(99,102,241,0.3), 0 20px 60px rgba(0,0,0,0.4);
        }
    </style>
</head>
<body class="antialiased">

    <!-- Background Blobs -->
    <div style="position:fixed;inset:0;overflow:hidden;pointer-events:none;z-index:0;">
        <div class="float-blob" style="width:700px;height:700px;top:-200px;left:-150px;background:radial-gradient(circle,rgba(99,102,241,1),transparent);animation-duration:12s;"></div>
        <div class="float-blob" style="width:500px;height:500px;bottom:-100px;right:-100px;background:radial-gradient(circle,rgba(6,182,212,1),transparent);animation-duration:15s;animation-delay:4s;"></div>
        <div class="float-blob" style="width:400px;height:400px;top:50%;left:50%;transform:translate(-50%,-50%);background:radial-gradient(circle,rgba(16,185,129,0.5),transparent);animation-duration:10s;animation-delay:2s;"></div>
    </div>

    <!-- Navigation -->
    <nav style="position:relative;z-index:10;display:flex;align-items:center;justify-content:space-between;padding:1.25rem 2rem;max-width:1280px;margin:0 auto;">
        <div style="display:flex;align-items:center;gap:0.625rem;">
            <div style="width:2.25rem;height:2.25rem;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:0.625rem;display:flex;align-items:center;justify-content:center;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <span style="font-size:1.25rem;font-weight:800;background:linear-gradient(135deg,#818cf8,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">EasyColoc</span>
        </div>
        <div style="display:flex;gap:0.75rem;">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-ghost" style="text-decoration:none;">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost" style="text-decoration:none;">Sign in</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-primary" style="text-decoration:none;">Get started</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <main style="position:relative;z-index:1;">
        <div style="max-width:1280px;margin:0 auto;padding:5rem 2rem 4rem;text-align:center;">

            <!-- Badge -->
            <div style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.4rem 1rem;border-radius:9999px;background:rgba(99,102,241,0.12);border:1px solid rgba(99,102,241,0.25);color:var(--primary-light);font-size:0.8125rem;font-weight:600;margin-bottom:2rem;animation:slideDown 0.6s ease both;">
                <svg width="14" height="14" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                Smart Colocation Management Platform
            </div>

            <!-- Hero Title -->
            <h1 class="hero-title animate-fade-in" style="margin-bottom:1.5rem;">
                <span class="hero-gradient-text">Shared living,</span>
                <br>
                <span style="color:var(--text-primary);">made simple.</span>
            </h1>

            <p style="font-size:1.25rem;color:var(--text-secondary);max-width:600px;margin:0 auto 3rem;line-height:1.7;animation:fadeIn 0.6s 0.2s ease both;">
                EasyColoc makes it effortless to manage your shared home — split expenses, track balances, and live together <strong style="color:var(--text-primary);">stress-free</strong>.
            </p>

            <!-- CTA Buttons -->
            <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-bottom:4rem;animation:fadeIn 0.6s 0.3s ease both;">
                @auth
                    <a href="{{ url('/dashboard') }}" class="btn btn-primary btn-lg cta-glow" style="text-decoration:none;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></rect></svg>
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg cta-glow" style="text-decoration:none;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        Start for free
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-lg" style="text-decoration:none;">
                        Sign in
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                @endauth
            </div>

            <!-- Features Grid -->
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.25rem;text-align:left;max-width:1000px;margin:0 auto;animation:fadeIn 0.6s 0.4s ease both;">

                @php
                $features = [
                    [
                        'Create & Manage',
                        'Set up your colocation, invite housemates, and manage everything in one place.',
                        'rgba(99,102,241,0.1)',
                        '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#818cf8" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'
                    ],
                    [
                        'Split Expenses',
                        'Add expenses, assign payers, and let EasyColoc calculate who owes what automatically.',
                        'rgba(6,182,212,0.1)',
                        '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#67e8f9" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>'
                    ],
                    [
                        'Balance Tracking',
                        'Always know the current balance with a clear view of who owes who and how much.',
                        'rgba(16,185,129,0.1)',
                        '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#6ee7b7" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>'
                    ],
                    [
                        'Invite by Email',
                        'Send secure invitation links to your future housemates directly via email.',
                        'rgba(245,158,11,0.1)',
                        '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fcd34d" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>'
                    ],
                    [
                        'Reputation System',
                        'Build trust with a reputation score reflecting your financial responsibility.',
                        'rgba(239,68,68,0.1)',
                        '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fca5a5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>'
                    ],
                    [
                        'Admin Control',
                        'Global admins monitor the platform, manage users, and ensure smooth operation.',
                        'rgba(139,92,246,0.1)',
                        '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#a78bfa" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>'
                    ],
                ];
                @endphp
                @foreach($features as [$title, $desc, $bg, $icon])
                    <div class="glass-card" style="padding:1.5rem;">
                        <div class="feature-icon" style="background:{{ $bg }};">{!! $icon !!}</div>
                        <h3 style="font-size:1rem;font-weight:700;color:var(--text-primary);margin-bottom:0.5rem;">{{ $title }}</h3>
                        <p style="font-size:0.875rem;color:var(--text-secondary);line-height:1.6;">{{ $desc }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Footer -->
        <footer style="text-align:center;padding:3rem 2rem;color:var(--text-muted);font-size:0.875rem;border-top:1px solid rgba(255,255,255,0.05);position:relative;z-index:1;">
            <div style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-bottom:0.75rem;">
                <div style="width:1.5rem;height:1.5rem;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:0.375rem;display:flex;align-items:center;justify-content:center;">
                    <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <span style="font-weight:700;color:var(--text-secondary);">EasyColoc</span>
            </div>
            <p>© {{ date('Y') }} EasyColoc. Smart colocation management.</p>
        </footer>
    </main>
</body>
</html>
