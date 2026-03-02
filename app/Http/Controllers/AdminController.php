<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Category;
use App\Models\Membership;
use App\Models\Settlement;
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
            'active_colocations' => Colocation::where('status', 'ACTIVE')->count(),
            'cancelled_colocations' => Colocation::where('status', 'CANCELLED')->count(),
            'total_expenses' => Expense::sum('amount'),
            'banned_users' => User::where('is_banned', true)->count(),
        ];

        $users = User::orderBy('created_at', 'desc')->paginate(10);
        $colocations = Colocation::with('memberships')->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact('stats', 'users', 'colocations'));
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

        if ($user->is_banned) {
            // Find all active memberships
            $activeMemberships = Membership::where('user_id', $user->id)
                ->whereNull('left')
                ->get();

            foreach ($activeMemberships as $membership) {
                $colocation = $membership->colocation;

                if ($membership->role === 'OWNER') {
                    // Try to find successor: earliest joining member who is not the banned user
                    $successor = Membership::where('colocation_id', $colocation->id)
                        ->where('user_id', '!=', $user->id)
                        ->whereNull('left')
                        ->orderBy('join', 'asc')
                        ->first();

                    if ($successor) {
                        // Update successor to OWNER
                        $successor->update(['role' => 'OWNER']);
                    } else {
                        // No successor found, cancel the colocation
                        $colocation->update(['status' => 'CANCELLED']);
                    }
                }

                // Kick the user from the colocation
                $membership->update(['left' => now()]);

                // Recalculate balances for the group
                $colocation->recalculateBalances();

                // Debt handling: If banned user has debt, transfer to owner (new or existing)
                $debtAmount = Settlement::where('colocation_id', $colocation->id)
                    ->where('from_user_id', $user->id)
                    ->where('is_paid', false)
                    ->sum('amount');

                if ($debtAmount > 0.01) {
                    $owner = Membership::where('colocation_id', $colocation->id)
                        ->where('role', 'OWNER')
                        ->whereNull('left')
                        ->first();

                    if ($owner && $owner->user_id !== $user->id) {
                        Settlement::create([
                            'colocation_id' => $colocation->id,
                            'from_user_id' => $user->id,
                            'to_user_id' => $owner->user_id,
                            'amount' => $debtAmount,
                            'is_paid' => true,
                        ]);
                    }
                }

                // Update user reputation based on debt
                $user->increment('reputation', $debtAmount > 0.01 ? -1 : 1);

                // Recalculate again to reflect the debt transfer
                $colocation->recalculateBalances();
            }
        }

        $status = $user->is_banned ? 'banned' : 'unbanned';
        $message = "User {$user->name} has been {$status}.";

        return redirect()->back()->with('success', $message);
    }
}
