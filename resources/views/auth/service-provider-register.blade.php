<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Register as Service Provider | {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="/backend/img/icons/icon-48x48.png" />
    <link href="/backend/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .category-grid { display:grid; grid-template-columns:repeat(auto-fill,minmax(150px,1fr)); gap:10px; }
        .category-pick { position:relative; }
        .category-pick input { position:absolute; opacity:0; inset:0; cursor:pointer; margin:0; }
        .category-pick label {
            display:flex; align-items:center; gap:8px; padding:12px 14px;
            border:1.5px solid #e4e8f0; border-radius:10px; cursor:pointer;
            font-size:.85rem; font-weight:600; color:#475569; transition:all .15s;
        }
        .category-pick input:checked + label {
            border-color:#0078d4; background:#f0f7ff; color:#0a2d5e;
        }
        .category-pick label i { color:#0078d4; }
    </style>
</head>

<body>
<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row py-5">
            <div class="col-sm-11 col-md-9 col-lg-7 mx-auto">

                <div class="text-center mt-4 mb-3">
                    <h1 class="h2">Register as a Service Provider</h1>
                    <p class="text-muted">One signup for every home service — Electricians, Plumbers, Interior Designers, Loan Providers and more.</p>
                </div>

                @if($errors->any())
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    @foreach($errors->all() as $error)
                        {{ $error }}<br>
                    @endforeach
                </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('service-provider.register') }}">
                            @csrf

                            <div class="row g-3 mb-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name *</label>
                                    <input class="form-control form-control-lg" type="text" name="full_name"
                                           value="{{ old('full_name') }}" placeholder="Your name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Business / Shop Name</label>
                                    <input class="form-control form-control-lg" type="text" name="business_name"
                                           value="{{ old('business_name') }}" placeholder="e.g. Sharma Electrical Works (optional)">
                                </div>
                            </div>

                            <div class="row g-3 mb-1 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address *</label>
                                    <input class="form-control form-control-lg" type="email" name="email"
                                           value="{{ old('email') }}" placeholder="you@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number *</label>
                                    <input class="form-control form-control-lg" type="text" name="phone"
                                           value="{{ old('phone') }}" placeholder="10-digit mobile number" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-1 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">City *</label>
                                    <input class="form-control form-control-lg" type="text" name="city"
                                           value="{{ old('city') }}" placeholder="e.g. Zirakpur" required>
                                </div>
                            </div>

                            <div class="row g-3 mb-1 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Password *</label>
                                    <input class="form-control form-control-lg" type="password" name="password"
                                           placeholder="Minimum 8 characters" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Confirm Password *</label>
                                    <input class="form-control form-control-lg" type="password" name="password_confirmation"
                                           placeholder="Re-enter password" required>
                                </div>
                            </div>

                            <hr class="my-4">

                            <label class="form-label fw-semibold mb-2">
                                I want to sign up as <span class="text-danger">*</span>
                                <span class="d-block fw-normal text-muted small">Select one or more services you provide. You can update this anytime from your dashboard.</span>
                            </label>
                            <div class="category-grid mb-2">
                                @foreach($categories as $category)
                                    <div class="category-pick">
                                        <input type="checkbox" id="cat-{{ $category->id }}" name="categories[]"
                                               value="{{ $category->id }}"
                                               {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                                        <label for="cat-{{ $category->id }}">
                                            <i class="bi {{ $category->icon ?? 'bi-tools' }}"></i> {{ $category->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('categories')
                                <div class="text-danger small mb-3">{{ $message }}</div>
                            @enderror

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-lg btn-primary fw-semibold">
                                    Create My Service Provider Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3 mb-4 small text-muted">
                    Already registered?
                    <a href="{{ route('service-provider.login') }}" class="text-primary fw-semibold">Sign in here</a>
                </div>

            </div>
        </div>
    </div>
</main>

<script src="/backend/js/app.js"></script>
</body>
</html>
