@extends('backend.layout')

@section('title', 'User Bulk Emails')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>User Bulk Emails</h1>
    <a href="{{ route('admin.users.bulk-email.create') }}" class="btn btn-primary">Create New Bulk Email</a>
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
                                    <span class="badge bg-secondary">Draft</span>
                                @endif
                            </td>
                            <td>{{ $email->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if($email->status !== 'queued')
                                        <form action="{{ route('admin.users.bulk-email.queue', $email->id) }}" method="POST"
                                              onsubmit="return confirm('Send this email to all users?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">Send to Users</button>
                                        </form>
                                        <a href="{{ route('admin.users.bulk-email.edit', $email->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                    @endif
                                    <form action="{{ route('admin.users.bulk-email.destroy', $email->id) }}" method="POST"
                                          onsubmit="return confirm('Delete this draft?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No email drafts found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $emails->links() }}</div>
    </div>
</div>
@endsection