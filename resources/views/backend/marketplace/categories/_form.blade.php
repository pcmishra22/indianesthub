@php
    $isEdit = isset($category) && $category;
@endphp
<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold">Name *</label>
        <input type="text" name="name" class="form-control" required
               value="{{ old('name', $category->name ?? '') }}" placeholder="e.g. Curtains & Blinds">
    </div>
    <div class="col-md-3">
        <label class="form-label fw-semibold">Sort order</label>
        <input type="number" name="sort_order" min="0" class="form-control"
               value="{{ old('sort_order', $category->sort_order ?? 0) }}">
        <div class="form-text">Lower numbers show first.</div>
    </div>
    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active_cat"
                {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active_cat">Active (visible on site)</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label fw-semibold">Icon</label>
        <div class="input-group">
            <span class="input-group-text"><i class="bi {{ old('icon', $category->icon ?? '') ?: 'bi-shop' }}"></i></span>
            <input type="text" name="icon" class="form-control"
                   value="{{ old('icon', $category->icon ?? '') }}" placeholder="bi-lightbulb">
        </div>
        <div class="form-text">
            Any <a href="https://icons.getbootstrap.com/" target="_blank" rel="noopener">Bootstrap Icons</a> class name, e.g. <code>bi-lightbulb</code>, <code>bi-house-door</code>.
        </div>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold">Tagline</label>
        <input type="text" name="tagline" class="form-control" maxlength="160"
               value="{{ old('tagline', $category->tagline ?? '') }}" placeholder="Short line shown under the category name">
    </div>

    @if($isEdit)
    <div class="col-12">
        <div class="alert alert-light border small mb-0">
            <strong>Slug:</strong> <code>{{ $category->slug }}</code> —
            public URL: <a href="{{ route('marketplace.category', $category) }}" target="_blank">{{ route('marketplace.category', $category) }}</a>.
            The slug only changes automatically if you edit the name.
        </div>
    </div>
    @endif
</div>
