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
            <label class="form-label">CSV file (required for Property)</label>
            <input type="file" name="csv_file" class="form-control" accept=".csv,.txt">
            <div class="form-text">
                Required columns: <code>title, property_type, looking_for, address, city, state, price</code>.
                Optional: <code>description, country, bedrooms, bathrooms, area, furnishing, amenities</code>.
                <code>looking_for</code> should be Sale, Rent, PG, or Renovate.
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
        city). Properties can't be auto-crawled from listing portals or search engines — that would violate their
        Terms of Service — so choose <strong>Property</strong> and upload a CSV instead. You'll still review and
        tick every row before anything is saved.
    </p>
</div>
@endsection
