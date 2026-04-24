@extends('dealer.layout')
@section('title', 'My Inquiries')
@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3">My Enquiries</h1>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Message</th>
                            <th>Property ID</th>
                            <th>Property</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inquiries as $inq)
                        <tr>
                            <td>{{ $inq->id }}</td>
                            <td>{{ $inq->name }}</td>
                            <td>{{ $inq->email }}</td>
                            <td>{{ $inq->phone }}</td>
                            <td>{{ $inq->message }}</td>
                            <td>{{ $inq->property_id }}</td>
                            <td>
                                @if($inq->property)
                                    <a href="{{ route('property-details', $inq->property) }}" target="_blank">{{ $inq->property->title }}</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $inq->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No enquiries found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">{{ $inquiries->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
