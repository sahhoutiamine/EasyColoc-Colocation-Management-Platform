<x-app-layout>
<div class="page-container" style="max-width:560px;">
    <div style="display:flex;align-items:center;justify-content:center;min-height:60vh;">

        <div style="width:100%;">
            <!-- Animated envelope icon -->
            <div style="text-align:center;margin-bottom:2rem;" class="animate-fade-in">
                <div style="width:5rem;height:5rem;border-radius:1.5rem;background:linear-gradient(135deg,rgba(99,102,241,0.2),rgba(6,182,212,0.2));border:1px solid rgba(99,102,241,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;animation:floatAnim 4s ease-in-out infinite;">
                    <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="url(#grad)" stroke-width="1.75">
                        <defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" stop-color="#818cf8"/><stop offset="100%" stop-color="#67e8f9"/></linearGradient></defs>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h1 style="font-size:2rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.03em;margin-bottom:0.5rem;">You're invited!</h1>
                <p style="color:var(--text-secondary);font-size:1rem;">You've been invited to join a colocation</p>
            </div>

            <!-- Invitation Card -->
            <div class="glass-card invitation-glow animate-fade-in-delay-1" style="padding:2rem;text-align:center;">
                <!-- Colocation Name -->
                <div style="margin-bottom:1.75rem;">
                    <p style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-muted);margin-bottom:0.625rem;">Colocation</p>
                    <div style="font-size:2rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.03em;margin-bottom:0.75rem;">
                        {{ $invitation->colocation->name }}
                    </div>
                    <div style="display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;font-size:0.8125rem;color:var(--text-muted);">
                        <div style="display:flex;align-items:center;gap:0.375rem;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Expires {{ $invitation->expires_at->format('F j, Y') }}
                        </div>
                        <div style="display:flex;align-items:center;gap:0.375rem;">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            {{ $invitation->colocation->memberships()->whereNull('left')->count() }} member(s)
                        </div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Actions -->
                <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;margin-top:1.5rem;">
                    <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            Accept & Join
                        </button>
                    </form>
                    <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}">
                        @csrf
                        <button type="submit" class="btn btn-ghost btn-lg" style="color:var(--text-secondary);"
                            onclick="return confirm('Decline this invitation?')">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Decline
                        </button>
                    </form>
                </div>

                <p style="font-size:0.8125rem;color:var(--text-muted);margin-top:1.5rem;">
                    By accepting, you'll become a member of this colocation.
                    <br>You can only be part of one active colocation at a time.
                </p>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
