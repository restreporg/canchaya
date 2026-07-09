<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/canchaya.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canchaya - Confirmar contraseña</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --verde: #1db954;
            --verde-dark: #17a045;
            --verde-oscuro: #0d5c2e;
        }

        body {
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(10,40,20,0.90) 0%, rgba(13,92,46,0.82) 100%),
                url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=1600&q=80') center/cover no-repeat fixed;
        }

        .confirm-card {
            border: none;
            border-radius: 20px;
            background: rgba(255,255,255,0.97);
        }

        .icon-badge {
            width: 56px; height: 56px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            background: rgba(29,185,84,0.12);
            color: var(--verde);
            font-size: 1.4rem;
        }

        .form-control:focus {
            border-color: var(--verde);
            box-shadow: 0 0 0 0.2rem rgba(29,185,84,0.25);
        }

        .btn-verde {
            background-color: var(--verde);
            border-color: var(--verde);
            color: #fff;
        }
        .btn-verde:hover {
            background-color: var(--verde-dark);
            border-color: var(--verde-dark);
            color: #fff;
        }

        .text-verde { color: var(--verde) !important; }

        .brand-link {
            color: #fff;
            text-decoration: none;
        }
    </style>
</head>
<body class="d-flex align-items-center py-5">

    <div class="container">
        <div class="text-center mb-4">
            <a href="/" class="brand-link fw-bold fs-4">
                <img src="/canchaya.png" alt="Logo" style="height:28px;" class="me-2">Canchaya
            </a>
        </div>

        <div class="row justify-content-center">
            <div class="col-11 col-sm-9 col-md-7 col-lg-5">
                <div class="card confirm-card shadow-lg p-4 p-md-5">

                    <div class="text-center mb-4">
                        <div class="icon-badge mx-auto mb-3">
                            <i class="bi bi-shield-lock"></i>
                        </div>
                        <p class="text-uppercase fw-semibold text-verde small mb-2">Área segura</p>
                        <h1 class="fw-bold h3 mb-2">Confirma tu contraseña</h1>
                        <p class="text-muted mb-0">
                            Esta es una zona segura de la aplicación. Confirma tu contraseña antes de continuar.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold">Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bi bi-lock text-verde"></i></span>
                                <input id="password" type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="••••••••" required autocomplete="current-password">
                            </div>
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-verde btn-lg w-100 fw-semibold">
                            <i class="bi bi-check-circle me-2"></i>Confirmar
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>