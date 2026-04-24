{{-- Shared Property/Unit Form Partial --}}
<div class="row g-3">

    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Unit Information</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Unit Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $property->title ?? '') }}"
                               placeholder="e.g. 3BHK Premium Flat – Block A, Unit 501" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Property Type <span class="text-danger">*</span></label>
                        <select name="property_type" class="form-select @error('property_type') is-invalid @enderror" required>
                            <option value="">Select...</option>
                            @foreach(['Apartment', 'Villa', 'Plot', 'Row House', 'Penthouse', 'Studio', 'Commercial', 'Office'] as $type)
                            <option value="{{ $type }}" {{ old('property_type', $property->property_type ?? '') === $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </select>
                        @error('property_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">BHK Type</label>
                        <select name="bhk_type" class="form-select">
                            <option value="">Select...</option>
                            @foreach(['1 RK', '1 BHK', '2 BHK', '3 BHK', '4 BHK', '5 BHK', '5+ BHK'] as $bhk)
                            <option value="{{ $bhk }}" {{ old('bhk_type', $property->bhk_type ?? '') === $bhk ? 'selected' : '' }}>{{ $bhk }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Option Type</label>
                        <select name="option_type" class="form-select">
                            <option value="">Select...</option>
                            @foreach(['New Booking', 'Under Construction', 'Ready to Move', 'Resale'] as $opt)
                            <option value="{{ $opt }}" {{ old('option_type', $property->option_type ?? '') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">For Sale / Rent</label>
                        <select name="looking_for" class="form-select">
                            <option value="Sale"  {{ old('looking_for', $property->looking_for ?? 'Sale') === 'Sale'  ? 'selected' : '' }}>For Sale</option>
                            <option value="Rent"  {{ old('looking_for', $property->looking_for ?? 'Sale') === 'Rent'  ? 'selected' : '' }}>For Rent</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Availability Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(['Available', 'Booked', 'Sold', 'On Hold'] as $s)
                            <option value="{{ $s }}" {{ old('status', $property->status ?? 'Available') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Details about this unit...">{{ old('description', $property->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Size & Configuration</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Area (sqft)</label>
                        <input type="number" name="area" class="form-control" min="0" step="any"
                               value="{{ old('area', $property->area ?? '') }}" placeholder="e.g. 1250">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bedrooms</label>
                        <input type="number" name="bedrooms" class="form-control" min="0"
                               value="{{ old('bedrooms', $property->bedrooms ?? '') }}" placeholder="e.g. 3">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Bathrooms</label>
                        <input type="number" name="bathrooms" class="form-control" min="0"
                               value="{{ old('bathrooms', $property->bathrooms ?? '') }}" placeholder="e.g. 2">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Balconies</label>
                        <input type="number" name="balconies" class="form-control" min="0"
                               value="{{ old('balconies', $property->balconies ?? '') }}" placeholder="e.g. 1">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Floor Number</label>
                        <input type="text" name="floor_number" class="form-control"
                               value="{{ old('floor_number', $property->floor_number ?? '') }}" placeholder="e.g. 5 or Ground">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Furnishing Status</label>
                        <select name="furnishing_status" class="form-select">
                            <option value="">Select...</option>
                            @foreach(['Fully Furnished', 'Semi Furnished', 'Unfurnished'] as $f)
                            <option value="{{ $f }}" {{ old('furnishing_status', $property->furnishing_status ?? '') === $f ? 'selected' : '' }}>{{ $f }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Pricing</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Price (₹)</label>
                        <input type="number" name="price" class="form-control" min="0" step="any"
                               value="{{ old('price', $property->price ?? '') }}" placeholder="Total price">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $property->city ?? $project->city ?? '') }}" placeholder="City">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control"
                               value="{{ old('address', $property->address ?? $project->address ?? '') }}" placeholder="Unit address">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control"
                               value="{{ old('state', $property->state ?? $project->state ?? '') }}" placeholder="State">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Unit Cover Image</h5></div>
            <div class="card-body">
                @if(isset($property) && $property->cover_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $property->cover_image) }}" alt="Cover"
                             class="img-thumbnail" style="max-height:120px;">
                    </div>
                @endif
                <input type="file" name="cover_image" class="form-control" accept="image/*">
                <small class="text-muted">Max 3 MB.</small>
            </div>
        </div>
    </div>

</div>
