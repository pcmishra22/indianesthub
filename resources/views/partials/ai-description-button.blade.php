{{-- =====================================================================
     AI Property Description Generator button
     Usage: @include('partials.ai-description-button', [
                'url' => route('dealer.ai.property-description'),   // or builder.ai.property-description
                'descriptionField' => 'description-editor',          // textarea id (CKEditor id if used)
                'usesCkeditor' => true,                               // false for plain textarea
                'metaField' => 'meta_description',                   // optional, name attribute
            ])
     ===================================================================== --}}
@php
    $usesCkeditor = $usesCkeditor ?? false;
    $metaField = $metaField ?? null;
    $btnId = 'ai-desc-btn-' . uniqid();
@endphp

<button type="button" id="{{ $btnId }}" class="btn btn-sm btn-outline-primary mb-2" data-ai-desc-btn
        data-url="{{ $url }}"
        data-field="{{ $descriptionField }}"
        data-ckeditor="{{ $usesCkeditor ? '1' : '0' }}"
        @if($metaField) data-meta-field="{{ $metaField }}" @endif>
    <i class="fas fa-magic me-1"></i> Generate with AI
</button>
<span class="ai-desc-status small text-muted ms-2" data-ai-desc-status="{{ $btnId }}"></span>

@once
<script>
document.addEventListener('click', function (e) {
    const btn = e.target.closest('[data-ai-desc-btn]');
    if (!btn) return;

    const form = btn.closest('form');
    if (!form) return;

    const statusEl = document.querySelector('[data-ai-desc-status="' + btn.id + '"]');
    const fieldName = (name) => form.elements[name] ? form.elements[name].value : null;
    const multiValues = (name) => {
        const el = form.elements[name + '[]'] || form.elements[name];
        if (!el) return [];
        if (el.multiple) return Array.from(el.selectedOptions).map(o => o.value);
        return [];
    };

    const payload = {
        title: fieldName('title'),
        property_type: fieldName('property_type'),
        bhk_type: fieldName('bhk_type'),
        bedrooms: fieldName('bedrooms'),
        bathrooms: fieldName('bathrooms'),
        city: fieldName('city'),
        locality: fieldName('locality'),
        sub_locality: fieldName('sub_locality'),
        area: fieldName('area') || fieldName('super_builtup_area') || fieldName('carpet_area'),
        area_unit: fieldName('area_unit'),
        price: fieldName('price') || fieldName('expected_price'),
        furnishing_status: fieldName('furnishing_status'),
        facing: fieldName('facing'),
        floor_number: fieldName('floor_number'),
        total_floors: fieldName('total_floors'),
        listing_type: (fieldName('looking_for') || '').toLowerCase().includes('rent') ? 'rent' : 'sale',
        amenities: multiValues('amenities'),
    };

    btn.disabled = true;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Generating...';
    if (statusEl) statusEl.textContent = '';

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

    fetch(btn.dataset.url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
    })
    .then(res => res.json().then(data => ({ ok: res.ok, data })))
    .then(({ ok, data }) => {
        if (!ok || data.error) {
            if (statusEl) statusEl.textContent = data.message || 'Could not generate a description. Please try again.';
            return;
        }

        if (btn.dataset.ckeditor === '1' && window.CKEDITOR && CKEDITOR.instances[btn.dataset.field]) {
            CKEDITOR.instances[btn.dataset.field].setData(data.description);
        } else {
            const el = document.getElementById(btn.dataset.field) || form.elements[btn.dataset.field];
            if (el) el.value = data.description;
        }

        const metaFieldName = btn.dataset.metaField;
        if (metaFieldName && data.meta_description && form.elements[metaFieldName] && !form.elements[metaFieldName].value.trim()) {
            form.elements[metaFieldName].value = data.meta_description;
        }

        if (statusEl) statusEl.textContent = 'Description generated — feel free to edit it before saving.';
    })
    .catch(() => {
        if (statusEl) statusEl.textContent = 'Something went wrong. Please try again.';
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
    });
});
</script>
@endonce
