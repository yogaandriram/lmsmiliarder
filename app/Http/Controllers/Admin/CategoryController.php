<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('name')->get();
        return view('pages.admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('pages.admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        Category::create($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dibuat');
    }

    public function edit(Category $category)
    {
        return view('pages.admin.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required','string','max:120'],
            'description' => ['nullable','string'],
            'is_active' => ['nullable','boolean'],
        ]);
        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);
        return redirect()->route('admin.categories.index')->with('success', 'Kategori diperbarui');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Kategori dihapus');
    }

    public function toggle(Category $category)
    {
        $category->is_active = ! $category->is_active;
        $category->save();
        return back()->with('success', 'Status kategori diperbarui');
    }
}