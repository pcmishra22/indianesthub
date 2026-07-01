<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Service Provider Login | {{ config('app.name') }}</title>
    <link rel="shortcut icon" href="/backend/img/icons/icon-48x48.png" />
    <link href="/backend/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
<main class="d-flex w-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-5 col-xl-4 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4 mb-3">
                        <h1 class="h2">Service Provider Login</h1>
                        <p class="text-muted">Electricians, Interior Designers, Loan Providers & more</p>
                    </div>

                    @if(session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        @foreach($errors->all() as $error)
                            {{ $error }}
                        @endforeach
                    </div>
                    @endif

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('service-provider.login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Email Address</label>
                                    <input class="form-control form-control-lg @error('email') is-invalid @enderror"
                                           type="email" name="email"
                                           value="{{ old('email') }}"
                                           placeholder="you@example.com"
                                           required autofocus>
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Password</label>
                                    <input class="form-control form-control-lg"
                                           type="password" name="password"
                                           placeholder="Enter your password"
                                           required>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input id="remember" type="checkbox" class="form-check-input"
                                               name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label text-muted small" for="remember">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                <div class="d-grid mt-3">
                                    <button type="submit" class="btn btn-lg btn-primary fw-semibold">
                                        Sign In
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-3 mb-4 small text-muted">
                        Don't have an account?
                        <a href="{{ route('service-provider.register') }}" class="text-primary fw-semibold">Register as a Service Provider</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>

<script src="/backend/js/app.js"></script>
</body>
</html>
