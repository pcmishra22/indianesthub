@extends('backend.layout')
@section('title', 'Viewing Request #' . $scheduleViewing->id)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <a href="{{ route('admin.schedule-viewings.index') }}" class="btn btn-sm btn-light me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fs-5 fw-bold"><i class="fas fa-calendar-check me-2 text-primary"></i>Viewing Request #{{ $scheduleViewing->id }}</span>
    </div>
    <span class="badge bg-{{ $scheduleViewing->statusBadge() }} px-3 py-2" style="font-size:.85rem;">
        {{ \App\Models\ScheduleViewing::statusOptions()[$scheduleViewing->status] ?? $scheduleViewing->status }}
    </span>
</div>

<div class="row g-4">

    {{-- Left: Details --}}
    <div class="col-lg-8">

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-user me-2 text-primary"></i>Visitor Details
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                    <tr><td class="text-muted" width="170">Name</td><td class="fw-semibold">{{ $scheduleViewing->name }}</td></tr>
                    <tr><td class="text-muted">Email</td><td><a href="mailto:{{ $scheduleViewing->email }}">{{ $scheduleViewing->email }}</a></td></tr>
                    <tr><td class="text-muted">Phone</td>
                        <td>
                            @if($scheduleViewing->phone)
                            <a href="tel:{{ $scheduleViewing->phone }}" class="fw-semibold text-dark">{{ $scheduleViewing->phone }}</a>
                            <a href="https://wa.me/91{{ preg_replace('/[^0-9]/','',$scheduleViewing->phone) }}"
                               target="_blank" class="btn btn-sm btn-success ms-2 py-0 px-2" style="font-size:.72rem;">
                                <i class="bi bi-whatsapp"></i> WhatsApp
                            </a>
                            @else —
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted">Message</td><td>{{ $scheduleViewing->message ?? '—' }}</td></tr>
                    <tr><td class="text-muted">Submitted</td><td>{{ $scheduleViewing->created_at->format('d M Y, h:i A') }}</td></tr>
                </table>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-calendar me-2 text-info"></i>Visit Details
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                    <tr><td class="text-muted" width="170">Preferred Date</td>
                        <td class="fw-semibold fs-6">{{ $scheduleViewing->date ? $scheduleViewing->date->format('l, d M Y') : '—' }}</td></tr>
                    <tr><td class="text-muted">Preferred Time</td>
                        <td class="fw-semibold">{{ $scheduleViewing->time }}</td></tr>
                    <tr><td class="text-muted">Property</td>
                        <td>
                            @if($scheduleViewing->property)
                            <a href="{{ route('admin.properties.show', $scheduleViewing->property) }}" class="text-primary" target="_blank">
                                {{ $scheduleViewing->property->title }}
                            </a>
                            @else Property #{{ $scheduleViewing->property_id }}
                            @endif
                        </td>
                    </tr>
                    <tr><td class="text-muted">Dealer</td>
                        <td>
                            @if($scheduleViewing->dealer)
                            <div>{{ $scheduleViewing->dealer->name }}</div>
                            <div class="text-muted small">{{ $scheduleViewing->dealer->phone }} · {{ $scheduleViewing->dealer->email }}</div>
                            @else —
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
        </div>

    </div>

    {{-- Right: Actions --}}
    <div class="col-lg-4">

        {{-- Status Update --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-edit me-2 text-primary"></i>Update Status
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.schedule-viewings.update-status', $scheduleViewing) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Status</label>
                        <select name="status" class="form-select form-select-sm">
                            @foreach(\App\Models\ScheduleViewing::statusOptions() as $val => $lbl)
                            <option value="{{ $val }}" {{ $scheduleViewing->status === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Admin Notes</label>
                        <textarea name="admin_notes" class="form-control form-control-sm" rows="3"
                                  placeholder="e.g. Called visitor, confirmed for 2 PM...">{{ $scheduleViewing->admin_notes }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-save me-1"></i> Save
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
            </div>
            <div class="card-body d-grid gap-2">
                @if($scheduleViewing->phone)
                <a href="tel:{{ $scheduleViewing->phone }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-phone me-2"></i>Call {{ $scheduleViewing->name }}
                </a>
                <a href="https://wa.me/91{{ preg_replace('/[^0-9]/','',$scheduleViewing->phone) }}?text={{ urlencode('Hi '.$scheduleViewing->name.'! This is regarding your property visit request scheduled for '.($scheduleViewing->date?->format('d M Y')).' at '.$scheduleViewing->time.'. We have confirmed your visit.') }}"
                   target="_blank" class="btn btn-outline-success btn-sm">
                    <i class="bi bi-whatsapp me-2"></i>WhatsApp Visitor
                </a>
                @endif
                @if($scheduleViewing->dealer && $scheduleViewing->dealer->phone)
                <a href="tel:{{ $scheduleViewing->dealer->phone }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-phone me-2"></i>Call Dealer
                </a>
                @endif
            </div>
        </div>

        {{-- Delete --}}
        <div class="card border-0 shadow-sm" style="border-top:3px solid #ef4444!important;">
            <div class="card-body py-3">
                <form method="POST" action="{{ route('admin.schedule-viewings.destroy', $scheduleViewing) }}"
                      onsubmit="return confirm('Delete this viewing request?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i> Delete
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
