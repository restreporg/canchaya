@extends('layouts.admin')
@section('title', 'Horarios - ' . $court->name)

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.courts.show', $court) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a la cancha
        </a>
        <a href="{{ route('admin.courts.schedules.create', $court) }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle me-1"></i> Agregar horario
        </a>
    </div>

    @php
        $dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'];
    @endphp

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>Día</th>
                        <th>Apertura</th>
                        <th>Cierre</th>
                        <th>Disponible</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($schedules as $schedule)
                        <tr>
                            <td class="fw-semibold">{{ $dias[$schedule->day_of_week] }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->open_time)->format('H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->close_time)->format('H:i') }}</td>
                            <td>
                                @if($schedule->is_available ?? true)
                                    <span class="badge bg-success">Sí</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.courts.schedules.edit', [$court, $schedule]) }}"
                                   class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.courts.schedules.destroy', [$court, $schedule]) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este horario?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-clock fs-4 d-block mb-2"></i>
                                No hay horarios configurados para esta cancha.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection