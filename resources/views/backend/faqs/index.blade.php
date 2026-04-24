@extends('backend.layout')
@section('title', 'FAQs')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="help-circle" class="me-2 text-primary"></i>FAQs</h4>
    <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary btn-sm">
        <i data-feather="plus" style="width:14px;height:14px;" class="me-1"></i> Add FAQ
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Question</th>
                        <th>Answer</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th style="width:130px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($faqs as $faq)
                    <tr>
                        <td class="text-muted small">{{ $faq->id }}</td>
                        <td class="fw-medium">{{ \Str::limit($faq->question, 60) }}</td>
                        <td class="text-muted small">{{ \Str::limit($faq->answer, 80) }}</td>
                        <td>
                            @if(($faq->status ?? 'active') === 'active')
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $faq->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.faqs.edit', $faq->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this FAQ?')">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No FAQs yet. <a href="{{ route('admin.faqs.create') }}">Add one</a>.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($faqs->hasPages())
    <div class="card-footer">{{ $faqs->links() }}</div>
    @endif
</div>

@endsection
