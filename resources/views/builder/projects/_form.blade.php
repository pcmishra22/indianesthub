{{-- Shared Project Form Partial --}}
<div class="row g-3">

    {{-- Basic Info --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Project Information</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Project Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $project->title ?? '') }}"
                               placeholder="e.g. Sunrise Heights Phase 2" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">RERA ID</label>
                        <input type="text" name="rera_id" class="form-control"
                               value="{{ old('rera_id', $project->rera_id ?? '') }}"
                               placeholder="RERA registration number">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Project Type <span class="text-danger">*</span></label>
                        <select name="project_type" class="form-select @error('project_type') is-invalid @enderror" required>
                            @foreach(\App\Models\BuilderProject::projectTypes() as $type)
                                <option value="{{ $type }}" {{ old('project_type', $project->project_type ?? 'Residential') === $type ? 'selected' : '' }}>
                                    {{ $type }}
                                </option>
                            @endforeach
                        </select>
                        @error('project_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            @foreach(\App\Models\BuilderProject::projectStatuses() as $status)
                                <option value="{{ $status }}" {{ old('status', $project->status ?? 'Upcoming') === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                        @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Possession Date</label>
                        <input type="date" name="possession_date" class="form-control"
                               value="{{ old('possession_date', isset($project) && $project->possession_date ? $project->possession_date->format('Y-m-d') : '') }}">
                    </div>
                    <div class="col-12">
                        <div class="form-check">
                            <input type="hidden" name="is_featured" value="0">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured" value="1"
                                {{ old('is_featured', $project->is_featured ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                Mark as Featured Project
                            </label>
                        </div>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"
                                  placeholder="Describe this project — amenities, highlights, USP...">{{ old('description', $project->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Location --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Location</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label">Address</label>
                        <input type="text" name="address" class="form-control"
                               value="{{ old('address', $project->address ?? '') }}"
                               placeholder="Full project address">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">City</label>
                        <input type="text" name="city" class="form-control"
                               value="{{ old('city', $project->city ?? '') }}" placeholder="City">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">State</label>
                        <input type="text" name="state" class="form-control"
                               value="{{ old('state', $project->state ?? '') }}" placeholder="State">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Units, Towers & Pricing --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Units, Towers & Pricing</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Total Units</label>
                        <input type="number" name="total_units" class="form-control" min="1"
                               value="{{ old('total_units', $project->total_units ?? '') }}" placeholder="e.g. 200">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Available Units</label>
                        <input type="number" name="available_units" class="form-control" min="0"
                               value="{{ old('available_units', $project->available_units ?? '') }}" placeholder="e.g. 150">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Total Towers</label>
                        <input type="number" name="total_towers" class="form-control" min="1"
                               value="{{ old('total_towers', $project->total_towers ?? '') }}" placeholder="e.g. 4">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Floors per Tower</label>
                        <input type="text" name="floors_per_tower" class="form-control"
                               value="{{ old('floors_per_tower', $project->floors_per_tower ?? '') }}" placeholder="e.g. G+22">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price From (₹)</label>
                        <input type="number" name="price_from" class="form-control" min="0" step="any"
                               value="{{ old('price_from', $project->price_from ?? '') }}" placeholder="Min price">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Price To (₹)</label>
                        <input type="number" name="price_to" class="form-control" min="0" step="any"
                               value="{{ old('price_to', $project->price_to ?? '') }}" placeholder="Max price">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Amenities (master table checkboxes) --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Amenities</h5></div>
            <div class="card-body">
                @php $allAmenities = \App\Models\Amenity::orderBy('category')->orderBy('name')->get()->groupBy('category'); @endphp
                @php $selectedIds = isset($project) ? $project->amenityItems->pluck('id')->toArray() : old('amenity_ids', []); @endphp
                @if($allAmenities->count())
                    @foreach($allAmenities as $category => $items)
                    <div class="mb-3">
                        <p class="fw-semibold text-muted mb-2" style="font-size:.78rem;text-transform:uppercase;letter-spacing:.6px;">{{ $category }}</p>
                        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2">
                            @foreach($items as $amenity)
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="amenity_ids[]" value="{{ $amenity->id }}"
                                           id="amenity_{{ $amenity->id }}"
                                           {{ in_array($amenity->id, (array)$selectedIds) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amenity_{{ $amenity->id }}">
                                        @if($amenity->icon)<i class="{{ $amenity->icon }} me-1"></i>@endif
                                        {{ $amenity->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted mb-2">No amenities seeded yet.</p>
                @endif
                <hr>
                <label class="form-label text-muted" style="font-size:.85rem;">Additional / Custom Amenities</label>
                <input type="text" name="amenities" class="form-control"
                       value="{{ old('amenities', $project->amenities ?? '') }}"
                       placeholder="Helipad, Private Pool, Butler Service...">
            </div>
        </div>
    </div>

    {{-- Location Intelligence --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Location Intelligence</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Latitude</label>
                        <input type="number" name="latitude" class="form-control" step="any"
                               value="{{ old('latitude', $project->latitude ?? '') }}" placeholder="e.g. 19.0760">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Longitude</label>
                        <input type="number" name="longitude" class="form-control" step="any"
                               value="{{ old('longitude', $project->longitude ?? '') }}" placeholder="e.g. 72.8777">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Metro Distance</label>
                        <input type="text" name="metro_distance" class="form-control"
                               value="{{ old('metro_distance', $project->metro_distance ?? '') }}" placeholder="e.g. 1.2 km">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Connectivity Score</label>
                        <input type="text" name="connectivity_score" class="form-control"
                               value="{{ old('connectivity_score', $project->connectivity_score ?? '') }}" placeholder="e.g. 8/10">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nearby Schools</label>
                        <input type="text" name="nearby_schools" class="form-control"
                               value="{{ old('nearby_schools', $project->nearby_schools ?? '') }}"
                               placeholder="DPS (0.5km), Ryan International (1km)...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nearby Hospitals</label>
                        <input type="text" name="nearby_hospitals" class="form-control"
                               value="{{ old('nearby_hospitals', $project->nearby_hospitals ?? '') }}"
                               placeholder="Fortis (1km), Lilavati (2km)...">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Future Infrastructure</label>
                        <input type="text" name="future_infra" class="form-control"
                               value="{{ old('future_infra', $project->future_infra ?? '') }}"
                               placeholder="Metro line extension (2026), Ring road bypass (2027)...">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Media --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5 class="card-title mb-0">Media & Documents</h5></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Cover / Hero Image</label>
                        @if(isset($project) && $project->cover_image)
                            <div class="mb-2"><img src="{{ asset('storage/' . $project->cover_image) }}" class="img-thumbnail" style="max-height:100px;"></div>
                        @endif
                        <input type="file" name="cover_image" class="form-control" accept="image/*">
                        <small class="text-muted">Max 3 MB.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Master Plan Image</label>
                        @if(isset($project) && $project->master_plan)
                            <div class="mb-2"><img src="{{ asset('storage/' . $project->master_plan) }}" class="img-thumbnail" style="max-height:100px;"></div>
                        @endif
                        <input type="file" name="master_plan" class="form-control" accept="image/*">
                        <small class="text-muted">Layout / site plan image.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Brochure (PDF)</label>
                        @if(isset($project) && $project->brochure)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $project->brochure) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i data-feather="file-text" style="width:14px;height:14px;"></i> View Current Brochure
                                </a>
                            </div>
                        @endif
                        <input type="file" name="brochure" class="form-control" accept=".pdf">
                        <small class="text-muted">PDF only. Max 10 MB.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Gallery Images (multiple)</label>
                        @if(isset($project) && $project->gallery_images && count($project->gallery_images))
                            <div class="d-flex flex-wrap gap-1 mb-2">
                                @foreach($project->gallery_images as $img)
                                    <img src="{{ asset('storage/' . $img) }}" class="img-thumbnail" style="max-height:55px;">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="gallery_images[]" class="form-control" accept="image/*" multiple>
                        <small class="text-muted">Multiple allowed. Appended to existing.</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Video URL</label>
                        <input type="url" name="video_url" class="form-control"
                               value="{{ old('video_url', $project->video_url ?? '') }}"
                               placeholder="YouTube / Vimeo URL">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Virtual Tour URL</label>
                        <input type="url" name="virtual_tour_url" class="form-control"
                               value="{{ old('virtual_tour_url', $project->virtual_tour_url ?? '') }}"
                               placeholder="Matterport / 360° tour URL">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
