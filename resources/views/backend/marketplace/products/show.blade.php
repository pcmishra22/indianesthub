@extends('backend.layout')
@section('title', $product->name)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
    <h4 class="mb-0"><i class="fas fa-box me-2 text-primary"></i>{{ $product->name }}</h4>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.marketplace.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            @if($product->cover_image)
                <img src="{{ asset('storage/'.$product->cover_image) }}" class="card-img-top" alt="{{ $product->name }}" style="max-height:340px;object-fit:cover;">
            @endif
            <div class="card-body">
                <div class="mb-2">
                    <span class="badge bg-info-subtle text-info">{{ $product->category?->name }}</span>
                    <span class="badge bg-light text-dark">{{ $product->price_label }}</span>
                    @if($product->is_featured)<span class="badge bg-warning text-dark">Featured</span>@endif
                    @if(!$product->is_active)<span class="badge bg-secondary">Inactive</span>@endif
                </div>
                <h5 class="mb-1">Vendor: <a href="{{ route('admin.marketplace.vendors.show', $product->vendor) }}">{{ $product->vendor?->business_name }}</a></h5>
                @if($product->description)
                    <p class="text-muted mb-2">{{ $product->description }}</p>
                @endif
                @if($product->bhk_fit)
                    <p class="mb-1"><strong>Fits:</strong>
                        @foreach($product->bhk_fit as $b)
                            <span class="badge bg-primary-subtle text-primary">{{ $b }}BHK</span>
                        @endforeach
                    </p>
                @endif
                @if($product->tags)
                    <p class="mb-0"><strong>Tags:</strong>
                        @foreach((array) $product->tags as $t)
                            <span class="badge bg-light text-dark">{{ $t }}</span>
                        @endforeach
                    </p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3 text-center mb-3">
            <div class="fw-bold fs-3 text-primary">{{ $product->leads_count }}</div>
            <div class="small text-muted">Leads from property pages</div>
        </div>
        <div class="card border-0 shadow-sm p-3 text-center">
            <div class="fw-bold fs-3 text-info">{{ $product->images->count() }}</div>
            <div class="small text-muted">Gallery images</div>
        </div>
    </div>
</div>

@if($product->images->count())
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><strong>Gallery</strong></div>
    <div class="card-body">
        <div class="row g-2">
            @foreach($product->images as $img)
                <div class="col-6 col-md-3">
                    <img src="{{ asset('storage/'.$img->image_path) }}" class="img-fluid rounded" alt="">
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection
