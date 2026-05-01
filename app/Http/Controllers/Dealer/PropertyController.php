<?php

namespace App\Http\Controllers\Dealer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;
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
                             ->paidAndValid($dealerId)
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|string|max:255',
            'looking_for' => 'required|string|in:Rent,Sale',
            'address' => 'required|string|max:255',
            'floor' => 'nullable|string|max:255',
            'floor_plan_details' => 'nullable|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'sub_locality' => 'nullable|string|max:255',
            'society_name' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'map_url' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'total_floors' => 'nullable|integer',
            'floor_number' => 'nullable|integer',
            'facing' => 'nullable|string|in:East,West,North,South',
            'property_age' => 'nullable|string|max:50',
            'furnishing_status' => 'nullable|string|in:Furnished,Semi,Unfurnished',
            'area' => 'nullable|integer',
            'furnishing' => 'nullable|string|max:255',
            'amenities' => 'nullable|array',
            'virtual_tour_url' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'security_deposit' => 'nullable|string|max:255',
            'possession_date' => 'nullable|string|max:255',
            'price_range' => 'nullable|string|max:255',
            'bhk_type' => 'nullable|string|max:255',
            'option_type' => 'nullable|string|max:255',
            'expected_price' => 'required|numeric',
            'price_per_sqft' => 'nullable|numeric',
            'negotiable' => 'required|boolean',
            'maintenance_charges' => 'nullable|numeric',
            'booking_amount' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'lease_duration' => 'nullable|string|max:255',
            'possession_status' => 'required|string|max:255',
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
            'featured' => 'nullable|boolean',
            'priority_score' => 'nullable|integer',
        ]);

        $data['share_with_agents'] = $request->has('share_with_agents');
        $data['pet_friendly'] = $request->has('pet_friendly');
        $data['isreal'] = $request->has('isreal');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_premium'] = $request->has('is_premium');
        $data['property_dealer_id'] = $dealerId;

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
            $property->cover_image = $file->storeAs("{$basePath}/cover", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
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
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'property_type' => 'required|string|max:255',
            'looking_for' => 'required|string|in:Rent,Sale',
            'address' => 'required|string|max:255',
            'floor' => 'nullable|string|max:255',
            'floor_plan_details' => 'nullable|string',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'locality' => 'nullable|string|max:255',
            'sub_locality' => 'nullable|string|max:255',
            'society_name' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'map_url' => 'nullable|string|max:255',
            'price' => 'required|numeric',
            'bedrooms' => 'nullable|integer',
            'bathrooms' => 'nullable|integer',
            'balconies' => 'nullable|integer',
            'total_floors' => 'nullable|integer',
            'floor_number' => 'nullable|integer',
            'facing' => 'nullable|string|in:East,West,North,South',
            'property_age' => 'nullable|string|max:50',
            'furnishing_status' => 'nullable|string|in:Furnished,Semi,Unfurnished',
            'area' => 'nullable|integer',
            'furnishing' => 'nullable|string|max:255',
            'amenities' => 'nullable|array',
            'virtual_tour_url' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'security_deposit' => 'nullable|string|max:255',
            'possession_date' => 'nullable|string|max:255',
            'price_range' => 'nullable|string|max:255',
            'bhk_type' => 'nullable|string|max:255',
            'option_type' => 'nullable|string|max:255',
            'expected_price' => 'required|numeric',
            'price_per_sqft' => 'nullable|numeric',
            'negotiable' => 'required|boolean',
            'maintenance_charges' => 'nullable|numeric',
            'booking_amount' => 'nullable|numeric',
            'monthly_rent' => 'nullable|numeric',
            'lease_duration' => 'nullable|string|max:255',
            'possession_status' => 'required|string|max:255',
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
            'featured' => 'nullable|boolean',
            'priority_score' => 'nullable|integer',
        ]);

        $data['share_with_agents'] = $request->has('share_with_agents');
        // Handle boolean checkboxes
        $data['pet_friendly'] = $request->has('pet_friendly');
        $data['isreal'] = $request->has('isreal');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_premium'] = $request->has('is_premium');

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
            $data['cover_image'] = $file->storeAs("{$basePath}/cover", uniqid().'.'.$file->getClientOriginalExtension(), 'public');
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
        return view('dealer.properties.show', compact('property'));
    }
}
