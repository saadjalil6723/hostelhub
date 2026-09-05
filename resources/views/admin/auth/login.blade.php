<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | HostelHub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: radial-gradient(120% 140% at 15% 0%, #1E3E37 0%, #15302B 55%, #0F221E 100%);
        }
        .login-card {
            max-width: 400px;
            margin: 0 auto;
            border-radius: 16px;
            background: var(--chalk);
            box-shadow: 0 24px 60px rgba(0,0,0,0.35);
            border: none;
        }
        .login-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 600;
            color: var(--ink);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card login-card shadow p-4">
        <div class="login-brand mb-1">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="24" height="24">
                <circle cx="8" cy="8" r="5.25" stroke="#C79A46" stroke-width="1.8"/>
                <path d="M11.7 11.7L20 20M20 20V15.5M20 20H15.5" stroke="#C79A46" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            HostelHub
        </div>
        <p class="text-center text-muted small mb-4">Administrator Login</p>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.attempt') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">Remember me</label>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <a href="{{ route('home') }}" class="small text-muted">&larr; Back to website</a>
        </div>
    </div>
</div>
</body>
</html>
