@extends('backend.layout')
@section('title', 'Builder Leads')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i data-feather="user-plus" class="me-2 text-primary"></i>Builder Leads</h4>
    <span class="badge bg-primary fs-6">{{ $leads->total() }} total</span>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- Filter Bar --}}
<div class="card shadow-sm mb-3">
    <div class="card-body py-2">
        <form method="GET" class="row g-2 align-items-center">
            <div class="col-auto">
                <label class="form-label mb-0 small fw-semibold">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Statuses</option>
                    @foreach(['new','contacted','converted','lost'] as $s)
                        <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="form-label mb-0 small fw-semibold">Lead Type</label>
                <select name="lead_type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    @foreach(['general','visit','callback','brochure','whatsapp'] as $t)
                        <option value="{{ $t }}" {{ request('lead_type') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto align-self-end">
                <button class="btn btn-primary btn-sm px-3">Filter</button>
                <a href="{{ route('admin.builder-leads.index') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:0.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Project</th>
                        <th>Builder</th>
                        <th>Type</th>
                        <th>Source</th>
                        <th>IP</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($leads as $lead)
                    @php $ltColors = ['general'=>'primary','visit'=>'success','callback'=>'warning','brochure'=>'info','whatsapp'=>'secondary']; @endphp
                    <tr>
                        <td class="text-muted">{{ $lead->id }}</td>
                        <td class="fw-medium">{{ $lead->name }}</td>
                        <td>{{ $lead->phone }}</td>
                        <td>{{ $lead->email ?: '–' }}</td>
                        <td>
                            @if($lead->project)
                                <a href="{{ route('admin.builder-projects.show', $lead->builder_project_id) }}" class="text-decoration-none small">
                                    {{ \Str::limit($lead->project->title, 25) }}
                                </a>
                            @else – @endif
                        </td>
                        <td>
                            @if($lead->builder)
                                <a href="{{ route('admin.builders.show', $lead->builder_id) }}" class="text-decoration-none small text-muted">
                                    {{ \Str::limit($lead->builder->company_name ?? $lead->builder->name, 20) }}
                                </a>
                            @else – @endif
                        </td>
                        <td><span class="badge bg-{{ $ltColors[$lead->lead_type] ?? 'secondary' }}">{{ ucfirst($lead->lead_type) }}</span></td>
                        <td><span class="badge bg-light text-dark">{{ $lead->source }}</span></td>
                        <td><code style="font-size:0.72rem;">{{ $lead->ip_address ?: '–' }}</code></td>
                        <td>
                            {{-- Inline status update --}}
                            <form action="{{ route('admin.builder-leads.update-status', $lead->id) }}" method="POST" class="d-flex gap-1">
                                @csrf
                                <select name="status" class="form-select form-select-sm" style="width:110px;">
                                    @foreach(['new','contacted','converted','lost'] as $s)
                                        <option value="{{ $s }}" {{ $lead->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-secondary px-1" title="Save">✓</button>
                            </form>
                        </td>
                        <td class="text-muted small text-nowrap">{{ $lead->created_at?->format('d M Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('admin.builder-leads.show', $lead->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <form action="{{ route('admin.builder-leads.destroy', $lead->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Delete this lead?')">Del</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="12" class="text-center text-muted py-4">No builder leads found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($leads->hasPages())
    <div class="card-footer d-flex justify-content-between align-items-center">
        <small class="text-muted">Showing {{ $leads->firstItem() }}–{{ $leads->lastItem() }} of {{ $leads->total() }}</small>
        {{ $leads->appends(request()->query())->links() }}
    </div>
    @endif
</div>

@endsection
