@extends('layouts.client')
@section('title', 'Detalle de Pago')

@section('body')

    <div class="mb-4">
        <a href="{{ route('client.reservations.show', $reservation) }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver a la reserva
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 480px;">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-receipt me-2"></i>Comprobante de Pago
        </div>
        <div class="card-body">
            @if($payment)
                <table class="table table-sm table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width:45%">ID de pago</td>
                        <td>#{{ $payment->id }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Cancha</td>
                        <td>{{ $reservation->court->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Fecha reserva</td>
                        <td>{{ $reservation->start_datetime->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Monto pagado</td>
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
            @else
                <p class="text-muted mb-0">Esta reserva no tiene pago registrado aún.</p>
                <a href="{{ route('client.reservations.show', $reservation) }}" class="btn btn-primary btn-sm mt-3">
                    Ir a pagar
                </a>
            @endif
        </div>
    </div>

@endsection