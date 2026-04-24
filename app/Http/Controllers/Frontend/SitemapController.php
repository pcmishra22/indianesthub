<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Models\Property;

class SitemapController extends Controller
{
    public function index()
    {
        $properties = Property::all();
        $urls = [];
        foreach ($properties as $property) {
            $urls[] = [
                'loc' => URL::to('/properties/' . $property->id),
                'lastmod' => $property->updated_at->toAtomString(),
            ];
        }
        return response()->view('frontend.sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
