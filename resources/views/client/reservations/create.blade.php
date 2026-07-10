@extends('layouts.client')
@section('title', 'Nueva Reserva')

@push('styles')
<style>
    /* Colores e íconos por deporte, para que cada tarjeta tenga identidad propia */
    .sport-Fútbol      { background: linear-gradient(135deg, #e6f9ee, #c8f0d9); color: #1db954; }
    .sport-Tenis       { background: linear-gradient(135deg, #fff6e0, #ffe9b3); color: #d99a00; }
    .sport-Basketball  { background: linear-gradient(135deg, #ffece6, #ffd0c2); color: #e8571f; }
    .sport-Pádel       { background: linear-gradient(135deg, #e8f0ff, #cfe0ff); color: #2563eb; }
    .sport-default     { background: linear-gradient(135deg, #f1f0ff, #dcd8ff); color: #6d5bd0; }

    .court-card {
        border-radius: 14px;
        overflow: hidden;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .court-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08) !important;
    }
    .court-thumb {
        height: 160px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.75rem;
    }
    .btn-reservar {
        border-radius: 10px;
    }
</style>
@endpush

@section('body')

    <h5 class="fw-bold mb-4">Nueva Reserva</h5>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
        <div class="card-body">
            <form method="GET" action="{{ route('client.reservations.create') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Deporte</label>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('client.reservations.create', array_filter(['location' => request('location')])) }}"
                               class="btn btn-sm rounded-pill px-3 {{ !request('type') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                Todos
                            </a>
                            @foreach($tipos as $tipo)
                                <a href="{{ route('client.reservations.create', array_filter(['type' => $tipo, 'location' => request('location')])) }}"
                                   class="btn btn-sm rounded-pill px-3 {{ request('type') == $tipo ? 'btn-primary' : 'btn-outline-secondary' }}">
                                    @if($tipo == 'Fútbol') <i class="bi bi-dribbble me-1"></i>
                                    @elseif($tipo == 'Tenis') <i class="bi bi-circle me-1"></i>
                                    @elseif($tipo == 'Basketball') <i class="bi bi-trophy me-1"></i>
                                    @elseif($tipo == 'Pádel') <i class="bi bi-grid me-1"></i>
                                    @else <i class="bi bi-lightning me-1"></i>
                                    @endif
                                    {{ $tipo }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Ubicación</label>
                        <select name="location" class="form-select rounded-3" onchange="this.form.submit()">
                            <option value="">Todas las ubicaciones</option>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion }}" {{ request('location') == $ubicacion ? 'selected' : '' }}>
                                    {{ $ubicacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('client.reservations.create') }}" class="btn btn-outline-secondary w-100 rounded-3">
                            <i class="bi bi-x-circle me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Canchas --}}
    @if($courts->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-emoji-frown fs-1 d-block mb-3"></i>
            <p>No encontramos canchas con ese filtro. ¡Probemos con otro!</p>
            <a href="{{ route('client.reservations.create') }}" class="btn btn-outline-primary rounded-pill px-4">Ver todas</a>
        </div>
    @else
        <div class="row g-4 mb-4">
            @foreach($courts as $court)
                @php
                    $sportIcons = [
                        'Fútbol' => 'bi-dribbble',
                        'Tenis' => 'bi-circle',
                        'Basketball' => 'bi-trophy',
                        'Pádel' => 'bi-grid',
                    ];
                    $icon = $sportIcons[$court->type] ?? 'bi-lightning';
                    $sportClass = 'sport-' . ($court->type ?? 'default');
                @endphp
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 court-card">
                        @if($court->image)
                            <img src="{{ Storage::url($court->image) }}" alt="{{ $court->name }}"
                                 class="card-img-top" style="height: 160px; object-fit: cover;">
                        @else
                            <div class="court-thumb {{ $sportClass }}">
                                <i class="bi {{ $icon }}"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">{{ $court->name }}</h6>
                                <span class="badge badge-pill-soft {{ $sportClass }}">{{ $court->type }}</span>
                            </div>
                            @if($court->location)
                                <p class="text-muted small mb-1">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $court->location }}
                                </p>
                            @endif
                            @if($court->description)
                                <p class="text-muted small mb-2">{{ $court->description }}</p>
                            @endif
                            <p class="fw-bold text-primary mb-3">${{ number_format($court->price_per_hour, 0) }}/hora</p>

                            <button class="btn btn-primary btn-reservar w-100"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#form-{{ $court->id }}">
                                <i class="bi bi-calendar-check me-1"></i> Reservar
                            </button>
                        </div>

                        {{-- Formulario de reserva --}}
                        <div class="collapse" id="form-{{ $court->id }}">
                            <div class="card-body border-top">
                                <form action="{{ route('client.reservations.store') }}" method="POST"
                                      class="reservation-form" data-price="{{ $court->price_per_hour }}">
                                    @csrf
                                    <input type="hidden" name="court_id" value="{{ $court->id }}">
                                    <input type="hidden" name="start_datetime" class="js-start-datetime">
                                    <input type="hidden" name="end_datetime" class="js-end-datetime">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Fecha</label>
                                        <input type="date" class="form-control form-control-sm js-res-date"
                                               min="{{ now()->toDateString() }}"
                                               value="{{ now()->toDateString() }}">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Hora de inicio</label>
                                        <select class="form-select form-select-sm js-start-time">
                                            @for($h = 6; $h <= 23; $h++)
                                                @foreach(['00', '30'] as $m)
                                                    @php $t = sprintf('%02d:%s', $h, $m); @endphp
                                                    <option value="{{ $t }}">{{ $t }}</option>
                                                @endforeach
                                            @endfor
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small d-block">Duración</label>
                                        <div class="btn-group btn-group-sm w-100 js-duration-group" role="group">
                                            <button type="button" class="btn btn-outline-primary js-duration-btn active" data-hours="1">1h</button>
                                            <button type="button" class="btn btn-outline-primary js-duration-btn" data-hours="1.5">1h30</button>
                                            <button type="button" class="btn btn-outline-primary js-duration-btn" data-hours="2">2h</button>
                                            <button type="button" class="btn btn-outline-primary js-duration-btn" data-hours="3">3h</button>
                                        </div>
                                    </div>

                                    <div class="rounded-3 p-2 mb-3 small js-summary" style="background:#f1f8f4;">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Horario</span>
                                            <span class="fw-semibold js-summary-time">06:00 – 07:00</span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">Total</span>
                                            <span class="fw-bold text-primary js-summary-price">$0</span>
                                        </div>
                                    </div>

                                    @error('start_datetime')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                                    @error('end_datetime')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                                    <button type="submit" class="btn btn-success w-100 btn-sm rounded-3">
                                        <i class="bi bi-check-circle me-1"></i> Confirmar reserva
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection

@push('scripts')
<script>
document.querySelectorAll('.reservation-form').forEach(function (form) {
    const pricePerHour = parseFloat(form.dataset.price);
    const dateInput     = form.querySelector('.js-res-date');
    const startSelect   = form.querySelector('.js-start-time');
    const durationBtns  = form.querySelectorAll('.js-duration-btn');
    const startHidden   = form.querySelector('.js-start-datetime');
    const endHidden      = form.querySelector('.js-end-datetime');
    const summaryTime   = form.querySelector('.js-summary-time');
    const summaryPrice  = form.querySelector('.js-summary-price');

    let hours = parseFloat(form.querySelector('.js-duration-btn.active').dataset.hours);

    function addMinutes(timeStr, minutesToAdd) {
        const [h, m] = timeStr.split(':').map(Number);
        const total = h * 60 + m + minutesToAdd;
        const newH = Math.floor(((total % 1440) + 1440) % 1440 / 60);
        const newM = ((total % 60) + 60) % 60;
        return String(newH).padStart(2, '0') + ':' + String(newM).padStart(2, '0');
    }

    function update() {
        const date = dateInput.value;
        const start = startSelect.value;
        const end = addMinutes(start, hours * 60);

        startHidden.value = date + 'T' + start;
        endHidden.value   = date + 'T' + end;

        summaryTime.textContent = start + ' – ' + end;

        const total = pricePerHour * hours;
        summaryPrice.textContent = '$' + total.toLocaleString('es-CO');
    }

    function setDefaultStartTime() {
        const today = new Date().toISOString().split('T')[0];
        if (dateInput.value !== today) {
            return;
        }

        const now = new Date();
        const nowMinutes = now.getHours() * 60 + now.getMinutes() + 30; // margen de 30 min

        let chosen = null;
        for (const option of startSelect.options) {
            const [h, m] = option.value.split(':').map(Number);
            if (h * 60 + m >= nowMinutes) {
                chosen = option.value;
                break;
            }
        }

        if (chosen) {
            startSelect.value = chosen;
        } else {
            // Ya no quedan horarios hoy: saltamos al día siguiente
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            dateInput.value = tomorrow.toISOString().split('T')[0];
            startSelect.selectedIndex = 0;
        }
    }

    durationBtns.forEach(function (btn) {
        btn.addEventListener('click', function () {
            durationBtns.forEach(b => b.classList.remove('active', 'btn-primary'));
            durationBtns.forEach(b => b.classList.add('btn-outline-primary'));
            btn.classList.add('active', 'btn-primary');
            btn.classList.remove('btn-outline-primary');
            hours = parseFloat(btn.dataset.hours);
            update();
        });
    });

    dateInput.addEventListener('change', function () {
        setDefaultStartTime();
        update();
    });
    startSelect.addEventListener('change', update);

    setDefaultStartTime();
    update();
});
</script>
@endpush