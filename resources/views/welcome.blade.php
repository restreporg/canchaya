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

        /* Cómo funciona */
        .step-number {
            width: 56px; height: 56px; border-radius: 50%;
            background: var(--verde);
            color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; font-weight: 800;
            margin: 0 auto 1.25rem auto;
        }
        .step-connector {
            display: none;
        }
        @media (min-width: 768px) {
            .step-connector {
                display: block;
                position: absolute;
                top: 28px;
                left: 60%;
                width: 80%;
                height: 2px;
                background: repeating-linear-gradient(90deg, #cfd8d4 0 8px, transparent 8px 16px);
                z-index: 0;
            }
            .step-col:last-child .step-connector { display: none; }
        }
        .step-col { position: relative; z-index: 1; }

        /* FAQ */
        .accordion-faq .accordion-item {
            border: none;
            border-radius: 12px !important;
            overflow: hidden;
            margin-bottom: 1rem;
        }
        .accordion-faq .accordion-button {
            font-weight: 600;
            background: #fff;
        }
        .accordion-faq .accordion-button:not(.collapsed) {
            background: rgba(29,185,84,0.08);
            color: var(--verde-oscuro);
            box-shadow: none;
        }
        .accordion-faq .accordion-button:focus {
            box-shadow: none;
            border-color: rgba(29,185,84,0.25);
        }
        .accordion-faq .accordion-button::after {
            filter: invert(48%) sepia(89%) saturate(419%) hue-rotate(93deg) brightness(93%) contrast(92%);
        }

        /* CTA final */
        .cta-final {
            background: linear-gradient(135deg, var(--verde-oscuro) 0%, var(--verde-dark) 100%);
            border-radius: 24px;
        }
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
                    <a href="/downloads/canchaya.apk" class="btn btn-outline-light btn-sm" dowload>
                        <i class="bi bi-download me-1"></i>Descargar App
                    </a>
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

    {{-- Cómo funciona --}}
    <section class="py-6" style="padding: 80px 0;">
        <div class="container">
            <h2 class="text-center fw-bold mb-2">¿Cómo funciona?</h2>
            <p class="text-center text-muted mb-5">Reservar tu cancha nunca fue tan simple</p>

            <div class="row g-4 text-center">
                <div class="col-md-4 step-col">
                    <div class="step-connector"></div>
                    <div class="step-number">1</div>
                    <h5 class="fw-bold">Busca tu cancha</h5>
                    <p class="text-muted mb-0">Filtra por deporte, ubicación y fecha para encontrar la opción ideal.</p>
                </div>
                <div class="col-md-4 step-col">
                    <div class="step-connector"></div>
                    <div class="step-number">2</div>
                    <h5 class="fw-bold">Elige tu horario</h5>
                    <p class="text-muted mb-0">Consulta la disponibilidad en tiempo real y selecciona el horario que más te convenga.</p>
                </div>
                <div class="col-md-4 step-col">
                    <div class="step-number">3</div>
                    <h5 class="fw-bold">Confirma y juega</h5>
                    <p class="text-muted mb-0">Paga de forma segura y recibe la confirmación al instante. ¡Listo para jugar!</p>
                </div>
            </div>
        </div>
    </section>

    {{-- FAQ --}}
    <section class="py-6 bg-light" style="padding: 80px 0;">
        <div class="container">
            <h2 class="text-center fw-bold mb-2">Preguntas frecuentes</h2>
            <p class="text-center text-muted mb-5">Resolvemos las dudas más comunes sobre Canchaya</p>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="accordion accordion-faq" id="faqAccordion">

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                    ¿Cómo reservo una cancha?
                                </button>
                            </h2>
                            <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Regístrate, elige el deporte y la ubicación, selecciona la cancha y el horario disponible, y confirma tu pago. Recibirás la confirmación al instante.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                    ¿Qué métodos de pago aceptan?
                                </button>
                            </h2>
                            <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Puedes pagar con tarjeta de crédito o débito, transferencia bancaria o efectivo en el lugar, según lo que ofrezca la cancha que elijas.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                    ¿Puedo cancelar o cambiar mi reserva?
                                </button>
                            </h2>
                            <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Sí, puedes cancelar o reprogramar tu reserva desde la sección "Mis Reservas" siempre que lo hagas con anticipación, según la política de cada cancha.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                    ¿Tiene algún costo usar Canchaya?
                                </button>
                            </h2>
                            <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    Crear tu cuenta y buscar canchas es totalmente gratis. Solo pagas el valor de la reserva al momento de confirmarla.
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                    ¿Necesito descargar la app para reservar?
                                </button>
                            </h2>
                            <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
                                    No es obligatorio, puedes reservar desde el navegador. Sin embargo, la app te permite recibir notificaciones y gestionar tus reservas más fácilmente.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA final --}}
    <section class="py-6" style="padding: 80px 0;">
        <div class="container">
            <div class="cta-final text-center text-white py-5 px-4">
                <h2 class="fw-bold mb-3">¿Listo para reservar tu cancha?</h2>
                <p class="text-white-50 mb-4 mx-auto" style="max-width: 500px;">
                    Únete a miles de usuarios que ya reservan sus canchas favoritas en segundos.
                </p>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-lg px-5">
                            <i class="bi bi-speedometer2 me-2"></i>Ir al panel
                        </a>
                    @else
                        <a href="{{ route('client.reservations.create') }}" class="btn btn-light btn-lg px-5">
                            <i class="bi bi-lightning-charge me-2"></i>Reservar ahora
                        </a>
                    @endif
                @else
                    <a href="{{ route('register') }}" class="btn btn-light btn-lg px-5">
                        <i class="bi bi-lightning-charge me-2"></i>Crear cuenta gratis
                    </a>
                @endauth
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