<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        $query = Property::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('property_type', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('looking_for', 'like', "%{$search}%");
            });
        }

        if ($lookingFor = $request->input('looking_for')) {
            $query->where('looking_for', $lookingFor);
        }

        $properties = $query->latest()->paginate(20)->withQueryString();

        return view('backend.properties.index', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('backend.properties.show', compact('property'));
    }

    public function create()
    {
        $property = new Property();
        return view('backend.properties.create', compact('property'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $data['share_with_agents'] = $request->has('share_with_agents');
        $data['pet_friendly'] = $request->has('pet_friendly');
        $data['isreal'] = $request->has('isreal');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_premium'] = $request->has('is_premium');

        // Compatibility sync: 'floor', 'furnishing' and 'featured' are older
        // columns some display views still read (see Dealer\PropertyController
        // for the same note) — the form only collects their newer
        // replacements, so keep the legacy columns populated too.
        $data['floor'] = isset($data['floor_number']) ? (string) $data['floor_number'] : null;
        $data['furnishing'] = $data['furnishing_status'] ?? null;
        $data['featured'] = $data['is_featured'];

        if (!empty($data['amenities']) && is_array($data['amenities'])) {
            $data['amenities'] = json_encode($data['amenities']);
        }

        $property = Property::create($data);

        $basePath = "admin/{$property->id}";
        $needsSave = false;

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $property->cover_image = $file->storeAs("{$basePath}/images", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
            $needsSave = true;
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $storedPath = $image->storeAs("{$basePath}/images", uniqid() . '.' . $image->getClientOriginalExtension(), 'public');
                $property->images()->create(['image_path' => $storedPath]);
            }
        }

        if ($request->hasFile('floor_plan_images')) {
            $paths = [];
            foreach ($request->file('floor_plan_images') as $file) {
                $paths[] = $file->storeAs("{$basePath}/floor_plans", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
            }
            $property->floor_plan_images = $paths;
            $needsSave = true;
        }

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $property->video_url = $file->storeAs("{$basePath}/videos", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
            $needsSave = true;
        }

        if ($request->hasFile('brochure_pdf')) {
            $file = $request->file('brochure_pdf');
            $property->brochure_pdf = $file->storeAs("{$basePath}/brochure", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
            $needsSave = true;
        }

        if ($needsSave) {
            $property->save();
        }

        return redirect()->route('admin.properties.show', $property->id)->with('success', 'Property created successfully.');
    }

    public function edit($id)
    {
        $property = Property::findOrFail($id);
        return view('backend.properties.edit', compact('property'));
    }

    public function update(Request $request, $id)
    {
        $property = Property::findOrFail($id);
        $data = $this->validated($request, forUpdate: true);

        $data['share_with_agents'] = $request->has('share_with_agents');
        $data['pet_friendly'] = $request->has('pet_friendly');
        $data['isreal'] = $request->has('isreal');
        $data['is_featured'] = $request->has('is_featured');
        $data['is_premium'] = $request->has('is_premium');

        $data['floor'] = isset($data['floor_number']) ? (string) $data['floor_number'] : null;
        $data['furnishing'] = $data['furnishing_status'] ?? null;
        $data['featured'] = $data['is_featured'];

        if (!empty($data['amenities']) && is_array($data['amenities'])) {
            $data['amenities'] = json_encode($data['amenities']);
        }

        if ($request->filled('distance_metrics')) {
            $distanceMetrics = $request->input('distance_metrics');
            if (is_string($distanceMetrics)) {
                $decoded = json_decode($distanceMetrics, true);
                $data['distance_metrics'] = is_array($decoded) ? $decoded : $distanceMetrics;
            } else {
                $data['distance_metrics'] = $distanceMetrics;
            }
        }

        $basePath = "admin/{$property->id}";

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $data['cover_image'] = $file->storeAs("{$basePath}/images", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('floor_plan_images')) {
            $paths = $property->floor_plan_images ?? [];
            foreach ($request->file('floor_plan_images') as $file) {
                $paths[] = $file->storeAs("{$basePath}/floor_plans", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
            }
            $data['floor_plan_images'] = $paths;
        }

        if ($request->hasFile('brochure_pdf')) {
            $file = $request->file('brochure_pdf');
            $data['brochure_pdf'] = $file->storeAs("{$basePath}/brochure", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
        }

        if ($request->hasFile('video')) {
            $file = $request->file('video');
            $data['video_url'] = $file->storeAs("{$basePath}/videos", uniqid() . '.' . $file->getClientOriginalExtension(), 'public');
        }

        $property->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $storedPath = $image->storeAs("{$basePath}/images", uniqid() . '.' . $image->getClientOriginalExtension(), 'public');
                $property->images()->create(['image_path' => $storedPath]);
            }
        }

        return redirect()->route('admin.properties.show', $property->id)->with('success', 'Property updated successfully.');
    }

    /**
     * Shared validation rules for store() and update() (mirrors
     * Dealer\PropertyController's rule set so both forms stay compatible).
     */
    protected function validated(Request $request, bool $forUpdate = false): array
    {
        return $request->validate([
            'gated_society' => 'nullable|boolean',
            'corner_property' => 'nullable|boolean',
            'vastu_compliant' => 'nullable|boolean',
            'wheelchair_friendly' => 'nullable|boolean',
            'overlooking_park' => 'nullable|boolean',
            'overlooking_road' => 'nullable|boolean',
            'income_property' => 'nullable|boolean',
            'distress_sale' => 'nullable|boolean',
            'user_id' => 'nullable|integer|exists:users,id',
            'property_dealer_id' => 'nullable|integer|exists:property_dealers,id',
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
            'looking_for' => 'required|string|in:Rent,Sale,Renovate',
            'address' => 'required|string|max:255',
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
            'nearby_schools' => 'nullable|string|max:255',
            'nearby_hospitals' => 'nullable|string|max:255',
            'nearby_malls' => 'nullable|string|max:255',
            'nearby_metro' => 'nullable|string|max:255',
            'nearby_bus_stand' => 'nullable|string|max:255',
            'distance_metrics' => 'nullable',
            'slug' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'search_tags' => 'nullable|string|max:255',
            'priority_score' => 'nullable|integer',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'floor_plan_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:30720',
            'brochure_pdf' => 'nullable|file|mimes:pdf|max:10240',
        ]);
    }

    public function destroy($id)
    {
        $property = Property::findOrFail($id);
        $property->delete(); // soft delete — row is retained so the public listing URL can show a graceful "no longer available" page instead of a hard 404
        return back()->with('success', 'Property deleted successfully.');
    }

    public function toggleFeatured(Property $property)
    {
        $property->is_featured = !$property->is_featured;
        $property->save();

        return back()->with('success', 'Property featured status updated.');
    }

    public function togglePublicContact(Request $request, Property $property)
    {
        $enabled = (bool) $request->input('enabled', false);
        $property->public_contact_enabled = $enabled;
        $property->save();

        return back()->with('success', 'Public contact setting updated successfully.');
    }

    /**
     * Enable / disable a property's public visibility.
     * Disabling sets status = inactive (already excluded everywhere on the
     * public site) while remembering the previous status so re-enabling
     * restores it instead of always going back to "active".
     */
    public function toggleStatus(Property $property)
    {
        if ($property->status === 'inactive') {
            $property->status = $property->previous_status ?: 'active';
            $property->previous_status = null;
            $label = 'Enabled';
        } else {
            $property->previous_status = $property->status;
            $property->status = 'inactive';
            $label = 'Disabled';
        }
        $property->save();

        return back()->with('success', "Property {$label} successfully.");
    }
}
