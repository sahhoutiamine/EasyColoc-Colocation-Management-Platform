<x-guest-layout>
    <div class="auth-card animate-fade-in">

        <!-- Logo + Header -->
        <div style="text-align:center;margin-bottom:2rem;">
            <div style="width:3.5rem;height:3.5rem;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:1rem;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;">
                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
            </div>
            <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.03em;margin-bottom:0.375rem;">Create account</h1>
            <p style="color:var(--text-secondary);font-size:0.9375rem;">Join EasyColoc and manage your colocation</p>
        </div>

        <form method="POST" action="{{ route('register') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
            @csrf

            <!-- Name -->
            <div class="form-group">
                <label for="name" class="form-label">Full name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                    class="form-input" placeholder="John Doe">
                @error('name')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label for="email" class="form-label">Email address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
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
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="form-input" placeholder="Min. 8 characters">
                @error('password')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirm password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                    class="form-input" placeholder="Repeat your password">
                @error('password_confirmation')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:0.5rem;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                Create my account
            </button>
        </form>

        <!-- Login link -->
        <div style="text-align:center;margin-top:1.75rem;padding-top:1.75rem;border-top:1px solid rgba(255,255,255,0.06);">
            <p style="font-size:0.875rem;color:var(--text-secondary);">
                Already have an account?
                <a href="{{ route('login') }}" style="color:var(--primary-light);font-weight:600;text-decoration:none;margin-left:0.25rem;transition:color 0.2s;"
                    onmouseenter="this.style.color='#c7d2fe'" onmouseleave="this.style.color='var(--primary-light)'">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</x-guest-layout>
