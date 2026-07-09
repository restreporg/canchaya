<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="/canchaya.png">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - @yield('title', 'Panel')</title>

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

        .sidebar { min-height: 100vh; background-color: #212529; }
        .sidebar .nav-link { color: #adb5bd; padding: 8px 12px; border-radius: 6px; }
        .sidebar .nav-link:hover { color: #fff; background-color: #343a40; }
        .sidebar .nav-link.active { color: #fff; background-color: #1db954; }
        .sidebar .nav-title { color: #6c757d; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1px; padding: 0 12px; }
        .main-content { min-height: 100vh; }

        /* Offcanvas (sidebar en móvil) hereda los mismos estilos oscuros */
        #mobileSidebar { background-color: #212529; }
        #mobileSidebar .btn-close { filter: invert(1) grayscale(100%) brightness(200%); }

        /* Topbar solo visible en móvil */
        .mobile-topbar {
            background-color: #212529;
        }

        /* Lenguaje visual compartido por las vistas de admin: badges pastel, tarjetas y botones redondeados */
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
        .status-activa      { background: #e6f9ee; color: #178a45; }
        .status-inactiva    { background: #f1f2f3; color: #6c757d; }
        .status-fallido     { background: #fdeeee; color: #b23c3c; }
        .status-reembolsado { background: #e8f0ff; color: #2563eb; }

        .card-soft { border-radius: 14px; }
        .btn-pill  { border-radius: 999px; }
    </style>

    @stack('styles')
</head>
<body>

{{-- Topbar solo en móvil, con botón hamburguesa --}}
<nav class="navbar mobile-topbar d-md-none px-3 py-2">
    <button class="btn btn-outline-light border-0" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar" aria-controls="mobileSidebar">
        <i class="bi bi-list fs-3"></i>
    </button>
    <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none ms-2 d-flex align-items-center">
        <img src="/canchaya.png" alt="Logo" style="height: 24px;" class="me-2">
        <span class="fw-bold">Canchaya</span>
    </a>
</nav>

{{-- Offcanvas: mismo contenido del sidebar, para móvil --}}
<div class="offcanvas offcanvas-start text-white" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
    <div class="offcanvas-header">
        <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
            <img src="/canchaya.png" alt="Logo" style="height: 28px;" class="me-2">
            <span class="fw-bold fs-5">Canchaya</span>
        </a>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body py-2 px-3">
        <p class="nav-title mb-2 mt-2">General</p>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2 me-2"></i> Dashboard
                </a>
            </li>
        </ul>

        <p class="nav-title mb-2 mt-3">Gestión</p>
        <ul class="nav flex-column gap-1 mb-3">
            <li class="nav-item">
                <a href="{{ route('admin.courts.index') }}"
                   class="nav-link {{ request()->routeIs('admin.courts*') ? 'active' : '' }}">
                    <i class="bi bi-grid me-2"></i> Canchas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reservations.index') }}"
                   class="nav-link {{ request()->routeIs('admin.reservations*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check me-2"></i> Reservas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.payments.index') }}"
                   class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                    <i class="bi bi-credit-card me-2"></i> Pagos
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.reviews.index') }}"
                   class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                    <i class="bi bi-star me-2"></i> Reseñas
                </a>
            </li>
        </ul>

        <p class="nav-title mb-2 mt-3">Cuenta</p>
        <ul class="nav flex-column gap-1">
            <li class="nav-item">
                <span class="nav-link text-white-50">
                    <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name }}
                </span>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="nav-link border-0 bg-transparent text-start w-100">
                        <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>

<div class="container-fluid">
    <div class="row">

        {{-- Sidebar fijo, solo visible en escritorio (md en adelante) --}}
        <nav class="col-md-2 sidebar d-none d-md-block py-4 px-3">
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-block mb-4 ps-2">
                <img src="/canchaya.png" alt="Logo" style="height: 28px;" class="me-2">
                <span class="fw-bold fs-5">Canchaya</span>
            </a>

            <p class="nav-title mb-2 mt-3">General</p>
            <ul class="nav flex-column gap-1 mb-3">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
            </ul>

            <p class="nav-title mb-2 mt-3">Gestión</p>
            <ul class="nav flex-column gap-1 mb-3">
                <li class="nav-item">
                    <a href="{{ route('admin.courts.index') }}"
                       class="nav-link {{ request()->routeIs('admin.courts*') ? 'active' : '' }}">
                        <i class="bi bi-grid me-2"></i> Canchas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reservations.index') }}"
                       class="nav-link {{ request()->routeIs('admin.reservations*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check me-2"></i> Reservas
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.payments.index') }}"
                       class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card me-2"></i> Pagos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.reviews.index') }}"
                       class="nav-link {{ request()->routeIs('admin.reviews*') ? 'active' : '' }}">
                        <i class="bi bi-star me-2"></i> Reseñas
                    </a>
                </li>
            </ul>

            <p class="nav-title mb-2 mt-3">Cuenta</p>
            <ul class="nav flex-column gap-1">
                <li class="nav-item">
                    <span class="nav-link text-white-50">
                        <i class="bi bi-person-circle me-2"></i>{{ auth()->user()->name }}
                    </span>
                </li>
                <li class="nav-item">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link border-0 bg-transparent text-start w-100">
                            <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                        </button>
                    </form>
                </li>
            </ul>
        </nav>

        {{-- Contenido principal --}}
        <main class="col-md-10 main-content px-4 py-4">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h5 class="mb-0 fw-bold">@yield('title', 'Panel Admin')</h5>
                <small class="text-muted d-none d-md-inline">
                    <i class="bi bi-person-circle me-1"></i>{{ auth()->user()->name }}
                </small>
            </div>

            {{-- Alerta éxito --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Alerta errores --}}
            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-circle me-2"></i>
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Contenido de cada vista --}}
            @yield('body')

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>