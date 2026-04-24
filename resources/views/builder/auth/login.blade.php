<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Builder Login – {{ config('app.name') }}</title>
    <link href="{{ asset('adminkit/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
<main class="d-flex w-100 h-100 min-vh-100">
    <div class="container d-flex flex-column">
        <div class="row vh-100">
            <div class="col-sm-10 col-md-8 col-lg-5 col-xl-4 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">

                    <div class="text-center mt-4">
                        <h1 class="h2">Builder Portal</h1>
                        <p class="lead">Sign in to manage your projects & properties</p>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="m-sm-3">
                                <div class="text-center mb-4">
                                    <i data-feather="layers" style="width:48px;height:48px;color:#0d6efd;"></i>
                                    <h4 class="mt-2 mb-0">Welcome back!</h4>
                                    <small class="text-muted">Builder sign in</small>
                                </div>

                                @if($errors->any())
                                <div class="alert alert-danger">
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                                @endif

                                <form method="POST" action="{{ route('builder.login') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email</label>
                                        <input class="form-control form-control-lg @error('email') is-invalid @enderror"
                                               type="email" name="email" value="{{ old('email') }}"
                                               placeholder="Enter your email" required autofocus>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Password</label>
                                        <input class="form-control form-control-lg @error('password') is-invalid @enderror"
                                               type="password" name="password"
                                               placeholder="Enter your password" required>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                            <label class="form-check-label" for="remember">Remember me</label>
                                        </div>
                                    </div>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-lg btn-primary">Sign In</button>
                                    </div>
                                </form>

                                <div class="text-center mt-3">
                                    <small>Don't have an account?
                                        <a href="{{ route('builder.register') }}">Register here</a>
                                    </small>
                                </div>
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
