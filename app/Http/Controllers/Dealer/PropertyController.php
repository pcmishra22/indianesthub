<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    /**
     * Show CSV upload form for bulk property upload.
     */
    public function showCsvUploadForm()
    {
        return view('dealer.properties.upload-csv');
    }

    /**
     * Handle CSV upload and import properties.
     */
    public function uploadCsv(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);
        $dealerId = Auth::guard('dealer')->id();
        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        $header = fgetcsv($handle);
        $imported = 0;
        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            // Minimal required fields
            if (!isset($data['title'], $data['property_type'], $data['looking_for'], $data['address'], $data['city'], $data['state'], $data['country'], $data['price'])) {
                continue;
            }
            $data['property_dealer_id'] = $dealerId;
            Property::create($data);
            $imported++;
        }
        fclose($handle);
        return redirect()->route('dealer.properties.index')->with('success', "$imported properties imported from CSV.");
    }
    public function index()
    {
        $dealerId = Auth::guard('dealer')->id();
        $properties = Property::where('property_dealer_id', $dealerId)
                             ->withExists(['payments as is_paid' => function($query) use ($dealerId) {
                                 $query->whereIn('status', ['completed', '1', 1])
                                       ->where('payment_type', 'property_listing')
                                       ->where('listing_end_date', '>=', now())
                                       ->where('dealer_id', $dealerId);
                             }])
                             ->latest()
                             ->paginate(10);
        return view('dealer.properties.index', compact('properties'));
    }

    public function create()
    {
        $property = new Property();
        return view('dealer.properties.create', compact('property'));
    }

    public function store(Request $request)
    {
        $dealerId = Auth::guard('dealer')->id();
        $data = $request->validate([
            // Boolean flags
            'gated_society' => 'nullable|boolean',
            'corner_property' => 'nullable|boolean',
            'vastu_compliant' => 'nullable|boolean',
            'wheelchair_friendly' => 'nullable|boolean',
            'overlooking_park' => 'nullable|boolean',
            'overlooking_road' => 'nullable|boolean',
            'income_property' => 'nullable|boolean',
            'distress_sale' => 'nullable|boolean',
            'user_id' => 'nullable|integer|exists:users,id',
            'contact_name' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:30',
            'contact_email' => 'nullable|email|max:100',
            'company_name' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:50',
            'verified_user' => 'nullable|boolean',
            'ownership_type' => 'nullable|string|in:Freehold,Leasehold',
            'property_approval' => 'nullable|string|max:100',
            'rera_id' => 'nullable|string|max:100',
            'rera_verified' => 'nullable|boolean',
            'occupancy_certificate' => 'nullable|string|max:100',
            'completion_certificate' => 'nullable|string|max:100',
            'legal_clearance_status' => 'nullable|string|max:100',
            'covered_parking' => 'nullable|integer',
            'open_parking' => 'nullable|integer',
            'water_supply' => 'nullable|string|in:Municipal,Borewell',
            'electricity_status' => 'nullable|string|max:50',
            'gas_pipeline' => 'nullable|boolean',
            'drainage' => 'nullable|boolean',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'nullable|string|max:255',
            'looking_for' => 'nullable|string|in:Rent,Sale,Renovate',
            'address' => 'nullable|string|max:255',
            'floor_plan_details' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'locality' => 'nullable|string|max:255',
            'sub_locality' => 'nullable|string|max:255',
            'society_name' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'map_url' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'total_floors' => 'nullable|integer',
            'floor_number' => 'nullable|integer',
            'facing' => 'nullable|string|in:East,West,North,South',
            'property_age' => 'nullable|string|max:50',
            'furnishing_status' => 'nullable|string|in:Furnished,Semi,Unfurnished',
            'area' => 'nullable|integer',
            'amenities' => 'nullable|array',
            'virtual_tour_url' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'security_deposit' => 'nullable|string|max:255',
            'possession_date' => 'nullable|string|max:255',
            'price_range' => 'nullable|string|max:255',
            'bhk_type' => 'nullable|string|max:255',
            'option_type' => 'nullable|string|max:255',
            'expected_price' => 'nullable|numeric',
            'price_per_sqft' => 'nullable|numeric',
            'negotiable' => 'nullable|boolean',
            'maintenance_charges' => 'nullable|numeric',
            'booking_amount' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'lease_duration' => 'nullable|string|max:255',
            'possession_status' => 'nullable|string|max:255',
            'super_builtup_area' => 'nullable|numeric',
            'builtup_area' => 'nullable|numeric',
            'carpet_area' => 'nullable|numeric',
            'area_unit' => 'nullable|string|max:10',
            'plot_area' => 'nullable|numeric',
            'plot_length' => 'nullable|numeric',
            'plot_breadth' => 'nullable|numeric',
            'share_with_agents' => 'nullable|boolean',
            // Nearby & Distance fields
            'nearby_schools' => 'nullable|string|max:255',
            'nearby_hospitals' => 'nullable|string|max:255',
            'nearby_malls' => 'nullable|string|max:255',
            'nearby_metro' => 'nullable|string|max:255',
            'nearby_bus_stand' => 'nullable|string|max:255',
            'distance_metrics' => 'nullable',
            // SEO & Featured fields
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'search_tags' => 'nullable|string|max:255',
            'priority_score' => 'nullable|integer',
        ]);

        $data['share_with_agents'] = $request->has('share_with_agents');
        $data['pet_friendly'] = $request->has('pet_friendly');
        $data['isreal'] = $request->has('isreal');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_premium'] = $request->has('is_premium');
        $data['property_dealer_id'] = $dealerId;

        // Validation above is intentionally lenient (dealers often don't have
        // every field on hand at listing time and fill the rest in later via
        // edit). But a handful of DB columns are NOT NULL with no default, so
        // fall back to a placeholder rather than letting an empty submission
        // crash with a SQL "cannot be null" error. These placeholders are
        // obviously incomplete on purpose, to prompt the dealer to edit later.
        $data['title'] = ($data['title'] ?? '') ?: 'Untitled Property (edit to add details)';
        $data['property_type'] = ($data['property_type'] ?? '') ?: 'Residential';
        $data['address'] = ($data['address'] ?? '') ?: 'Address not provided yet';
        $data['city'] = ($data['city'] ?? '') ?: 'Not specified';
        $data['state'] = ($data['state'] ?? '') ?: 'Not specified';
        $data['price'] = $data['price'] ?? 0;

        // Compatibility sync: 'floor', 'furnishing' and 'featured' are older
        // columns some display views still read (e.g. dealer property show
        // page). The form only collects their newer replacements
        // (floor_number, furnishing_status, is_featured) now — this keeps
        // the legacy columns populated too rather than leaving them blank.
        $data['floor'] = isset($data['floor_number']) ? (string) $data['floor_number'] : null;
        $data['furnishing'] = $data['furnishing_status'] ?? null;
        $data['featured'] = $data['is_featured'];

        // Convert amenities array to JSON string for storage
        if (!empty($data['amenities']) && is_array($data['amenities'])) {
            $data['amenities'] = json_encode($data['amenities']);
        }

        // Create property first to get ID
        $property = Property::create($data);

        // Handle all file uploads — stored inside dealer/{dealerId}/{propertyId}/
        $basePath = "dealer/{$dealerId}/{$property->id}";
        $needsSave = false;

        // Cover image
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $property->cover_image = $file->storeAs("{$basePath}/images", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
            $needsSave = true;
        }

        // Property images (property_images table)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $storedPath = $image->storeAs("{$basePath}/images", uniqid().'.'.$image->getClientOriginalExtension(), 'public');
                $property->images()->create(['image_path' => $storedPath]);
            }
        }

        // Floor plan images (JSON array)
        if ($request->hasFile('floor_plan_images')) {
            $paths = [];
            foreach ($request->file('floor_plan_images') as $file) {
                $paths[] = $file->storeAs("{$basePath}/floor_plans", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
            }
            $property->floor_plan_images = $paths;
            $needsSave = true;
        }

        // Video
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $property->video_url = $file->storeAs("{$basePath}/videos", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
            $needsSave = true;
        }

        // Brochure PDF
        if ($request->hasFile('brochure_pdf')) {
            $file = $request->file('brochure_pdf');
            $property->brochure_pdf = $file->storeAs("{$basePath}/brochure", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
            $needsSave = true;
        }

        if ($needsSave) {
            $property->save();
        }

        // Notify dealer + admins about new property
        try {
            $dealer = Auth::guard('dealer')->user();
            $adminRecipients = [
                'admin@indianesthub.com',
                'pcmishra22@gmail.com',
            ];

            if ($dealer && !empty($dealer->email)) {
                \Illuminate\Support\Facades\Mail::raw(
                    "New Property is added: {$property->title} (ID: {$property->id})",
                    function ($message) use ($dealer) {
                        $message->to($dealer->email)
                            ->subject('New Property is added');
                    }
                );
            }

            \Illuminate\Support\Facades\Mail::raw(
                "New Property is added: {$property->title} (ID: {$property->id})",
                function ($message) use ($adminRecipients) {
                    $message->to($adminRecipients)
                        ->subject('New Property is added');
                }
            );
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Dealer property notification failed', [
                'property_id' => $property->id,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('dealer.properties.index')->with('success', 'Property created successfully.');
    }

    public function edit(Property $property)
    {
        $this->authorizeDealer($property);
        return view('dealer.properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorizeDealer($property);
        $dealerId = Auth::guard('dealer')->id();
        $data = $request->validate([
            // Boolean flags
            'gated_society' => 'nullable|boolean',
            'corner_property' => 'nullable|boolean',
            'vastu_compliant' => 'nullable|boolean',
            'wheelchair_friendly' => 'nullable|boolean',
            'overlooking_park' => 'nullable|boolean',
            'overlooking_road' => 'nullable|boolean',
            'income_property' => 'nullable|boolean',
            'distress_sale' => 'nullable|boolean',
            'user_id' => 'nullable|integer|exists:users,id',
            // ...existing code...
            'contact_name' => 'nullable|string|max:100',
            'contact_phone' => 'nullable|string|max:30',
            'contact_email' => 'nullable|email|max:100',
            'company_name' => 'nullable|string|max:100',
            'license_number' => 'nullable|string|max:50',
            'verified_user' => 'nullable|boolean',
            'ownership_type' => 'nullable|string|in:Freehold,Leasehold',
            'property_approval' => 'nullable|string|max:100',
            'rera_id' => 'nullable|string|max:100',
            'rera_verified' => 'nullable|boolean',
            'occupancy_certificate' => 'nullable|string|max:100',
            'completion_certificate' => 'nullable|string|max:100',
            'legal_clearance_status' => 'nullable|string|max:100',
            'covered_parking' => 'nullable|integer',
            'open_parking' => 'nullable|integer',
            'water_supply' => 'nullable|string|in:Municipal,Borewell',
            'electricity_status' => 'nullable|string|max:50',
            'gas_pipeline' => 'nullable|boolean',
            'drainage' => 'nullable|boolean',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'nullable|string|max:255',
            'looking_for' => 'nullable|string|in:Rent,Sale,Renovate',
            'address' => 'nullable|string|max:255',
            'floor_plan_details' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'locality' => 'nullable|string|max:255',
            'sub_locality' => 'nullable|string|max:255',
            'society_name' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'map_url' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'total_floors' => 'nullable|integer',
            'floor_number' => 'nullable|integer',
            'facing' => 'nullable|string|in:East,West,North,South',
            'property_age' => 'nullable|string|max:50',
            'furnishing_status' => 'nullable|string|in:Furnished,Semi,Unfurnished',
            'area' => 'nullable|integer',
            'amenities' => 'nullable|array',
            'virtual_tour_url' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'security_deposit' => 'nullable|string|max:255',
            'possession_date' => 'nullable|string|max:255',
            'price_range' => 'nullable|string|max:255',
            'bhk_type' => 'nullable|string|max:255',
            'option_type' => 'nullable|string|max:255',
            'expected_price' => 'nullable|numeric',
            'price_per_sqft' => 'nullable|numeric',
            'negotiable' => 'nullable|boolean',
            'maintenance_charges' => 'nullable|numeric',
            'booking_amount' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'lease_duration' => 'nullable|string|max:255',
            'possession_status' => 'nullable|string|max:255',
            'super_builtup_area' => 'nullable|numeric',
            'builtup_area' => 'nullable|numeric',
            'carpet_area' => 'nullable|numeric',
            'area_unit' => 'nullable|string|max:10',
            'plot_area' => 'nullable|numeric',
            'plot_length' => 'nullable|numeric',
            'plot_breadth' => 'nullable|numeric',
            'share_with_agents' => 'nullable|boolean',
            // Nearby & Distance fields
            'nearby_schools' => 'nullable|string|max:255',
            'nearby_hospitals' => 'nullable|string|max:255',
            'nearby_malls' => 'nullable|string|max:255',
            'nearby_metro' => 'nullable|string|max:255',
            'nearby_bus_stand' => 'nullable|string|max:255',
            'distance_metrics' => 'nullable',
            // SEO & Featured fields
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'search_tags' => 'nullable|string|max:255',
            'priority_score' => 'nullable|integer',
            // File fields validation to prevent silent failures
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'floor_plan_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:30720',
            'brochure_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $data['share_with_agents'] = $request->has('share_with_agents');
        // Handle boolean checkboxes
        $data['pet_friendly'] = $request->has('pet_friendly');
        $data['isreal'] = $request->has('isreal');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_premium'] = $request->has('is_premium');

        // Compatibility sync: see the same note in store() above.
        $data['floor'] = isset($data['floor_number']) ? (string) $data['floor_number'] : null;
        $data['furnishing'] = $data['furnishing_status'] ?? null;
        $data['featured'] = $data['is_featured'];

        // Convert amenities array to JSON string for storage
        if (!empty($data['amenities']) && is_array($data['amenities'])) {
            $data['amenities'] = json_encode($data['amenities']);
        }

        // Handle distance_metrics JSON for update
        if ($request->filled('distance_metrics')) {
            $distanceMetrics = $request->input('distance_metrics');
            if (is_string($distanceMetrics)) {
                $decoded = json_decode($distanceMetrics, true);
                $data['distance_metrics'] = is_array($decoded) ? $decoded : $distanceMetrics;
            } else {
                $data['distance_metrics'] = $distanceMetrics;
            }
        }

        // Handle all file uploads — stored inside dealer/{dealerId}/{propertyId}/
        $basePath = "dealer/{$dealerId}/{$property->id}";

        // Cover image
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $data['cover_image'] = $file->storeAs("{$basePath}/images", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
        }

        // Floor plan images (JSON array)
        if ($request->hasFile('floor_plan_images')) {
            $paths = $property->floor_plan_images ?? [];
            foreach ($request->file('floor_plan_images') as $file) {
                $paths[] = $file->storeAs("{$basePath}/floor_plans", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
            }
            $data['floor_plan_images'] = $paths;
        }

        // Brochure PDF
        if ($request->hasFile('brochure_pdf')) {
            $file = $request->file('brochure_pdf');
            $data['brochure_pdf'] = $file->storeAs("{$basePath}/brochure", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
        }

        // Video
        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $data['video_url'] = $file->storeAs("{$basePath}/videos", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
        }

        $property->update($data);

        // Property images (property_images table)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $storedPath = $image->storeAs("{$basePath}/images", uniqid().'.'.$image->getClientOriginalExtension(), 'public');
                $property->images()->create(['image_path' => $storedPath]);
            }
        }

        return redirect()->route('dealer.properties.index')->with('success', 'Property updated successfully.');
    }

    public function destroy(Property $property)
    {
        $this->authorizeDealer($property);
        $property->delete();
        return redirect()->route('dealer.properties.index')->with('success', 'Property deleted successfully.');
    }

    private function authorizeDealer(Property $property)
    {
        if ($property->property_dealer_id !== Auth::guard('dealer')->id()) {
            abort(403);
        }
    }
public function show(Property $property)
    {
        $this->authorizeDealer($property);

        $dealerId = $property->property_dealer_id;
        $hasValidPayment = $property->payments()
            ->whereIn('status', ['completed', '1', 1])
            ->where('payment_type', 'property_listing')
            ->where('listing_end_date', '>=', now())
            ->where('dealer_id', $dealerId)
            ->exists();

        return view('dealer.properties.show', compact('property', 'hasValidPayment'));
    }

    /**
     * Start a UPI payment to activate/renew this property's listing.
     * Reuses the existing dealer.payment view + generic markPaid flow
     * that the Subscription checkout already uses.
     */
    public function payProperty(Property $property)
    {
        $this->authorizeDealer($property);

        $isRent = str_contains(strtolower((string) $property->listing_type), 'rent')
            || str_contains(strtolower((string) $property->listing_type), 'pg');
        $amount = $isRent ? 100 : 500;
        $durationDays = 30;

        $payment = Payment::create([
            'dealer_id'              => $property->property_dealer_id,
            'property_id'            => $property->id,
            'plan_type'               => 'property_listing',
            'plan_name'               => $property->title,
            'amount'                  => $amount,
            'status'                  => 'pending',
            'payment_method'          => 'upi',
            'payment_type'            => 'property_listing',
            'listing_duration_days'   => $durationDays,
        ]);

        $upi_id = env('UPI_ID', 'your-upi-id@bank');
        $upi_url = "upi://pay?pa={$upi_id}&pn=PropertyDealer&am={$amount}&tn=" . urlencode('Listing Payment - ' . $property->title);

        return view('dealer.payment', [
            'type'    => 'property_listing',
            'plan'    => $property->title,
            'amount'  => $amount,
            'upi_url' => $upi_url,
            'payment' => $payment,
        ]);
    }
}
