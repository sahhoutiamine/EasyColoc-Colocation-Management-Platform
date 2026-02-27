<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Membership;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettlementTest extends TestCase
{
    use RefreshDatabase;

    public function test_expense_is_split_only_between_active_members(): void
    {
        $owner = User::factory()->create(['name' => 'Owner']);
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        Membership::create([
            'user_id' => $owner->id,
            'colocation_id' => $colocation->id,
            'role' => 'OWNER',
            'join' => now()->subDays(10),
        ]);
        $category = Category::create(['name' => 'Food', 'icon' => '🍕', 'color' => '#ff0000']);

        // Expense 1: Only Owner is active
        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Initial Food',
            'amount' => 100.00,
            'expense_date' => now()->subDays(5),
        ]);

        $colocation->recalculateBalances();
        // Owner should owe 100, paid 100 -> balance 0
        $this->assertEquals(0, Settlement::where('colocation_id', $colocation->id)->count());

        // Member joins 2 days ago
        $member = User::factory()->create(['name' => 'Member']);
        Membership::create([
            'user_id' => $member->id,
            'colocation_id' => $colocation->id,
            'role' => 'MEMBER',
            'join' => now()->subDays(2),
        ]);

        // Expense 2: Today (both active)
        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Shared Food',
            'amount' => 60.00,
            'expense_date' => now(),
        ]);

        $colocation->recalculateBalances();

        // Total for Member: 0 (Expense 1) + 30 (Expense 2) = 30
        // Total for Owner: 100 (Expense 1) + 30 (Expense 2) = 130. Paid 160. Balance +30.
        // So Member should owe Owner 30.
        $this->assertDatabaseHas('settlements', [
            'from_user_id' => $member->id,
            'to_user_id' => $owner->id,
            'amount' => 30.00,
            'is_paid' => false,
        ]);
    }

    public function test_paid_settlements_are_accounted_in_balances(): void
    {
        $owner = User::factory()->create(['name' => 'Owner']);
        $member = User::factory()->create(['name' => 'Member']);
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        foreach([$owner, $member] as $u) {
            Membership::create([
                'user_id' => $u->id,
                'colocation_id' => $colocation->id,
                'role' => $u->id === $owner->id ? 'OWNER' : 'MEMBER',
                'join' => now()->subDays(10),
            ]);
        }
        $category = Category::create(['name' => 'Food', 'icon' => '🍕', 'color' => '#ff0000']);

        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Shared Food',
            'amount' => 100.00,
            'expense_date' => now(),
        ]);

        $colocation->recalculateBalances();
        $this->assertDatabaseHas('settlements', ['from_user_id' => $member->id, 'amount' => 50.00, 'is_paid' => false]);

        // Mark as paid
        $settlement = Settlement::first();
        $settlement->update(['is_paid' => true]);

        $colocation->recalculateBalances();
        // Should NOT create a new unpaid settlement because it's now balanced
        $this->assertEquals(0, Settlement::where('is_paid', false)->count());
    }

    public function test_removed_member_debt_is_imputed_to_owner(): void
    {
        $owner = User::factory()->create(['name' => 'Owner']);
        $member = User::factory()->create(['name' => 'Member']);
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        foreach([$owner, $member] as $u) {
            Membership::create([
                'user_id' => $u->id,
                'colocation_id' => $colocation->id,
                'role' => $u->id === $owner->id ? 'OWNER' : 'MEMBER',
                'join' => now()->subDays(10),
            ]);
        }
        $category = Category::create(['name' => 'Food', 'icon' => '🍕', 'color' => '#ff0000']);

        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Shared Food',
            'amount' => 100.00,
            'expense_date' => now(),
        ]);

        $colocation->recalculateBalances();
        
        // Remove member
        $this->actingAs($owner)->delete(route('colocations.removeMember', [$colocation, $member->id]));

        // Member balance should now be 0 because a paid settlement was created
        // And Owner should now own the 50 debt he previously was credited (so net balance should reflect it)
        // Wait, the Owner previously had +50 credit. Member -50.
        // Owner paid Member 50 (imputation). 
        // Owner balance: 50 - 50 = 0. Member: -50 + 50 = 0.
        // Actually, who owes who now? 
        // The 50 was owed to Owner. If Owner takes the debt, he owes himself, which is 0.
        // So the colocation should have 0 settlements.
        $this->assertEquals(0, Settlement::where('colocation_id', $colocation->id)->where('is_paid', false)->count());
        $this->assertDatabaseHas('settlements', [
            'is_paid' => true, 
            'from_user_id' => $member->id, 
            'to_user_id' => $owner->id, 
            'amount' => 50
        ]);

        // Reputation check
        $member->refresh();
        $this->assertEquals(-1, $member->reputation);
    }

    public function test_reputation_increases_on_leave_without_debt(): void
    {
        $owner = User::factory()->create();
        $member = User::factory()->create(['reputation' => 0]);
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        
        Membership::create(['user_id' => $owner->id, 'colocation_id' => $colocation->id, 'role' => 'OWNER', 'join' => now()]);
        Membership::create(['user_id' => $member->id, 'colocation_id' => $colocation->id, 'role' => 'MEMBER', 'join' => now()]);

        $this->actingAs($member)->post(route('colocations.leave', $colocation));

        $member->refresh();
        $this->assertEquals(1, $member->reputation);
    }

    public function test_reputation_on_colocation_cancellation(): void
    {
        $owner = User::factory()->create();
        $memberWithDebt = User::factory()->create(['reputation' => 0]);
        $memberNoDebt = User::factory()->create(['reputation' => 0]);
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        
        Membership::create(['user_id' => $owner->id, 'colocation_id' => $colocation->id, 'role' => 'OWNER', 'join' => now()]);
        Membership::create(['user_id' => $memberWithDebt->id, 'colocation_id' => $colocation->id, 'role' => 'MEMBER', 'join' => now()]);
        Membership::create(['user_id' => $memberNoDebt->id, 'colocation_id' => $colocation->id, 'role' => 'MEMBER', 'join' => now()]);

        $category = Category::create(['name' => 'Food', 'icon' => '🍕', 'color' => '#ff0000']);
        
        // Expense paid by owner, split among 3
        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $owner->id,
            'category_id' => $category->id,
            'title' => 'Big Meal',
            'amount' => 90.00,
            'expense_date' => now(),
        ]);

        // memberNoDebt pays their share
        $colocation->recalculateBalances();
        $settlement = Settlement::where('from_user_id', $memberNoDebt->id)->first();
        $settlement->update(['is_paid' => true]);

        // Members must leave before owner can cancel
        $this->actingAs($memberWithDebt)->post(route('colocations.leave', $colocation));
        $this->actingAs($memberNoDebt)->post(route('colocations.leave', $colocation));

        // Cancel colocation
        $this->actingAs($owner)->delete(route('colocations.destroy', $colocation));

        $memberWithDebt->refresh();
        $memberNoDebt->refresh();
        $owner->refresh();

        $this->assertEquals(-1, $memberWithDebt->reputation);
        $this->assertEquals(1, $memberNoDebt->reputation);
        $this->assertEquals(1, $owner->reputation); // Owner had no debt (he was the creditor)
    }
}
