<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceVendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MarketplaceVendorController extends Controller
{
    public function index(Request $request)
    {
        $q = MarketplaceVendor::query()->withCount('products');

        if ($request->filled('search')) {
            $term = '%' . $request->search . '%';
            $q->where(function ($x) use ($term) {
                $x->where('business_name', 'like', $term)
                  ->orWhere('owner_name', 'like', $term)
                  ->orWhere('city', 'like', $term);
            });
        }
        if ($request->filled('status')) {
            $q->where('is_active', $request->status === 'active');
        }

        $vendors = $q->orderByDesc('is_verified')->orderBy('business_name')->paginate(20);

        return view('backend.marketplace.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('backend.marketplace.vendors.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateVendor($request);
        $data['slug'] = Str::slug($data['business_name']);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('marketplace/vendors', 'public');
        }

        MarketplaceVendor::create($data);

        return redirect()->route('admin.marketplace.vendors.index')
            ->with('success', 'Vendor added.');
    }

    public function show(MarketplaceVendor $vendor)
    {
        $vendor->load('products', 'leads');
        return view('backend.marketplace.vendors.show', compact('vendor'));
    }

    public function edit(MarketplaceVendor $vendor)
    {
        return view('backend.marketplace.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, MarketplaceVendor $vendor)
    {
        $data = $this->validateVendor($request, $vendor->id);

        if ($request->hasFile('logo')) {
            if ($vendor->logo) {
                Storage::disk('public')->delete($vendor->logo);
            }
            $data['logo'] = $request->file('logo')->store('marketplace/vendors', 'public');
        }

        $vendor->update($data);

        return redirect()->route('admin.marketplace.vendors.index')
            ->with('success', 'Vendor updated.');
    }

    public function destroy(MarketplaceVendor $vendor)
    {
        if ($vendor->logo) {
            Storage::disk('public')->delete($vendor->logo);
        }
        $vendor->delete();
        return redirect()->route('admin.marketplace.vendors.index')
            ->with('success', 'Vendor removed.');
    }

    public function toggleVerified(MarketplaceVendor $vendor)
    {
        $vendor->update(['is_verified' => !$vendor->is_verified]);
        return back()->with('success', 'Verification toggled.');
    }

    public function toggleActive(MarketplaceVendor $vendor)
    {
        $vendor->update(['is_active' => !$vendor->is_active]);
        return back()->with('success', 'Active status toggled.');
    }

    private function validateVendor(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'business_name'   => 'required|string|max:160',
            'owner_name'      => 'nullable|string|max:120',
            'phone'           => 'required|string|max:20',
            'whatsapp'        => 'nullable|string|max:20',
            'email'           => 'nullable|email|max:160',
            'city'            => 'nullable|string|max:80',
            'area'            => 'nullable|string|max:120',
            'address'         => 'nullable|string|max:255',
            'latitude'        => 'nullable|numeric|between:-90,90',
            'longitude'       => 'nullable|numeric|between:-180,180',
            'gst_number'      => 'nullable|string|max:15',
            'description'     => 'nullable|string',
            'years_in_business' => 'nullable|integer|min:0',
            'commission_pct'  => 'required|numeric|min:0|max:50',
            'is_verified'     => 'sometimes|boolean',
            'is_active'       => 'sometimes|boolean',
            'logo'            => 'nullable|image|max:2048',
        ]) + [
            'is_verified' => (bool) $request->input('is_verified', false),
            'is_active'   => (bool) $request->input('is_active', true),
        ];
    }
}
