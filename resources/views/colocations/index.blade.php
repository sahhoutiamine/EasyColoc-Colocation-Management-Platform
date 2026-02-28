<x-app-layout>
@php
    $user = auth()->user();
    $members = $activeMembership?->colocation?->memberships()->whereNull('left')->with('user')->get() ?? collect();
    $colocation = $activeMembership?->colocation;
@endphp

<div class="page-container">

    <!-- Page Header -->
    <div class="page-header animate-fade-in" style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:1rem;">
        <div>
            <p style="font-size:0.8125rem;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:0.5rem;">Colocation</p>
            <h1 class="page-title">My Colocation</h1>
            <p class="page-subtitle">Manage your shared living space and housemates</p>
        </div>
        @if(!$activeMembership)
            <a href="{{ route('colocations.create') }}" class="btn btn-primary animate-fade-in">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Create Colocation
            </a>
        @endif
    </div>

    @if($activeMembership && $colocation)
        <!-- Colocation Overview Card -->
        <div class="glass-card animate-fade-in" style="padding:2rem;margin-bottom:1.5rem;background:linear-gradient(135deg,rgba(99,102,241,0.08) 0%,rgba(6,182,212,0.04) 100%);">
            <div style="display:flex;flex-wrap:wrap;align-items:flex-start;justify-content:space-between;gap:1.5rem;">
                <div>
                    <div style="display:flex;align-items:center;gap:0.875rem;margin-bottom:0.875rem;">
                        <div style="width:3rem;height:3rem;border-radius:0.875rem;background:linear-gradient(135deg,#6366f1,#4f46e5);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 15px rgba(99,102,241,0.3);">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                        <div>
                            <h2 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.03em;line-height:1.1;">
                                {{ $colocation->name }}
                            </h2>
                            <div style="display:flex;gap:0.5rem;align-items:center;margin-top:0.25rem;">
                                <span class="{{ $activeMembership->role === 'OWNER' ? 'badge badge-owner' : 'badge badge-member' }}">
                                    @if($activeMembership->role === 'OWNER')
                                        <svg width="10" height="10" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endif
                                    {{ $activeMembership->role }}
                                </span>
                                <span class="badge badge-active">
                                    <svg width="7" height="7" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                                    Active
                                </span>
                            </div>
                        </div>
                    </div>
                    <p style="font-size:0.875rem;color:var(--text-muted);">
                        Member since <strong style="color:var(--text-secondary);">{{ $activeMembership->join->format('F j, Y') }}</strong>
                        &nbsp;·&nbsp; {{ $members->count() }} {{ Str::plural('member', $members->count()) }}
                    </p>
                </div>

                <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;">
                    <a href="{{ route('colocations.expenses.index', $colocation) }}" class="btn btn-primary" style="background:linear-gradient(135deg,var(--primary),var(--accent));">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Expenses & Balances
                    </a>
                    @if($activeMembership->role === 'OWNER')
                        <a href="{{ route('invitations.create', $colocation) }}" class="btn btn-accent">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Invite Member
                        </a>
                        <a href="{{ route('colocations.edit', $colocation) }}" class="btn btn-ghost">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('colocations.destroy', $colocation) }}" class="inline" id="cancel-coloc-form">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmCancel()" class="btn btn-danger">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Cancel Colocation
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('colocations.leave', $colocation) }}" id="leave-coloc-form">
                            @csrf
                            <button type="button" onclick="confirmLeave()" class="btn btn-warning">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Leave Colocation
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Members Grid -->
        <div class="animate-fade-in-delay-1">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <h3 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);">
                    Housemates <span style="font-size:0.875rem;font-weight:400;color:var(--text-muted);">({{ $members->count() }})</span>
                </h3>
            </div>
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:1rem;">
                @foreach($members as $membership)
                    @php
                        $m = $membership->user;
                        $rep = $m->reputation ?? 0;
                        $initial = strtoupper(substr($m->name, 0, 1));
                        $isMe = $m->id === $user->id;
                    @endphp
                    <div class="glass-card {{ $isMe ? 'invitation-glow' : '' }}" style="padding:1.25rem;display:flex;flex-direction:column;gap:1rem;">
                        <div style="display:flex;align-items:center;gap:1rem;">
                            <!-- Avatar -->
                            <div style="width:3rem;height:3rem;border-radius:1rem;background:linear-gradient(135deg,{{ ['#6366f1,#818cf8','#06b6d4,#67e8f9','#10b981,#6ee7b7','#f59e0b,#fcd34d','#ef4444,#fca5a5'][crc32($m->email) % 5] }});display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.125rem;color:white;flex-shrink:0;">
                                {{ $initial }}
                            </div>
                            <div style="min-width:0;">
                                <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap;">
                                    <span style="font-weight:700;color:var(--text-primary);font-size:0.9375rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $m->name }}</span>
                                    @if($isMe) <span style="font-size:0.7rem;color:var(--primary-light);background:rgba(99,102,241,0.12);padding:0.15rem 0.5rem;border-radius:9999px;font-weight:600;">You</span> @endif
                                </div>
                                <div style="font-size:0.8125rem;color:var(--text-muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $m->email }}</div>
                            </div>
                        </div>

                        <div style="display:flex;align-items:center;justify-content:space-between;">
                            <div style="display:flex;gap:0.5rem;align-items:center;">
                                <span class="{{ $membership->role === 'OWNER' ? 'badge badge-owner' : 'badge badge-member' }}">
                                    {{ $membership->role }}
                                </span>
                                <span class="reputation-badge {{ $rep > 0 ? 'reputation-positive' : ($rep < 0 ? 'reputation-negative' : 'reputation-neutral') }}">
                                    {{ $rep > 0 ? '+' : '' }}{{ $rep }}
                                </span>
                            </div>

                            @if($activeMembership->role === 'OWNER' && $membership->role !== 'OWNER')
                                <form method="POST" action="{{ route('colocations.removeMember', [$colocation, $m->id]) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Remove {{ $m->name }} from the colocation?')"
                                        title="Remove member">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                        Remove
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div style="font-size:0.75rem;color:var(--text-muted);padding-top:0.75rem;border-top:1px solid rgba(255,255,255,0.05);">
                            Joined <strong style="color:var(--text-secondary);">{{ $membership->join->format('M j, Y') }}</strong>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

    @else
        <!-- Empty State: No Colocation -->
        <div class="glass-card animate-fade-in" style="padding:0;">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="var(--text-muted)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <div style="max-width:400px;">
                    <h2 style="font-size:1.375rem;font-weight:800;color:var(--text-primary);margin-bottom:0.625rem;">No active colocation</h2>
                    <p style="color:var(--text-secondary);line-height:1.6;">You're not part of any colocation yet. Create your own or wait for someone to invite you via a link.</p>
                </div>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;justify-content:center;margin-top:0.5rem;">
                    <a href="{{ route('colocations.create') }}" class="btn btn-primary">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Create a Colocation
                    </a>
                </div>
                <div class="divider" style="width:200px;"></div>
                <p style="font-size:0.875rem;color:var(--text-muted);">Already have an invitation? Check your email for the invitation link.</p>
            </div>
        </div>
    @endif
</div>

<!-- Confirm Modals via JS -->
<script>
    function confirmCancel() {
        if (confirm('Cancel this colocation? This action cannot be undone.')) {
            document.getElementById('cancel-coloc-form').submit();
        }
    }
    function confirmLeave() {
        if (confirm('Are you sure you want to leave this colocation?')) {
            document.getElementById('leave-coloc-form').submit();
        }
    }
</script>
</x-app-layout>
