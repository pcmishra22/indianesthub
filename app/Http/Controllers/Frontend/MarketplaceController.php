<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MarketplaceCategory;
use App\Models\MarketplaceLead;
use App\Models\MarketplaceProduct;
use App\Models\MarketplaceVendor;
use Illuminate\Http\Request;

/**
 * Public "Home Marketplace" browsing surface.
 *
 * This sits alongside the property-page widget (which cross-sells
 * matched products on a property detail page). This controller is
 * the standalone shop: /marketplace → category → product, so the
 * marketplace can be found, indexed by Google, and shared directly —
 * not only discovered from inside a property page.
 *
 * Still a lead-gen flow (no cart/checkout): every page funnels into
 * the same marketplace.lead.submit endpoint used by the widget.
 */
class MarketplaceController extends Controller
{
    /** /marketplace — hub page: categories + featured products */
    public function index(Request $request)
    {
        $categories = MarketplaceCategory::active()
            ->withCount(['products' => function ($q) {
                $q->where('is_active', true)->whereHas('vendor', fn ($v) => $v->where('is_active', true));
            }])
            ->get();

        $productsQuery = MarketplaceProduct::with('vendor', 'category')
            ->where('is_active', true)
            ->whereHas('vendor', fn ($v) => $v->where('is_active', true));

        if ($request->filled('city')) {
            $productsQuery->whereHas('vendor', fn ($v) => $v->where('city', $request->city));
        }

        $featuredProducts = (clone $productsQuery)
            ->orderByDesc('is_featured')
            ->orderByDesc('leads_count')
            ->orderBy('sort_order')
            ->limit(12)
            ->get();

        $vendorCount  = MarketplaceVendor::where('is_active', true)->count();
        $productCount = MarketplaceProduct::where('is_active', true)->count();
        $cities       = MarketplaceVendor::where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        return view('frontend.marketplace.index', compact(
            'categories', 'featuredProducts', 'vendorCount', 'productCount', 'cities'
        ));
    }

    /** /marketplace/{category} — all products in one category, filterable by city/BHK/search */
    public function category(MarketplaceCategory $category, Request $request)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $products = MarketplaceProduct::with('vendor', 'category')
            ->where('category_id', $category->id)
            ->where('is_active', true)
            ->whereHas('vendor', fn ($v) => $v->where('is_active', true))
            ->when($request->filled('city'), function ($q) use ($request) {
                $q->whereHas('vendor', fn ($v) => $v->where('city', $request->city));
            })
            ->when($request->filled('bhk'), function ($q) use ($request) {
                // bhk_fit is a JSON array of strings like ["1","2","3"]; null/empty means "fits all"
                $q->where(function ($q2) use ($request) {
                    $q2->whereNull('bhk_fit')
                       ->orWhereJsonContains('bhk_fit', (string) $request->bhk);
                });
            })
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where(function ($q2) use ($request) {
                    $q2->where('name', 'like', '%' . $request->search . '%')
                       ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            })
            ->orderByDesc('is_featured')
            ->orderByDesc('leads_count')
            ->orderBy('sort_order')
            ->paginate(12)
            ->withQueryString();

        $cities = MarketplaceVendor::where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $allCategories = MarketplaceCategory::active()->get();

        return view('frontend.marketplace.category', compact('category', 'products', 'cities', 'allCategories'));
    }

    /** /marketplace/{category}/{product} — product detail + quote form */
    public function product(MarketplaceCategory $category, MarketplaceProduct $product)
    {
        if (!$product->is_active || $product->category_id !== $category->id) {
            abort(404);
        }

        $product->load('vendor', 'category', 'images');

        $relatedProducts = MarketplaceProduct::with('vendor', 'category')
            ->where('category_id', $category->id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->whereHas('vendor', fn ($v) => $v->where('is_active', true))
            ->orderByDesc('is_featured')
            ->limit(4)
            ->get();

        return view('frontend.marketplace.product', compact('product', 'category', 'relatedProducts'));
    }

    /** /marketplace/vendor/{vendor} — vendor profile: reviews, portfolio, map, GST, product catalog */
    public function vendor(MarketplaceVendor $vendor, Request $request)
    {
        if (!$vendor->is_active) {
            abort(404);
        }

        $vendor->load('portfolios');

        $products = $vendor->products()
            ->where('is_active', true)
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(12);

        $reviews = $vendor->approvedReviews()->with('user')->latest()->paginate(10);

        return view('frontend.marketplace.vendor', compact('vendor', 'products', 'reviews'));
    }

    /**
     * Records a lead when a logged-in visitor clicks Call or WhatsApp on a
     * vendor's profile — reuses the existing MarketplaceLead system (same
     * one the quote-request forms feed into) rather than a separate
     * tracking table, so these show up in the same admin Leads view.
     * AJAX-friendly: always 204, never blocks the actual tel:/wa.me
     * navigation even if the visitor isn't logged in (in which case
     * nothing is recorded).
     */
    public function recordVendorContactClick(MarketplaceVendor $vendor, Request $request)
    {
        $request->validate(['contact_method' => 'required|in:call,whatsapp']);

        if (\Illuminate\Support\Facades\Auth::check()) {
            $user = \Illuminate\Support\Facades\Auth::user();
            MarketplaceLead::create([
                'vendor_id'   => $vendor->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'phone'       => $user->phone ?? '',
                'source_page' => 'vendor_profile_' . $request->contact_method,
                'ip_address'  => $request->ip(),
                'user_agent'  => $request->userAgent(),
                'status'      => 'new',
            ]);
        }

        return response()->noContent();
    }
}
