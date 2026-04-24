@extends('backend.layout')
@section('title', 'Dashboard')
@section('content')
<div class="container-fluid p-0">
    <h1 class="h3 mb-3"><strong>Analytics</strong> Dashboard</h1>
    <div class="row">
        <div class="col-xl-6 col-xxl-5 d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Users</h5>
                                <h1 class="mt-1 mb-3">{{ \App\Models\User::count() }}</h1>
                                <div class="mb-1">
                                    <span class="text-success"> <i class="mdi mdi-arrow-bottom-right"></i> +5% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Properties</h5>
                                <h1 class="mt-1 mb-3">{{ \App\Models\Property::count() }}</h1>
                                <div class="mb-1">
                                    <span class="text-danger"> <i class="mdi mdi-arrow-bottom-right"></i> -1% </span>
                                    <span class="text-muted">Since last week</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-xxl-7">
            <div class="card flex-fill w-100">
                <div class="card-header">Recent Activity</div>
                <div class="card-body py-3">
                    <ul>
                        <li>New user registrations, property approvals, etc.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
