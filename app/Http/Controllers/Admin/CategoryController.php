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
        return view('admin.categories.index', ['categories' => Category::withCount('photos')->orderBy('name_es')->get()]);
    }

    public function create()
    {
        return view('admin.categories.form', ['category' => null]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateCategory($request);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', __('Category created successfully'));
    }

    public function edit(Category $category)
    {
        return view('admin.categories.form', ['category' => $category]);
    }

    public function update(Request $request, Category $category)
    {
        $validated = $this->validateCategory($request, $category);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', __('Category updated successfully'));
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', __('Category deleted successfully'));
    }

    private function validateCategory(Request $request, ?Category $category = null): array
    {
        $validated = $request->validate([
            'name_es' => ['required', 'string', 'max:255'],
            'name_en' => ['required', 'string', 'max:255'],
            'description_es' => ['nullable', 'string', 'max:2000'],
            'description_en' => ['nullable', 'string', 'max:2000'],
        ]);

        $slugBase = $request->input('slug', $category?->slug ?? $validated['name_es']);

        $validated['slug'] = Str::slug($slugBase);

        if ($category) {
            $validated['slug'] = $this->uniqueSlug($validated['slug'], $category);
        } else {
            $validated['slug'] = $this->uniqueSlug($validated['slug']);
        }

        return $validated;
    }

    private function uniqueSlug(string $slug, ?Category $ignore = null): string
    {
        $base = $slug;
        $count = 2;

        while (Category::where('slug', $slug)->when($ignore, fn ($q) => $q->where('id', '!=', $ignore->id))->exists()) {
            $slug = $base.'-'.$count;
            $count++;
        }

        return $slug;
    }
}
