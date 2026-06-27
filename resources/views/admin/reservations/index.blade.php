@extends('layouts.admin')
@section('title', 'Reservas')

@section('body')

    <p class="text-muted mb-4">Todas las reservas del sistema</p>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Cancha</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $res)
                        @php
                            $colors = ['pendiente'=>'warning','confirmada'=>'success','cancelada'=>'secondary','completada'=>'info'];
                            $color = $colors[$res->status] ?? 'secondary';
                        @endphp
                        <tr>
                            <td>{{ $res->id }}</td>
                            <td>{{ $res->user->name ?? '-' }}</td>
                            <td>{{ $res->court->name ?? '-' }}</td>
                            <td>{{ $res->start_datetime->format('d/m/Y H:i') }}</td>
                            <td>{{ $res->end_datetime->format('d/m/Y H:i') }}</td>
                            <td>${{ number_format($res->total_price, 2) }}</td>
                            <td><span class="badge bg-{{ $color }}">{{ ucfirst($res->status) }}</span></td>
                            <td>
                                <a href="{{ route('admin.reservations.show', $res) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.reservations.destroy', $res) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar esta reserva?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                No hay reservas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection