<x-app-layout>
@php
    $user = auth()->user();
    $activeMembership = $user->activeMembership();
    $colocation = $activeMembership?->colocation;
    $rep = $user->reputation ?? 0;
@endphp

<div class="page-container">

    <!-- Page Header -->
    <div class="page-header animate-fade-in" style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:1rem;">
        <div>
            <p style="font-size:0.875rem;color:var(--text-muted);font-weight:500;letter-spacing:0.05em;text-transform:uppercase;margin-bottom:0.5rem;display:flex;align-items:center;gap:0.4rem;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Welcome back
            </p>
            <h1 class="page-title">{{ $user->name }}</h1>
            <p class="page-subtitle">Here's what's happening with your colocation.</p>
        </div>
        <div style="display:flex;gap:0.75rem;align-items:center;">
            @if($user->is_admin)
                <span class="badge badge-admin" style="font-size:0.8rem;padding:0.35rem 0.75rem;">
                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Global Admin
                </span>
            @endif
            @if($activeMembership)
                <span class="badge badge-active">
                    <svg width="8" height="8" fill="currentColor" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
                    {{ $activeMembership->role === 'OWNER' ? 'Owner' : 'Member' }}
                </span>
            @endif
        </div>
    </div>

    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:2rem;" class="animate-fade-in-delay-1">

        <!-- Reputation -->
        <div class="stat-card">
            <div class="stat-icon" style="background:{{ $rep >= 0 ? 'rgba(16,185,129,0.15)' : 'rgba(239,68,68,0.15)' }};">
                @if($rep >= 0)
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6ee7b7" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                @else
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fca5a5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/></svg>
                @endif
            </div>
            <div class="stat-value" style="background:{{ $rep > 0 ? 'linear-gradient(135deg,#6ee7b7,#10b981)' : ($rep < 0 ? 'linear-gradient(135deg,#fca5a5,#ef4444)' : 'linear-gradient(135deg,#94a3b8,#64748b)') }};-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;">
                {{ $rep > 0 ? '+' : '' }}{{ $rep }}
            </div>
            <div class="stat-label">Reputation</div>
        </div>

        @if($colocation)
            <!-- To Pay -->
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(239,68,68,0.15);">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fca5a5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-value" style="color:#fca5a5;">{{ number_format($stats['to_pay'], 2) }} €</div>
                <div class="stat-label">To Pay</div>
            </div>

            <!-- To Receive -->
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(16,185,129,0.15);">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#6ee7b7" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div class="stat-value" style="color:#6ee7b7;">{{ number_format($stats['to_receive'], 2) }} €</div>
                <div class="stat-label">To Receive</div>
            </div>
        @else
            <!-- Active Colocation (Empty state) -->
            <div class="stat-card">
                <div class="stat-icon" style="background:rgba(99,102,241,0.15);">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#818cf8" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <div class="stat-value">0</div>
                <div class="stat-label">Colocations</div>
            </div>
        @endif

        <!-- Housemates count -->
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(6,182,212,0.15);">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#67e8f9" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <div class="stat-value">
                {{ $colocation ? $colocation->memberships()->whereNull('left')->count() : '0' }}
            </div>
            <div class="stat-label">Housemates</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="animate-fade-in-delay-2">

        <!-- Left: Current Colocation -->
        <div class="glass-card" style="padding:1.75rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;">
                <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);display:flex;align-items:center;gap:0.5rem;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    My Colocation
                </h2>
                @if($activeMembership)
                    <span class="badge badge-active">Active</span>
                @endif
            </div>

            @if($activeMembership && $colocation)
                <div style="margin-bottom:1.25rem;">
                    <div style="font-size:1.5rem;font-weight:800;color:var(--text-primary);letter-spacing:-0.02em;margin-bottom:0.5rem;">
                        {{ $colocation->name }}
                    </div>
                    <div style="display:flex;gap:1.5rem;font-size:0.8125rem;color:var(--text-muted);">
                        <span>
                            <span class="{{ $activeMembership->role === 'OWNER' ? 'badge badge-owner' : 'badge badge-member' }}">
                                {{ $activeMembership->role }}
                            </span>
                        </span>
                        <span style="display:flex;align-items:center;gap:0.3rem;">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            Joined {{ $activeMembership->join->format('M j, Y') }}
                        </span>
                    </div>
                </div>

                <!-- Quick Members Preview -->
                <div style="background:rgba(255,255,255,0.03);border-radius:0.75rem;padding:1rem;margin-bottom:1.25rem;border:1px solid rgba(255,255,255,0.05);">
                    <p style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.07em;color:var(--text-muted);margin-bottom:0.875rem;">Members ({{ $colocation->memberships()->whereNull('left')->count() }})</p>
                    <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
                        @foreach($colocation->memberships()->whereNull('left')->with('user')->get()->take(5) as $m)
                            <div style="display:flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.04);padding:0.375rem 0.75rem;border-radius:9999px;border:1px solid rgba(255,255,255,0.06);">
                                <div style="width:1.5rem;height:1.5rem;border-radius:50%;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:700;color:white;">
                                    {{ strtoupper(substr($m->user->name, 0, 1)) }}
                                </div>
                                <span style="font-size:0.8125rem;color:var(--text-secondary);">{{ $m->user->name }}</span>
                                @if($m->role === 'OWNER') <svg width="11" height="11" fill="#fcd34d" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg> @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <a href="{{ route('colocations.index') }}" class="btn btn-primary btn-sm">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    Manage Colocation
                </a>
            @else
                <div class="empty-state" style="padding:2rem 1rem;">
                    <div class="empty-state-icon">
                        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="var(--text-muted)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </div>
                    <div>
                        <p style="font-size:1rem;font-weight:600;color:var(--text-primary);margin-bottom:0.5rem;">No colocation yet</p>
                        <p style="font-size:0.875rem;color:var(--text-muted);">Create one or join via an invitation link</p>
                    </div>
                    <a href="{{ route('colocations.create') }}" class="btn btn-primary btn-sm">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                        Create Colocation
                    </a>
                </div>
            @endif
        </div>

        <!-- Right: Quick Actions + Info -->
        <div style="display:flex;flex-direction:column;gap:1.5rem;">

            <!-- Quick Actions -->
            <div class="glass-card" style="padding:1.75rem;">
                <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="var(--accent)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Quick Actions
                </h2>
                <div style="display:flex;flex-direction:column;gap:0.625rem;">
                    @if(!$activeMembership)
                        <a href="{{ route('colocations.create') }}" class="btn btn-primary" style="justify-content:flex-start;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Create a Colocation
                        </a>
                    @else
                        @if($activeMembership->role === 'OWNER')
                            <a href="{{ route('invitations.create', $colocation) }}" class="btn btn-accent" style="justify-content:flex-start;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                Invite a Member
                            </a>
                            <a href="{{ route('colocations.edit', $colocation) }}" class="btn btn-ghost" style="justify-content:flex-start;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Edit Colocation
                            </a>
                        @else
                            <a href="{{ route('colocations.index') }}" class="btn btn-primary" style="justify-content:flex-start;">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                View Colocation
                            </a>
                        @endif
                    @endif
                    <a href="{{ route('profile.edit') }}" class="btn btn-ghost" style="justify-content:flex-start;">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            <!-- Reputation Info -->
            <div class="glass-card" style="padding:1.75rem;">
                <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Reputation System
                </h2>
                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;border-radius:0.625rem;background:rgba(16,185,129,0.08);border:1px solid rgba(16,185,129,0.15);">
                        <div style="width:2rem;height:2rem;border-radius:0.5rem;background:rgba(16,185,129,0.2);display:flex;align-items:center;justify-content:center;font-size:0.875rem;flex-shrink:0;">+1</div>
                        <div>
                            <p style="font-size:0.8125rem;font-weight:600;color:#6ee7b7;">Good standing</p>
                            <p style="font-size:0.75rem;color:var(--text-muted);">Leave or cancel without any debt</p>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem;border-radius:0.625rem;background:rgba(239,68,68,0.08);border:1px solid rgba(239,68,68,0.15);">
                        <div style="width:2rem;height:2rem;border-radius:0.5rem;background:rgba(239,68,68,0.2);display:flex;align-items:center;justify-content:center;font-size:0.875rem;flex-shrink:0;color:#fca5a5;">-1</div>
                        <div>
                            <p style="font-size:0.8125rem;font-weight:600;color:#fca5a5;">Penalty</p>
                            <p style="font-size:0.75rem;color:var(--text-muted);">Leave or cancel while having a debt</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
