@extends('dealer.layout')
@section('title', $property->title)
@section('content')
<div class="container-fluid p-0">

    {{-- Page header with actions --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <a href="{{ route('dealer.properties.index') }}" class="btn btn-sm btn-light me-2">
                <i class="align-middle" data-feather="arrow-left"></i> Back
            </a>
            <span class="fw-bold fs-6">{{ $property->title }}</span>
        </div>
        <a href="{{ route('dealer.properties.edit', $property->slug) }}" class="btn btn-warning btn-sm">
            <i class="align-middle" data-feather="edit-2"></i> Edit Property
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card mb-4">
                @php
                    $dealerId = $property->dealer_id ?? ($property->dealer->id ?? '');
                    if (!empty($property->image_url)) {
                        $imgPath = 'dealer/' . $dealerId . '/' . $property->id . '/images/' . $property->image_url;
                    } else {
                        $img = $property->images->where('is_video', false)->first();
                        $imgPath = ($img && $img->image_path) ? $img->image_path : null;

                    }
                    if (!empty($property->video_url)) {
                        $vidPath = $property->video_url;
                    } else {
                        $vid = $property->images->where('is_video', true)->first();
                        $vidPath = ($vid && $vid->image_url) ? 'dealer/' . $dealerId . '/' . $property->id . '/video/' . $vid->image_url : null;
                    }
                @endphp
                
                @if($imgPath)
                    <img class="card-img-top" src="{{ asset('storage/' . $imgPath) }}" alt="Property Image" style="max-height:350px;object-fit:cover;">
                @else
                    <img class="card-img-top" src="{{ asset('backend/img/no-image.png') }}" alt="No Image" style="max-height:350px;object-fit:cover;">
                @endif
                <div class="card-header">
                    <h3 class="card-title mb-0">{{ $property->title }}</h3>
                    <a href="{{ route('dealer.schedule-viewings.index') }}?property_id={{ $property->id }}" class="btn btn-outline-primary float-end">View Scheduled Viewings</a>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-8">
                                                                                                                <p class="mb-1"><strong>Cover Image:</strong><br>
                                                                                                                    @if(!empty($property->cover_image))
                                                                                                                        <img src="{{ asset('storage/' . $property->cover_image) }}" alt="Cover" style="max-width:200px;max-height:200px;">
                                                                                                                    @else
                                                                                                                        <span class="text-muted">No Image</span>
                                                                                                                    @endif
                                                                                                                </p>
                                                                                                                <p class="mb-1"><strong>Gallery Images:</strong><br>
                                                                                                                    @if(!empty($property->gallery_images) && is_array($property->gallery_images))
                                                                                                                        @foreach($property->gallery_images as $img)
                                                                                                                            <img src="{{ asset('storage/' . $img) }}" alt="Gallery" style="max-width:80px;max-height:80px;margin:2px;">
                                                                                                                        @endforeach
                                                                                                                    @else
                                                                                                                        <span class="text-muted">None</span>
                                                                                                                    @endif
                                                                                                                </p>
                                                                                                                <p class="mb-1"><strong>Floor Plan Images:</strong><br>
                                                                                                                    @if(!empty($property->floor_plan_images) && is_array($property->floor_plan_images))
                                                                                                                        @foreach($property->floor_plan_images as $img)
                                                                                                                            <img src="{{ asset('storage/' . $img) }}" alt="Floor Plan" style="max-width:80px;max-height:80px;margin:2px;">
                                                                                                                        @endforeach
                                                                                                                    @else
                                                                                                                        <span class="text-muted">None</span>
                                                                                                                    @endif
                                                                                                                </p>
                                                                                                                <p class="mb-1"><strong>Video URL:</strong> @if($property->video_url)<a href="{{ $property->video_url }}" target="_blank">Watch Video</a>@else <span class="text-muted">None</span>@endif</p>
                                                                                                                <p class="mb-1"><strong>Virtual Tour URL:</strong> @if($property->virtual_tour_url)<a href="{{ $property->virtual_tour_url }}" target="_blank">View Tour</a>@else <span class="text-muted">None</span>@endif</p>
                                                                                                                <p class="mb-1"><strong>Brochure PDF:</strong> @if($property->brochure_pdf)<a href="{{ asset('storage/' . $property->brochure_pdf) }}" target="_blank">View Brochure</a>@else <span class="text-muted">None</span>@endif</p>
                                                                                    <p class="mb-1"><strong>Ownership Type:</strong> {{ $property->ownership_type }}</p>
                                                                                    <p class="mb-1"><strong>Property Approval:</strong> {{ $property->property_approval }}</p>
                                                                                    <p class="mb-1"><strong>RERA ID:</strong> {{ $property->rera_id }}</p>
                                                                                    <p class="mb-1"><strong>RERA Verified:</strong> {{ $property->rera_verified ? 'Yes' : 'No' }}</p>
                                                                                    <p class="mb-1"><strong>Occupancy Certificate:</strong> {{ $property->occupancy_certificate }}</p>
                                                                                    <p class="mb-1"><strong>Completion Certificate:</strong> {{ $property->completion_certificate }}</p>
                                                                                    <p class="mb-1"><strong>Legal Clearance Status:</strong> {{ $property->legal_clearance_status }}</p>
                                                        <p class="mb-1"><strong>Covered Parking:</strong> {{ $property->covered_parking }}</p>
                                                        <p class="mb-1"><strong>Open Parking:</strong> {{ $property->open_parking }}</p>
                                                        <p class="mb-1"><strong>Water Supply:</strong> {{ $property->water_supply }}</p>
                                                        <p class="mb-1"><strong>Electricity Status:</strong> {{ $property->electricity_status }}</p>
                                                        <p class="mb-1"><strong>Gas Pipeline:</strong> {{ $property->gas_pipeline ? 'Yes' : 'No' }}</p>
                                                        <p class="mb-1"><strong>Drainage:</strong> {{ $property->drainage ? 'Yes' : 'No' }}</p>
                            @if(!empty($property->bedrooms) || $property->bedrooms === 0)
                                <p class="mb-1"><strong>Bedrooms:</strong> {{ $property->bedrooms }}</p>
                            @endif
                            @if(!empty($property->bathrooms) || $property->bathrooms === 0)
                                <p class="mb-1"><strong>Bathrooms:</strong> {{ $property->bathrooms }}</p>
                            @endif
                            <p class="mb-1"><strong>Balconies:</strong> {{ $property->balconies }}</p>
                            <p class="mb-1"><strong>Total Floors:</strong> {{ $property->total_floors }}</p>
                            <p class="mb-1"><strong>Floor Number:</strong> {{ $property->floor_number }}</p>
                            <p class="mb-1"><strong>Facing:</strong> {{ $property->facing }}</p>
                            <p class="mb-1"><strong>Property Age:</strong> {{ $property->property_age }}</p>
                            <p class="mb-1"><strong>Furnishing Status:</strong> {{ $property->furnishing_status }}</p>
                            <p class="mb-1"><strong>Super Built-up Area:</strong> {{ $property->super_builtup_area }} {{ $property->area_unit }}</p>
                            <p class="mb-1"><strong>Built-up Area:</strong> {{ $property->builtup_area }} {{ $property->area_unit }}</p>
                            <p class="mb-1"><strong>Carpet Area:</strong> {{ $property->carpet_area }} {{ $property->area_unit }}</p>
                            <p class="mb-1"><strong>Plot Area:</strong> {{ $property->plot_area }} {{ $property->area_unit }}</p>
                            <p class="mb-1"><strong>Plot Length:</strong> {{ $property->plot_length }} {{ $property->area_unit }}</p>
                            <p class="mb-1"><strong>Plot Breadth:</strong> {{ $property->plot_breadth }} {{ $property->area_unit }}</p>
                            <h5 class="mb-2">{{ $property->property_type }}</h5>
                            <p class="mb-1"><strong>Address:</strong> {{ $property->address }}, {{ $property->locality }}, {{ $property->sub_locality }}, {{ $property->society_name }}, {{ $property->landmark }}, {{ $property->city }}, {{ $property->state }}, {{ $property->country }} - {{ $property->pincode }}</p>
                            <p class="mb-1"><strong>Latitude:</strong> {{ $property->latitude }} <strong>Longitude:</strong> {{ $property->longitude }}</p>
                            <p class="mb-1"><strong>Price:</strong> ₹{{ number_format($property->price) }}</p>
                            <p class="mb-1"><strong>Expected Price:</strong> ₹{{ number_format($property->expected_price) }}</p>
                            <p class="mb-1"><strong>Price per Sqft:</strong> ₹{{ number_format($property->price_per_sqft) }}</p>
                            <p class="mb-1"><strong>Negotiable:</strong> {{ $property->negotiable ? 'Yes' : 'No' }}</p>
                            <p class="mb-1"><strong>Maintenance Charges:</strong> ₹{{ number_format($property->maintenance_charges) }}</p>
                            <p class="mb-1"><strong>Booking Amount:</strong> ₹{{ number_format($property->booking_amount) }}</p>
                            <p class="mb-1"><strong>Monthly Rent:</strong> ₹{{ number_format($property->monthly_rent) }}</p>
                            <p class="mb-1"><strong>Lease Duration:</strong> {{ $property->lease_duration }}</p>
                            <p class="mb-1"><strong>Possession Status:</strong> {{ $property->possession_status }}</p>
                            <p class="mb-1"><strong>Possession Date:</strong> {{ $property->possession_date }}</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="badge bg-info">{{ $property->status }}</span></p>
                            <p class="mb-1"><strong>Area:</strong> {{ $property->area }} sq ft</p>
                            @if(!empty($property->bedrooms) || !empty($property->bathrooms) || $property->bedrooms === 0 || $property->bathrooms === 0)
                                <p class="mb-1">
                                    @if(!empty($property->bedrooms) || $property->bedrooms === 0)
                                        <strong>Bedrooms:</strong> {{ $property->bedrooms }}
                                    @endif
                                    @if((!empty($property->bedrooms) || $property->bedrooms === 0) && (!empty($property->bathrooms) || $property->bathrooms === 0))
                                        |
                                    @endif
                                    @if(!empty($property->bathrooms) || $property->bathrooms === 0)
                                        <strong>Bathrooms:</strong> {{ $property->bathrooms }}
                                    @endif
                                </p>
                            @endif
                            <p class="mb-1"><strong>Furnishing:</strong> {{ $property->furnishing }}</p>
                            <p class="mb-1"><strong>Amenities:</strong> 
                                @php
                                    $amenitiesArr = [];
                                    if (!empty($property->amenities)) {
                                        $amenitiesArr = is_array($property->amenities) ? $property->amenities : json_decode($property->amenities, true);
                                    }
                                @endphp
                                @if(!empty($amenitiesArr))
                                    {{ implode(', ', $amenitiesArr) }}
                                @else
                                    <span class="text-muted">None</span>
                                @endif
                            </p>
                            <p class="mb-1"><strong>RERA ID:</strong> {{ $property->rera_id }}</p>
                            <p class="mb-1"><strong>Price Range:</strong> {{ $property->price_range }}</p>
                            <p class="mb-1"><strong>Floor Plan:</strong> {{ $property->floor_plan }}</p>
                            <p class="mb-1"><strong>Virtual Tour:</strong> @if($property->virtual_tour_url)<a href="{{ $property->virtual_tour_url }}" target="_blank">View Tour</a>@else N/A @endif</p>
                            <p class="mb-1"><strong>Pet Friendly:</strong> {{ $property->pet_friendly ? 'Yes' : 'No' }}</p>
                            <p class="mb-1"><strong>Is Real:</strong> {{ $property->isreal ? 'Yes' : 'No' }}</p>
                            <p class="mb-1"><strong>Is Featured:</strong> {{ $property->is_featured ? 'Yes' : 'No' }}</p>
                            <p class="mb-1"><strong>Is Premium:</strong> {{ $property->is_premium ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="col-md-4">
                            @if($vidPath)
                                <div class="mb-2">
                                    <video width="100%" height="200" controls>
                                        <source src="{{ asset('storage/' . $vidPath) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @else
                                <div class="mb-2 text-muted">No Video</div>
                            @endif
                        </div>
                    </div>
                    <div class="mb-2">
                        <strong>Description:</strong>
                        <div class="border rounded p-2 bg-light">{{ $property->description }}</div>
                    </div>
                    <div class="mb-2">
                        <strong>Map:</strong>
                        @if($property->map_url)
                            <a href="{{ $property->map_url }}" target="_blank" class="btn btn-sm btn-outline-secondary">View on Map</a>
                        @else
                            <span class="text-muted">No Map URL</span>
                        @endif
                    </div>
                    <div class="mb-2">
                        <strong>All Property Fields:</strong>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm bg-white">
                                <tbody>
                                @foreach($property->getAttributes() as $key => $value)
                                    <tr>
                                        <th style="width:220px">{{ ucwords(str_replace(['_', '-'], ' ', $key)) }}</th>
                                        <td>
                                            @if(is_bool($value))
                                                {{ $value ? 'Yes' : 'No' }}
                                            @elseif($key === 'virtual_tour_url' && $value)
                                                <a href="{{ $value }}" target="_blank">View Tour</a>
                                            @elseif($key === 'video_url' && $value)
                                                <a href="{{ asset('storage/' . $value) }}" target="_blank">Watch Video</a>
                                            @elseif($key === 'map_url' && $value)
                                                <a href="{{ $value }}" target="_blank">View Map</a>
                                            @else
                                                {{ $value ?? 'N/A' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                                                                                                    <div class="mb-2">
                                                                                    <strong>Special Features:</strong>
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-bordered table-sm bg-white">
                                                                                            <tbody>
                                                                                                <tr><th style="width:220px">Gated Society</th><td>{{ $property->gated_society ? 'Yes' : 'No' }}</td></tr>
                                                                                                <tr><th>Corner Property</th><td>{{ $property->corner_property ? 'Yes' : 'No' }}</td></tr>
                                                                                                <tr><th>Vastu Compliant</th><td>{{ $property->vastu_compliant ? 'Yes' : 'No' }}</td></tr>
                                                                                                <tr><th>Wheelchair Friendly</th><td>{{ $property->wheelchair_friendly ? 'Yes' : 'No' }}</td></tr>
                                                                                                <tr><th>Overlooking Park</th><td>{{ $property->overlooking_park ? 'Yes' : 'No' }}</td></tr>
                                                                                                <tr><th>Overlooking Road</th><td>{{ $property->overlooking_road ? 'Yes' : 'No' }}</td></tr>
                                                                                                <tr><th>Income Property</th><td>{{ $property->income_property ? 'Yes' : 'No' }}</td></tr>
                                                                                                <tr><th>Distress Sale</th><td>{{ $property->distress_sale ? 'Yes' : 'No' }}</td></tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="mb-2">
                                                                                    <strong>Stats & Expiry Details:</strong>
                                                                                    <div class="table-responsive">
                                                                                        <table class="table table-bordered table-sm bg-white">
                                                                                            <tbody>
                                                                                                <tr><th style="width:220px">Views Count</th><td>{{ $property->views_count }}</td></tr>
                                                                                                <tr><th>Shortlist Count</th><td>{{ $property->shortlist_count }}</td></tr>
                                                                                                <tr><th>Inquiries Count</th><td>{{ $property->inquiries_count }}</td></tr>
                                                                                                <tr><th>Last Viewed At</th><td>{{ $property->last_viewed_at ? $property->last_viewed_at->format('Y-m-d H:i') : '' }}</td></tr>
                                                                                                <tr><th>Expiry Date</th><td>{{ $property->expiry_date ? $property->expiry_date->format('Y-m-d') : '' }}</td></tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                            <div class="mb-2">
                                                                <strong>SEO & Featured Details:</strong>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered table-sm bg-white">
                                                                        <tbody>
                                                                            <tr><th style="width:220px">Slug</th><td>{{ $property->slug }}</td></tr>
                                                                            <tr><th>Meta Title</th><td>{{ $property->meta_title }}</td></tr>
                                                                            <tr><th>Meta Description</th><td>{{ $property->meta_description }}</td></tr>
                                                                            <tr><th>Search Tags</th><td>{{ $property->search_tags }}</td></tr>
                                                                            <tr><th>Featured</th><td>{{ $property->featured ? 'Yes' : 'No' }}</td></tr>
                                                                            <tr><th>Priority Score</th><td>{{ $property->priority_score }}</td></tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                        <div class="mb-2">
                                            <strong>Nearby & Distance Details:</strong>
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-sm bg-white">
                                                    <tbody>
                                                        <tr><th style="width:220px">Nearby Schools</th><td>{{ $property->nearby_schools }}</td></tr>
                                                        <tr><th>Nearby Hospitals</th><td>{{ $property->nearby_hospitals }}</td></tr>
                                                        <tr><th>Nearby Malls</th><td>{{ $property->nearby_malls }}</td></tr>
                                                        <tr><th>Nearby Metro</th><td>{{ $property->nearby_metro }}</td></tr>
                                                        <tr><th>Nearby Bus Stand</th><td>{{ $property->nearby_bus_stand }}</td></tr>
                                                        <tr><th>Distance Metrics</th><td>
                                                            @if(is_array($property->distance_metrics))
                                                                @foreach($property->distance_metrics as $k => $v)
                                                                    <div><strong>{{ $k }}:</strong> {{ $v }}</div>
                                                                @endforeach
                                                            @else
                                                                {{ $property->distance_metrics }}
                                                            @endif
                                                        </td></tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                    <div class="mb-2">
                        <strong>Contact/User Details:</strong>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm bg-white">
                                <tbody>
                                    <tr><th style="width:220px">User ID</th><td>{{ $property->user_id }}</td></tr>
                                    <tr><th>Contact Name</th><td>{{ $property->contact_name }}</td></tr>
                                    <tr><th>Contact Phone</th><td>{{ $property->contact_phone }}</td></tr>
                                    <tr><th>Contact Email</th><td>{{ $property->contact_email }}</td></tr>
                                    <tr><th>Company Name</th><td>{{ $property->company_name }}</td></tr>
                                    <tr><th>License Number</th><td>{{ $property->license_number }}</td></tr>
                                    <tr><th>Verified User</th><td>{{ $property->verified_user ? 'Yes' : 'No' }}</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mb-2">
                        <strong>All Images:</strong>
                        <div class="d-flex flex-wrap">
                            @foreach($property->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}" alt="Property Image" width="120" class="me-2 mb-2 border rounded">
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <script>
        function scanPayment() {
            alert('QR Scanner coming soon! Screenshot QR when ready.');
        }
    </script>
@endsection
