<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MarketplaceCategoryController extends Controller
{
    public function index()
    {
        $categories = MarketplaceCategory::withCount('products')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('backend.marketplace.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('backend.marketplace.categories.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateCategory($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        MarketplaceCategory::create($data);

        return redirect()->route('admin.marketplace.categories.index')
            ->with('success', 'Category added.');
    }

    public function edit(MarketplaceCategory $category)
    {
        return view('backend.marketplace.categories.edit', compact('category'));
    }

    public function update(Request $request, MarketplaceCategory $category)
    {
        $data = $this->validateCategory($request, $category->id);

        // Only re-slug if the name actually changed, so existing public URLs don't break silently.
        if ($data['name'] !== $category->name) {
            $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        }

        $category->update($data);

        return redirect()->route('admin.marketplace.categories.index')
            ->with('success', 'Category updated.');
    }

    public function destroy(MarketplaceCategory $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Cannot delete a category that still has products. Move or delete its products first.');
        }

        $category->delete();

        return redirect()->route('admin.marketplace.categories.index')
            ->with('success', 'Category removed.');
    }

    public function toggleActive(MarketplaceCategory $category)
    {
        $category->update(['is_active' => !$category->is_active]);
        return back()->with('success', 'Category status toggled.');
    }

    private function validateCategory(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name'       => 'required|string|max:80',
            'icon'       => 'nullable|string|max:60',
            'tagline'    => 'nullable|string|max:160',
            'sort_order' => 'nullable|integer|min:0',
        ]) + [
            'sort_order' => (int) $request->input('sort_order', 0),
            'is_active'  => (bool) $request->input('is_active', true),
        ];
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name ?: 'category');
        $slug = $base;
        $i = 1;
        while (
            MarketplaceCategory::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }
        return $slug;
    }
}
