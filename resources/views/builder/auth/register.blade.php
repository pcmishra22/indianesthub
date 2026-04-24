<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Builder Registration – {{ config('app.name') }}</title>
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<main class="d-flex w-100 min-vh-100">
    <div class="container d-flex flex-column py-5">
        <div class="row">
            <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto">

                <div class="text-center mb-4">
                    <i data-feather="layers" style="width:48px;height:48px;color:#0d6efd;"></i>
                    <h1 class="h2 mt-2">Create Builder Account</h1>
                    <p class="lead">Join {{ config('app.name') }} to list your projects and properties</p>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="m-sm-3">

                            @if($errors->any())
                            <div class="alert alert-danger">
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                            @endif

                            <form method="POST" action="{{ route('builder.register') }}">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Your Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror"
                                           type="text" name="name" value="{{ old('name') }}"
                                           placeholder="Full name" required>
                                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Company / Builder Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('company_name') is-invalid @enderror"
                                           type="text" name="company_name" value="{{ old('company_name') }}"
                                           placeholder="e.g. Sobha Developers" required>
                                    @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input class="form-control @error('email') is-invalid @enderror"
                                           type="email" name="email" value="{{ old('email') }}"
                                           placeholder="company@example.com" required>
                                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                                    <input class="form-control @error('phone') is-invalid @enderror"
                                           type="text" name="phone" value="{{ old('phone') }}"
                                           placeholder="+91 98765 43210" required>
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="row g-3 mb-3">
                                    <div class="col">
                                        <label class="form-label">Password <span class="text-danger">*</span></label>
                                        <input class="form-control @error('password') is-invalid @enderror"
                                               type="password" name="password"
                                               placeholder="Min 8 characters" required>
                                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col">
                                        <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                        <input class="form-control" type="password" name="password_confirmation"
                                               placeholder="Re-enter password" required>
                                    </div>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-lg btn-primary">Create Account</button>
                                </div>
                            </form>

                            <div class="text-center mt-3">
                                <small>Already have an account?
                                    <a href="{{ route('builder.login') }}">Sign in here</a>
                                </small>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
<script src="{{ asset('adminkit/js/app.js') }}"></script>
</body>
</html>
