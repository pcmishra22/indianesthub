<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Dealer;
use App\Models\Builder;
use App\Models\BuilderProject;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    /**
     * All supported locations for location-based pages.
     * Kept in sync with PropertiesController::getLocationMap().
     */
    public static function getSeoLocations(): array
    {
        return [
            'dhakoli', 'zirakpur', 'derabassi', 'panchkula',
            'chandigarh', 'mohali', 'manimajra', 'banur',
            'landran', 'mullanpur', 'kharar', 'gharuan',
            'kurali', 'morinda', 'fatehgarh-sahib', 'pinjore',
            'kalka', 'solan', 'baddi', 'barotiwala',
            'nalagarh', 'rajpura', 'ambala', 'ropar', 'patiala',
        ];
    }

    /**
     * Cities that have dedicated hyperlocal landing pages.
     */
    public static function getSeoLandingCities(): array
    {
        return [
            'zirakpur', 'mohali', 'chandigarh', 'panchkula',
            'kharar', 'derabassi', 'mullanpur', 'patiala', 'ambala',
        ];
    }

    /**
     * Generate a comprehensive sitemap.xml for all public-facing pages.
     * Covers: static pages, location pages, hyperlocal landing pages,
     *         properties, dealers, builders, builder projects, blog posts.
     */
    public function sitemap()
    {
        // Active properties with slugs
        $properties = Property::whereNotIn('status', ['sold', 'rented', 'inactive', 'draft', 'expired', 'Sold', 'Rented'])
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Active dealers
        $dealers = Dealer::where('status', 'active')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select('slug', 'updated_at')
            ->get();

        // Active builders
        $builders = Builder::where('status', 'active')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select('slug', 'updated_at')
            ->get();

        // All builder projects
        $projects = BuilderProject::select('id', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        // Published blog posts
        $blogs = BlogPost::where('status', 'published')
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->select('slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $locations    = self::getSeoLocations();
        $landingCities = self::getSeoLandingCities();

        $content = view('frontend.seo.sitemap', compact(
            'properties', 'dealers', 'builders', 'projects',
            'blogs', 'locations', 'landingCities'
        ))->render();

        return response($content, 200)->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
