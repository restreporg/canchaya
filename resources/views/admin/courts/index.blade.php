@extends('layouts.admin')
@section('title', 'Canchas')

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <p class="text-muted mb-0">Listado de todas las canchas registradas</p>
        <a href="{{ route('admin.courts.create') }}" class="btn btn-primary btn-sm btn-pill px-3">
            <i class="bi bi-plus-circle me-1"></i> Nueva Cancha
        </a>
    </div>

    {{-- Tabla: solo en escritorio (md en adelante) --}}
    <div class="card shadow-sm border-0 card-soft d-none d-md-block">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Precio/hora</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courts as $court)
                        <tr>
                            <td>{{ $court->id }}</td>
                            <td class="fw-semibold">{{ $court->name }}</td>
                            <td>{{ $court->type }}</td>
                            <td>${{ number_format($court->price_per_hour, 2) }}</td>
                            <td>{{ $court->location ?? '-' }}</td>
                            <td>
                                @if($court->is_active)
                                    <span class="badge badge-pill-soft status-activa">Activa</span>
                                @else
                                    <span class="badge badge-pill-soft status-inactiva">Inactiva</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.courts.show', $court) }}"
                                   class="btn btn-sm btn-outline-info me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.courts.edit', $court) }}"
                                   class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.courts.destroy', $court) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Desactivar esta cancha?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-slash-circle"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                No hay canchas registradas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tarjetas: solo en móvil (debajo de md) --}}
    <div class="d-md-none">
        @forelse($courts as $court)
            <div class="card shadow-sm border-0 card-soft mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small">#{{ $court->id }}</span>
                            <h6 class="fw-bold mb-0">{{ $court->name }}</h6>
                        </div>
                        @if($court->is_active)
                            <span class="badge badge-pill-soft status-activa">Activa</span>
                        @else
                            <span class="badge badge-pill-soft status-inactiva">Inactiva</span>
                        @endif
                    </div>

                    <p class="mb-1 small text-muted">
                        <i class="bi bi-tag me-1"></i>{{ $court->type }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-geo-alt me-1"></i>{{ $court->location ?? '-' }}
                    </p>
                    <p class="fw-bold text-primary mb-3">${{ number_format($court->price_per_hour, 2) }}/hora</p>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.courts.show', $court) }}"
                           class="btn btn-sm btn-outline-info flex-fill">
                            <i class="bi bi-eye me-1"></i> Ver
                        </a>
                        <a href="{{ route('admin.courts.edit', $court) }}"
                           class="btn btn-sm btn-outline-warning flex-fill">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="{{ route('admin.courts.destroy', $court) }}"
                              method="POST" class="flex-fill"
                              onsubmit="return confirm('¿Desactivar esta cancha?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-slash-circle"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                No hay canchas registradas aún.
            </div>
        @endforelse
    </div>

@endsection