<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dealer;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DealerPropertyController extends Controller
{
    public function index($dealer)
    {
        $dealer = Dealer::findOrFail($dealer);

        // Mask dealer contact details if the user is not logged in
        if (!Auth::check()) {
            $dealer->phone = 'Login to view';
            $dealer->email = 'Login to view';
        }

        $properties = Property::where('property_dealer_id', $dealer->id)->get();
        return view('frontend.dealer-properties', [
            'dealer' => $dealer,
            'properties' => $properties
        ]);
    }
}
