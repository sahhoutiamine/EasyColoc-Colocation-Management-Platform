<x-guest-layout>
    <div class="auth-card animate-fade-in">

        <!-- Logo + Header -->
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.03em;margin-bottom:0.375rem;">Verify Email</h1>
            <p style="color:var(--text-secondary);font-size:0.9375rem;">Confirm your email address to continue</p>
        </div>

        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);border-radius:0.75rem;padding:1rem;margin-bottom:1.5rem;font-size:0.875rem;color:var(--text-secondary);line-height:1.6;">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div style="background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.2);color:#6ee7b7;padding:0.75rem;border-radius:0.625rem;font-size:0.8125rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:0.5rem;">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div style="display:flex;flex-direction:column;gap:1rem;">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-ghost" style="width:100%;justify-content:center;color:var(--text-muted);">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
