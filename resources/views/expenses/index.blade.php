<x-app-layout>
@php
    $user = auth()->user();
    $activeMembership = $colocation->memberships()->where('user_id', $user->id)->whereNull('left')->first();
    $isOwner = $activeMembership?->role === 'OWNER';
@endphp

<div class="page-container">

    <!-- Page Header -->
    <div class="page-header animate-fade-in" style="display:flex;flex-wrap:wrap;align-items:flex-end;justify-content:space-between;gap:1rem;">
        <div>
            <p style="font-size:0.8125rem;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:0.5rem;">Managing Finances</p>
            <h1 class="page-title">Expenses & Balances</h1>
            <p class="page-subtitle">Track shared costs for <strong style="color:var(--text-primary);">{{ $colocation->name }}</strong></p>
        </div>
        <div style="display:flex;gap:0.75rem;">
            <button onclick="document.getElementById('expense-modal').style.display='flex'" class="btn btn-primary">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                Add Expense
            </button>
        </div>
    </div>

    <div style="display:grid;grid-template-columns: 2fr 1fr; gap: 1.5rem;" class="animate-fade-in-delay-1">
        
        <!-- Left: Expense List -->
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            
            <!-- Filters -->
            <div class="glass-card" style="padding:1.25rem;">
                <form method="GET" style="display:flex;align-items:center;gap:1rem;">
                    <div class="form-group" style="flex:1;">
                        <select name="month" class="form-input" onchange="this.form.submit()">
                            <option value="">All Months</option>
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                    {{ date("F", mktime(0, 0, 0, $m, 1)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div style="color:var(--text-muted);font-size:0.875rem;">
                        Filter expenses by month
                    </div>
                </form>
            </div>

            <div class="glass-card" style="padding:0; overflow:hidden;">
                <div style="padding:1.5rem;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                    <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);">Expense History</h2>
                    <span class="badge badge-active">{{ $expenses->count() }} Records</span>
                </div>
                
                @if($expenses->isEmpty())
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="var(--text-muted)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                        </div>
                        <p style="color:var(--text-secondary);">No expenses recorded for this period.</p>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table style="width:100%;border-collapse:collapse;text-align:left;">
                            <thead>
                                <tr style="background:rgba(255,255,255,0.02);">
                                    <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Title</th>
                                    <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Category</th>
                                    <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Paid By</th>
                                    <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);">Date</th>
                                    <th style="padding:1rem 1.5rem;font-size:0.75rem;text-transform:uppercase;color:var(--text-muted);border-bottom:1px solid var(--border);text-align:right;">Amount</th>
                                    <th style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($expenses as $expense)
                                <tr hover="background:rgba(255,255,255,0.01)" style="border-bottom:1px solid var(--border);">
                                    <td style="padding:1rem 1.5rem;">
                                        <div style="font-weight:600;color:var(--text-primary);">{{ $expense->title }}</div>
                                    </td>
                                    <td style="padding:1rem 1.5rem;">
                                        <span style="font-size:0.75rem;padding:0.2rem 0.6rem;background:rgba(255,255,255,0.05);border-radius:9999px;border:1px solid var(--border);">
                                            {{ $expense->category->name }}
                                        </span>
                                    </td>
                                    <td style="padding:1rem 1.5rem;">
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <div style="width:1.5rem;height:1.5rem;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:0.65rem;color:white;font-weight:700;">
                                                {{ strtoupper(substr($expense->payer->name, 0, 1)) }}
                                            </div>
                                            <span style="font-size:0.875rem;color:var(--text-secondary);">{{ $expense->payer->name }}</span>
                                        </div>
                                    </td>
                                    <td style="padding:1rem 1.5rem;font-size:0.875rem;color:var(--text-muted);">
                                        {{ $expense->expense_date->format('M j, Y') }}
                                    </td>
                                    <td style="padding:1rem 1.5rem;text-align:right;font-weight:700;color:var(--text-primary);font-size:1rem;">
                                        {{ number_format($expense->amount, 2) }} €
                                    </td>
                                    <td style="padding:1rem 1.5rem;text-align:right;display:flex;justify-content:flex-end;gap:0.5rem;">
                                        @if($expense->payer_id === $user->id || $isOwner)
                                        <a href="{{ route('colocations.expenses.edit', [$colocation, $expense]) }}" style="color:var(--text-muted);cursor:pointer;padding:0.25rem;display:flex;align-items:center;">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </a>
                                        <form method="POST" action="{{ route('colocations.expenses.destroy', [$colocation, $expense]) }}" onsubmit="return confirm('Remove this expense?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" style="background:none;border:none;color:var(--danger);cursor:pointer;padding:0.25rem;display:flex;align-items:center;">
                                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right: Balances & Settlements -->
        <div style="display:flex;flex-direction:column;gap:1.5rem;">
            
            <div class="glass-card" style="padding:1.5rem; margin-bottom: 1.5rem;">
                <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);margin-bottom:1.25rem;">Contribution Summary</h2>
                <div style="display:flex; flex-direction:column; gap:0.75rem;">
                    @foreach($memberStats as $stat)
                        <div style="display:flex; justify-content:space-between; align-items:center; padding:0.5rem 0; border-bottom:1px solid var(--border);">
                            <span style="font-size:0.875rem; color:var(--text-secondary);">{{ $stat['name'] }}</span>
                            <span style="font-size:1rem; font-weight:700; color:var(--text-primary);">{{ number_format($stat['total_paid'], 2) }} €</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="glass-card" style="padding:1.5rem;">
                <h2 style="font-size:1.125rem;font-weight:700;color:var(--text-primary);margin-bottom:1.25rem;display:flex;align-items:center;gap:0.5rem;">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="var(--primary-light)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>
                    Who owes how much
                </h2>

                <div style="display:flex;flex-direction:column;gap:0.75rem;">
                    @forelse($settlements as $settlement)
                        <div style="background:rgba(255,255,255,0.03);border:1px solid var(--border);border-radius:0.75rem;padding:1rem;">
                            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
                                <div style="display:flex;align-items:center;gap:0.5rem;">
                                    <span style="font-weight:700;color:var(--text-primary);">{{ $settlement->fromUser->name }}</span>
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="var(--text-muted)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    <span style="font-weight:700;color:var(--text-primary);">{{ $settlement->toUser->name }}</span>
                                </div>
                                <div style="font-weight:800;color:var(--primary-light);font-size:1.125rem;">
                                    {{ number_format($settlement->amount, 2) }} €
                                </div>
                            </div>

                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <span class="badge" style="background:rgba(255,255,255,0.05);color:var(--text-muted);">Unpaid</span>
                                @if($settlement->from_user_id === $user->id || $settlement->to_user_id === $user->id)
                                <form method="POST" action="{{ route('colocations.settlements.paid', [$colocation, $settlement]) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Mark as Paid</button>
                                </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p style="text-align:center;color:var(--text-muted);font-size:0.875rem;padding:1rem;">All balances are settled!</p>
                    @endforelse
                </div>
            </div>

            <!-- Summary & Stats -->
            <div style="display:flex;flex-direction:column;gap:1rem;">
                <div class="glass-card" style="padding:1.5rem;background:linear-gradient(135deg,rgba(16,185,129,0.05) 0%,transparent 100%);">
                    <h3 style="font-size:0.875rem;font-weight:700;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-bottom:1rem;">Monthly Breakdown</h3>
                    <div style="display:flex;flex-direction:column;gap:0.75rem;">
                        @forelse($statsByCategory as $stats)
                            <div>
                                <div style="display:flex;justify-content:space-between;margin-bottom:0.25rem;font-size:0.875rem;">
                                    <span style="color:var(--text-secondary);display:flex;align-items:center;gap:0.4rem;">
                                        {{ $stats['name'] }}
                                    </span>
                                    <span style="color:var(--text-primary);font-weight:700;">{{ number_format($stats['total'], 2) }} €</span>
                                </div>
                                <div style="width:100%;height:4px;background:rgba(255,255,255,0.05);border-radius:999px;overflow:hidden;">
                                    @php
                                        $totalMonth = $expenses->sum('amount');
                                        $percentage = $totalMonth > 0 ? ($stats['total'] / $totalMonth) * 100 : 0;
                                    @endphp
                                    <div style="width:{{ $percentage }}%;height:100%;background:{{ $stats['color'] }};border-radius:999px;"></div>
                                </div>
                            </div>
                        @empty
                            <p style="color:var(--text-muted);font-size:0.875rem;">No data for this month.</p>
                        @endforelse
                    </div>

                    <div style="margin-top:1.5rem;padding-top:1rem;border-top:1px solid var(--border);display:flex;justify-content:space-between;align-items:center;">
                        <span style="font-size:0.875rem;color:var(--text-muted);">Total Spend:</span>
                        <span style="font-size:1.125rem;font-weight:800;color:var(--text-primary);">{{ number_format($expenses->sum('amount'), 2) }} €</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Expense Modal -->
<div id="expense-modal" style="position:fixed;inset:0;background:rgba(0,0,0,0.8);backdrop-filter:blur(4px);z-index:1000;display:none;align-items:center;justify-content:center;padding:1rem;">
    <div class="glass-card animate-fade-in" style="width:100%;max-width:500px;padding:2rem;background:var(--bg-dark);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h2 style="font-size:1.5rem;font-weight:800;color:var(--text-primary);">Add New Expense</h2>
            <button onclick="document.getElementById('expense-modal').style.display='none'" style="background:none;border:none;color:var(--text-muted);cursor:pointer;">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('colocations.expenses.store', $colocation) }}" style="display:flex;flex-direction:column;gap:1.5rem;">
            @csrf
            <div class="form-group">
                <label class="form-label">Expense Title</label>
                <input type="text" name="title" value="{{ old('title') }}" required class="form-input" placeholder="e.g. Weekly Groceries">
                @error('title') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                <div class="form-group">
                    <label class="form-label">Amount (€)</label>
                    <input type="number" name="amount" step="0.01" value="{{ old('amount') }}" required class="form-input" placeholder="0.00">
                    @error('amount') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Category</label>
                    <select name="category_id" required class="form-input">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Date</label>
                <input type="date" name="expense_date" required class="form-input" value="{{ old('expense_date', date('Y-m-d')) }}">
                @error('expense_date') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
            </div>

            <div style="display:flex;gap:0.75rem;margin-top:0.5rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Save Expense</button>
                <button type="button" onclick="document.getElementById('expense-modal').style.display='none'" class="btn btn-ghost">Cancel</button>
            </div>
        </form>
    </div>
</div>

</x-app-layout>
