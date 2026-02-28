<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Settlement;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class ExpenseController extends Controller
{
    public function index(Colocation $colocation, Request $request)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership) {
            abort(403, 'You are not a member of this colocation.');
        }

        $query = $colocation->expenses()->with(['payer', 'category']);

        if ($request->has('month') && $request->month != "") {
            $query->whereMonth('expense_date', $request->month);
            $query->whereYear('expense_date', now()->year);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();
        $categories = Category::all();
        // Only show UNPAID settlements in "Who owes how much".
        // Paid imputation settlements (created when a member is removed/leaves with debt)
        // are internal accounting records and should not appear to remaining members.
        $settlements = $colocation->settlements()->with(['fromUser', 'toUser'])->where('is_paid', false)->get();

        // Calculate total paid per member
        $members = $colocation->users()->wherePivot('left', null)->get();
        $memberStats = $members->map(function ($member) use ($colocation) {
            return [
                'name' => $member->name,
                'total_paid' => $colocation->expenses()->where('payer_id', $member->id)->sum('amount'),
            ];
        });

        // Calculate statistics per category
        $statsByCategory = $expenses->groupBy('category_id')->map(function ($items) {
            return [
                'name' => $items->first()->category->name,
                'icon' => $items->first()->category->icon,
                'color' => $items->first()->category->color,
                'total' => $items->sum('amount'),
            ];
        });

        return view('expenses.index', compact('colocation', 'expenses', 'categories', 'settlements', 'statsByCategory', 'memberStats'));
    }

    public function store(Request $request, Colocation $colocation)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'expense_date' => 'required|date',
        ]);

        $validated['colocation_id'] = $colocation->id;
        $validated['payer_id'] = Auth::id();

        DB::transaction(function () use ($validated, $colocation) {
            Expense::create($validated);
            $colocation->recalculateBalances();
        });

        return redirect()->back()->with('success', 'Expense added and balances updated.');
    }

    public function edit(Colocation $colocation, Expense $expense)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || ($expense->payer_id !== Auth::id() && $membership->role !== 'OWNER')) {
            abort(403);
        }

        $categories = Category::all();
        return view('expenses.edit', compact('colocation', 'expense', 'categories'));
    }

    public function update(Request $request, Colocation $colocation, Expense $expense)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || ($expense->payer_id !== Auth::id() && $membership->role !== 'OWNER')) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0.01',
            'category_id' => 'required|exists:categories,id',
            'expense_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated, $colocation, $expense) {
            $expense->update($validated);
            $colocation->recalculateBalances();
        });

        return redirect()->route('colocations.expenses.index', $colocation)->with('success', 'Expense updated and balances recalculated.');
    }

    public function destroy(Colocation $colocation, Expense $expense)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || ($expense->payer_id !== Auth::id() && $membership->role !== 'OWNER')) {
            abort(403);
        }

        DB::transaction(function () use ($expense, $colocation) {
            $expense->delete();
            $colocation->recalculateBalances();
        });

        return redirect()->back()->with('success', 'Expense removed and balances updated.');
    }

    public function markPaid(Colocation $colocation, Settlement $settlement)
    {
        $membership = $colocation->memberships()
            ->where('user_id', Auth::id())
            ->whereNull('left')
            ->first();

        if (!$membership || ($settlement->from_user_id !== Auth::id() && $settlement->to_user_id !== Auth::id())) {
            abort(403);
        }

        $settlement->update(['is_paid' => true]);
        $colocation->recalculateBalances();

        return redirect()->back()->with('success', 'Payment marked as paid.');
    }
}
