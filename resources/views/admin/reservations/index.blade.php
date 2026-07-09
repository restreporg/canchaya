@extends('layouts.admin')
@section('title', 'Reservas')

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Reservas</h5>
        <span class="text-muted small">{{ $reservations->total() }} en total</span>
    </div>

    @if($reservations->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
            <p class="mb-0">Todavía no hay reservas registradas.</p>
        </div>
    @else
        {{-- Tabla: solo en escritorio --}}
        <div class="card border-0 shadow-sm card-soft d-none d-md-block">
            <div class="card-body p-0">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Cliente</th>
                            <th>Cancha</th>
                            <th>Fecha</th>
                            <th>Horario</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th class="text-end pe-3">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $res)
                            <tr>
                                <td class="ps-3">{{ $res->user->name ?? '-' }}</td>
                                <td>{{ $res->court->name ?? '-' }}</td>
                                <td>{{ $res->start_datetime->format('d/m/Y') }}</td>
                                <td>{{ $res->start_datetime->format('H:i') }} – {{ $res->end_datetime->format('H:i') }}</td>
                                <td>${{ number_format($res->total_price, 2) }}</td>
                                <td>
                                    <span class="badge badge-pill-soft status-{{ $res->status }}">{{ ucfirst($res->status) }}</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.reservations.show', $res) }}" class="btn btn-sm btn-outline-primary btn-pill px-3">
                                        Ver detalle
                                    </a>
                                    <form action="{{ route('admin.reservations.destroy', $res) }}" method="POST"
                                          class="d-inline" onsubmit="return confirm('¿Eliminar esta reserva?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger btn-pill px-3">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tarjetas: solo en móvil --}}
        <div class="d-md-none">
            @foreach($reservations as $res)
                <div class="card border-0 shadow-sm card-soft mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="fw-bold mb-0">{{ $res->user->name ?? '-' }}</h6>
                            <span class="badge badge-pill-soft status-{{ $res->status }}">{{ ucfirst($res->status) }}</span>
                        </div>

                        <p class="mb-1 small text-muted">
                            <i class="bi bi-geo-alt me-1"></i>{{ $res->court->name ?? '-' }}
                        </p>
                        <p class="mb-1 small text-muted">
                            <i class="bi bi-calendar me-1"></i>{{ $res->start_datetime->format('d/m/Y') }}
                            &nbsp;·&nbsp;
                            <i class="bi bi-clock me-1"></i>{{ $res->start_datetime->format('H:i') }} – {{ $res->end_datetime->format('H:i') }}
                        </p>
                        <p class="fw-bold text-primary mb-3">${{ number_format($res->total_price, 2) }}</p>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.reservations.show', $res) }}" class="btn btn-sm btn-outline-primary flex-fill">
                                Ver detalle
                            </a>
                            <form action="{{ route('admin.reservations.destroy', $res) }}" method="POST"
                                  onsubmit="return confirm('¿Eliminar esta reserva?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $reservations->links() }}
        </div>
    @endif

@endsection