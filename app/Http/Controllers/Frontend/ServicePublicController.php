<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceProvider;
use Illuminate\Http\Request;

class ServicePublicController extends Controller
{
    /**
     * City slug => display name. Kept in sync with SeoController::getSeoLandingCities().
     * Add a city here and every category automatically gets a clean
     * /services/{category}/{city} page — no new routes ever required.
     */
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

    /**
     * /services
     * Hub page listing every active category.
     */
    public function index()
    {
        $categories = ServiceCategory::active()
            ->withCount(['providers' => fn ($q) => $q->where('status', 'approved')])
            ->get();

        $cities = self::getCityMap();

        return view('frontend.services.index', compact('categories', 'cities'));
    }

    /**
     * /services/{category}
     * All approved providers in one category, across every city, with a city filter.
     */
    public function category(ServiceCategory $category, Request $request)
    {
        $providers = $category->providers()
            ->when($request->filled('city'), fn ($q) => $q->where('city', $request->city))
            ->orderByDesc('is_verified')
            ->paginate(12);

        $cities = self::getCityMap();

        return view('frontend.services.category', compact('category', 'providers', 'cities'));
    }

    /**
     * /services/{category}/{city}
     * The hyperlocal SEO money page — e.g. "Electricians in Zirakpur".
     */
    public function categoryCity(ServiceCategory $category, string $city)
    {
        $cityMap = self::getCityMap();

        if (!array_key_exists($city, $cityMap)) {
            abort(404);
        }

        $cityLabel = $cityMap[$city];

        $providers = $category->providers()
            ->where(function ($q) use ($cityLabel) {
                $q->where('city', $cityLabel)
                  ->orWhereJsonContains('operating_areas', $cityLabel);
            })
            ->orderByDesc('is_verified')
            ->paginate(12);

        return view('frontend.services.category-city', compact('category', 'city', 'cityLabel', 'providers', 'cityMap'));
    }

    /**
     * /professionals/{provider}
     * Individual public profile page (lead capture lives here).
     */
    public function profile(ServiceProvider $provider)
    {
        if ($provider->status !== 'approved') {
            abort(404);
        }

        $provider->load('categories');

        return view('frontend.services.profile', compact('provider'));
    }
}
