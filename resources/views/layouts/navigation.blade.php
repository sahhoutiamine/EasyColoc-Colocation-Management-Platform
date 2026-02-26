<nav class="app-nav" x-data="{ open: false }">
    <div style="max-width:1280px;margin:0 auto;padding:0 1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;height:4rem;">

            <!-- Logo -->
            <a href="{{ route('dashboard') }}" style="display:flex;align-items:center;gap:0.625rem;text-decoration:none;">
                <div style="width:2rem;height:2rem;background:linear-gradient(135deg,#6366f1,#06b6d4);border-radius:0.5rem;display:flex;align-items:center;justify-content:center;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="nav-logo-text">EasyColoc</span>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden sm:flex" style="align-items:center;gap:0.25rem;">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('colocations.index') }}" class="nav-link {{ request()->routeIs('colocations.*') ? 'active' : '' }}">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    My Colocation
                </a>
                @if(Auth::user()->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    Admin
                </a>
                @endif
            </div>

            <!-- Desktop User Menu -->
            <div class="hidden sm:flex" style="align-items:center;gap:1rem;">
                <!-- Notification Bell -->
                <div style="position:relative;" x-data="{ notificationOpen: false }">
                    <button @click="notificationOpen = !notificationOpen"
                        style="position:relative;display:flex;align-items:center;justify-content:center;width:2.25rem;height:2.25rem;border-radius:0.625rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);cursor:pointer;transition:all 0.2s;color:var(--text-secondary);"
                        onmouseenter="this.style.background='rgba(255,255,255,0.08)';this.style.color='var(--text-primary)'"
                        onmouseleave="this.style.background='rgba(255,255,255,0.04)';this.style.color='var(--text-secondary)'">
                        <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @php $invitationCount = Auth::user()->pendingInvitations->count(); @endphp
                        @if($invitationCount > 0)
                            <span style="position:absolute;top:-2px;right:-2px;background:#ef4444;color:white;font-size:0.625rem;font-weight:700;width:1.1rem;height:1.1rem;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid #0f0f1e;">
                                {{ $invitationCount }}
                            </span>
                        @endif
                    </button>

                    <div x-show="notificationOpen" @click.away="notificationOpen=false" x-transition
                        style="position:absolute;right:0;top:calc(100% + 0.5rem);width:300px;background:rgba(15,15,30,0.98);border:1px solid rgba(255,255,255,0.1);border-radius:0.75rem;padding:0.5rem;z-index:200;backdrop-filter:blur(20px);box-shadow:0 10px 25px -5px rgba(0,0,0,0.5);">
                        <div style="padding:0.75rem 0.875rem;border-bottom:1px solid rgba(255,255,255,0.06);margin-bottom:0.375rem;">
                            <span style="font-weight:600;font-size:0.875rem;color:var(--text-primary);">Notifications</span>
                        </div>
                        <div style="max-height:300px;overflow-y:auto;padding:0.25rem;">
                            @forelse(Auth::user()->pendingInvitations as $invitation)
                                <div style="padding:0.75rem 0.875rem;border-radius:0.5rem;background:rgba(255,255,255,0.03);margin-bottom:0.25rem;border:1px solid rgba(255,255,255,0.05);">
                                    <a href="{{ route('invitations.show', $invitation->token) }}" style="display:block;font-size:0.8125rem;color:var(--text-secondary);margin-bottom:0.8rem;line-height:1.4;text-decoration:none;transition:color 0.2s;" onmouseenter="this.style.color='var(--text-primary)'" onmouseleave="this.style.color='var(--text-secondary)'">
                                        Invitation to join <span style="color:var(--text-primary);font-weight:600;">{{ $invitation->colocation->name }}</span>
                                    </a>
                                    <div style="display:flex;gap:0.5rem;">
                                        <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}" style="flex:1;">
                                            @csrf
                                            <button type="submit" style="width:100%;padding:0.375rem;background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;border-radius:0.375rem;font-size:0.75rem;font-weight:600;cursor:pointer;transition:opacity 0.2s;" onmouseenter="this.style.opacity='0.9'" onmouseleave="this.style.opacity='1'">Accept</button>
                                        </form>
                                        <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}" style="flex:1;">
                                            @csrf
                                            <button type="submit" style="width:100%;padding:0.375rem;background:rgba(239,68,68,0.1);color:#fca5a5;border:1px solid rgba(239,68,68,0.2);border-radius:0.375rem;font-size:0.75rem;font-weight:600;cursor:pointer;transition:all 0.2s;" onmouseenter="this.style.background='rgba(239,68,68,0.2)'" onmouseleave="this.style.background='rgba(239,68,68,0.1)'">Refuse</button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div style="padding:2rem 1rem;text-align:center;color:var(--text-muted);font-size:0.8125rem;">
                                    <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 0.75rem;opacity:0.3;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div x-data="{ userOpen: false }" style="display:flex;align-items:center;gap:1rem;">
                <!-- Reputation -->
                @php $rep = Auth::user()->reputation ?? 0; @endphp
                <div class="reputation-badge {{ $rep > 0 ? 'reputation-positive' : ($rep < 0 ? 'reputation-negative' : 'reputation-neutral') }}">
                    @if($rep > 0)
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @elseif($rep < 0)
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
                    @else
                        <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 000 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/></svg>
                    @endif
                    {{ $rep > 0 ? '+' : '' }}{{ $rep }}
                </div>

                <!-- User Dropdown -->
                <div style="position:relative;">
                    <button @click="userOpen = !userOpen"
                        style="display:flex;align-items:center;gap:0.625rem;padding:0.375rem 0.75rem;border-radius:0.625rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);cursor:pointer;transition:all 0.2s;color:var(--text-primary);"
                        onmouseenter="this.style.background='rgba(255,255,255,0.08)'"
                        onmouseleave="this.style.background='rgba(255,255,255,0.04)'">
                        <div style="width:1.75rem;height:1.75rem;border-radius:50%;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;color:white;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span style="font-size:0.875rem;font-weight:500;">{{ Auth::user()->name }}</span>
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" :style="userOpen ? 'transform:rotate(180deg);transition:0.2s' : 'transition:0.2s'"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                    </button>

                    <div x-show="userOpen" @click.away="userOpen=false" x-transition
                        style="position:absolute;right:0;top:calc(100% + 0.5rem);width:200px;background:rgba(15,15,30,0.98);border:1px solid rgba(255,255,255,0.1);border-radius:0.75rem;padding:0.5rem;z-index:200;backdrop-filter:blur(20px);">
                        <a href="{{ route('profile.edit') }}"
                            style="display:flex;align-items:center;gap:0.625rem;padding:0.625rem 0.875rem;border-radius:0.5rem;color:var(--text-secondary);font-size:0.875rem;text-decoration:none;transition:all 0.15s;"
                            onmouseenter="this.style.background='rgba(255,255,255,0.06)';this.style.color='var(--text-primary)'"
                            onmouseleave="this.style.background='transparent';this.style.color='var(--text-secondary)'">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <div style="height:1px;background:rgba(255,255,255,0.06);margin:0.375rem 0;"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                style="width:100%;display:flex;align-items:center;gap:0.625rem;padding:0.625rem 0.875rem;border-radius:0.5rem;color:#fca5a5;font-size:0.875rem;background:transparent;border:none;cursor:pointer;transition:all 0.15s;text-align:left;"
                                onmouseenter="this.style.background='rgba(239,68,68,0.1)'"
                                onmouseleave="this.style.background='transparent'">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Mobile Hamburger -->
            <button @click="open = !open" class="sm:hidden"
                style="display:flex;align-items:center;justify-content:center;width:2.5rem;height:2.5rem;border-radius:0.5rem;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.08);cursor:pointer;color:var(--text-secondary);">
                <svg x-show="!open" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                <svg x-show="open" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="open" x-transition class="sm:hidden" style="border-top:1px solid rgba(255,255,255,0.06);padding:1rem 1.5rem 1.5rem;">
        <div style="display:flex;flex-direction:column;gap:0.25rem;margin-bottom:1rem;">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('colocations.index') }}" class="nav-link {{ request()->routeIs('colocations.*') ? 'active' : '' }}">My Colocation</a>
            @if(Auth::user()->is_admin)
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">Admin Panel</a>
            @endif
        </div>

        @if($invitationCount > 0)
        <div style="margin-bottom:1.5rem;padding:1rem;background:rgba(255,255,255,0.03);border-radius:0.75rem;border:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;gap:0.5rem;color:var(--text-primary);font-weight:600;font-size:0.875rem;margin-bottom:0.75rem;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                Pending Invitations ({{ $invitationCount }})
            </div>
            <div style="display:flex;flex-direction:column;gap:0.75rem;">
                @foreach(Auth::user()->pendingInvitations as $invitation)
                    <div style="padding:0.75rem;background:rgba(255,255,255,0.02);border-radius:0.5rem;border:1px solid rgba(255,255,255,0.03);">
                        <a href="{{ route('invitations.show', $invitation->token) }}" style="display:block;font-size:0.8125rem;color:var(--text-secondary);margin-bottom:0.625rem;line-height:1.4;text-decoration:none;">
                            Invitation to join <span style="color:var(--text-primary);font-weight:600;">{{ $invitation->colocation->name }}</span>
                        </a>
                        <div style="display:flex;gap:0.5rem;">
                             <form method="POST" action="{{ route('invitations.accept', $invitation->token) }}" style="flex:1;">
                                @csrf
                                <button type="submit" style="width:100%;padding:0.5rem;background:linear-gradient(135deg,#6366f1,#06b6d4);color:white;border:none;border-radius:0.375rem;font-size:0.75rem;font-weight:600;cursor:pointer;">Accept</button>
                            </form>
                            <form method="POST" action="{{ route('invitations.refuse', $invitation->token) }}" style="flex:1;">
                                @csrf
                                <button type="submit" style="width:100%;padding:0.5rem;background:rgba(239,68,68,0.1);color:#fca5a5;border:1px solid rgba(239,68,68,0.2);border-radius:0.375rem;font-size:0.75rem;font-weight:600;cursor:pointer;">Refuse</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div style="border-top:1px solid rgba(255,255,255,0.06);padding-top:1rem;">
            <div style="font-weight:600;color:var(--text-primary);margin-bottom:0.25rem;">{{ Auth::user()->name }}</div>
            <div style="font-size:0.8125rem;color:var(--text-muted);margin-bottom:1rem;">{{ Auth::user()->email }}</div>
            <a href="{{ route('profile.edit') }}" class="nav-link" style="display:block;margin-bottom:0.25rem;">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link" style="background:none;border:none;cursor:pointer;color:#fca5a5;width:100%;text-align:left;">Log Out</button>
            </form>
        </div>
    </div>
</nav>
