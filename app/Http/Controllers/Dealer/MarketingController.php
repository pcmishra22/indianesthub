<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Services\PropertyBrochureService;
use Illuminate\Support\Facades\Auth;

class MarketingController extends Controller
{
    public function __construct(protected PropertyBrochureService $brochures)
    {
    }

    private function authorizeProperty(Property $property): void
    {
        if ($property->property_dealer_id !== Auth::guard('dealer')->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Marketing Studio hub for a single property.
     */
    public function index(Property $property)
    {
        $this->authorizeProperty($property);

        $publicUrl = $property->slug ? route('property-details', $property) : null;

        return view('dealer.properties.marketing', compact('property', 'publicUrl'));
    }

    /**
     * Generate the brochure PDF and stream it for download.
     * Also saves a copy against the property's brochure_pdf field.
     */
    public function brochure(Property $property)
    {
        $this->authorizeProperty($property);

        $this->brochures->generateAndStore($property);

        return $this->brochures->render($property)
            ->download($this->brochures->downloadFilename($property));
    }
}
