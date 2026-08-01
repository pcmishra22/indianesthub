@extends('backend.layout')
@section('title', 'AI Chat — ' . ($session->name ?: 'Anonymous'))
@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('admin.ai-chat.index') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-arrow-left"></i>
    </a>
    <h4 class="mb-0"><i class="fas fa-comment-dots me-2 text-primary"></i>Conversation #{{ $session->id }}</h4>
    <span class="badge bg-{{ $session->status === 'lead-captured' ? 'success' : ($session->status === 'closed' ? 'secondary' : 'info') }} ms-1 fs-6">
        {{ ucwords(str_replace('-', ' ', $session->status)) }}
    </span>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold bg-transparent"><i class="fas fa-user me-2 text-primary"></i>Visitor Details</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tbody>
                        <tr><td class="text-muted fw-medium ps-3" style="width:130px;">Name</td><td class="fw-semibold">{{ $session->name ?: '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Phone</td>
                            <td>
                                @if($session->phone)
                                    <a href="tel:{{ $session->phone }}" class="text-primary fw-semibold">{{ $session->phone }}</a>
                                    <a href="https://wa.me/91{{ preg_replace('/[^0-9]/', '', $session->phone) }}" target="_blank" class="btn btn-sm btn-success ms-2 py-0 px-2" style="font-size:0.72rem;">
                                        <i class="fab fa-whatsapp"></i>
                                    </a>
                                @else — @endif
                            </td>
                        </tr>
                        <tr><td class="text-muted fw-medium ps-3">Email</td><td>{{ $session->email ?: '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Property</td><td>{{ $session->property?->title ?? '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Source Page</td><td class="small text-truncate d-inline-block" style="max-width:200px;">{{ $session->source_page ?: '—' }}</td></tr>
                        <tr><td class="text-muted fw-medium ps-3">Started</td><td>{{ $session->created_at->format('d M Y, h:i A') }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header fw-semibold bg-transparent"><i class="fas fa-comments me-2 text-primary"></i>Transcript</div>
            <div class="card-body" style="max-height:600px; overflow-y:auto;">
                @forelse($session->messages as $m)
                    <div class="d-flex mb-3 {{ $m->role === 'user' ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="p-2 px-3 rounded-3 {{ $m->role === 'user' ? 'bg-primary text-white' : 'bg-light border' }}" style="max-width:75%;">
                            <div class="small">{{ $m->content }}</div>
                            <div class="small mt-1 {{ $m->role === 'user' ? 'text-white-50' : 'text-muted' }}" style="font-size:.68rem;">
                                {{ $m->role === 'user' ? 'Visitor' : 'AI Assistant' }} &middot; {{ $m->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-4">No messages.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
