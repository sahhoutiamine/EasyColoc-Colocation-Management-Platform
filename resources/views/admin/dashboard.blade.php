<x-app-layout>
<div class="page-container">
    
    <div class="page-header animate-fade-in" style="display:flex;justify-content:space-between;align-items:flex-end;width:100%;">
        <div>
            <h1 class="page-title">Global Admin Dashboard</h1>
            <p class="page-subtitle">Platform statistics and user management</p>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('admin.categories') }}" class="btn btn-ghost">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                Manage Categories
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:1.5rem;margin-bottom:2.5rem;" class="animate-fade-in-delay-1">
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(99,102,241,0.15);"><span style="font-size:1.25rem;">👥</span></div>
            <div class="stat-value">{{ $stats['total_users'] }}</div>
            <div class="stat-label">Total Users</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(6,182,212,0.15);"><span style="font-size:1.25rem;">🏠</span></div>
            <div class="stat-value">{{ $stats['total_colocations'] }}</div>
            <div class="stat-label">Colocations</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(16,185,129,0.15);"><span style="font-size:1.25rem;">💰</span></div>
            <div class="stat-value">{{ number_format($stats['total_expenses'] / 1000, 1) }}k</div>
            <div class="stat-label">Total Expenses (€)</div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:rgba(239,68,68,0.15);"><span style="font-size:1.25rem;">🚫</span></div>
            <div class="stat-value">{{ $stats['banned_users'] }}</div>
            <div class="stat-label">Banned Users</div>
        </div>
    </div>

    <!-- User Management List -->
    <div class="glass-card animate-fade-in-delay-2" style="padding:0; overflow:hidden;">
        <div style="padding:1.5rem;border-bottom:1px solid var(--border);background:rgba(255,255,255,0.02);">
            <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);">User Moderation</h2>
        </div>
        
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead>
                    <tr style="background:rgba(255,255,255,0.02);">
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">User</th>
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Reputation</th>
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Status</th>
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Joined</th>
                        <th style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:1rem 1.5rem;">
                            <div style="display:flex;align-items:center;gap:0.75rem;">
                                <div style="width:2.25rem;height:2.25rem;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--accent));display:flex;align-items:center;justify-content:center;font-weight:700;color:white;font-size:0.875rem;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--text-primary);">{{ $u->name }}</div>
                                    <div style="font-size:0.8125rem;color:var(--text-muted);">{{ $u->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding:1rem 1.5rem;">
                            <span class="reputation-badge {{ $u->reputation > 0 ? 'reputation-positive' : ($u->reputation < 0 ? 'reputation-negative' : 'reputation-neutral') }}">
                                {{ $u->reputation > 0 ? '+' : '' }}{{ $u->reputation ?? 0 }}
                            </span>
                        </td>
                        <td style="padding:1rem 1.5rem;">
                            @if($u->is_banned)
                                <span class="badge" style="background:rgba(239,68,68,0.2);color:var(--danger);border:1px solid rgba(239,68,68,0.3);">Banned</span>
                            @else
                                <span class="badge badge-active">Active</span>
                            @endif
                        </td>
                        <td style="padding:1rem 1.5rem;font-size:0.875rem;color:var(--text-muted);">
                            {{ $u->created_at->format('M j, Y') }}
                        </td>
                        <td style="padding:1rem 1.5rem;text-align:right;">
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.users.ban', $u) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $u->is_banned ? 'btn-success' : 'btn-danger' }}" style="min-width:100px;">
                                    {{ $u->is_banned ? 'Unban User' : 'Ban User' }}
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="padding:1.5rem;background:rgba(255,255,255,0.01);">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Recent Colocations -->
    <div class="glass-card animate-fade-in-delay-3" style="padding:0; overflow:hidden; margin-top: 2rem;">
        <div style="padding:1.5rem;border-bottom:1px solid var(--border);background:rgba(255,255,255,0.02);">
            <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);">Recent Colocations</h2>
        </div>
        
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;text-align:left;">
                <thead>
                    <tr style="background:rgba(255,255,255,0.02);">
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Name</th>
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Status</th>
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Members</th>
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Expenses Total</th>
                        <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Created</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($colocations as $c)
                    <tr style="border-bottom:1px solid var(--border);">
                        <td style="padding:1rem 1.5rem;font-weight:600;color:var(--text-primary);">{{ $c->name }}</td>
                        <td style="padding:1rem 1.5rem;">
                            <span class="badge {{ $c->status === 'ACTIVE' ? 'badge-active' : 'badge-cancelled' }}">
                                {{ $c->status }}
                            </span>
                        </td>
                        <td style="padding:1rem 1.5rem;color:var(--text-secondary);">
                            {{ $c->memberships()->whereNull('left')->count() }} active
                        </td>
                        <td style="padding:1rem 1.5rem;color:var(--text-primary);font-weight:600;">
                            {{ number_format($c->expenses()->sum('amount'), 2) }} €
                        </td>
                        <td style="padding:1rem 1.5rem;font-size:0.875rem;color:var(--text-muted);">
                            {{ $c->created_at->format('M j, Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-app-layout>
