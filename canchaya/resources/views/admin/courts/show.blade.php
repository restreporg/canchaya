@extends('layouts.admin')
@section('title', 'Detalle de Cancha')

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.courts.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
        <div>
            <a href="{{ route('admin.courts.schedules.index', $court) }}" class="btn btn-sm btn-outline-primary me-2">
                <i class="bi bi-clock me-1"></i> Horarios
            </a>
            <a href="{{ route('admin.courts.edit', $court) }}" class="btn btn-sm btn-warning">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <h5 class="card-title fw-bold mb-3">{{ $court->name }}</h5>
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width:40%">Tipo</td>
                            <td class="fw-semibold">{{ $court->type }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Precio/hora</td>
                            <td class="fw-semibold">${{ number_format($court->price_per_hour, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Ubicación</td>
                            <td>{{ $court->location ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado</td>
                            <td>
                                @if($court->is_active)
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Descripción</td>
                            <td>{{ $court->description ?? '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold border-bottom">
                    <i class="bi bi-calendar-check me-2"></i>Últimas reservas
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($court->reservations->take(8) as $res)
                                <tr>
                                    <td>{{ $res->user->name ?? '-' }}</td>
                                    <td>{{ $res->start_datetime->format('d/m/Y H:i') }}</td>
                                    <td>{{ $res->end_datetime->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php
                                            $colors = ['pendiente'=>'warning','confirmada'=>'success','cancelada'=>'secondary','completada'=>'info'];
                                            $color = $colors[$res->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $color }}">{{ ucfirst($res->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sin reservas aún</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection