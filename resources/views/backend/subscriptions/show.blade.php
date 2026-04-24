@extends('backend.layout')
@section('title', 'Subscription #' . $subscription->id)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-sm btn-light me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fs-5 fw-bold"><i class="fas fa-crown me-2 text-warning"></i>Subscription #{{ $subscription->id }}</span>
    </div>
    @php
        $badge = match($subscription->status) { 'active'=>'success','expired'=>'danger',default=>'secondary' };
    @endphp
    <span class="badge bg-{{ $badge }} px-3 py-2" style="font-size:.85rem;">{{ ucfirst($subscription->status) }}</span>
</div>

<div class="row g-4">
    {{-- Left: Subscription Details --}}
    <div class="col-lg-8">

        {{-- Dealer Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-user-tie me-2 text-primary"></i>Dealer
            </div>
            <div class="card-body">
                @if($subscription->dealer)
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                         style="width:48px;height:48px;font-size:1.2rem;">
                        {{ strtoupper(substr($subscription->dealer->name ?? 'D', 0, 1)) }}
                    </div>
                    <div>
                        <div class="fw-semibold fs-6">{{ $subscription->dealer->name }}</div>
                        <div class="text-muted small">{{ $subscription->dealer->email }}</div>
                        @if($subscription->dealer->phone)
                        <div class="text-muted small"><i class="fas fa-phone me-1"></i>{{ $subscription->dealer->phone }}</div>
                        @endif
                    </div>
                </div>
                @else
                <span class="text-muted">Dealer #{{ $subscription->property_dealer_id }}</span>
                @endif
            </div>
        </div>

        {{-- Plan Details --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-crown me-2 text-warning"></i>Plan Details
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="p-3 rounded text-center" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div class="text-muted small mb-1">Plan</div>
                            <span class="badge bg-{{ $subscription->planBadge() }} px-3 py-2" style="font-size:.9rem;">
                                {{ ucfirst($subscription->plan) }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded text-center" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div class="text-muted small mb-1">Price</div>
                            <div class="fw-bold fs-5 text-success">₹{{ number_format($subscription->price) }}</div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3 rounded text-center" style="background:#f8fafc;border:1px solid #e2e8f0;">
                            <div class="text-muted small mb-1">Status</div>
                            <span class="badge bg-{{ $badge }} px-3 py-2" style="font-size:.9rem;">
                                {{ ucfirst($subscription->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <hr class="my-3">

                <table class="table table-sm table-borderless mb-0" style="font-size:.9rem;">
                    <tr>
                        <td class="text-muted" width="180">Property Limit</td>
                        <td class="fw-semibold"><i class="fas fa-home text-muted me-1"></i>{{ $subscription->property_limit }} properties</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Featured Limit</td>
                        <td class="fw-semibold"><i class="fas fa-star text-warning me-1"></i>{{ $subscription->featured_limit }} featured</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Priority Support</td>
                        <td>
                            @if($subscription->priority_support)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Analytics Access</td>
                        <td>
                            @if($subscription->analytics_access)
                                <span class="badge bg-success">Yes</span>
                            @else
                                <span class="badge bg-secondary">No</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Start Date</td>
                        <td>{{ $subscription->start_date?->format('d M Y') ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">End Date</td>
                        <td>{{ $subscription->end_date?->format('d M Y') ?? '—' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Created</td>
                        <td>{{ $subscription->created_at->format('d M Y, h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Last Updated</td>
                        <td>{{ $subscription->updated_at->format('d M Y, h:i A') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Dealer's Properties --}}
        @if($subscription->dealer && $subscription->dealer->properties()->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-home me-2 text-info"></i>Dealer's Properties
                <span class="badge bg-secondary ms-2">{{ $subscription->dealer->properties()->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" style="font-size:.82rem;">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Type</th>
                                <th>City</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($subscription->dealer->properties()->latest()->take(10)->get() as $prop)
                        <tr>
                            <td>
                                <a href="{{ route('admin.properties.show', $prop) }}" class="text-primary" target="_blank">
                                    {{ Str::limit($prop->title, 40) }}
                                </a>
                            </td>
                            <td>{{ ucfirst($prop->property_type ?? '—') }}</td>
                            <td>{{ $prop->city ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $prop->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($prop->status ?? 'unknown') }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>

    {{-- Right: Actions --}}
    <div class="col-lg-4">

        {{-- Days Left Card --}}
        @if($subscription->status === 'active')
        @php $days = $subscription->daysLeft(); @endphp
        <div class="card border-0 shadow-sm mb-3"
             style="border-top:3px solid {{ $days <= 7 ? '#ef4444' : ($days <= 30 ? '#f59e0b' : '#16a34a') }}!important;">
            <div class="card-body text-center py-4">
                <div class="display-5 fw-bold {{ $days <= 7 ? 'text-danger' : ($days <= 30 ? 'text-warning' : 'text-success') }}">
                    {{ $days }}
                </div>
                <div class="text-muted">days remaining</div>
                <div class="small text-muted mt-1">Expires {{ $subscription->end_date?->format('d M Y') }}</div>
            </div>
        </div>
        @endif

        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.subscriptions.edit', $subscription) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Subscription
                </a>
                @if($subscription->dealer)
                <a href="mailto:{{ $subscription->dealer->email }}" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2"></i>Email Dealer
                </a>
                @if($subscription->dealer->phone)
                <a href="https://wa.me/91{{ preg_replace('/\D/', '', $subscription->dealer->phone) }}" target="_blank"
                   class="btn btn-outline-success">
                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                </a>
                @endif
                @endif
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-0 shadow-sm" style="border-top:3px solid #ef4444!important;">
            <div class="card-body py-3">
                <form method="POST" action="{{ route('admin.subscriptions.destroy', $subscription) }}"
                      onsubmit="return confirm('Delete this subscription permanently? This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i> Delete Subscription
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
