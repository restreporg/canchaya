@extends('layouts.admin')
@section('title', 'Detalle de Reserva')

@section('body')

    <div class="mb-4">
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Reserva #{{ $reservation->id }}</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width:40%">Cliente</td>
                            <td>{{ $reservation->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cancha</td>
                            <td>{{ $reservation->court->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Inicio</td>
                            <td>{{ $reservation->start_datetime->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fin</td>
                            <td>{{ $reservation->end_datetime->format('d/m/Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Total</td>
                            <td class="fw-bold">${{ number_format($reservation->total_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado</td>
                            <td>
                                @php
                                    $colors = ['pendiente'=>'warning','confirmada'=>'success','cancelada'=>'secondary','completada'=>'info'];
                                    $color = $colors[$reservation->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($reservation->status) }}</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Pago</div>
                <div class="card-body">
                    @if($reservation->payment)
                        <p class="mb-1">Monto: <strong>${{ number_format($reservation->payment->amount, 2) }}</strong></p>
                        <p class="mb-1">Método: {{ ucfirst($reservation->payment->method) }}</p>
                        <p class="mb-0">Estado: <span class="badge bg-success">{{ ucfirst($reservation->payment->status) }}</span></p>
                    @else
                        <p class="text-muted mb-0">Sin pago registrado.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Cambiar Estado</div>
                <div class="card-body">
                    <form action="{{ route('admin.reservations.update', $reservation) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="input-group">
                            <select name="status" class="form-select">
                                @foreach(['pendiente','confirmada','cancelada','completada'] as $s)
                                    <option value="{{ $s }}" {{ $reservation->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Reseña</div>
                <div class="card-body">
                    @if($reservation->review)
                        <div class="mb-1">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $reservation->review->rating ? '-fill' : '' }} text-warning"></i>
                            @endfor
                        </div>
                        <p class="mb-0 text-muted">{{ $reservation->review->comment ?? 'Sin comentario.' }}</p>
                    @else
                        <p class="text-muted mb-0">Sin reseña aún.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection