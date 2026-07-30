<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;

class ServicePublicController extends Controller
{
    public static function getCityMap(): array
    {
        return [
            'zirakpur'   => 'Zirakpur',
            'mohali'     => 'Mohali',
            'chandigarh' => 'Chandigarh',
            'panchkula'  => 'Panchkula',
            'kharar'     => 'Kharar',
            'derabassi'  => 'Derabassi',
            'mullanpur'  => 'Mullanpur',
            'patiala'    => 'Patiala',
            'pinjore'    => 'Pinjore',
            'ambala'     => 'Ambala',
        ];
    }

    /** /services — hub page listing every category */
    public function index()
    {
        $categories = ServiceCategory::active()
            ->withCount(['providers' => fn($q) => $q->where('status', 'approved')])
            ->get();

        $cities            = self::getCityMap();
        $totalProviders    = ServiceProvider::where('status', 'approved')->count();

        return view('frontend.services.index', compact('categories', 'cities', 'totalProviders'));
    }

    /** /services/{category} — all cities, with search + AJAX scroll */
    public function category(ServiceCategory $category, Request $request)
    {
        if ($request->ajax()) {
            $providers = $category->providers()
                ->when($request->filled('city'),   fn($q) => $q->where('city', $request->city))
                ->when($request->filled('search'), fn($q) =>
                    $q->where(function($q2) use ($request) {
                        $q2->where('full_name', 'like', '%'.$request->search.'%')
                           ->orWhere('business_name', 'like', '%'.$request->search.'%')
                           ->orWhere('city', 'like', '%'.$request->search.'%');
                    })
                )
                ->orderByDesc('is_verified')
                ->paginate(12);

            return response()->json([
                'html'     => view('frontend.services.partials.provider-cards', compact('providers', 'category'))->render(),
                'has_more' => $providers->hasMorePages(),
                'total'    => $providers->total(),
            ]);
        }

        $providers = $category->providers()
            ->when($request->filled('city'),   fn($q) => $q->where('city', $request->city))
            ->when($request->filled('search'), fn($q) =>
                $q->where(function($q2) use ($request) {
                    $q2->where('full_name', 'like', '%'.$request->search.'%')
                       ->orWhere('business_name', 'like', '%'.$request->search.'%')
                       ->orWhere('city', 'like', '%'.$request->search.'%');
                })
            )
            ->orderByDesc('is_verified')
            ->paginate(12);

        $cities      = self::getCityMap();
        $allCats     = ServiceCategory::active()->get();

        return view('frontend.services.category', compact('category', 'providers', 'cities', 'allCats'));
    }

    /** /services/{category}/{city} — hyperlocal SEO page */
    public function categoryCity(ServiceCategory $category, string $city)
    {
        $cityMap = self::getCityMap();
        if (!array_key_exists($city, $cityMap)) abort(404);
        $cityLabel = $cityMap[$city];

        $providers = $category->providers()
            ->where(function($q) use ($cityLabel) {
                $q->where('city', $cityLabel)
                  ->orWhereJsonContains('operating_areas', $cityLabel);
            })
            ->orderByDesc('is_verified')
            ->paginate(12);

        return view('frontend.services.category-city', compact('category', 'city', 'cityLabel', 'providers', 'cityMap'));
    }

    /** /professionals/{provider} — public profile with login-gate contact */
    public function profile(ServiceProvider $provider, Request $request)
    {
        if ($provider->status !== 'approved') abort(404);
        $provider->load('categories', 'portfolios');

        // Basic session-deduped view counting — one increment per visitor per
        // provider per session, so refreshes/back-and-forth browsing don't
        // inflate the count. (A simpler approach than the bot-aware
        // PropertyView system used for property listings — good enough for
        // a first version here.)
        $seenKey = 'viewed_service_providers';
        $seen = $request->session()->get($seenKey, []);
        if (!in_array($provider->id, $seen)) {
            $provider->increment('views_count');
            $seen[] = $provider->id;
            $request->session()->put($seenKey, $seen);
        }

        $reviews = $provider->approvedReviews()->with('user')->latest()->paginate(10);

        return view('frontend.services.profile', compact('provider', 'reviews'));
    }

    /**
     * Record a lead when a logged-in visitor reveals/clicks Call or WhatsApp
     * contact on a provider's profile — this is what the provider's dashboard
     * "Leads Received" stat counts. AJAX-friendly: returns 204 either way so
     * it never blocks the actual tel:/wa.me navigation.
     */
    public function recordContactClick(ServiceProvider $provider, Request $request)
    {
        $request->validate(['contact_method' => 'required|in:call,whatsapp']);

        if (\Illuminate\Support\Facades\Auth::check()) {
            \App\Models\ServiceProviderLead::create([
                'service_provider_id' => $provider->id,
                'user_id'             => \Illuminate\Support\Facades\Auth::id(),
                'contact_method'      => $request->contact_method,
            ]);
        }

        return response()->noContent();
    }
}
