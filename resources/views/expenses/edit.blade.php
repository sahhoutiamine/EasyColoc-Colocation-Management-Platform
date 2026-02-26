<x-app-layout>
<div class="page-container">
    <div class="page-header animate-fade-in">
        <div>
            <p style="font-size:0.8125rem;letter-spacing:0.06em;text-transform:uppercase;color:var(--text-muted);margin-bottom:0.5rem;">Managing Finances</p>
            <h1 class="page-title">Edit Expense</h1>
            <p class="page-subtitle">Update details for <strong style="color:var(--text-primary);">{{ $expense->title }}</strong></p>
        </div>
        <div>
            <a href="{{ route('colocations.expenses.index', $colocation) }}" class="btn btn-ghost">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Expenses
            </a>
        </div>
    </div>

    <div class="animate-fade-in-delay-1" style="max-width: 600px;">
        <div class="glass-card" style="padding: 2rem;">
            <form method="POST" action="{{ route('colocations.expenses.update', [$colocation, $expense]) }}" style="display:flex;flex-direction:column;gap:1.5rem;">
                @csrf
                @method('PATCH')
                
                <div class="form-group">
                    <label class="form-label">Expense Title</label>
                    <input type="text" name="title" value="{{ old('title', $expense->title) }}" required class="form-input" placeholder="e.g. Weekly Groceries">
                    @error('title') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                    <div class="form-group">
                        <label class="form-label">Amount (€)</label>
                        <input type="number" name="amount" step="0.01" value="{{ old('amount', $expense->amount) }}" required class="form-input" placeholder="0.00">
                        @error('amount') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category_id" required class="form-input">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $expense->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" name="expense_date" value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}" required class="form-input">
                    @error('expense_date') <span style="color:var(--danger);font-size:0.75rem;">{{ $message }}</span> @enderror
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex:1;">Update Expense</button>
                    <a href="{{ route('colocations.expenses.index', $colocation) }}" class="btn btn-ghost" style="flex:1;text-align:center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
</x-app-layout>
