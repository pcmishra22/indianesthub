<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Dealer Registration | Property Dealer</title>
    <link rel="shortcut icon" href="/backend/img/icons/icon-48x48.png" />
    <link href="/backend/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row min-vh-100 py-4">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4 mb-3">
                        <h1 class="h2">Create Partner Account</h1>
                        <p class="text-muted">Start listing properties or offering services on our platform</p>
                    </div>

                    @include('partials.partner-role-switcher', ['activeRole' => 'dealer', 'mode' => 'register'])

                    @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <strong>Please fix the following:</strong>
                        <ul class="mb-0 mt-1 small">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('dealer.register') }}">
                                @csrf

                                <div class="row g-3 mb-3">
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label>
                                        <input class="form-control @error('first_name') is-invalid @enderror"
                                               type="text" name="first_name"
                                               value="{{ old('first_name') }}"
                                               placeholder="Rajesh" required autofocus>
                                        @error('first_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label>
                                        <input class="form-control @error('last_name') is-invalid @enderror"
                                               type="text" name="last_name"
                                               value="{{ old('last_name') }}"
                                               placeholder="Sharma" required>
                                        @error('last_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                    <input class="form-control @error('email') is-invalid @enderror"
                                           type="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="you@example.com" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                    <input class="form-control @error('phone') is-invalid @enderror"
                                           type="text" name="phone"
                                           value="{{ old('phone') }}"
                                           placeholder="9876543210" required>
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Company / Agency Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('company_name') is-invalid @enderror"
                                           type="text" name="company_name"
                                           value="{{ old('company_name') }}"
                                           placeholder="e.g. Tricity Realty" required>
                                    @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                                    <input class="form-control @error('password') is-invalid @enderror"
                                           type="password" name="password"
                                           placeholder="Minimum 8 characters" required>
                                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                                    <input class="form-control"
                                           type="password" name="password_confirmation"
                                           placeholder="Re-enter password" required>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg btn-primary fw-semibold">
                                        Create Account
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3 mb-4 small text-muted">
                        Already have an account?
                        <a href="{{ route('dealer.login') }}" class="text-primary fw-semibold">Sign in</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<script src="/backend/js/app.js"></script>
</body>
</html>
