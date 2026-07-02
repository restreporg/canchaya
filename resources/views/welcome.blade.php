<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/canchaya.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canchaya - Reserva tu cancha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --verde: #1db954;
            --verde-dark: #17a045;
            --verde-oscuro: #0d5c2e;
        }

        .hero {
            background:
                linear-gradient(135deg, rgba(10,40,20,0.88) 0%, rgba(13,92,46,0.80) 100%),
                url('https://images.unsplash.com/photo-1529900748604-07564a03e7a6?w=1600&q=80') center/cover no-repeat;
            min-height: 100vh;
        }
        .hero-title { font-size: 3.5rem; font-weight: 800; }
        .card-feature { border: none; border-radius: 16px; transition: transform .2s; }
        .card-feature:hover { transform: translateY(-5px); }
        .icon-circle {
            width: 60px; height: 60px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
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

    {{-- Hero --}}
    <section class="hero d-flex align-items-center text-white">
        <div class="container text-center py-5">
            <p class="text-uppercase fw-semibold mb-2 text-verde">
                <i class="bi bi-geo-alt me-1"></i> La mejor plataforma de reservas
            </p>
            <h1 class="hero-title mb-4">
                Reserva tu cancha <br>
                <span class="text-verde">en segundos</span>
            </h1>
            <p class="lead text-white-50 mb-5 mx-auto" style="max-width: 500px;">
                Encuentra disponibilidad en tiempo real, elige tu horario y paga fácilmente.
            </p>
            <div class="d-flex justify-content-center gap-3">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-verde btn-lg px-5">
                            <i class="bi bi-speedometer2 me-2"></i>Ir al panel
                        </a>
                    @else
                        <a href="{{ route('client.reservations.create') }}" class="btn btn-verde btn-lg px-5">
                            <i class="bi bi-lightning-charge me-2"></i>Reservar ahora
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-verde btn-lg px-5">
                        <i class="bi bi-lightning-charge me-2"></i>Reservar ahora
                    </a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Iniciar sesión
                    </a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="py-6 bg-light" style="padding: 80px 0;">
        <div class="container">
            <h2 class="text-center fw-bold mb-2">¿Por qué Canchaya?</h2>
            <p class="text-center text-muted mb-5">Todo lo que necesitas para reservar en un solo lugar</p>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card card-feature p-4 h-100 shadow-sm">
                        <div class="icon-circle mb-3" style="background:rgba(29,185,84,0.12); color:var(--verde);">
                            <i class="bi bi-calendar2-check"></i>
                        </div>
                        <h5 class="fw-bold">Reserva fácil</h5>
                        <p class="text-muted mb-0">Elige cancha, fecha y hora en pocos clics. Sin llamadas ni filas.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-feature p-4 h-100 shadow-sm">
                        <div class="icon-circle mb-3" style="background:rgba(29,185,84,0.12); color:var(--verde);">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5 class="fw-bold">Disponibilidad en tiempo real</h5>
                        <p class="text-muted mb-0">Consulta los horarios disponibles al instante y reserva sin conflictos.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card card-feature p-4 h-100 shadow-sm">
                        <div class="icon-circle mb-3" style="background:rgba(29,185,84,0.12); color:var(--verde);">
                            <i class="bi bi-credit-card"></i>
                        </div>
                        <h5 class="fw-bold">Pago seguro</h5>
                        <p class="text-muted mb-0">Paga con efectivo, tarjeta o transferencia de forma rápida y segura.</p>
                    </div>
                </div>
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