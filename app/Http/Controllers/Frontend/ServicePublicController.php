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
    public function profile(ServiceProvider $provider)
    {
        if ($provider->status !== 'approved') abort(404);
        $provider->load('categories');
        return view('frontend.services.profile', compact('provider'));
    }
}
