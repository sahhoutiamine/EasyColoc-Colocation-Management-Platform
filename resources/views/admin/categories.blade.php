<x-app-layout>
<div class="page-container">
    <div class="page-header animate-fade-in">
        <div style="display:flex;justify-content:space-between;align-items:flex-end;width:100%;">
            <div>
                <a href="{{ route('admin.dashboard') }}" style="display:inline-flex;align-items:center;gap:0.5rem;color:var(--text-muted);font-size:0.875rem;text-decoration:none;margin-bottom:1.25rem;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 17l-5-5m0 0l5-5m-5 5h12"/></svg>
                    Back to Admin Dashboard
                </a>
                <h1 class="page-title">Category Management</h1>
                <p class="page-subtitle">Define global expense categories for all colocations</p>
            </div>
            <button onclick="document.getElementById('category-modal').style.display='flex'" class="btn btn-primary">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                New Category
            </button>
        </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(auto-fill, minmax(300px, 1fr));gap:1.5rem;" class="animate-fade-in-delay-1">
        @foreach($categories as $cat)
        <div class="glass-card" style="padding:1.5rem;display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:1.25rem;">
                <div style="width:3.5rem;height:3.5rem;border-radius:1rem;background:{{ $cat->color }}15;border:1px solid {{ $cat->color }}30;display:flex;align-items:center;justify-content:center;">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="{{ $cat->color }}" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/></svg>
                </div>
                <div>
                    <h3 style="font-weight:700;color:var(--text-primary);margin-bottom:0.25rem;">{{ $cat->name }}</h3>
                    <p style="font-size:0.8125rem;color:var(--text-muted);">{{ $cat->expenses_count }} expenses linked</p>
                </div>
            </div>
            
            <div style="display:flex;gap:0.5rem;">
                @if($cat->expenses_count == 0)
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Delete this category?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-ghost btn-sm" style="color:var(--danger);padding:0.5rem;">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                </form>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Category Modal -->
<div id="category-modal" style="position:fixed;inset:0;background:rgba(0,0,0,0.8);backdrop-filter:blur(4px);z-index:1000;display:none;align-items:center;justify-content:center;padding:1rem;">
    <div class="glass-card animate-fade-in" style="width:100%;max-width:450px;padding:2rem;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <h2 style="font-size:1.5rem;font-weight:800;color:var(--text-primary);">New Category</h2>
            <button onclick="document.getElementById('category-modal').style.display='none'" style="background:none;border:none;color:var(--text-muted);cursor:pointer;">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('admin.categories.store') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
            @csrf
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" name="name" required class="form-input" placeholder="e.g. Subscriptions">
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                <div class="form-group">
                    <label class="form-label">Color Accent (Hex)</label>
                    <input type="text" name="icon" class="form-input" placeholder="e.g. Food, Rent" style="display:none">
                </div>
                <div class="form-group">
                    <label class="form-label">Color (Hex)</label>
                    <input type="color" name="color" class="form-input" style="height:42px;padding:4px;" value="#6366f1">
                </div>
            </div>
            <div style="display:flex;gap:0.75rem;margin-top:1rem;">
                <button type="submit" class="btn btn-primary" style="flex:1;">Create Category</button>
                <button type="button" onclick="document.getElementById('category-modal').style.display='none'" class="btn btn-ghost">Cancel</button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>
