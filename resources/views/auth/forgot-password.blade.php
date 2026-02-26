<x-guest-layout>
    <div class="auth-card animate-fade-in">

        <!-- Logo + Header -->
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.03em;margin-bottom:0.375rem;">Reset Password</h1>
            <p style="color:var(--text-secondary);font-size:0.9375rem;">Enter your email to receive a recovery link</p>
        </div>

        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.05);border-radius:0.75rem;padding:1rem;margin-bottom:1.5rem;font-size:0.875rem;color:var(--text-secondary);line-height:1.6;">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="alert alert-info" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" style="display:flex;flex-direction:column;gap:1.5rem;">
            @csrf

            <!-- Email Address -->
            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="form-input" placeholder="you@example.com">
                @error('email')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;">
                {{ __('Email Password Reset Link') }}
            </button>
        </form>

        <!-- Back to Login -->
        <div style="text-align:center;margin-top:1.75rem;padding-top:1.75rem;border-top:1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('login') }}" style="color:var(--text-secondary);font-size:0.875rem;text-decoration:none;display:inline-flex;align-items:center;gap:0.5rem;transition:color 0.2s;"
                onmouseenter="this.style.color='var(--text-primary)'" onmouseleave="this.style.color='var(--text-secondary)'">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                Back to sign in
            </a>
        </div>
    </div>
</x-guest-layout>
