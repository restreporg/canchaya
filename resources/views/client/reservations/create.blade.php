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
    .badge-pill-soft {
        border-radius: 999px;
        font-weight: 600;
        padding: .4em .9em;
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
                                <form action="{{ route('client.reservations.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="court_id" value="{{ $court->id }}">

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Fecha y hora de inicio</label>
                                        <input type="datetime-local" name="start_datetime"
                                               min="{{ now()->format('Y-m-d\TH:i') }}"
                                               value="{{ old('start_datetime') }}"
                                               class="form-control form-control-sm @error('start_datetime') is-invalid @enderror">
                                        @error('start_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Fecha y hora de fin</label>
                                        <input type="datetime-local" name="end_datetime"
                                               min="{{ now()->format('Y-m-d\TH:i') }}"
                                               value="{{ old('end_datetime') }}"
                                               class="form-control form-control-sm @error('end_datetime') is-invalid @enderror">
                                        @error('end_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

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