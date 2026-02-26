<x-guest-layout>
    <div class="auth-card animate-fade-in">

        <!-- Logo + Header -->
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
            </div>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.03em;margin-bottom:0.375rem;">Welcome back</h1>
            <p style="color:var(--text-secondary);font-size:0.9375rem;">Sign in to your EasyColoc account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="alert alert-info" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
            @csrf

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                    class="form-input" placeholder="you@example.com">
                @error('email')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <div style="display:flex;justify-content:space-between;align-items:center;">
                    <label for="password" class="form-label">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="font-size:0.8125rem;color:var(--primary-light);text-decoration:none;transition:color 0.2s;"
                            onmouseenter="this.style.color='#c7d2fe'" onmouseleave="this.style.color='var(--primary-light)'">
                            Forgot password?
                        </a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="form-input" placeholder="••••••••">
                @error('password')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Remember Me -->
            <label style="display:flex;align-items:center;gap:0.625rem;cursor:pointer;">
                <input id="remember_me" type="checkbox" name="remember"
                    style="width:1rem;height:1rem;border-radius:0.25rem;accent-color:#6366f1;cursor:pointer;">
                <span style="font-size:0.875rem;color:var(--text-secondary);">Remember me for 30 days</span>
            </label>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                Sign in
            </button>
        </form>

        <!-- Register link -->
        <div style="text-align:center;margin-top:1.75rem;padding-top:1.75rem;border-top:1px solid rgba(255,255,255,0.06);">
            <p style="font-size:0.875rem;color:var(--text-secondary);">
                Don't have an account?
                <a href="{{ route('register') }}" style="color:var(--primary-light);font-weight:600;text-decoration:none;margin-left:0.25rem;transition:color 0.2s;"
                    onmouseenter="this.style.color='#c7d2fe'" onmouseleave="this.style.color='var(--primary-light)'">
                    Create account
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
