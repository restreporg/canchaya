<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/canchaya.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demasiadas solicitudes - Canchaya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --verde: #1db954;
            --verde-dark: #17a045;
            --verde-oscuro: #0d5c2e;
        }
        body { margin: 0; }
        .hero {
            background:
                linear-gradient(135deg, rgba(10,40,20,0.88) 0%, rgba(13,92,46,0.80) 100%),
                url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=1600&q=80') center/cover no-repeat;
            min-height: 100vh;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 800;
            line-height: 1;
        }
        .hero-title { font-size: 2.5rem; font-weight: 800; }
        .icon-circle-lg {
            width: 90px; height: 90px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 2.25rem;
            background: rgba(29,185,84,0.15);
            color: var(--verde);
            margin: 0 auto 1.5rem auto;
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
    </style>
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent position-absolute w-100" style="z-index:10">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4" href="/">
                <img src="/canchaya.png" alt="Logo" style="height:28px;" class="me-2">Canchaya
            </a>
            <div class="ms-auto d-flex gap-2">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-grid me-1"></i>Panel Admin
                        </a>
                    @else
                        <a href="{{ route('client.reservations.index') }}" class="btn btn-light btn-sm">
                            <i class="bi bi-calendar-check me-1"></i>Mis Reservas
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm">
                        <i class="bi bi-box-arrow-in-right me-1"></i>Iniciar sesión
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-verde btn-sm">
                        <i class="bi bi-person-plus me-1"></i>Registrarse
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Hero de error --}}
    <section class="hero d-flex align-items-center text-white">
        <div class="container text-center py-5">

            <div class="icon-circle-lg">
                <i class="bi bi-speedometer2"></i>
            </div>

            <p class="error-code text-verde mb-0">429</p>
            <h1 class="hero-title mb-4">Demasiadas solicitudes</h1>
            <p class="lead text-white-50 mb-5 mx-auto" style="max-width: 500px;">
                Has realizado demasiadas solicitudes en poco tiempo. Espera un momento e inténtalo de nuevo.
            </p>

            <div class="d-flex justify-content-center gap-3">
                @auth
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('client.reservations.index') }}"
                       class="btn btn-verde btn-lg px-5">
                        <i class="bi bi-house-door me-2"></i>Ir a mi panel
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-verde btn-lg px-5">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                    </a>
                @endauth
                <a href="javascript:history.back()" class="btn btn-outline-light btn-lg px-5">
                    <i class="bi bi-arrow-left me-2"></i>Volver
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-dark text-white-50 py-4 text-center">
        <div class="container">
            <p class="mb-0">
                <img src="/canchaya.png" alt="Logo" style="height:20px;" class="me-2">
                <strong class="text-white">Canchaya</strong> &copy; {{ date('Y') }} — Todos los derechos reservados.
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>