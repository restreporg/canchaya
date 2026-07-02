@extends('layouts.client')
@section('title', 'Nueva Reserva')

@section('body')

    <h5 class="fw-bold mb-4">Nueva Reserva</h5>

    {{-- Filtros --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('client.reservations.create') }}">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label fw-semibold">Deporte</label>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('client.reservations.create', array_filter(['location' => request('location')])) }}"
                               class="btn btn-sm {{ !request('type') ? 'btn-primary' : 'btn-outline-secondary' }}">
                                Todos
                            </a>
                            @foreach($tipos as $tipo)
                                <a href="{{ route('client.reservations.create', array_filter(['type' => $tipo, 'location' => request('location')])) }}"
                                   class="btn btn-sm {{ request('type') == $tipo ? 'btn-primary' : 'btn-outline-secondary' }}">
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
                        <select name="location" class="form-select" onchange="this.form.submit()">
                            <option value="">Todas las ubicaciones</option>
                            @foreach($ubicaciones as $ubicacion)
                                <option value="{{ $ubicacion }}" {{ request('location') == $ubicacion ? 'selected' : '' }}>
                                    {{ $ubicacion }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <a href="{{ route('client.reservations.create') }}" class="btn btn-outline-secondary w-100">
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
            <i class="bi bi-search fs-1 d-block mb-3"></i>
            <p>No hay canchas disponibles con ese filtro.</p>
            <a href="{{ route('client.reservations.create') }}" class="btn btn-outline-primary">Ver todas</a>
        </div>
    @else
        <div class="row g-4 mb-4">
            @foreach($courts as $court)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100">
                        @if($court->image)
                            <img src="{{ Storage::url($court->image) }}" alt="{{ $court->name }}"
                                 class="card-img-top" style="height: 180px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center"
                                 style="height: 180px;">
                                <i class="bi bi-dribbble fs-1 text-muted"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h6 class="fw-bold mb-0">{{ $court->name }}</h6>
                                <span class="badge bg-primary">{{ $court->type }}</span>
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

                            <button class="btn btn-primary w-100"
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
                                               value="{{ old('start_datetime') }}"
                                               class="form-control form-control-sm @error('start_datetime') is-invalid @enderror">
                                        @error('start_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small">Fecha y hora de fin</label>
                                        <input type="datetime-local" name="end_datetime"
                                               value="{{ old('end_datetime') }}"
                                               class="form-control form-control-sm @error('end_datetime') is-invalid @enderror">
                                        @error('end_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <button type="submit" class="btn btn-success w-100 btn-sm">
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