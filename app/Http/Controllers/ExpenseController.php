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

        if ($request->has('month')) {
            $query->whereMonth('expense_date', $request->month);
            $query->whereYear('expense_date', now()->year);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();
        $categories = Category::all();
        $settlements = $colocation->settlements()->with(['fromUser', 'toUser'])->get();

        return view('expenses.index', compact('colocation', 'expenses', 'categories', 'settlements'));
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
            $this->recalculateSettlements($colocation);
        });

        return redirect()->back()->with('success', 'Expense added and balances updated.');
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
            $this->recalculateSettlements($colocation);
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

        return redirect()->back()->with('success', 'Payment marked as paid.');
    }

    private function recalculateSettlements(Colocation $colocation)
    {
        // 1. Clear old UNPAID settlements
        $colocation->settlements()->where('is_paid', false)->delete();

        $activeMemberships = $colocation->memberships()->whereNull('left')->get();
        $memberCount = $activeMemberships->count();

        if ($memberCount <= 1) return;

        $totalExpenses = $colocation->expenses()->sum('amount');
        $fairShare = $totalExpenses / $memberCount;

        $balances = [];
        foreach ($activeMemberships as $m) {
            $paid = $colocation->expenses()->where('payer_id', $m->user_id)->sum('amount');
            $balances[$m->user_id] = $paid - $fairShare;
        }

        // 2. Settlement logic
        $debtors = []; // negative balance
        $creditors = []; // positive balance

        foreach ($balances as $userId => $balance) {
            if ($balance < -0.01) {
                $debtors[$userId] = abs($balance);
            } elseif ($balance > 0.01) {
                $creditors[$userId] = $balance;
            }
        }

        arsort($debtors);
        arsort($creditors);

        foreach ($debtors as $debtorId => $debtAmount) {
            foreach ($creditors as $creditorId => &$creditAmount) {
                if ($debtAmount <= 0) break;
                if ($creditAmount <= 0) continue;

                $amount = min($debtAmount, $creditAmount);
                
                Settlement::create([
                    'colocation_id' => $colocation->id,
                    'from_user_id' => $debtorId,
                    'to_user_id' => $creditorId,
                    'amount' => $amount,
                    'is_paid' => false,
                ]);

                $debtAmount -= $amount;
                $creditAmount -= $amount;
            }
        }
    }
}
