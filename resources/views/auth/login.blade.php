<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CHAMS - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #2a1a2e 0%, #1a0f1d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
            margin: 20px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #8b5a8f 0%, #6b3e70 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header img {
            width: 30%;
            height: auto;
            margin-bottom: 15px;
        }

        .login-header h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-header p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 0.9rem;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-label {
            font-weight: 600;
            color: #2a1a2e;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: #f5f0f7;
            border-right: none;
            color: #8b5a8f;
        }

        .form-control {
            border-left: none;
            padding: 12px;
        }

        .form-control:focus {
            border-color: #8b5a8f;
            box-shadow: 0 0 0 0.2rem rgba(139, 90, 143, 0.25);
        }

        .form-control:focus+.input-group-text {
            border-color: #8b5a8f;
        }

        .btn-login {
            background: linear-gradient(135deg, #8b5a8f 0%, #6b3e70 100%);
            border: none;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(139, 90, 143, 0.4);
        }

        .demo-credentials {
            background-color: #f5f0f7;
            border-radius: 12px;
            padding: 15px;
            margin-top: 20px;
        }

        .demo-credentials h6 {
            color: #2a1a2e;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .demo-credentials small {
            display: block;
            color: #7a6a7e;
            margin-bottom: 5px;
        }

        .demo-credentials code {
            background: white;
            padding: 2px 6px;
            border-radius: 4px;
            color: #8b5a8f;
            font-weight: 600;
        }

        .alert {
            border-radius: 12px;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('images/PAMS logo.png') }}" alt="CHAMS Logo"
                    onerror="this.src='https://via.placeholder.com/80x80?text=CHAMS'">
                <h3>C.H.A.M.S.</h3>
                <p>Clinical Health Appointment Management System</p>
            </div>
            <div class="login-body">

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Login failed!</strong> Please check your credentials.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('status'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('status') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}"
                                placeholder="admin@example.com" required autofocus>
                        </div>
                        @error('email')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" required>
                        </div>
                        @error('password')
                            <small class="text-danger mt-1 d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-login w-100 mb-3">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-decoration-none small">
                                Forgot your password?
                            </a>
                        </div>
                    @endif
                </form>

                <!-- Demo Credentials (Optional - remove in production) -->
              
                    <div class="demo-credentials">
                        <h6><i class="bi bi-info-circle"></i> Demo Credentials</h6>
                        <small><i class="bi bi-person-circle"></i> <strong>Admin:</strong> <code>admin@gmail.com</code>
                            / <code>admin123</code></small>
                        <small><i class="bi bi-person"></i> <strong>Staff:</strong> <code>staff@gmail.com</code> /
                            <code>staff123</code></small>
                        <small><i class="bi bi-person-badge"></i> <strong>Receptionist:</strong>
                            <code>receptionist@gmail.com</code> / <code>receptionist123</code></small>
                    </div>
        
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
