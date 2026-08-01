@extends('backend.layout')
@section('title', 'AI Chat Leads')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i class="fas fa-comment-dots me-2 text-primary"></i>AI Chat Assistant — Conversations</h4>
    <span class="badge bg-primary fs-6">{{ $sessions->total() }} total</span>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row g-3 mb-4">
    @php
        $statCards = [
            ['label'=>'Total Chats',    'value'=>$stats['total'],         'color'=>'primary', 'icon'=>'message-circle'],
            ['label'=>'Leads Captured', 'value'=>$stats['lead_captured'], 'color'=>'success',  'icon'=>'user-check'],
            ['label'=>'Today',          'value'=>$stats['today'],         'color'=>'info',     'icon'=>'calendar'],
        ];
    @endphp
    @foreach($statCards as $sc)
    <div class="col-6 col-sm-4">
        <div class="card border-0 shadow-sm text-center py-3">
            <div class="card-body p-2">
                <div class="fw-bold fs-4 text-{{ $sc['color'] }}">{{ $sc['value'] }}</div>
                <div class="text-muted small">{{ $sc['label'] }}</div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All</option>
                    @foreach(['open','lead-captured','closed'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucwords(str_replace('-', ' ', $s)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label mb-1 small fw-semibold">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Name, phone, email">
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary"><i class="fas fa-filter me-1"></i>Filter</button>
                <a href="{{ route('admin.ai-chat.index') }}" class="btn btn-sm btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3">Visitor</th>
                    <th>Property</th>
                    <th>Messages</th>
                    <th>Status</th>
                    <th>Last Activity</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sessions as $s)
                <tr>
                    <td class="ps-3">
                        <div class="fw-semibold">{{ $s->name ?: 'Anonymous visitor' }}</div>
                        <div class="small text-muted">
                            {{ $s->phone ?: '—' }} @if($s->email) &middot; {{ $s->email }} @endif
                        </div>
                    </td>
                    <td>{{ $s->property?->title ?? '—' }}</td>
                    <td>{{ $s->messages_count }}</td>
                    <td>
                        <span class="badge bg-{{ $s->status === 'lead-captured' ? 'success' : ($s->status === 'closed' ? 'secondary' : 'info') }}">
                            {{ ucwords(str_replace('-', ' ', $s->status)) }}
                        </span>
                    </td>
                    <td class="small text-muted">{{ $s->last_message_at?->diffForHumans() ?? $s->created_at->diffForHumans() }}</td>
                    <td class="text-end pe-3">
                        <a href="{{ route('admin.ai-chat.show', $s->id) }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('admin.ai-chat.destroy', $s->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this conversation?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">No AI chat conversations yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ $sessions->links() }}
    </div>
</div>

@endsection
