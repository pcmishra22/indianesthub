@extends('backend.layout')
@section('title', 'Dealer — ' . $dealer->name)
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <a href="{{ route('admin.dealers.index') }}" class="btn btn-sm btn-light me-2">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
        <span class="fs-5 fw-bold">
            <i class="fas fa-user-tie me-2 text-primary"></i>{{ $dealer->name }}
        </span>
    </div>
    @php $sc = match($dealer->status ?? 'active') { 'active'=>'success','blocked'=>'danger',default=>'secondary' }; @endphp
    <span class="badge bg-{{ $sc }} px-3 py-2" style="font-size:.85rem;">{{ ucfirst($dealer->status ?? 'active') }}</span>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-3">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="row g-4">

    {{-- Left: Details --}}
    <div class="col-lg-8">

        {{-- Profile Card --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center gap-4 mb-4">
                    @if($dealer->profile_photo)
                    <img src="{{ asset('storage/'.$dealer->profile_photo) }}"
                         class="rounded-circle border" style="width:72px;height:72px;object-fit:cover;">
                    @else
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center fw-bold"
                         style="width:72px;height:72px;font-size:1.6rem;flex-shrink:0;">
                        {{ strtoupper(substr($dealer->first_name ?? 'D', 0, 1)) }}
                    </div>
                    @endif
                    <div>
                        <h5 class="mb-0">{{ $dealer->name }}</h5>
                        <div class="text-muted">{{ $dealer->company_name ?? 'Independent Dealer' }}</div>
                        <div class="text-muted small">Member since {{ $dealer->created_at->format('M Y') }}</div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                            <tr>
                                <td class="text-muted" width="130">First Name</td>
                                <td class="fw-semibold">{{ $dealer->first_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Last Name</td>
                                <td class="fw-semibold">{{ $dealer->last_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Email</td>
                                <td>
                                    <a href="mailto:{{ $dealer->email }}">{{ $dealer->email }}</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="text-muted">Phone</td>
                                <td>{{ $dealer->phone ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Company</td>
                                <td>{{ $dealer->company_name ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless mb-0" style="font-size:.88rem;">
                            <tr>
                                <td class="text-muted" width="130">Status</td>
                                <td><span class="badge bg-{{ $sc }}">{{ ucfirst($dealer->status ?? 'active') }}</span></td>
                            </tr>
                            <tr>
                                <td class="text-muted">Slug</td>
                                <td class="text-muted small">{{ $dealer->slug }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Joined</td>
                                <td>{{ $dealer->created_at->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Updated</td>
                                <td>{{ $dealer->updated_at->format('d M Y') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                @if($dealer->bio)
                <hr>
                <div>
                    <div class="text-muted small fw-semibold mb-1">Bio</div>
                    <p class="mb-0 small" style="line-height:1.7;">{{ $dealer->bio }}</p>
                </div>
                @endif

                @if(!empty($dealer->specializations))
                <hr>
                <div>
                    <div class="text-muted small fw-semibold mb-2">Specializations</div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($dealer->specializations as $spec)
                        <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1" style="font-size:.8rem;">{{ $spec }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($dealer->operating_cities))
                <hr>
                <div>
                    <div class="text-muted small fw-semibold mb-2">Operating Cities</div>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($dealer->operating_cities as $city)
                        <span class="badge bg-success bg-opacity-10 text-success px-2 py-1" style="font-size:.8rem;">
                            <i class="fas fa-map-marker-alt me-1"></i>{{ $city }}
                        </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Properties --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex align-items-center justify-content-between border-0 pb-0">
                <div class="fw-semibold">
                    <i class="fas fa-home me-2 text-info"></i>Properties
                </div>
                <div class="d-flex gap-2 small">
                    <span class="badge bg-success bg-opacity-10 text-success">{{ $propStats['active'] }} active</span>
                    <span class="badge bg-secondary bg-opacity-10 text-secondary">{{ $propStats['inactive'] }} other</span>
                    <span class="badge bg-dark bg-opacity-10 text-dark">{{ $propStats['total'] }} total</span>
                </div>
            </div>
            <div class="card-body p-0">
                @if($properties->count())
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0 align-middle" style="font-size:.82rem;">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>City</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Added</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($properties as $prop)
                        <tr>
                            <td class="text-muted">{{ $prop->id }}</td>
                            <td>
                                <a href="{{ route('admin.properties.show', $prop) }}" class="text-primary" target="_blank">
                                    {{ Str::limit($prop->title, 45) }}
                                </a>
                            </td>
                            <td>{{ ucfirst($prop->property_type ?? '—') }}</td>
                            <td>{{ $prop->city ?? '—' }}</td>
                            <td class="fw-semibold">
                                @if($prop->price)
                                ₹{{ number_format($prop->price) }}
                                @else —
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $prop->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($prop->status ?? 'unknown') }}
                                </span>
                            </td>
                            <td class="text-muted">{{ $prop->created_at->format('d M Y') }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-3">{{ $properties->links() }}</div>
                @else
                <div class="text-center text-muted py-4 small">No properties listed yet.</div>
                @endif
            </div>
        </div>

    </div>

    {{-- Right: Sidebar --}}
    <div class="col-lg-4">

        {{-- Subscription --}}
        <div class="card border-0 shadow-sm mb-3"
             style="border-top:3px solid {{ $dealer->subscription && $dealer->subscription->status === 'active' ? '#16a34a' : '#94a3b8' }}!important;">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-crown me-2 text-warning"></i>Subscription
            </div>
            <div class="card-body">
                @if($dealer->subscription)
                @php $sub = $dealer->subscription; $sb = match($sub->status) { 'active'=>'success','expired'=>'danger',default=>'secondary' }; @endphp
                <table class="table table-sm table-borderless mb-2" style="font-size:.85rem;">
                    <tr>
                        <td class="text-muted">Plan</td>
                        <td><span class="badge bg-{{ $sub->planBadge() }} px-2">{{ ucfirst($sub->plan) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Status</td>
                        <td><span class="badge bg-{{ $sb }}">{{ ucfirst($sub->status) }}</span></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Price</td>
                        <td class="fw-semibold">₹{{ number_format($sub->price) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Expires</td>
                        <td>{{ $sub->end_date?->format('d M Y') ?? '—' }}</td>
                    </tr>
                    @if($sub->status === 'active')
                    <tr>
                        <td class="text-muted">Days Left</td>
                        <td>
                            @php $days = $sub->daysLeft(); @endphp
                            <span class="fw-semibold {{ $days <= 7 ? 'text-danger' : ($days <= 30 ? 'text-warning' : 'text-success') }}">
                                {{ $days }} days
                            </span>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td class="text-muted">Props Limit</td>
                        <td>{{ $sub->property_limit }}</td>
                    </tr>
                </table>
                <a href="{{ route('admin.subscriptions.edit', $sub) }}"
                   class="btn btn-sm btn-outline-primary w-100">
                    <i class="fas fa-edit me-1"></i> Manage Subscription
                </a>
                @else
                <div class="text-center py-2 text-muted small">No subscription</div>
                <a href="{{ route('admin.subscriptions.create') }}"
                   class="btn btn-sm btn-outline-success w-100 mt-2">
                    <i class="fas fa-plus me-1"></i> Add Subscription
                </a>
                @endif
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold border-0 pb-0">
                <i class="fas fa-bolt me-2 text-warning"></i>Quick Actions
            </div>
            <div class="card-body d-grid gap-2">
                <a href="{{ route('admin.dealers.edit', $dealer) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Dealer
                </a>
                <a href="mailto:{{ $dealer->email }}" class="btn btn-outline-primary">
                    <i class="fas fa-envelope me-2"></i>Send Email
                </a>
                @if($dealer->phone)
                <a href="tel:{{ $dealer->phone }}" class="btn btn-outline-secondary">
                    <i class="fas fa-phone me-2"></i>Call {{ $dealer->phone }}
                </a>
                <a href="https://wa.me/91{{ preg_replace('/\D/', '', $dealer->phone) }}"
                   target="_blank" class="btn btn-outline-success">
                    <i class="fab fa-whatsapp me-2"></i>WhatsApp
                </a>
                @endif
                <form method="POST" action="{{ route('admin.dealers.toggle-status', $dealer) }}">
                    @csrf
                    <button type="submit"
                            class="btn w-100 {{ ($dealer->status ?? 'active') === 'blocked' ? 'btn-outline-success' : 'btn-outline-warning' }}"
                            onclick="return confirm('{{ ($dealer->status ?? 'active') === 'blocked' ? 'Unblock' : 'Block' }} this dealer?')">
                        <i class="fas {{ ($dealer->status ?? 'active') === 'blocked' ? 'fa-unlock' : 'fa-ban' }} me-2"></i>
                        {{ ($dealer->status ?? 'active') === 'blocked' ? 'Unblock Dealer' : 'Block Dealer' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Danger Zone --}}
        <div class="card border-0 shadow-sm" style="border-top:3px solid #ef4444!important;">
            <div class="card-body py-3">
                <form method="POST" action="{{ route('admin.dealers.destroy', $dealer) }}"
                      onsubmit="return confirm('Permanently delete dealer \'{{ addslashes($dealer->name) }}\'? Their properties will be unlinked. This cannot be undone.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-1"></i> Delete Dealer
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
