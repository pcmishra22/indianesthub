@extends('backend.layout')

@section('title', 'City Data Import')

@section('content')
<div class="container py-4" style="max-width: 640px;">
    <h1 class="h4 mb-3">Add City Data</h1>
    <p class="text-muted">
        Enter a city and choose what to look for. You'll review every result
        on the next screen and confirm once before anything is saved.
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->has('discovery'))
        <div class="alert alert-danger">{{ $errors->first('discovery') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.city-import.discover') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">City</label>
            <input type="text" name="city" class="form-control" placeholder="e.g. Zirakpur" required value="{{ old('city') }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Type</label>
            <select name="type" id="import-type" class="form-select" required onchange="document.getElementById('csv-field').style.display = this.value === 'property' ? 'block' : 'none'; document.getElementById('builder-name-field').style.display = this.value === 'builder' ? 'block' : 'none';">
                <option value="builder">Builder</option>
                <option value="agent">Agent</option>
                <option value="property" {{ old('type') == 'property' ? 'selected' : '' }}>Property</option>
            </select>
        </div>

        <div class="mb-3" id="builder-name-field" style="display: {{ old('type') == 'builder' ? 'block' : 'none' }};">
            <label class="form-label">Builder name <span class="text-muted">(optional)</span></label>
            <input type="text" name="builder_name" class="form-control" placeholder="e.g. Prestige Group, Sobha, Brigade" value="{{ old('builder_name') }}">
            <div class="form-text">
                Leave blank to discover all builders in the city, or type a name to narrow the search
                to a specific builder (e.g. "Prestige", "Sobha").
            </div>
        </div>

        <div class="mb-3" id="csv-field" style="display: {{ old('type') == 'property' ? 'block' : 'none' }};">
            <label class="form-label">How do you want to add properties?</label>
            <div class="btn-group d-block mb-2" role="group">
                <input type="radio" class="btn-check" name="property_input" id="input-csv" value="csv" {{ old('property_input', 'csv') == 'csv' ? 'checked' : '' }} onchange="document.getElementById('csv-upload-block').style.display='block'; document.getElementById('manual-entry-block').style.display='none';">
                <label class="btn btn-outline-secondary btn-sm" for="input-csv">Upload CSV (bulk)</label>

                <input type="radio" class="btn-check" name="property_input" id="input-manual" value="manual" {{ old('property_input') == 'manual' ? 'checked' : '' }} onchange="document.getElementById('csv-upload-block').style.display='none'; document.getElementById('manual-entry-block').style.display='block';">
                <label class="btn btn-outline-secondary btn-sm" for="input-manual">Enter one property manually</label>
            </div>

            <div id="csv-upload-block" style="display: {{ old('property_input') == 'manual' ? 'none' : 'block' }};">
                <label class="form-label">CSV file</label>
                <input type="file" name="csv_file" class="form-control" accept=".csv,.txt">
                <div class="form-text">
                    Required columns: <code>title, property_type, looking_for, address, city, state, price</code>.
                    Optional: <code>description, country, bedrooms, bathrooms, area, furnishing, amenities</code>.
                    <code>looking_for</code> should be Sale, Rent, PG, or Renovate.
                </div>
            </div>

            <div id="manual-entry-block" style="display: {{ old('property_input') == 'manual' ? 'block' : 'none' }};" class="border rounded p-3 bg-light">
                <div class="row g-2">
                    <div class="col-md-8">
                        <label class="form-label small">Title</label>
                        <input type="text" name="manual_title" class="form-control form-control-sm" placeholder="e.g. 3BHK Apartment in Sector 20" value="{{ old('manual_title') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Price (₹)</label>
                        <input type="number" name="manual_price" class="form-control form-control-sm" min="0" value="{{ old('manual_price') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Property Type</label>
                        <select name="manual_property_type" class="form-select form-select-sm">
                            @foreach(['Apartment','Villa','Independent House','Plot','Studio','Farmhouse','Commercial'] as $pt)
                                <option value="{{ $pt }}" {{ old('manual_property_type') == $pt ? 'selected' : '' }}>{{ $pt }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Looking For</label>
                        <select name="manual_looking_for" class="form-select form-select-sm">
                            @foreach(['Sale','Rent','PG','Renovate'] as $lf)
                                <option value="{{ $lf }}" {{ old('manual_looking_for') == $lf ? 'selected' : '' }}>{{ $lf }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">State</label>
                        <input type="text" name="manual_state" class="form-control form-control-sm" value="{{ old('manual_state') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small">Address</label>
                        <input type="text" name="manual_address" class="form-control form-control-sm" value="{{ old('manual_address') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">City <span class="text-muted">(defaults to City field above)</span></label>
                        <input type="text" name="manual_city" class="form-control form-control-sm" value="{{ old('manual_city') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Bedrooms</label>
                        <input type="number" name="manual_bedrooms" class="form-control form-control-sm" min="0" value="{{ old('manual_bedrooms') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Bathrooms</label>
                        <input type="number" name="manual_bathrooms" class="form-control form-control-sm" min="0" value="{{ old('manual_bathrooms') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Area (sq ft)</label>
                        <input type="number" name="manual_area" class="form-control form-control-sm" min="0" value="{{ old('manual_area') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Furnishing</label>
                        <input type="text" name="manual_furnishing" class="form-control form-control-sm" placeholder="e.g. Semi-furnished" value="{{ old('manual_furnishing') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small">Amenities</label>
                        <input type="text" name="manual_amenities" class="form-control form-control-sm" placeholder="e.g. Lift, Parking, Power Backup" value="{{ old('manual_amenities') }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label small">Description</label>
                        <textarea name="manual_description" class="form-control form-control-sm" rows="2">{{ old('manual_description') }}</textarea>
                    </div>
                </div>
                <div class="form-text mt-2">You'll still review this on the next screen before it's saved.</div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Search</button>
    </form>

    <p class="text-muted small mt-4">
        Builders &amp; Agents use Mappls (MapmyIndia) if <code>MAPPLS_API_KEY</code> is set in your
        <code>.env</code> — sign up free at
        <a href="https://www.mapmyindia.com/api/maps-api-free/" target="_blank" rel="noopener">mapmyindia.com/api/maps-api-free</a>
        (no credit card required), then grab the Static Key from your app's Credentials tab in the
        <a href="https://auth.mappls.com/console" target="_blank" rel="noopener">Mappls Console</a>. Mappls is an
        India-focused mapping company with much deeper coverage of Indian cities and towns than global providers.
        Without a key, it automatically falls back to OpenStreetMap (free, no signup, but coverage varies more by
        city). Properties can't be auto-discovered from listing portals or search engines the way builders/agents
        are — no map or business directory has individual listing data, and scraping portals like 99acres/MagicBricks
        would violate their Terms of Service — so for <strong>Property</strong>, upload a CSV for bulk import or
        enter one manually above. You'll still review and confirm before anything is saved.
    </p>
</div>
@endsection
