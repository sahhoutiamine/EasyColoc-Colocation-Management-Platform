<x-app-layout>
<div class="page-container" style="max-width:640px;">

    <div class="page-header animate-fade-in">
        <a href="{{ route('colocations.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--text-muted);font-size:0.875rem;text-decoration:none;margin-bottom:1.25rem;transition:color 0.2s;"
            onmouseenter="this.style.color='var(--text-secondary)'" onmouseleave="this.style.color='var(--text-muted)'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            Back to My Colocation
        </a>
        <h1 class="page-title">Create a Colocation</h1>
        <p class="page-subtitle">Set up your shared living space and invite your housemates</p>
    </div>

    <div class="glass-card animate-fade-in-delay-1" style="padding:2rem;">

        <!-- Tips -->
        <div style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.2);border-radius:0.75rem;padding:1rem 1.25rem;margin-bottom:2rem;display:flex;gap:0.875rem;">
            <div style="flex-shrink:0;width:1.75rem;height:1.75rem;display:flex;align-items:center;justify-content:center;">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--primary-light)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </div>
            <div>
                <p style="font-size:0.875rem;font-weight:600;color:var(--primary-light);margin-bottom:0.25rem;">Tip</p>
                <p style="font-size:0.8125rem;color:var(--text-secondary);line-height:1.6;">
                    You'll automatically become the <strong>Owner</strong> of this colocation. As owner, you can invite members, manage expenses, and oversee the space.
                </p>
            </div>
        </div>

        <form method="POST" action="{{ route('colocations.store') }}" style="display:flex;flex-direction:column;gap:1.5rem;">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">
                    Colocation name <span style="color:var(--danger);">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    required
                    class="form-input"
                    placeholder="e.g. Sunshine Loft, The Blue House…"
                    value="{{ old('name') }}"
                    style="font-size:1rem;"
                >
                @error('name')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
                <p style="font-size:0.8125rem;color:var(--text-muted);">Give your colocation a memorable name. You can always change it later.</p>
            </div>

            <div style="display:flex;gap:0.75rem;padding-top:0.5rem;">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Create Colocation
                </button>
                <a href="{{ route('colocations.index') }}" class="btn btn-ghost btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
