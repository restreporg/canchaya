@extends('layouts.admin')
@section('title', 'Detalle de Pago')

@section('body')

    <div class="mb-4">
        <a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Información del Pago</div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width:40%">ID</td>
                            <td>#{{ $payment->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Cliente</td>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Monto</td>
                            <td class="fw-bold fs-5">${{ number_format($payment->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Método</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado</td>
                            <td>
                                @php
                                    $colors = ['pendiente'=>'warning','pagado'=>'success','fallido'=>'danger','reembolsado'=>'info'];
                                    $color = $colors[$payment->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-muted">Fecha de pago</td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white fw-semibold">Reserva Asociada</div>
                <div class="card-body">
                    @if($payment->reservation)
                        <table class="table table-sm table-borderless mb-0">
                            <tr>
                                <td class="text-muted" style="width:40%">Cancha</td>
                                <td>{{ $payment->reservation->court->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Inicio</td>
                                <td>{{ $payment->reservation->start_datetime->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Fin</td>
                                <td>{{ $payment->reservation->end_datetime->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td class="text-muted">Estado reserva</td>
                                <td>{{ ucfirst($payment->reservation->status) }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('admin.reservations.show', $payment->reservation) }}" class="btn btn-sm btn-outline-primary mt-2">
                            Ver reserva
                        </a>
                    @else
                        <p class="text-muted mb-0">Sin reserva asociada.</p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Cambiar Estado</div>
                <div class="card-body">
                    <form action="{{ route('admin.payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="input-group">
                            <select name="status" class="form-select">
                                @foreach(['pendiente','pagado','fallido','reembolsado'] as $s)
                                    <option value="{{ $s }}" {{ $payment->status === $s ? 'selected' : '' }}>
                                        {{ ucfirst($s) }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-primary" type="submit">Guardar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection