@php
    $isEdit = isset($product) && $product;
    $bhkSelected = old('bhk_fit', $isEdit ? ($product->bhk_fit ?? []) : []);
    $tagsValue   = old('tags', $isEdit ? (is_array($product->tags) ? implode(', ', $product->tags) : '') : '');
@endphp
<div class="row g-3">
    <div class="col-md-7">
        <label class="form-label fw-semibold">Product name *</label>
        <input type="text" name="name" class="form-control" required
               value="{{ old('name', $product->name ?? '') }}">
    </div>
    <div class="col-md-5">
        <label class="form-label fw-semibold">Category *</label>
        <select name="category_id" class="form-select" required>
            <option value="">Select</option>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ (string) old('category_id', $product->category_id ?? '') === (string) $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-7">
        <label class="form-label fw-semibold">Vendor *</label>
        <select name="vendor_id" class="form-select" required>
            <option value="">Select</option>
            @foreach($vendors as $v)
                <option value="{{ $v->id }}" {{ (string) old('vendor_id', $product->vendor_id ?? '') === (string) $v->id ? 'selected' : '' }}>
                    {{ $v->business_name }} @if($v->city) — {{ $v->city }} @endif
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        <label class="form-label fw-semibold">Price unit</label>
        <input type="text" name="price_unit" class="form-control" placeholder="onwards / per panel / per set"
               value="{{ old('price_unit', $product->price_unit ?? 'onwards') }}">
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Price min (₹)</label>
        <input type="number" name="price_min" min="0" step="0.01" class="form-control"
               value="{{ old('price_min', $product->price_min ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Price max (₹)</label>
        <input type="number" name="price_max" min="0" step="0.01" class="form-control"
               value="{{ old('price_max', $product->price_max ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Sort order</label>
        <input type="number" name="sort_order" min="0" class="form-control"
               value="{{ old('sort_order', $product->sort_order ?? 0) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Fits BHK (leave empty = all)</label>
        <div class="d-flex flex-wrap gap-3 mt-1">
            @for($i = 1; $i <= 5; $i++)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="bhk_fit[]" value="{{ $i }}" id="bhk{{ $i }}"
                        {{ in_array((string) $i, (array) $bhkSelected, true) ? 'checked' : '' }}>
                    <label class="form-check-label" for="bhk{{ $i }}">{{ $i }}BHK</label>
                </div>
            @endfor
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Tags (comma separated)</label>
        <input type="text" name="tags" class="form-control" placeholder="eyelet, sheer, blackout"
               value="{{ $tagsValue }}">
    </div>

    <div class="col-12">
        <label class="form-label fw-semibold">Description</label>
        <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4">
        <label class="form-label fw-semibold">Cover image</label>
        <input type="file" name="cover_image" accept="image/*" class="form-control">
        @if($isEdit && $product->cover_image)
            <small class="text-muted">Current: <a href="{{ asset('storage/'.$product->cover_image) }}" target="_blank">view</a></small>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label fw-semibold">Gallery (multiple)</label>
        <input type="file" name="gallery[]" accept="image/*" multiple class="form-control">
    </div>
    <div class="col-md-4 d-flex align-items-end gap-3">
        <div class="form-check">
            <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
                {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_featured">Featured</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_p"
                {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active_p">Active</label>
        </div>
    </div>
</div>
