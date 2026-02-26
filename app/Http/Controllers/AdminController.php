<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $stats = [
            'total_users' => User::count(),
            'total_colocations' => Colocation::count(),
            'total_expenses' => Expense::sum('amount'),
            'banned_users' => User::where('is_banned', true)->count(),
        ];

        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $colocations = Colocation::with('memberships')->orderBy('created_at', 'desc')->take(5)->get();
        $categories = Category::all();

        return view('admin.dashboard', compact('stats', 'users', 'colocations', 'categories'));
    }

    public function categories()
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $categories = Category::withCount('expenses')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function destroyCategory(Category $category)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        if ($category->expenses()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category with associated expenses.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted.');
    }

    public function toggleBan(User $user)
    {
        if (!Auth::user()->is_admin) {
            abort(403);
        }

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'You cannot ban yourself.');
        }

        $user->update(['is_banned' => !$user->is_banned]);
        $status = $user->is_banned ? 'banned' : 'unbanned';

        return redirect()->back()->with('success', "User {$user->name} has been {$status}.");
    }
}
