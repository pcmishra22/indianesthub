@extends('backend.layout')
@section('title', 'Dealers')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h4 class="mb-0"><i class="fas fa-user-tie me-2 text-primary"></i>Dealers</h4>
    <a href="{{ route('admin.dealers.create') }}" class="btn btn-primary btn-sm">
        <i class="fas fa-plus me-1"></i> Add Dealer
    </a>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['total',    'Total',         'dark',      'fas fa-users'],
        ['active',   'Active',        'success',   'fas fa-check-circle'],
        ['inactive', 'Inactive',      'secondary', 'fas fa-pause-circle'],
        ['blocked',  'Blocked',       'danger',    'fas fa-ban'],
        ['with_sub', 'With Active Sub','primary',  'fas fa-crown'],
    ] as [$key, $label, $color, $icon])
    <div class="col-6 col-md">
        <div class="card border-0 shadow-sm text-center py-3"
             style="border-left:3px solid var(--bs-{{ $color }})!important;">
            <div class="fw-bold fs-4 text-{{ $color }}">{{ $stats[$key] }}</div>
            <div class="small text-muted"><i class="{{ $icon }} me-1"></i>{{ $label }}</div>
        </div>
    </div>
    @endforeach
</div>

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-md-2">
                <label class="form-label small mb-1">Status</label>
                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="active"   {{ request('status')==='active'   ? 'selected':'' }}>Active</option>
                    <option value="inactive" {{ request('status')==='inactive' ? 'selected':'' }}>Inactive</option>
                    <option value="blocked"  {{ request('status')==='blocked'  ? 'selected':'' }}>Blocked</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">Sort By</label>
                <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()">
                    <option value="newest"     {{ request('sort','newest')==='newest'     ? 'selected':'' }}>Newest First</option>
                    <option value="oldest"     {{ request('sort')==='oldest'     ? 'selected':'' }}>Oldest First</option>
                    <option value="name"       {{ request('sort')==='name'       ? 'selected':'' }}>Name A→Z</option>
                    <option value="properties" {{ request('sort')==='properties' ? 'selected':'' }}>Most Properties</option>
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control"
                           placeholder="Name, email, phone, company…"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if(request()->anyFilled(['status','sort','search']))
            <div class="col-md-2">
                <a href="{{ route('admin.dealers.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Dealer</th>
                        <th>Phone</th>
                        <th>Company</th>
                        <th>Properties</th>
                        <th>Subscription</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($dealers as $dealer)
                <tr>
                    <td class="text-muted">{{ $dealer->id }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            @if($dealer->profile_photo)
                            <img src="{{ asset('storage/'.$dealer->profile_photo) }}"
                                 class="rounded-circle" style="width:34px;height:34px;object-fit:cover;">
                            @else
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                                 style="width:34px;height:34px;font-size:.8rem;flex-shrink:0;">
                                {{ strtoupper(substr($dealer->first_name ?? 'D', 0, 1)) }}
                            </div>
                            @endif
                            <div>
                                <div class="fw-semibold">{{ $dealer->name }}</div>
                                <div class="text-muted small">{{ $dealer->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $dealer->phone ?? '—' }}</td>
                    <td>{{ $dealer->company_name ?? '—' }}</td>
                    <td>
                        <span class="fw-semibold">{{ $dealer->properties_count }}</span>
                        <span class="text-muted small">props</span>
                    </td>
                    <td>
                        @if($dealer->subscription)
                            @php $sub = $dealer->subscription; $sb = match($sub->status) { 'active'=>'success','expired'=>'danger',default=>'secondary' }; @endphp
                            <span class="badge bg-{{ $sb }}">{{ ucfirst($sub->plan) }}</span>
                            @if($sub->status === 'active')
                            <div class="text-muted" style="font-size:.75rem;">{{ $sub->daysLeft() }}d left</div>
                            @endif
                        @else
                            <span class="text-muted small">None</span>
                        @endif
                    </td>
                    <td>
                        @php $sc = match($dealer->status ?? 'active') { 'active'=>'success','blocked'=>'danger',default=>'secondary' }; @endphp
                        <span class="badge bg-{{ $sc }}">{{ ucfirst($dealer->status ?? 'active') }}</span>
                    </td>
                    <td class="text-muted small">{{ $dealer->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="{{ route('admin.dealers.show', $dealer) }}"
                               class="btn btn-sm btn-outline-primary" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.dealers.edit', $dealer) }}"
                               class="btn btn-sm btn-outline-secondary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.dealers.toggle-status', $dealer) }}" class="d-inline">
                                @csrf
                                <button type="submit"
                                        class="btn btn-sm {{ ($dealer->status ?? 'active') === 'blocked' ? 'btn-outline-success' : 'btn-outline-warning' }}"
                                        title="{{ ($dealer->status ?? 'active') === 'blocked' ? 'Unblock' : 'Block' }}"
                                        onclick="return confirm('{{ ($dealer->status ?? 'active') === 'blocked' ? 'Unblock' : 'Block' }} this dealer?')">
                                    <i class="fas {{ ($dealer->status ?? 'active') === 'blocked' ? 'fa-unlock' : 'fa-ban' }}"></i>
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.dealers.destroy', $dealer) }}" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete"
                                        onclick="return confirm('Delete dealer {{ addslashes($dealer->name) }}? This cannot be undone.')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">
                        <i class="fas fa-user-tie fa-2x mb-2 d-block opacity-25"></i>
                        No dealers found.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $dealers->links() }}</div>

@endsection
