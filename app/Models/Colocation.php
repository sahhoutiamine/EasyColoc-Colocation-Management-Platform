<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Colocation extends Model
{
    protected $fillable = ['name', 'status'];

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'memberships')
            ->withPivot('role', 'join', 'left')
            ->withTimestamps();
    }

    public function invitations(): HasMany
    {
        return $this->hasMany(Invitation::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function settlements(): HasMany
    {
        return $this->hasMany(Settlement::class);
    }

    /**
     * Recalculates all balances and updates unpaid settlements.
     */
    public function recalculateBalances()
    {
        // 1. Clear old UNPAID settlements
        $this->settlements()->where('is_paid', false)->delete();

        $memberships = $this->memberships()->get();
        $expenses = $this->expenses()->get();
        $paidSettlements = $this->settlements()->where('is_paid', true)->get();

        $balances = [];
        foreach ($memberships as $m) {
            $balances[$m->user_id] = 0;
        }

        // 2. Process Expenses (Shared logic)
        foreach ($expenses as $expense) {
            $expenseDate = $expense->expense_date->copy()->startOfDay();
          
            $expenseCreatedAt = $expense->created_at;

            // Find members active at the time of the expense.
            $activeAtTime = $memberships->filter(function ($m) use ($expenseDate, $expenseCreatedAt) {
                $joinDate = $m->join->copy()->startOfDay();
                
                if ($m->left === null) {
                    // Member still active
                    $isLeft = false;
                } else {
                  
                    $isLeft = $expenseCreatedAt->gt($m->left);
                }
                return $joinDate->lte($expenseDate) && !$isLeft;
            });

            $count = $activeAtTime->count();
            if ($count === 0) continue;

            $share = $expense->amount / $count;

            foreach ($activeAtTime as $m) {
                $balances[$m->user_id] -= $share;
            }
            // The payer is credited the full amount (they "invested" it)
            if (isset($balances[$expense->payer_id])) {
                $balances[$expense->payer_id] += $expense->amount;
            }
        }

        // 3. Process PAID Settlements (Actual money transfers)
        foreach ($paidSettlements as $s) {
            if (isset($balances[$s->from_user_id])) {
                $balances[$s->from_user_id] += $s->amount; // They paid, reducing their debt
            }
            if (isset($balances[$s->to_user_id])) {
                $balances[$s->to_user_id] -= $s->amount;   // They received, reducing their credit
            }
        }

        // 4. Generate New Settlements to zero out balances
        $debtors = []; 
        $creditors = []; 

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
                
                $this->settlements()->create([
                    'from_user_id' => $debtorId,
                    'to_user_id' => $creditorId,
                    'amount' => round($amount, 2),
                    'is_paid' => false,
                ]);

                $debtAmount -= $amount;
                $creditAmount -= $amount;
            }
        }
    }

    public function owner()
    {
        return $this->memberships()->where('role', 'OWNER')->whereNull('left')->first()?->user;
    }
    
}
