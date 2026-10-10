<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BantayResolve - Citizen Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #07111f;
        }

        .register-page {
            background: linear-gradient(135deg, rgba(7, 17, 31, 0.92), rgba(15, 23, 42, 0.88));
        }

        .registration-card {
            background: rgba(255, 255, 255, 0.98);
            color: #172033;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 24px 70px rgba(0, 0, 0, 0.38);
        }

        .registration-card .form-label {
            color: #334155;
            font-size: 0.84rem;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        .registration-card .form-control {
            min-height: 48px;
            border: 1px solid #cbd5e1;
            border-radius: 14px;
            padding: 0.75rem 3rem;
            color: #172033;
            background: #ffffff;
            box-shadow: none;
            font-size: 0.98rem;
        }

        .registration-card .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
        }

        .registration-card .form-control::placeholder {
            color: #94a3b8;
        }

        .registration-card .input-icon {
            color: #64748b;
            z-index: 1;
        }

        .registration-card .password-toggle {
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

        .registration-card .password-toggle:hover,
        .registration-card .password-toggle:focus-visible {
            background: #eff6ff;
            color: #1d4ed8;
            outline: none;
        }

        .role-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .role-option {
            position: relative;
            cursor: pointer;
            color: #334155;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            transition: border-color 0.2s ease, box-shadow 0.2s ease, transform 0.2s ease;
        }

        .role-option:hover {
            transform: translateY(-1px);
            border-color: #93c5fd;
        }

        .role-option:has(input:checked) {
            color: #1e3a8a;
            border-color: #2563eb;
            background: #eff6ff;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .role-option input {
            accent-color: #2563eb;
        }

        .registration-card .btn-register {
            border: 0;
            border-radius: 14px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff;
            box-shadow: 0 12px 26px rgba(37, 99, 235, 0.25);
        }

        .registration-card .btn-register:hover {
            color: #ffffff;
            background: linear-gradient(135deg, #1d4ed8, #1e40af);
        }

        .registration-card .login-link {
            color: #475569;
        }

        .registration-card .login-link a {
            color: #2563eb;
        }

        @media (max-width: 520px) {
            .role-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="min-vh-100 bg-slate-950 text-white">
    <div class="min-vh-100 bg-cover bg-center position-relative" style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?q=80&w=1800&auto=format&fit=crop');">
        <div class="position-absolute inset-0 bg-slate-950/70"></div>

        <header class="position-relative z-3 mx-auto d-flex align-items-center justify-content-between px-4 px-md-5 py-4">
            <a href="{{ url('/') }}" class="d-flex align-items-center gap-2 fw-semibold text-white text-decoration-none">
                <i class="fa-solid fa-shield-halved text-warning"></i>
                BantayResolve
            </a>
            <a href="{{ route('login') }}" class="btn btn-outline-light rounded-pill px-4">Login</a>
        </header>

        <main class="position-relative z-2 d-flex align-items-center justify-content-center min-vh-100 px-4 pb-5 pt-3">
            <section class="registration-card w-100 rounded-4" style="max-width: 560px;">
                <div class="p-4 p-md-5 border-bottom border-slate-200 text-center">
                    <div class="mx-auto d-flex align-items-center justify-content-center fs-2 rounded-circle bg-primary text-white shadow-lg shadow-primary-500/20" style="width: 68px; height: 68px;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <h1 class="display-6 fw-bold mt-3 text-dark">Create your account</h1>
                    <p class="text-muted mb-0">Join BantayResolve and choose your access area.</p>
                </div>

                <div class="p-4 p-md-5">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 mb-3" role="alert">
                            @foreach ($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="name">Full name</label>
                            <div class="position-relative">
                                <i class="input-icon fa-regular fa-user position-absolute top-50 translate-middle-y ms-3"></i>
                                <input id="name" type="text" name="name" value="{{ old('name') }}" class="form-control" placeholder="Enter your full name" autocomplete="name" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email address</label>
                            <div class="position-relative">
                                <i class="input-icon fa-regular fa-envelope position-absolute top-50 translate-middle-y ms-3"></i>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="name@example.com" autocomplete="email" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <div class="position-relative">
                                <i class="input-icon fa-solid fa-lock position-absolute top-50 translate-middle-y ms-3"></i>
                                <input id="password" type="password" name="password" class="form-control" placeholder="At least 6 characters" autocomplete="new-password" required>
                                <button type="button" class="password-toggle" data-password-toggle="password" aria-label="Show password" aria-pressed="false">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Confirm password</label>
                            <div class="position-relative">
                                <i class="input-icon fa-solid fa-lock position-absolute top-50 translate-middle-y ms-3"></i>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" placeholder="Repeat your password" autocomplete="new-password" required>
                                <button type="button" class="password-toggle" data-password-toggle="password_confirmation" aria-label="Show password" aria-pressed="false">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <fieldset class="border-0 p-0 mb-4">
                            <legend class="form-label">I am registering as</legend>
                            <div class="d-grid role-grid gap-3">
                                <label class="role-option card border border-white-10 bg-white-5 p-3 rounded-3 {{ old('role') === 'citizen' ? 'border-warning' : '' }}">
                                    <input type="radio" name="role" value="citizen" class="form-check-input me-2" {{ old('role') === 'citizen' ? 'checked' : '' }} required>
                                    <i class="fa-solid fa-user me-2 text-warning"></i>Citizen
                                </label>
                                <label class="role-option card border border-white-10 bg-white-5 p-3 rounded-3 {{ old('role') === 'staff' ? 'border-warning' : '' }}">
                                    <input type="radio" name="role" value="staff" class="form-check-input me-2" {{ old('role') === 'staff' ? 'checked' : '' }} required>
                                    <i class="fa-solid fa-user-tie me-2 text-warning"></i>Staff
                                </label>
                            </div>
                        </fieldset>

                        <button type="submit" class="btn btn-warning rounded-3 fw-bold w-100 py-3">
                            <i class="fa-solid fa-user-plus me-2"></i>Create account
                        </button>

                        <p class="text-center text-white-50 small mt-3 mb-0">
                            Already have an account? <a href="{{ route('login') }}" class="text-warning fw-semibold text-decoration-none">Sign in</a>
                        </p>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.querySelectorAll('[data-password-toggle]').forEach((button) => {
            const input = document.getElementById(button.dataset.passwordToggle);
            const icon = button.querySelector('i');
            const isVisible = input.type === 'text';

            button.addEventListener('click', () => {
                input.type = isVisible ? 'password' : 'text';
                button.setAttribute('aria-pressed', String(!isVisible));
                button.setAttribute('aria-label', isVisible ? 'Show password' : 'Hide password');
                icon.classList.toggle('fa-eye', isVisible);
                icon.classList.toggle('fa-eye-slash', !isVisible);
            });
        });
    </script>

    @if (session('success'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                title: 'Registered Successfully!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#f59e0b',
                confirmButtonText: 'Proceed to login',
                customClass: { popup: 'border border-secondary' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('login') }}";
                }
            });
        </script>
    @endif
</body>
</html>