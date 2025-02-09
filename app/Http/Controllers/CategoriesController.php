<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('admin.category', compact('categories'));
    }

    public function setDefaultForUsers(Request $request)
    {
        // Reset semua kategori agar tidak default untuk user
        Category::where('is_default_for_users', true)->update(['is_default_for_users' => false]);

        // Update kategori yang dipilih sebagai default
        Category::whereIn('id', $request->default_categories)->update(['is_default_for_users' => true]);

        return redirect()->route('categoryAdmin')->with('success', 'Kategori default untuk user telah diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'is_default' => 'sometimes|boolean',
        ]);

        Category::create([
            'name' => $request->name,
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('categoryAdmin')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        return view('admin.category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $category->id,
            'is_default' => 'sometimes|boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('categoryAdmin')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categoryAdmin')->with('success', 'Kategori berhasil dihapus.');
    }
}
