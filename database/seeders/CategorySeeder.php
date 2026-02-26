<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Rent', 'icon' => '🏠', 'color' => '#6366f1'],
            ['name' => 'Groceries', 'icon' => '🛒', 'color' => '#10b981'],
            ['name' => 'Bills', 'icon' => '⚡', 'color' => '#f59e0b'],
            ['name' => 'Internet', 'icon' => '🌐', 'color' => '#06b6d4'],
            ['name' => 'Cleaning', 'icon' => '🧹', 'color' => '#ec4899'],
            ['name' => 'Others', 'icon' => '📦', 'color' => '#94a3b8'],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
