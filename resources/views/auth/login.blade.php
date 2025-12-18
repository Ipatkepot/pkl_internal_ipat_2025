<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #0f0f0f, #1c1c1c);
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .login-card {
            background: #1f1f1f;
            border-radius: 20px;
            padding: 50px 40px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6);
            max-width: 420px;
            width: 100%;
        }

        .login-title {
            color: #ffb700;
            font-size: 2.2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 8px;
        }

        .login-sub {
            color: #ccc;
            text-align: center;
            margin-bottom: 30px;
        }

        .form-control {
            background: #2a2a2a;
            border: 1px solid #333;
            color: #fff;
        }

        .form-control:focus {
            background: #2a2a2a;
            border-color: #ffb700;
            box-shadow: none;
            color: #fff;
        }

        .btn-primary {
            background-color: #ffb700;
            border: none;
            font-weight: 600;
        }

        .btn-primary:hover {
            background-color: #e6a600;
        }

        .btn-google {
            background-color: #4285F4;
            color: #fff;
            font-weight: 600;
        }

        .btn-google:hover {
            background-color: #357ae8;
            color: #fff;
        }

        .google-icon {
            width: 20px;
            margin-right: 8px;
        }

        a {
            color: #ffb700;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

    </style>
</head>
<body>

<div class="login-card">

    <h2 class="login-title">Welcome Back 👋</h2>
    <p class="login-sub">Sign in to access your dashboard</p>

    <!-- Login Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email Address') }}</label>
            <input id="email" type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">{{ __('Password') }}</label>
            <input id="password" type="password"
                   class="form-control @error('password') is-invalid @enderror"
                   name="password" required>
            @error('password')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox"
                   name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">{{ __('Login') }}</button>
    </form>

    <!-- OR Divider -->
    <div class="d-flex align-items-center my-3">
        <hr class="flex-grow-1" style="border-color: #333;">
        <span class="px-2 text-muted">OR</span>
        <hr class="flex-grow-1" style="border-color: #333;">
    </div>

    <!-- Google Login -->
    <a href="{{ route('auth.google') }}" class="btn btn-google w-100 py-2 d-flex align-items-center justify-content-center mb-2">
        <img src="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg" class="google-icon" alt="Google">
        Login with Google
    </a>

    @if (Route::has('password.request'))
    <div class="text-center mt-2">
        <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
    </div>
    @endif

</div>

</body>
</html>
