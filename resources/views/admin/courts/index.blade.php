@extends('layouts.admin')
@section('title', 'Canchas')

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <p class="text-muted mb-0">Listado de todas las canchas registradas</p>
        <a href="{{ route('admin.courts.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Cancha
        </a>
    </div>

    <div class="card shadow-sm border-0">
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
                                    <span class="badge bg-success">Activa</span>
                                @else
                                    <span class="badge bg-secondary">Inactiva</span>
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

@endsection