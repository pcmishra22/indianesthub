@extends('backend.layout')

@section('title', 'Bulk Email Campaigns')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Bulk Email Campaigns</h1>
    <a href="{{ route('admin.dealers.bulk-email.create') }}" class="btn btn-primary">Create New Bulk Email</a>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Created At</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($emails as $email)
                        <tr>
                            <td>{{ $email->id }}</td>
                            <td>{{ $email->subject }}</td>
                            <td>
                                @if($email->status === 'queued')
                                    <span class="badge bg-success">Queued</span>
                                @else
                                    <span class="badge bg-secondary">Saved</span>
                                @endif
                            </td>
                            <td>{{ $email->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if($email->status !== 'queued')
                                        <form action="{{ route('admin.dealers.bulk-email.queue', $email->id) }}" method="POST"
                                              onsubmit="return confirm('Are you sure you want to send this bulk email to all active dealers?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Send to Dealer</button>
                                        </form>

                                        <a href="{{ route('admin.dealers.bulk-email.edit', $email->id) }}" class="btn btn-sm btn-warning">Edit Draft</a>
                                    @endif

                                    <form action="{{ route('admin.dealers.bulk-email.destroy', $email->id) }}" method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this email draft?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No bulk email campaigns found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $emails->links() }}
        </div>
    </div>
</div>
@endsection