<x-app-layout>
<div class="page-container" style="max-width:600px;">

    <div class="page-header animate-fade-in">
        <a href="{{ route('colocations.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--text-muted);font-size:0.875rem;text-decoration:none;margin-bottom:1.25rem;transition:color 0.2s;"
            onmouseenter="this.style.color='var(--text-secondary)'" onmouseleave="this.style.color='var(--text-muted)'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            Back to My Colocation
        </a>
        <h1 class="page-title">Invite a Member</h1>
        <p class="page-subtitle">Send an invitation to join <strong style="color:var(--text-primary);">{{ $colocation->name }}</strong></p>
    </div>

    <div class="glass-card animate-fade-in-delay-1" style="padding:2rem;">

        <!-- Info card -->
        <div style="background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.2);border-radius:0.75rem;padding:1rem 1.25rem;margin-bottom:2rem;">
            <div style="display:flex;gap:0.875rem;align-items:flex-start;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#67e8f9" stroke-width="2" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <div>
                    <p style="font-size:0.875rem;font-weight:600;color:#67e8f9;margin-bottom:0.25rem;">How invitations work</p>
                    <p style="font-size:0.8125rem;color:var(--text-secondary);line-height:1.6;">
                        An email will be sent to the address below with a secure link valid for <strong>7 days</strong>. The person must not already be part of another colocation.
                    </p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('invitations.store', $colocation) }}" style="display:flex;flex-direction:column;gap:1.5rem;">
            @csrf

            <div class="form-group">
                <label for="email" class="form-label">
                    Email address <span style="color:var(--danger);">*</span>
                </label>
                <div style="position:relative;">
                    <div style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="rgba(100,116,139,0.8)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        class="form-input"
                        placeholder="housemate@example.com"
                        value="{{ old('email') }}"
                        style="padding-left:2.875rem;font-size:1rem;"
                    >
                </div>
                @error('email')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div style="display:flex;gap:0.75rem;padding-top:0.5rem;">
                <button type="submit" class="btn btn-accent btn-lg">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    Send Invitation
                </button>
                <a href="{{ route('colocations.index') }}" class="btn btn-ghost btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
