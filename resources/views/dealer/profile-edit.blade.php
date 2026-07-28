@extends('dealer.layout')

@section('title', 'My Profile')

@section('content')
<h1 class="h3 mb-3"><strong>My</strong> Profile</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($dealer->profile_photo)
                    <img src="{{ asset('storage/' . $dealer->profile_photo) }}" alt="{{ $dealer->first_name }}" class="rounded-circle mb-3" width="128" height="128" style="object-fit:cover;">
                @else
                    <img src="{{ asset('backend/img/avatars/avatar.jpg') }}" alt="{{ $dealer->first_name }}" class="rounded-circle mb-3" width="128" height="128">
                @endif
                <h5 class="card-title mb-0">{{ $dealer->first_name }} {{ $dealer->last_name }}</h5>
                <div class="text-muted mb-2">{{ $dealer->company_name }}</div>
                <div class="text-muted mb-2">{{ $dealer->email }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Edit Profile</h5>
            </div>
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('dealer.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">First Name</label>
                            <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $dealer->first_name) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $dealer->last_name) }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" value="{{ $dealer->email }}" disabled>
                        <small class="text-muted">Email cannot be changed.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $dealer->phone) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company Name</label>
                        <input type="text" name="company_name" class="form-control" value="{{ old('company_name', $dealer->company_name) }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4" maxlength="1000" placeholder="A short description about you or your agency, shown on your public profile.">{{ old('bio', $dealer->bio) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Profile Photo</label>
                        <input type="file" name="profile_photo" class="form-control" accept="image/*">
                    </div>

                    <hr class="my-4">

                    <h6 class="mb-3">Change Password</h6>
                    <p class="text-muted small">Leave these blank to keep your current password.</p>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" name="new_password" class="form-control" minlength="8" autocomplete="new-password">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" class="form-control" minlength="8" autocomplete="new-password">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
