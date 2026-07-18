@extends('service-provider.layout')

@section('title', 'Edit Profile')

@push('styles')
<style>
    .category-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:10px; }
    .category-pick { position:relative; }
    .category-pick input { position:absolute; opacity:0; inset:0; cursor:pointer; margin:0; }
    .category-pick label {
        display:flex; align-items:center; gap:8px; padding:12px 14px;
        border:1.5px solid #e4e8f0; border-radius:10px; cursor:pointer;
        font-size:.85rem; font-weight:600; color:#475569; transition:all .15s;
    }
    .category-pick input:checked + label { border-color:#0078d4; background:#f0f7ff; color:#0a2d5e; }
    .category-pick label i { color:#0078d4; }
</style>
@endpush

@section('content')
<div class="p-4">
    <div class="row">
        <div class="col-lg-9 mx-auto">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Edit Profile</h1>
                <a href="{{ route('service-provider.dashboard') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                </a>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error) {{ $error }}<br> @endforeach
            </div>
            @endif

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('service-provider.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Full Name *</label>
                                <input class="form-control" type="text" name="full_name"
                                       value="{{ old('full_name', $provider->full_name) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Business / Shop Name</label>
                                <input class="form-control" type="text" name="business_name"
                                       value="{{ old('business_name', $provider->business_name) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Phone *</label>
                                <input class="form-control" type="text" name="phone"
                                       value="{{ old('phone', $provider->phone) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">City *</label>
                                <input class="form-control" type="text" name="city"
                                       value="{{ old('city', $provider->city) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Years of Experience</label>
                                <input class="form-control" type="number" min="0" max="60" name="years_experience"
                                       value="{{ old('years_experience', $provider->years_experience) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Starting Price (₹)</label>
                                <input class="form-control" type="number" step="0.01" name="starting_price"
                                       value="{{ old('starting_price', $provider->starting_price) }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-semibold">Price Unit</label>
                                <input class="form-control" type="text" name="price_unit" placeholder="per visit / per sqft"
                                       value="{{ old('price_unit', $provider->price_unit) }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Bio / About Your Work</label>
                                <textarea class="form-control" name="bio" rows="4"
                                          placeholder="Tell customers about your experience and what makes your service stand out.">{{ old('bio', $provider->bio) }}</textarea>
                            </div>
                            <div class="col-12">
                                <label class="form-label fw-semibold">Profile Photo</label>
                                <input class="form-control" type="file" name="profile_photo" accept="image/*">
                                @if($provider->profile_photo)
                                    <img src="{{ asset('storage/'.$provider->profile_photo) }}" class="mt-2 rounded" style="width:80px;height:80px;object-fit:cover;">
                                @endif
                            </div>
                        </div>

                        <hr class="my-4">

                        <label class="form-label fw-semibold mb-2">
                            Services I Provide <span class="text-danger">*</span>
                            <span class="d-block fw-normal text-muted small">Update anytime as you add new skills/services.</span>
                        </label>
                        @php $selectedIds = old('categories', $provider->categories->pluck('id')->toArray()); @endphp
                        <div class="category-grid mb-2">
                            @foreach($categories as $category)
                                <div class="category-pick">
                                    <input type="checkbox" id="ecat-{{ $category->id }}" name="categories[]"
                                           value="{{ $category->id }}"
                                           {{ in_array($category->id, $selectedIds) ? 'checked' : '' }}>
                                    <label for="ecat-{{ $category->id }}">
                                        <i class="bi {{ $category->icon ?? 'bi-tools' }}"></i> {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('categories')
                            <div class="text-danger small mb-3">{{ $message }}</div>
                        @enderror

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-lg btn-primary fw-semibold">Save Profile</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
