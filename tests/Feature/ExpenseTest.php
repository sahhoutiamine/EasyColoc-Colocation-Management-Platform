<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Colocation;
use App\Models\Expense;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExpenseTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_expense(): void
    {
        $user = User::factory()->create();
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $colocation->id,
            'role' => 'OWNER',
            'join' => now(),
        ]);
        $category = Category::create(['name' => 'Food', 'icon' => '🍕', 'color' => '#ff0000']);

        $response = $this->actingAs($user)->post(route('colocations.expenses.store', $colocation), [
            'title' => 'Pizza Night',
            'amount' => 50.00,
            'category_id' => $category->id,
            'expense_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('expenses', [
            'title' => 'Pizza Night',
            'amount' => 50.00,
        ]);
    }

    public function test_user_can_filter_expenses_by_month(): void
    {
        $user = User::factory()->create();
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $colocation->id,
            'role' => 'OWNER',
            'join' => now(),
        ]);
        $category = Category::create(['name' => 'Food', 'icon' => '🍕', 'color' => '#ff0000']);

        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'January Expense',
            'amount' => 10.00,
            'expense_date' => '2026-01-15',
        ]);

        Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'February Expense',
            'amount' => 20.00,
            'expense_date' => '2026-02-15',
        ]);

        $response = $this->actingAs($user)->get(route('colocations.expenses.index', [$colocation, 'month' => 2]));

        $response->assertStatus(200);
        $response->assertSee('February Expense');
        $response->assertDontSee('January Expense');
    }

    public function test_user_can_edit_own_expense(): void
    {
        $user = User::factory()->create();
        $colocation = Colocation::create(['name' => 'Test Coloc']);
        Membership::create([
            'user_id' => $user->id,
            'colocation_id' => $colocation->id,
            'role' => 'OWNER',
            'join' => now(),
        ]);
        $category = Category::create(['name' => 'Food', 'icon' => '🍕', 'color' => '#ff0000']);
        $expense = Expense::create([
            'colocation_id' => $colocation->id,
            'payer_id' => $user->id,
            'category_id' => $category->id,
            'title' => 'Old Title',
            'amount' => 10.00,
            'expense_date' => now()->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->patch(route('colocations.expenses.update', [$colocation, $expense]), [
            'title' => 'New Title',
            'amount' => 15.00,
            'category_id' => $category->id,
            'expense_date' => now()->format('Y-m-d'),
        ]);

        $response->assertStatus(302);
        $this->assertDatabaseHas('expenses', [
            'id' => $expense->id,
            'title' => 'New Title',
            'amount' => 15.00,
        ]);
    }
}
