@extends('dealer.layout')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>

<div class="row">
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Total Properties</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary">
                            <i class="align-middle" data-feather="home"></i>
                        </div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{ $totalProperties }}</h1>
                <div class="mb-0">
                    <a href="{{ route('dealer.properties.index') }}" class="text-muted">View all properties</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Active Properties</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-success">
                            <i class="align-middle" data-feather="check-circle"></i>
                        </div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{ $activeProperties }}</h1>
                <div class="mb-0">
                    <span class="text-muted">Currently available</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Total Inquiries</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-warning">
                            <i class="align-middle" data-feather="message-square"></i>
                        </div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{ $totalInquiries }}</h1>
                <div class="mb-0">
                    <a href="{{ route('dealer.inquiries.index') }}" class="text-muted">View all inquiries</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Total Views</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-info">
                            <i class="align-middle" data-feather="eye"></i>
                        </div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{ $totalViews ?? 0 }}</h1>
                <div class="mb-0">
                    <span class="text-muted">Property page views</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col mt-0">
                        <h5 class="card-title">Scheduled Viewings</h5>
                    </div>
                    <div class="col-auto">
                        <div class="stat text-primary">
                            <i class="align-middle" data-feather="calendar"></i>
                        </div>
                    </div>
                </div>
                <h1 class="mt-1 mb-3">{{ $totalViewings ?? 0 }}</h1>
                <div class="mb-0">
                    <a href="{{ route('dealer.schedule-viewings.index') }}" class="text-muted">View all viewings</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Recent Inquiries --}}
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Inquiries</h5>
            </div>
            <table class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Property</th>
                        <th>Phone</th>
                        <th class="d-none d-xl-table-cell">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentInquiries as $inquiry)
                        <tr>
                            <td>{{ $inquiry->name }}</td>
                            <td class="d-none d-md-table-cell">
                                @if($inquiry->property)
                                    <a href="{{ route('dealer.properties.show', $inquiry->property->slug) }}">{{ Str::limit($inquiry->property->title, 25) }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $inquiry->phone }}</td>
                            <td class="d-none d-xl-table-cell">{{ $inquiry->created_at->format('d M Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No inquiries yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($recentInquiries->count() > 0)
                <div class="card-footer text-end">
                    <a href="{{ route('dealer.inquiries.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            @endif
        </div>
    </div>

    {{-- Recent Viewings --}}
    <div class="col-12 col-lg-6 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
                <h5 class="card-title mb-0">Recent Schedule Viewings</h5>
            </div>
            <table class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="d-none d-md-table-cell">Property</th>
                        <th>Date</th>
                        <th class="d-none d-xl-table-cell">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentViewings as $viewing)
                        <tr>
                            <td>{{ $viewing->name }}</td>
                            <td class="d-none d-md-table-cell">
                                @if($viewing->property)
                                    <a href="{{ route('dealer.properties.show', $viewing->property->slug) }}">{{ Str::limit($viewing->property->title, 25) }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $viewing->date }}</td>
                            <td class="d-none d-xl-table-cell">{{ $viewing->time }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No viewings yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($recentViewings->count() > 0)
                <div class="card-footer text-end">
                    <a href="{{ route('dealer.schedule-viewings.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    {{-- Top Properties --}}
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Properties (by Views)</h5>
            </div>
            <table class="table table-hover my-0">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Price</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Views</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProperties as $property)
                        <tr>
                            <td>
                                <a href="{{ route('dealer.properties.show', $property->slug) }}">{{ Str::limit($property->title, 35) }}</a>
                            </td>
                            <td>{{ $property->property_type }}</td>
                            <td>{{ $property->price }}</td>
                            <td>{{ $property->city }}</td>
                            <td>
                                @if($property->status == 'Available')
                                    <span class="badge bg-success">{{ $property->status }}</span>
                                @elseif($property->status == 'Sold')
                                    <span class="badge bg-danger">{{ $property->status }}</span>
                                @else
                                    <span class="badge bg-warning">{{ $property->status ?? 'N/A' }}</span>
                                @endif
                            </td>
                            <td>{{ $property->views_count ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">No properties yet. <a href="{{ route('dealer.properties.create') }}">Add your first property</a></td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
