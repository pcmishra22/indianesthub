@extends('builder.layout')

@section('title', $project->title)

@section('content')
<div class="container-fluid p-0">

    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between px-3 pt-3 mb-3 flex-wrap gap-2">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('builder.projects.index') }}" class="btn btn-outline-secondary btn-sm">
                <i data-feather="arrow-left" style="width:14px;height:14px;"></i> Projects
            </a>
            <div>
                <h1 class="h3 mb-0 fw-bold">{{ $project->title }}</h1>
                <small class="text-muted">{{ $project->city }}{{ $project->city && $project->state ? ', ' : '' }}{{ $project->state }}</small>
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('builder.projects.edit', $project) }}" class="btn btn-outline-secondary btn-sm">
                <i data-feather="edit-2" style="width:14px;height:14px;"></i> Edit Project
            </a>
            <a href="{{ route('builder.projects.properties.create', $project) }}" class="btn btn-primary btn-sm">
                <i data-feather="plus" style="width:14px;height:14px;"></i> Add Unit
            </a>
        </div>
    </div>

    <div class="px-3 pb-4">
        <div class="row g-3">

            {{-- Project Details --}}
            <div class="col-lg-8">

                {{-- Cover Image --}}
                @if($project->cover_image)
                <div class="card mb-3">
                    <img src="{{ asset('storage/' . $project->cover_image) }}" alt="{{ $project->title }}"
                         class="card-img-top" style="max-height:300px;object-fit:cover;">
                </div>
                @endif

                {{-- Stats Row --}}
                <div class="row g-3 mb-3">
                    <div class="col-sm-3">
                        <div class="card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-0 fw-bold text-primary">{{ $project->total_units ?? '—' }}</h4>
                                <small class="text-muted">Total Units</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-0 fw-bold text-success">{{ $project->available_units ?? '—' }}</h4>
                                <small class="text-muted">Available</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-0 fw-bold text-info">{{ $properties->total() }}</h4>
                                <small class="text-muted">Listed Units</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card text-center">
                            <div class="card-body py-3">
                                <span class="badge {{ $project->status_badge_class }}" style="font-size:.8rem;padding:.4em .75em;">
                                    {{ $project->status }}
                                </span>
                                <br><small class="text-muted mt-1 d-block">Status</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Properties / Units Table --}}
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Units / Flats in this Project</h5>
                        <a href="{{ route('builder.projects.properties.create', $project) }}" class="btn btn-sm btn-primary">
                            <i data-feather="plus" style="width:14px;height:14px;"></i> Add Unit
                        </a>
                    </div>
                    @if($properties->count())
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Unit / Title</th>
                                    <th>Type</th>
                                    <th>BHK</th>
                                    <th>Area</th>
                                    <th>Price</th>
                                    <th>Floor</th>
                                    <th>Furnishing</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($properties as $property)
                                <tr>
                                    <td>
                                        <strong>{{ Str::limit($property->title, 35) }}</strong>
                                    </td>
                                    <td>{{ $property->property_type ?? '—' }}</td>
                                    <td>{{ $property->bhk_type ?? '—' }}</td>
                                    <td>{{ $property->area ? $property->area . ' sqft' : '—' }}</td>
                                    <td>{{ $property->price ? '₹' . number_format($property->price) : '—' }}</td>
                                    <td>{{ $property->floor_number ?? '—' }}</td>
                                    <td>{{ $property->furnishing_status ?? '—' }}</td>
                                    <td>
                                        <span class="badge {{ $property->status === 'Available' ? 'bg-success' : ($property->status === 'Booked' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ $property->status ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('builder.projects.properties.edit', [$project, $property]) }}"
                                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                                <i data-feather="edit-2" style="width:13px;height:13px;"></i>
                                            </a>
                                            <a href="{{ route('builder.projects.properties.marketing', [$project, $property]) }}"
                                               class="btn btn-sm btn-outline-primary" title="Marketing Studio">
                                                <i data-feather="megaphone" style="width:13px;height:13px;"></i>
                                            </a>
                                            <form action="{{ route('builder.projects.properties.destroy', [$project, $property]) }}"
                                                  method="POST" onsubmit="return confirm('Delete this unit?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i data-feather="trash-2" style="width:13px;height:13px;"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($properties->hasPages())
                    <div class="card-footer">{{ $properties->links() }}</div>
                    @endif
                    @else
                    <div class="card-body text-center py-4 text-muted">
                        <i data-feather="home" style="width:36px;height:36px;color:#dee2e6;"></i>
                        <p class="mt-2 mb-0">No units listed yet.</p>
                        <a href="{{ route('builder.projects.properties.create', $project) }}" class="btn btn-primary mt-3">
                            Add First Unit
                        </a>
                    </div>
                    @endif
                </div>

                {{-- Gallery --}}
                @if($project->gallery_images && count($project->gallery_images))
                <div class="card mt-3">
                    <div class="card-header"><h5 class="card-title mb-0">Project Gallery</h5></div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($project->gallery_images as $img)
                            <div class="col-6 col-md-4 col-lg-3">
                                <img src="{{ asset('storage/' . $img) }}" alt="Gallery"
                                     class="img-fluid rounded" style="height:100px;width:100%;object-fit:cover;">
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>

            {{-- Project Details Sidebar --}}
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Project Details</h5></div>
                    <div class="card-body">
                        <table class="table table-sm mb-0">
                            <tr>
                                <td class="text-muted">Type</td>
                                <td><strong>{{ $project->project_type }}</strong></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Status</td>
                                <td><span class="badge {{ $project->status_badge_class }}">{{ $project->status }}</span></td>
                            </tr>
                            @if($project->rera_id)
                            <tr>
                                <td class="text-muted">RERA ID</td>
                                <td><strong>{{ $project->rera_id }}</strong></td>
                            </tr>
                            @endif
                            @if($project->possession_date)
                            <tr>
                                <td class="text-muted">Possession</td>
                                <td><strong>{{ $project->possession_date->format('M Y') }}</strong></td>
                            </tr>
                            @endif
                            @if($project->price_from || $project->price_to)
                            <tr>
                                <td class="text-muted">Price Range</td>
                                <td>
                                    <strong>
                                        @if($project->price_from) ₹{{ number_format($project->price_from) }} @endif
                                        @if($project->price_from && $project->price_to) – @endif
                                        @if($project->price_to) ₹{{ number_format($project->price_to) }} @endif
                                    </strong>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td class="text-muted">Added</td>
                                <td>{{ $project->created_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($project->amenities)
                <div class="card mt-3">
                    <div class="card-header"><h5 class="card-title mb-0">Amenities</h5></div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-1">
                            @foreach(preg_split('/[,\n]+/', $project->amenities) as $amenity)
                                @if(trim($amenity))
                                <span class="badge bg-light text-dark border">{{ trim($amenity) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                @if($project->description)
                <div class="card mt-3">
                    <div class="card-header"><h5 class="card-title mb-0">About this Project</h5></div>
                    <div class="card-body">
                        <p class="mb-0" style="font-size:.9rem;line-height:1.6;">{{ $project->description }}</p>
                    </div>
                </div>
                @endif

                <div class="card mt-3">
                    <div class="card-body d-flex flex-column gap-2">
                        <a href="{{ route('builder.projects.edit', $project) }}" class="btn btn-outline-primary w-100">
                            <i data-feather="edit-2" style="width:14px;height:14px;"></i> Edit Project
                        </a>
                        <form action="{{ route('builder.projects.destroy', $project) }}" method="POST"
                              onsubmit="return confirm('Delete this project and all its units? This cannot be undone.')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger w-100">
                                <i data-feather="trash-2" style="width:14px;height:14px;"></i> Delete Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
