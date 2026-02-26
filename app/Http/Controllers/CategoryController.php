<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'icon' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:20',
        ]);

        Category::create($validated);

        return redirect()->back()->with('success', 'Category created successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->expenses()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete category with associated expenses.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Category deleted.');
    }
}
