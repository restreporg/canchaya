<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/canchaya.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canchaya - @yield('title', 'Reservas')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --bs-primary: #1db954;
            --bs-primary-rgb: 29, 185, 84;
            --bs-link-color: #1db954;
            --bs-link-hover-color: #17a045;
        }
        .btn-primary { background-color: #1db954; border-color: #1db954; }
        .btn-primary:hover { background-color: #17a045; border-color: #17a045; }
        .btn-outline-primary { color: #1db954; border-color: #1db954; }
        .btn-outline-primary:hover { background-color: #1db954; border-color: #1db954; color: #fff; }
        .text-primary { color: #1db954 !important; }
        .bg-primary { background-color: #1db954 !important; }
        .border-primary { border-color: #1db954 !important; }
        .badge.bg-primary { background-color: #1db954 !important; }
        body { background-color: #f8f9fa; }
        .navbar-brand { font-weight: 700; }

        /* Resalta con el verde de marca el link activo del navbar */
        .navbar-dark .navbar-nav .nav-link.active,
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #1db954;
        }

        /* Alertas más amigables: bordes redondeados, colores suaves, sin viñetas */
        .alert {
            border: none;
            border-radius: 12px;
            display: flex;
            align-items: flex-start;
            gap: .5rem;
        }
        .alert .btn-close { margin-left: auto; }
        .alert ul { list-style: none; padding-left: 0; margin-bottom: 0; }
        .alert ul li:not(:last-child) { margin-bottom: .15rem; }
        .alert-danger {
            background-color: #fdeeee;
            color: #b23c3c;
        }
        .alert-success {
            background-color: #e6f9ee;
            color: #178a45;
        }

        /* Lenguaje visual compartido por las vistas de cliente: badges pastel, tarjetas y botones redondeados */
        .badge-pill-soft {
            border-radius: 999px;
            font-weight: 600;
            padding: .4em .9em;
        }
        .status-pendiente  { background: #fff6e0; color: #d99a00; }
        .status-confirmada { background: #e6f9ee; color: #178a45; }
        .status-cancelada  { background: #f1f2f3; color: #6c757d; }
        .status-completada { background: #e8f0ff; color: #2563eb; }
        .status-pagado      { background: #e6f9ee; color: #178a45; }

        .card-soft { border-radius: 14px; }
        .btn-pill  { border-radius: 999px; }
    </style>

    @stack('styles')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('client.reservations.index') }}">
                <img src="/canchaya.png" alt="Logo" style="height:28px;" class="me-2">Canchaya
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a href="{{ route('client.reservations.index') }}"
                           class="nav-link {{ request()->routeIs('client.reservations.index') ? 'active' : '' }}">
                            <i class="bi bi-calendar-check me-1"></i> Mis Reservas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('client.reservations.create') }}"
                           class="nav-link {{ request()->routeIs('client.reservations.create') ? 'active' : '' }}">
                            <i class="bi bi-plus-circle me-1"></i> Nueva Reserva
                        </a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    {{-- Contenido --}}
    <div class="container py-4">

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" data-autodismiss>
                <i class="bi bi-check-circle fs-5"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" data-autodismiss>
                <i class="bi bi-exclamation-circle fs-5"></i>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('body')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-cierra las alertas de éxito/error después de 4 segundos
        document.querySelectorAll('[data-autodismiss]').forEach(function (el) {
            setTimeout(function () {
                bootstrap.Alert.getOrCreateInstance(el)?.close();
            }, 4000);
        });
    </script>
    @stack('scripts')
</body>
</html>