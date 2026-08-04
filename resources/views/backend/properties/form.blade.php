
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Basic Information</h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $property->title ?? '') }}" required>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label class="form-label mb-0">Description</label>
            </div>
            @include('partials.ai-description-button', [
                'url' => route('admin.ai.property-description'),
                'descriptionField' => 'description-editor',
                'usesCkeditor' => true,
                'metaField' => 'meta_description',
            ])
            <textarea name="description" id="description-editor" class="form-control">{{ old('description', $property->description ?? '') }}</textarea>
        </div>

        @push('scripts')
        <script>
            if (document.getElementById('description-editor')) {
                CKEDITOR.replace('description-editor');
            }
        </script>
        @endpush

        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="property_type" class="form-control" required>
                <option value="">Select Type</option>
                <option value="Residential" {{ old('property_type', $property->property_type ?? '') == 'Residential' ? 'selected' : '' }}>Residential</option>
                <option value="Commercial" {{ old('property_type', $property->property_type ?? '') == 'Commercial' ? 'selected' : '' }}>Commercial</option>
                <option value="Office" {{ old('property_type', $property->property_type ?? '') == 'Office' ? 'selected' : '' }}>Office</option>
                <option value="Retail Shop" {{ old('property_type', $property->property_type ?? '') == 'Retail Shop' ? 'selected' : '' }}>Retail Shop</option>
                <option value="Showroom" {{ old('property_type', $property->property_type ?? '') == 'Showroom' ? 'selected' : '' }}>Showroom</option>
                <option value="Warehouse" {{ old('property_type', $property->property_type ?? '') == 'Warehouse' ? 'selected' : '' }}>Warehouse</option>
                <option value="Plot" {{ old('property_type', $property->property_type ?? '') == 'Plot' ? 'selected' : '' }}>Plot</option>
                <option value="Farm House" {{ old('property_type', $property->property_type ?? '') == 'Farm House' ? 'selected' : '' }}>Farm House</option>
                <option value="Pentahouse" {{ old('property_type', $property->property_type ?? '') == 'Pentahouse' ? 'selected' : '' }}>Pentahouse</option>
                <option value="Studio" {{ old('property_type', $property->property_type ?? '') == 'Studio' ? 'selected' : '' }}>Studio</option>
                <option value="Villa" {{ old('property_type', $property->property_type ?? '') == 'Villa' ? 'selected' : '' }}>Villa</option>
                <option value="Independent Floor" {{ old('property_type', $property->property_type ?? '') == 'Independent Floor' ? 'selected' : '' }}>Independent Floor</option>
                <option value="Duplex" {{ old('property_type', $property->property_type ?? '') == 'Duplex' ? 'selected' : '' }}>Duplex</option>
                <option value="Apartment" {{ old('property_type', $property->property_type ?? '') == 'Apartment' ? 'selected' : '' }}>Apartment</option>
                <option value="Independent House" {{ old('property_type', $property->property_type ?? '') == 'Independent House' ? 'selected' : '' }}>Independent House</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Looking For</label>
            <select name="looking_for" class="form-control" required>
                <option value="">Select Option</option>
                <option value="Rent" {{ old('looking_for', $property->looking_for ?? '') == 'Rent' ? 'selected' : '' }}>Rent</option>
                <option value="Sale" {{ old('looking_for', $property->looking_for ?? '') == 'Sale' ? 'selected' : '' }}>Sale</option>
                <option value="Renovate" {{ old('looking_for', $property->looking_for ?? '') == 'Renovate' ? 'selected' : '' }}>Renovate</option>
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">BHK Type</label>
                <select name="bhk_type" class="form-control">
                    <option value="">Select BHK</option>
                    <option value="1 RK" {{ old('bhk_type', $property->bhk_type ?? '') == '1 RK' ? 'selected' : '' }}>1 RK</option>
                    <option value="1 BHK" {{ old('bhk_type', $property->bhk_type ?? '') == '1 BHK' ? 'selected' : '' }}>1 BHK</option>
                    <option value="2 BHK" {{ old('bhk_type', $property->bhk_type ?? '') == '2 BHK' ? 'selected' : '' }}>2 BHK</option>
                    <option value="3 BHK" {{ old('bhk_type', $property->bhk_type ?? '') == '3 BHK' ? 'selected' : '' }}>3 BHK</option>
                    <option value="4 BHK" {{ old('bhk_type', $property->bhk_type ?? '') == '4 BHK' ? 'selected' : '' }}>4 BHK</option>
                    <option value="5 BHK" {{ old('bhk_type', $property->bhk_type ?? '') == '5 BHK' ? 'selected' : '' }}>5 BHK</option>
                    <option value="5+ BHK" {{ old('bhk_type', $property->bhk_type ?? '') == '5+ BHK' ? 'selected' : '' }}>5+ BHK</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Option Type</label>
                <select name="option_type" class="form-control">
                    <option value="">Select Option</option>
                    <option value="New Booking" {{ old('option_type', $property->option_type ?? '') == 'New Booking' ? 'selected' : '' }}>New Booking</option>
                    <option value="Resale" {{ old('option_type', $property->option_type ?? '') == 'Resale' ? 'selected' : '' }}>Resale</option>
                    <option value="Ready to Move" {{ old('option_type', $property->option_type ?? '') == 'Ready to Move' ? 'selected' : '' }}>Ready to Move</option>
                    <option value="Under Construction" {{ old('option_type', $property->option_type ?? '') == 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                </select>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Floor Plan Details</label>
            <textarea name="floor_plan_details" class="form-control" rows="3" placeholder="Describe the floor plan here...">{{ old('floor_plan_details', $property->floor_plan_details ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Amenities</label>
            <select name="amenities[]" class="form-control" multiple>
                @php
                    $amenitiesList = [
                        'Lift',
                        'Power Backup',
                        'Park',
                        'Gym',
                        'Swimming Pool',
                        'Club House',
                        'Kids Play Area',
                        'Jogging Track',
                        'Garden',
                        'Community Hall',
                    ];
                    $selectedAmenities = old('amenities', isset($property->amenities) ? (is_array($property->amenities) ? $property->amenities : json_decode($property->amenities, true)) : []);
                @endphp
                @foreach($amenitiesList as $amenity)
                    <option value="{{ $amenity }}" {{ in_array($amenity, $selectedAmenities ?? []) ? 'selected' : '' }}>{{ $amenity }}</option>
                @endforeach
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple amenities.</small>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Property Media</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Cover Image</label>
                <input type="file" name="cover_image" class="form-control" accept="image/*">
                @if(!empty($property->cover_image))
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $property->cover_image) }}" alt="Cover Image" style="max-width:200px;max-height:200px;">
                    </div>
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Property Images</label>
                <input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                @if(isset($property) && $property->images && $property->images->count())
                    <div class="mt-2">
                        @foreach($property->images as $img)
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="Image" style="max-width:80px;max-height:80px;margin:2px;">
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Floor Plan Images</label>
                <input type="file" name="floor_plan_images[]" class="form-control" accept="image/*" multiple>
                @if(!empty($property->floor_plan_images) && is_array($property->floor_plan_images))
                    <div class="mt-2">
                        @foreach($property->floor_plan_images as $img)
                            <img src="{{ asset('storage/' . $img) }}" alt="Floor Plan Image" style="max-width:80px;max-height:80px;margin:2px;">
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Property Video</label>
                <input type="file" name="video" class="form-control" accept="video/*">
                @if(isset($property) && $property->video_url)
                    <div class="mt-2">
                        <video width="200" controls>
                            <source src="{{ asset('storage/' . $property->video_url) }}" type="video/mp4">
                        </video>
                    </div>
                @endif
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Virtual Tour URL</label>
                <input type="text" name="virtual_tour_url" class="form-control" value="{{ old('virtual_tour_url', $property->virtual_tour_url ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Brochure PDF</label>
                <input type="file" name="brochure_pdf" class="form-control" accept="application/pdf">
                @if(!empty($property->brochure_pdf))
                    <div class="mt-2">
                        <a href="{{ asset('storage/' . $property->brochure_pdf) }}" target="_blank">View Brochure</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Property Attributes</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Bedrooms</label>
                <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', $property->bedrooms ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Bathrooms</label>
                <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', $property->bathrooms ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Balconies</label>
                <input type="number" name="balconies" class="form-control" value="{{ old('balconies', $property->balconies ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Total Floors</label>
                <input type="number" name="total_floors" class="form-control" value="{{ old('total_floors', $property->total_floors ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Floor Number</label>
                <input type="number" name="floor_number" class="form-control" value="{{ old('floor_number', $property->floor_number ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Facing</label>
                <select name="facing" class="form-control">
                    <option value="">Select Facing</option>
                    <option value="East" {{ old('facing', $property->facing ?? '') == 'East' ? 'selected' : '' }}>East</option>
                    <option value="West" {{ old('facing', $property->facing ?? '') == 'West' ? 'selected' : '' }}>West</option>
                    <option value="North" {{ old('facing', $property->facing ?? '') == 'North' ? 'selected' : '' }}>North</option>
                    <option value="South" {{ old('facing', $property->facing ?? '') == 'South' ? 'selected' : '' }}>South</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Property Age</label>
                <input type="text" name="property_age" class="form-control" value="{{ old('property_age', $property->property_age ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Furnishing Status</label>
                <select name="furnishing_status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="Furnished" {{ old('furnishing_status', $property->furnishing_status ?? '') == 'Furnished' ? 'selected' : '' }}>Furnished</option>
                    <option value="Semi" {{ old('furnishing_status', $property->furnishing_status ?? '') == 'Semi' ? 'selected' : '' }}>Semi</option>
                    <option value="Unfurnished" {{ old('furnishing_status', $property->furnishing_status ?? '') == 'Unfurnished' ? 'selected' : '' }}>Unfurnished</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Area & Plot Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Super Built-up Area</label>
                <input type="number" step="0.01" name="super_builtup_area" class="form-control" value="{{ old('super_builtup_area', $property->super_builtup_area ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Built-up Area</label>
                <input type="number" step="0.01" name="builtup_area" class="form-control" value="{{ old('builtup_area', $property->builtup_area ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Carpet Area</label>
                <input type="number" step="0.01" name="carpet_area" class="form-control" value="{{ old('carpet_area', $property->carpet_area ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Area Unit</label>
                <select name="area_unit" class="form-control">
                    <option value="">Select Unit</option>
                    <option value="sqft" {{ old('area_unit', $property->area_unit ?? '') == 'sqft' ? 'selected' : '' }}>Sqft</option>
                    <option value="sqmt" {{ old('area_unit', $property->area_unit ?? '') == 'sqmt' ? 'selected' : '' }}>Sqmt</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Plot Area</label>
                <input type="number" step="0.01" name="plot_area" class="form-control" value="{{ old('plot_area', $property->plot_area ?? '') }}">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Plot Length</label>
                <input type="number" step="0.01" name="plot_length" class="form-control" value="{{ old('plot_length', $property->plot_length ?? '') }}">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Plot Breadth</label>
                <input type="number" step="0.01" name="plot_breadth" class="form-control" value="{{ old('plot_breadth', $property->plot_breadth ?? '') }}">
            </div>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Location & Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Address</label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $property->address ?? '') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">City</label>
                <input type="text" name="city" class="form-control" value="{{ old('city', $property->city ?? '') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">State</label>
                <input type="text" name="state" class="form-control" value="{{ old('state', $property->state ?? '') }}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Country</label>
                <input type="text" name="country" class="form-control" value="{{ old('country', $property->country ?? 'India') }}" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Locality</label>
                <input type="text" name="locality" class="form-control" value="{{ old('locality', $property->locality ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Sub Locality</label>
                <input type="text" name="sub_locality" class="form-control" value="{{ old('sub_locality', $property->sub_locality ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Society Name</label>
                <input type="text" name="society_name" class="form-control" value="{{ old('society_name', $property->society_name ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Landmark</label>
                <input type="text" name="landmark" class="form-control" value="{{ old('landmark', $property->landmark ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Pincode</label>
                <input type="text" name="pincode" class="form-control" value="{{ old('pincode', $property->pincode ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Latitude</label>
                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $property->latitude ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Longitude</label>
                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $property->longitude ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Price</label>
                <input type="number" name="price" class="form-control" value="{{ old('price', $property->price ?? '') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Map URL</label>
                <input type="text" name="map_url" class="form-control" value="{{ old('map_url', $property->map_url ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Area (sq ft)</label>
                <input type="number" name="area" class="form-control" value="{{ old('area', $property->area ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Status</label>
                <input type="text" name="status" class="form-control" value="{{ old('status', $property->status ?? 'Available') }}">
            </div>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Nearby & Distance Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Nearby Schools</label>
                <input type="text" name="nearby_schools" class="form-control" value="{{ old('nearby_schools', $property->nearby_schools ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nearby Hospitals</label>
                <input type="text" name="nearby_hospitals" class="form-control" value="{{ old('nearby_hospitals', $property->nearby_hospitals ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nearby Malls</label>
                <input type="text" name="nearby_malls" class="form-control" value="{{ old('nearby_malls', $property->nearby_malls ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Nearby Metro</label>
                <input type="text" name="nearby_metro" class="form-control" value="{{ old('nearby_metro', $property->nearby_metro ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Nearby Bus Stand</label>
                <input type="text" name="nearby_bus_stand" class="form-control" value="{{ old('nearby_bus_stand', $property->nearby_bus_stand ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Distance Metrics (JSON)</label>
                <textarea name="distance_metrics" class="form-control" rows="2">{{ old('distance_metrics', isset($property->distance_metrics) ? (is_array($property->distance_metrics) ? json_encode($property->distance_metrics) : $property->distance_metrics) : '') }}</textarea>
                <small class="text-muted">Enter as JSON, e.g. {"school": "1km", "hospital": "2km"}</small>
            </div>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Pricing & Transaction Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Expected Price</label>
                <input type="number" step="0.01" name="expected_price" class="form-control" value="{{ old('expected_price', $property->expected_price ?? '') }}" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Price per Sqft</label>
                <input type="number" step="0.01" name="price_per_sqft" class="form-control" value="{{ old('price_per_sqft', $property->price_per_sqft ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Negotiable</label>
                <select name="negotiable" class="form-control" required>
                    <option value="0" {{ old('negotiable', $property->negotiable ?? 0) == 0 ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('negotiable', $property->negotiable ?? 0) == 1 ? 'selected' : '' }}>Yes</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Maintenance Charges</label>
                <input type="number" step="0.01" name="maintenance_charges" class="form-control" value="{{ old('maintenance_charges', $property->maintenance_charges ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Booking Amount</label>
                <input type="number" step="0.01" name="booking_amount" class="form-control" value="{{ old('booking_amount', $property->booking_amount ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Monthly Rent</label>
                <input type="number" step="0.01" name="monthly_rent" class="form-control" value="{{ old('monthly_rent', $property->monthly_rent ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Lease Duration</label>
                <input type="text" name="lease_duration" class="form-control" value="{{ old('lease_duration', $property->lease_duration ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Possession Status</label>
                <select name="possession_status" class="form-control" required>
                    <option value="">Select Status</option>
                    <option value="Ready" {{ old('possession_status', $property->possession_status ?? '') == 'Ready' ? 'selected' : '' }}>Ready</option>
                    <option value="Under Construction" {{ old('possession_status', $property->possession_status ?? '') == 'Under Construction' ? 'selected' : '' }}>Under Construction</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Possession Date</label>
                <input type="text" name="possession_date" class="form-control" value="{{ old('possession_date', $property->possession_date ?? '') }}">
            </div>
        </div>
    </div>
</div>


<div class="card mb-3">
    <div class="card-body">
        <div class="mb-3 form-check">
            <input type="checkbox" name="share_with_agents" class="form-check-input" id="shareWithAgents" value="1" {{ old('share_with_agents', $property->share_with_agents ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="shareWithAgents">Share listing information with agents</label>
        </div>
        <div class="row">
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="gated_society" class="form-check-input" id="gatedSociety" value="1" {{ old('gated_society', $property->gated_society ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="gatedSociety">Gated Society</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="corner_property" class="form-check-input" id="cornerProperty" value="1" {{ old('corner_property', $property->corner_property ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="cornerProperty">Corner Property</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="vastu_compliant" class="form-check-input" id="vastuCompliant" value="1" {{ old('vastu_compliant', $property->vastu_compliant ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="vastuCompliant">Vastu Compliant</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="wheelchair_friendly" class="form-check-input" id="wheelchairFriendly" value="1" {{ old('wheelchair_friendly', $property->wheelchair_friendly ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="wheelchairFriendly">Wheelchair Friendly</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="overlooking_park" class="form-check-input" id="overlookingPark" value="1" {{ old('overlooking_park', $property->overlooking_park ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="overlookingPark">Overlooking Park</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="overlooking_road" class="form-check-input" id="overlookingRoad" value="1" {{ old('overlooking_road', $property->overlooking_road ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="overlookingRoad">Overlooking Road</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="income_property" class="form-check-input" id="incomeProperty" value="1" {{ old('income_property', $property->income_property ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="incomeProperty">Income Property</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="distress_sale" class="form-check-input" id="distressSale" value="1" {{ old('distress_sale', $property->distress_sale ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="distressSale">Distress Sale</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="pet_friendly" class="form-check-input" id="petFriendly" value="1" {{ old('pet_friendly', $property->pet_friendly ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="petFriendly">Pet Friendly</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="is_featured" class="form-check-input" id="isFeatured" value="1" {{ old('is_featured', $property->is_featured ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isFeatured">Featured</label>
                </div>
            </div>
            <div class="col-md-3 mb-2">
                <div class="form-check">
                    <input type="checkbox" name="is_premium" class="form-check-input" id="isPremium" value="1" {{ old('is_premium', $property->is_premium ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label" for="isPremium">Premium</label>
                </div>
            </div>
        </div>
        <div class="mb-3 mt-3">
            <label class="form-label">Security Deposit</label>
            <select name="security_deposit" class="form-control">
                <option value="">Select Security Deposit</option>
                <option value="None" {{ old('security_deposit', $property->security_deposit ?? '') == 'None' ? 'selected' : '' }}>None</option>
                <option value="1 Month" {{ old('security_deposit', $property->security_deposit ?? '') == '1 Month' ? 'selected' : '' }}>1 Month</option>
                <option value="2 Months" {{ old('security_deposit', $property->security_deposit ?? '') == '2 Months' ? 'selected' : '' }}>2 Months</option>
                <option value="3 Months" {{ old('security_deposit', $property->security_deposit ?? '') == '3 Months' ? 'selected' : '' }}>3 Months</option>
            </select>
        </div>
    </div>
</div>



<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Parking, Utilities & Supply</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 mb-3">
                <label class="form-label">Covered Parking</label>
                <input type="number" name="covered_parking" class="form-control" value="{{ old('covered_parking', $property->covered_parking ?? '') }}">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Open Parking</label>
                <input type="number" name="open_parking" class="form-control" value="{{ old('open_parking', $property->open_parking ?? '') }}">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Water Supply</label>
                <select name="water_supply" class="form-control">
                    <option value="">Select</option>
                    <option value="Municipal" {{ old('water_supply', $property->water_supply ?? '') == 'Municipal' ? 'selected' : '' }}>Municipal</option>
                    <option value="Borewell" {{ old('water_supply', $property->water_supply ?? '') == 'Borewell' ? 'selected' : '' }}>Borewell</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Electricity Status</label>
                <input type="text" name="electricity_status" class="form-control" value="{{ old('electricity_status', $property->electricity_status ?? '') }}">
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Gas Pipeline</label>
                <select name="gas_pipeline" class="form-control">
                    <option value="">Select</option>
                    <option value="1" {{ old('gas_pipeline', $property->gas_pipeline ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('gas_pipeline', $property->gas_pipeline ?? '') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
            <div class="col-md-2 mb-3">
                <label class="form-label">Drainage</label>
                <select name="drainage" class="form-control">
                    <option value="">Select</option>
                    <option value="1" {{ old('drainage', $property->drainage ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('drainage', $property->drainage ?? '') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Contact & User Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">User ID</label>
                <input type="number" name="user_id" class="form-control" value="{{ old('user_id', $property->user_id ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Contact Name</label>
                <input type="text" name="contact_name" class="form-control" value="{{ old('contact_name', $property->contact_name ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Contact Phone</label>
                <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $property->contact_phone ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Contact Email</label>
                <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $property->contact_email ?? '') }}">
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $property->company_name ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">License Number</label>
                <input type="text" name="license_number" class="form-control" value="{{ old('license_number', $property->license_number ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Verified User</label>
                <select name="verified_user" class="form-control">
                    <option value="">Select</option>
                    <option value="1" {{ old('verified_user', $property->verified_user ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('verified_user', $property->verified_user ?? '') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">SEO & Featured Details</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $property->slug ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $property->meta_title ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $property->meta_description ?? '') }}</textarea>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Search Tags (comma separated)</label>
                <input type="text" name="search_tags" class="form-control" value="{{ old('search_tags', $property->search_tags ?? '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Priority Score</label>
                <input type="number" name="priority_score" class="form-control" value="{{ old('priority_score', $property->priority_score ?? '') }}">
            </div>
        </div>
    </div>
</div>
<div class="card mb-3">
    <div class="card-header">
        <h5 class="card-title mb-0">Legal, Ownership & Approval</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 mb-3">
                <label class="form-label">Ownership Type</label>
                <select name="ownership_type" class="form-control">
                    <option value="">Select</option>
                    <option value="Freehold" {{ old('ownership_type', $property->ownership_type ?? '') == 'Freehold' ? 'selected' : '' }}>Freehold</option>
                    <option value="Leasehold" {{ old('ownership_type', $property->ownership_type ?? '') == 'Leasehold' ? 'selected' : '' }}>Leasehold</option>
                </select>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">Property Approval</label>
                <input type="text" name="property_approval" class="form-control" value="{{ old('property_approval', $property->property_approval ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">RERA ID</label>
                <input type="text" name="rera_id" class="form-control" value="{{ old('rera_id', $property->rera_id ?? '') }}">
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label">RERA Verified</label>
                <select name="rera_verified" class="form-control">
                    <option value="">Select</option>
                    <option value="1" {{ old('rera_verified', $property->rera_verified ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ old('rera_verified', $property->rera_verified ?? '') == '0' ? 'selected' : '' }}>No</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Occupancy Certificate</label>
                <input type="text" name="occupancy_certificate" class="form-control" value="{{ old('occupancy_certificate', $property->occupancy_certificate ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Completion Certificate</label>
                <input type="text" name="completion_certificate" class="form-control" value="{{ old('completion_certificate', $property->completion_certificate ?? '') }}">
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Legal Clearance Status</label>
                <input type="text" name="legal_clearance_status" class="form-control" value="{{ old('legal_clearance_status', $property->legal_clearance_status ?? '') }}">
            </div>
        </div>
    </div>
</div>
