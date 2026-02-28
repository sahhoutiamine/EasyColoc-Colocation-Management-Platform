<x-app-layout>
<div class="page-container" style="max-width:640px;">

    <div class="page-header animate-fade-in">
        <a href="{{ route('colocations.index') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--text-muted);font-size:0.875rem;text-decoration:none;margin-bottom:1.25rem;transition:color 0.2s;"
            onmouseenter="this.style.color='var(--text-secondary)'" onmouseleave="this.style.color='var(--text-muted)'">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
            Back to My Colocation
        </a>
        <h1 class="page-title">Edit Colocation</h1>
        <p class="page-subtitle">Update your colocation details</p>
    </div>

    <div class="glass-card animate-fade-in-delay-1" style="padding:2rem;">

        <!-- Current Info -->
        <div style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:0.75rem;padding:1rem 1.25rem;margin-bottom:2rem;display:flex;align-items:center;gap:1rem;">
            <div style="width:2.75rem;height:2.75rem;border-radius:0.75rem;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            </div>
            <div>
                <p style="font-size:0.75rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:0.2rem;">Currently</p>
                <p style="font-weight:700;color:var(--text-primary);font-size:1rem;">{{ $colocation->name }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('colocations.update', $colocation) }}" style="display:flex;flex-direction:column;gap:1.5rem;">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name" class="form-label">
                    New colocation name <span style="color:var(--danger);">*</span>
                </label>
                <input
                    type="text"
                    name="name"
                    id="name"
                    required
                    class="form-input"
                    placeholder="Enter new name…"
                    value="{{ old('name', $colocation->name) }}"
                    style="font-size:1rem;"
                >
                @error('name')
                    <p class="form-error">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div style="display:flex;gap:0.75rem;padding-top:0.5rem;">
                <button type="submit" class="btn btn-primary btn-lg">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    Save Changes
                </button>
                <a href="{{ route('colocations.index') }}" class="btn btn-ghost btn-lg">Cancel</a>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
