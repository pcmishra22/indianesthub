<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceCategory;
use App\Models\MarketplaceLead;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketplaceProductController extends Controller
{
    public function index(Request $request)
    {
        $q = MarketplaceProduct::with(['vendor', 'category']);

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $q->where('name', 'like', $term);
        }
        if ($request->filled('category_id')) {
            $q->where('category_id', $request->category_id);
        }
        if ($request->filled('vendor_id')) {
            $q->where('vendor_id', $request->vendor_id);
        }
        if ($request->filled('status')) {
            $q->where('is_active', $request->status === 'active');
        }

        $products  = $q->orderByDesc('is_featured')->orderBy('name')->paginate(20);
        $categories = MarketplaceCategory::active()->get();
        $vendors    = MarketplaceVendor::orderBy('business_name')->get();

        return view('backend.marketplace.products.index', compact('products', 'categories', 'vendors'));
    }

    public function create()
    {
        $categories = MarketplaceCategory::active()->get();
        $vendors    = MarketplaceVendor::orderBy('business_name')->get();
        return view('backend.marketplace.products.create', compact('categories', 'vendors'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data['slug'] = Str::slug($data['name']);
        $data['bhk_fit'] = $this->normalizeBhk($request->input('bhk_fit', []));
        $data['tags']    = $this->normalizeTags($request->input('tags', ''));

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('marketplace/products', 'public');
        }

        $product = MarketplaceProduct::create($data);

        $this->syncGallery($request, $product);

        return redirect()->route('admin.marketplace.products.index')
            ->with('success', 'Product added.');
    }

    public function show(MarketplaceProduct $product)
    {
        $product->load('vendor', 'category', 'images', 'leads');
        return view('backend.marketplace.products.show', compact('product'));
    }

    public function edit(MarketplaceProduct $product)
    {
        $categories = MarketplaceCategory::active()->get();
        $vendors    = MarketplaceVendor::orderBy('business_name')->get();
        $product->load('images');
        return view('backend.marketplace.products.edit', compact('product', 'categories', 'vendors'));
    }

    public function update(Request $request, MarketplaceProduct $product)
    {
        $data = $this->validateProduct($request);
        $data['bhk_fit'] = $this->normalizeBhk($request->input('bhk_fit', []));
        $data['tags']    = $this->normalizeTags($request->input('tags', ''));

        if ($request->hasFile('cover_image')) {
            if ($product->cover_image) {
                Storage::disk('public')->delete($product->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('marketplace/products', 'public');
        }

        $product->update($data);
        $this->syncGallery($request, $product);

        return redirect()->route('admin.marketplace.products.index')
            ->with('success', 'Product updated.');
    }

    public function destroy(MarketplaceProduct $product)
    {
        if ($product->cover_image) {
            Storage::disk('public')->delete($product->cover_image);
        }
        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $product->delete();
        return redirect()->route('admin.marketplace.products.index')
            ->with('success', 'Product removed.');
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'vendor_id'   => 'required|integer|exists:marketplace_vendors,id',
            'category_id' => 'required|integer|exists:marketplace_categories,id',
            'name'        => 'required|string|max:200',
            'description' => 'nullable|string',
            'price_min'   => 'nullable|numeric|min:0',
            'price_max'   => 'nullable|numeric|min:0',
            'price_unit'  => 'nullable|string|max:30',
            'sort_order'  => 'nullable|integer',
            'is_featured' => 'sometimes|boolean',
            'is_active'   => 'sometimes|boolean',
            'cover_image' => 'nullable|image|max:4096',
            'gallery.*'   => 'nullable|image|max:4096',
        ]) + [
            'is_featured' => (bool) $request->input('is_featured', false),
            'is_active'   => (bool) $request->input('is_active', true),
            'price_unit'  => $request->input('price_unit', 'onwards'),
        ];
    }

    private function normalizeBhk($value): ?array
    {
        if (is_array($value)) {
            $clean = array_values(array_filter(array_map('strval', $value)));
            return $clean ?: null;
        }
        if (is_string($value) && trim($value) !== '') {
            $parts = array_map('trim', explode(',', $value));
            return array_values(array_filter($parts));
        }
        return null; // null = fits all
    }

    private function normalizeTags($value): ?array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map('trim', $value)));
        }
        if (is_string($value) && trim($value) !== '') {
            return array_values(array_filter(array_map('trim', explode(',', $value))));
        }
        return null;
    }

    private function syncGallery(Request $request, MarketplaceProduct $product): void
    {
        if (!$request->hasFile('gallery')) {
            return;
        }
        $startSort = ($product->images()->max('sort_order') ?? 0) + 1;
        foreach ($request->file('gallery') as $i => $file) {
            $path = $file->store('marketplace/products', 'public');
            $product->images()->create([
                'image_path' => $path,
                'sort_order' => $startSort + $i,
            ]);
        }
    }
}
