@extends('builder.layout')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid p-0">

    <div class="px-3 pt-3 mb-3">
        <h1 class="h3 mb-0 fw-bold">My Profile</h1>
        <small class="text-muted">Manage your builder account details</small>
    </div>

    <div class="px-3 pb-4">
        <div class="row g-3">

            {{-- Profile Avatar --}}
            <div class="col-lg-3">
                <div class="card text-center">
                    <div class="card-body py-4">
                        @if($builder->logo)
                            <img src="{{ asset('storage/' . $builder->logo) }}" alt="Logo"
                                 class="img-fluid rounded-circle mb-3"
                                 style="width:100px;height:100px;object-fit:cover;border:3px solid #dee2e6;">
                        @else
                            <div class="rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center"
                                 style="width:100px;height:100px;background:linear-gradient(135deg,#0d6efd,#6ea8fe);font-size:2.5rem;font-weight:800;color:#fff;">
                                {{ strtoupper(substr($builder->name, 0, 1)) }}
                            </div>
                        @endif
                        <h5 class="mb-0">{{ $builder->company_name ?: $builder->name }}</h5>
                        <small class="text-muted">{{ $builder->email }}</small>
                        <div class="mt-2">
                            <span class="badge {{ $builder->status === 'active' ? 'bg-success' : 'bg-danger' }}">
                                {{ ucfirst($builder->status) }}
                            </span>
                        </div>
                        @if($builder->city)
                        <div class="mt-2 text-muted" style="font-size:.85rem;">
                            <i data-feather="map-pin" style="width:13px;height:13px;"></i> {{ $builder->city }}
                        </div>
                        @endif
                        @if($builder->established_year)
                        <div class="text-muted" style="font-size:.85rem;">
                            Est. {{ $builder->established_year }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Edit Profile Form --}}
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Edit Profile</h5></div>
                    <div class="card-body">
                        <form action="{{ route('builder.profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf @method('PUT')

                            @if($errors->any())
                            <div class="alert alert-danger mb-3">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control"
                                           value="{{ old('name', $builder->name) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Name</label>
                                    <input type="text" name="company_name" class="form-control"
                                           value="{{ old('company_name', $builder->company_name) }}"
                                           placeholder="e.g. Sobha Developers">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', $builder->email) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone</label>
                                    <input type="text" name="phone" class="form-control"
                                           value="{{ old('phone', $builder->phone) }}" placeholder="+91 98765 43210">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Website</label>
                                    <input type="url" name="website" class="form-control"
                                           value="{{ old('website', $builder->website) }}" placeholder="https://example.com">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">City</label>
                                    <input type="text" name="city" class="form-control"
                                           value="{{ old('city', $builder->city) }}" placeholder="Headquarters city">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Established Year</label>
                                    <input type="text" name="established_year" class="form-control"
                                           value="{{ old('established_year', $builder->established_year) }}" placeholder="e.g. 2005">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">About / Description</label>
                                    <textarea name="description" class="form-control" rows="4"
                                              placeholder="Brief about your company, projects, and expertise...">{{ old('description', $builder->description) }}</textarea>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Company Logo</label>
                                    @if($builder->logo)
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $builder->logo) }}" alt="Logo"
                                             class="img-thumbnail" style="max-height:60px;">
                                    </div>
                                    @endif
                                    <input type="file" name="logo" class="form-control" accept="image/*">
                                    <small class="text-muted">Max 2 MB. Square image recommended.</small>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h6 class="mb-3">Change Password <small class="text-muted fw-normal">(leave blank to keep current)</small></h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Current Password</label>
                                    <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control" autocomplete="new-password">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i data-feather="save" style="width:16px;height:16px;"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
