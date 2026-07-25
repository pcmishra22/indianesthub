@extends('dealer.layout')

@section('title', 'My Properties')

@section('content')
<div class="container-fluid p-0">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0"><strong>My</strong> Properties</h1>
        <a href="{{ route('dealer.properties.create') }}" class="btn btn-primary">
            <i class="align-middle" data-feather="plus"></i> Add Property
        </a>
    </div>

    @if(request()->boolean('marketing'))
        <div class="alert alert-primary d-flex align-items-center gap-2" role="alert">
            <i data-feather="volume-2" style="width:18px;height:18px;flex-shrink:0;"></i>
            <div>Pick a property below and click its <strong>Marketing Studio</strong> icon (<i data-feather="volume-2" style="width:14px;height:14px;"></i>) in the Actions column to generate a brochure or share it on WhatsApp.</div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Property List</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle mb-0">
<thead>
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>Listing Status</th>
                        <th>Payment</th>
                        <th>Price</th>
                        <th>City</th>
                        <th>State</th>
                        <th>Country</th>
                        <th>Pincode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($properties as $property)
                        <tr>
                            <td>{{ $property->title }}</td>
                            <td>{{ $property->property_type }}</td>
                            <td>
                                @if($property->listing_status)
                                    <span class="badge bg-{{ $property->listing_status == 'active' ? 'success' : ($property->listing_status == 'inactive' ? 'secondary' : 'warning') }}">
                                        {{ ucfirst($property->listing_status) }}
                                    </span>
                                @else
                                    <span class="badge bg-secondary">Unknown</span>
                                @endif
                            </td>
                            <td>
                                @if($property->is_paid)
                                    <span class="badge bg-success">Paid</span>
                                @else
                                    <span class="badge bg-danger">Unpaid</span>
                                    <form action="{{ route('dealer.properties.pay', $property->slug) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-link p-0 text-primary" title="Pay Now"><i class="align-middle" data-feather="credit-card"></i></button>
                                    </form>
                                @endif
                            </td>
                            <td>{{ $property->price }}</td>
                            <td>{{ $property->city }}</td>
                            <td>{{ $property->state }}</td>
                            <td>{{ $property->country }}</td>
                            <td>{{ $property->pincode }}</td>
                            <td class="table-action">
                                <a href="{{ route('dealer.properties.show', $property->slug) }}" class="text-primary me-2" title="View"><i class="align-middle" data-feather="eye"></i></a>
                                <a href="{{ route('dealer.properties.edit', $property->slug) }}" class="text-warning me-2" title="Edit"><i class="align-middle" data-feather="edit-2"></i></a>
                                <a href="{{ route('dealer.properties.marketing', $property->slug) }}" class="text-primary me-2" title="Marketing Studio"><i class="align-middle" data-feather="volume-2"></i></a>
                                <form method="POST" action="{{ route('dealer.properties.destroy', $property->slug) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-link p-0 text-danger" onclick="return confirm('Are you sure?')" title="Delete"><i class="align-middle" data-feather="trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center text-muted">No properties found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
