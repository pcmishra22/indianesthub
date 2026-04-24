@extends('backend.layout')
@section('title', 'Schedule Viewings')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-3">
    <h4 class="mb-0"><i class="fas fa-calendar-check me-2 text-primary"></i>Schedule Viewings</h4>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['total',     'Total',     'dark',    'fas fa-calendar-alt'],
        ['today',     'Today',     'primary', 'fas fa-calendar-day'],
        ['pending',   'Pending',   'warning', 'fas fa-clock'],
        ['confirmed', 'Confirmed', 'info',    'fas fa-check-circle'],
        ['completed', 'Completed', 'success', 'fas fa-flag-checkered'],
        ['cancelled', 'Cancelled', 'danger',  'fas fa-times-circle'],
    ] as [$key, $label, $color, $icon])
    <div class="col-6 col-md-2">
        <div class="card border-0 shadow-sm text-center py-3" style="border-left:3px solid var(--bs-{{ $color }})!important;">
            <div class="fw-bold fs-4 text-{{ $color }}">{{ $stats[$key] }}</div>
            <div class="small text-muted">{{ $label }}</div>
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
                    @foreach(\App\Models\ScheduleViewing::statusOptions() as $val => $label)
                    <option value="{{ $val }}" {{ request('status') === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">From Date</label>
                <input type="date" name="from_date" class="form-control form-control-sm" value="{{ request('from_date') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small mb-1">To Date</label>
                <input type="date" name="to_date" class="form-control form-control-sm" value="{{ request('to_date') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label small mb-1">Search</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="search" class="form-control" placeholder="Name / Email / Phone"
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            @if(request()->anyFilled(['status','from_date','to_date','search']))
            <div class="col-md-2">
                <a href="{{ route('admin.schedule-viewings.index') }}" class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-times me-1"></i> Clear
                </a>
            </div>
            @endif
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" style="font-size:.85rem;">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Visitor</th>
                        <th>Property</th>
                        <th>Dealer</th>
                        <th>Visit Date & Time</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($viewings as $v)
                <tr>
                    <td class="text-muted">{{ $v->id }}</td>
                    <td>
                        <div class="fw-semibold">{{ $v->name }}</div>
                        <div class="text-muted small">{{ $v->email }}</div>
                        @if($v->phone)<div class="text-muted small">{{ $v->phone }}</div>@endif
                    </td>
                    <td>
                        @if($v->property)
                        <a href="{{ route('admin.properties.show', $v->property) }}" class="text-primary small fw-semibold" target="_blank">
                            {{ Str::limit($v->property->title, 35) }}
                        </a>
                        @else
                        <span class="text-muted small">Property #{{ $v->property_id }}</span>
                        @endif
                    </td>
                    <td>
                        @if($v->dealer)
                        <div class="small">{{ $v->dealer->name }}</div>
                        <div class="text-muted" style="font-size:.7rem;">{{ $v->dealer->phone }}</div>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        <div class="fw-semibold">{{ $v->date ? $v->date->format('d M Y') : '—' }}</div>
                        <div class="text-muted small">{{ $v->time }}</div>
                    </td>
                    <td>
                        <form method="POST" action="{{ route('admin.schedule-viewings.update-status', $v) }}">
                            @csrf
                            <select name="status" class="form-select form-select-sm"
                                    style="min-width:115px;font-size:.78rem;"
                                    onchange="this.form.submit()">
                                @foreach(\App\Models\ScheduleViewing::statusOptions() as $val => $lbl)
                                <option value="{{ $val }}" {{ $v->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                @endforeach
                            </select>
                        </form>
                    </td>
                    <td class="text-muted small">{{ $v->created_at->format('d M Y') }}<br>{{ $v->created_at->format('h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.schedule-viewings.show', $v) }}"
                           class="btn btn-sm btn-outline-primary me-1">View</a>
                        <form method="POST" action="{{ route('admin.schedule-viewings.destroy', $v) }}"
                              onsubmit="return confirm('Delete this viewing request?')" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Del</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-calendar-times fa-2x mb-2 d-block opacity-25"></i>
                        No viewing requests found.
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">{{ $viewings->links() }}</div>

@endsection
