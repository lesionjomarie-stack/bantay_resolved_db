<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BantayResolve</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #07111f;
        }

        .login-page {
            background: linear-gradient(135deg, rgba(7, 17, 31, 0.96), rgba(15, 23, 42, 0.9));
        }

        .login-panel {
            background: rgba(255, 255, 255, 0.98);
            color: #172033;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.38);
        }

        .login-panel .form-label {
            color: #334155;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .login-panel .form-control {
            min-height: 48px;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 0.75rem 3rem;
            color: #172033;
            background: #ffffff;
            box-shadow: none;
            font-size: 0.98rem;
        }

        .login-panel .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .login-panel .form-control::placeholder {
            color: #94a3b8;
        }

        .login-panel .input-icon {
            color: #64748b;
            z-index: 1;
        }

        .login-panel .password-toggle {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #64748b;
            cursor: pointer;
            z-index: 2;
        }

        .login-panel .password-toggle:hover,
        .login-panel .password-toggle:focus-visible {
            background: #eff6ff;
            color: #1d4ed8;
            outline: none;
        }

        .login-panel .password-toggle svg {
            width: 19px;
            height: 19px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
        }

        .login-panel .btn-primary-custom {
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 12px 26px rgba(37, 99, 235, 0.25);
        }

        .login-panel .btn-primary-custom:hover {
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
            color: #ffffff;
        }

        .login-panel .login-link {
            color: #475569;
        }

        .login-panel .login-link a {
            color: #2563eb;
        }

        .login-panel .back-link {
            color: #475569;
        }

        .login-panel .back-link:hover {
            color: #1d4ed8;
        }

        @media (max-width: 576px) {
            .login-panel {
                border-radius: 20px;
            }
        }
    </style>
</head>
<body class="min-vh-100 bg-slate-950 text-white">
    <div class="login-page min-vh-100 bg-cover bg-center position-relative" style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1800&auto=format&fit=crop');">
        <div class="position-absolute inset-0 bg-slate-950/70"></div>

        <header class="position-relative z-3 mx-auto d-flex align-items-center justify-content-between px-4 px-md-5 py-4">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 fw-semibold text-white text-decoration-none">
                <i class="fa-solid fa-shield-halved text-warning"></i>
                BantayResolve
            </a>
        </header>

        <main class="position-relative z-2 d-flex align-items-center justify-content-center min-vh-100 px-4 pb-5 pt-3">
            <section class="login-panel w-100 rounded-4" style="max-width: 520px;">
                <div class="p-4 p-md-5 border-bottom border-slate-200 text-center">
                    <div class="mx-auto d-flex align-items-center justify-content-center fs-2 rounded-circle bg-primary text-white shadow-lg shadow-primary-500/20" style="width: 68px; height: 68px;">
                        <i class="fa-solid fa-shield-halved"></i>
                    </div>
                    <h1 class="display-6 fw-bold mt-3 text-dark">Welcome back</h1>
                    <p class="text-muted mb-0">Sign in to continue to your BantayResolve dashboard.</p>
                </div>

                <div class="p-4 p-md-5">
                    @if (session('success'))
                        <div class="alert alert-success border-0 mb-3" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger border-0 mb-3" role="alert">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="email">Email address</label>
                            <div class="position-relative">
                                <i class="input-icon fa-solid fa-envelope position-absolute top-50 translate-middle-y ms-3"></i>
                                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="admin@bantayresolve.com" autocomplete="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <div class="position-relative">
                                <i class="input-icon fa-solid fa-lock position-absolute top-50 translate-middle-y ms-3"></i>
                                <input id="password" type="password" name="password" class="form-control" placeholder="Enter your password" autocomplete="current-password" required>
                                <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                                    <svg viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path>
                                        <circle cx="12" cy="12" r="2.5"></circle>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <label class="form-label" for="dashboard_type">Access area</label>
                        <select id="dashboard_type" name="dashboard_type" class="form-select mb-3 rounded-3 border-slate-200" aria-describedby="dashboardTypeHelp">
                            <option value="">Select your role</option>
                            <option value="staff">Staff Dashboard</option>
                            <option value="citizen">Citizen Dashboard</option>
                        </select>
                        <div id="dashboardTypeHelp" class="form-text text-muted mb-3">Optional. Your registered role determines the dashboard.</div>

                        <button type="submit" class="btn btn-primary-custom w-100 py-3 fw-bold">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Sign in
                        </button>

                        <p class="login-link text-center small mt-3 mb-0">
                            New to BantayResolve?
                            <a href="{{ route('register') }}" class="d-block fw-semibold text-decoration-none mt-1">Create an account</a>
                        </p>
                    </form>
                </div>
            </section>
        </main>
    </div>

<script>
    document.querySelectorAll('[data-password-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const icon = button.querySelector('svg');
            const isVisible = input.type === 'text';

            input.type = isVisible ? 'password' : 'text';
            button.setAttribute('aria-pressed', String(!isVisible));
            button.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
            icon.innerHTML = isVisible
                ? '<path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6Z"></path><circle cx="12" cy="12" r="2.5"></circle>'
                : '<path d="m3 3 18 18"></path><path d="M10.6 6.1A9.7 9.7 0 0 1 12 6c6 0 9.5 6 9.5 6a15 15 0 0 1-2.1 2.7M6.2 6.2C3.8 8 2.5 12 2.5 12s3.5 6 9.5 6a9.2 9.2 0 0 0 3.4-.6"></path><path d="M9.9 9.9a3 3 0 0 0 4.2 4.2"></path>';
        });
    });
</script>
</body>
</html>