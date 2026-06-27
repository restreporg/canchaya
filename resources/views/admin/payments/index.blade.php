@extends('layouts.admin')
@section('title', 'Pagos')

@section('body')

    <p class="text-muted mb-4">Todos los pagos registrados en el sistema</p>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Cancha</th>
                        <th>Monto</th>
                        <th>Método</th>
                        <th>Estado</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>
                            <td>{{ $payment->user->name ?? '-' }}</td>
                            <td>{{ $payment->reservation->court->name ?? '-' }}</td>
                            <td class="fw-semibold">${{ number_format($payment->amount, 2) }}</td>
                            <td>{{ ucfirst($payment->method) }}</td>
                            <td>
                                @php
                                    $colors = ['pendiente'=>'warning','pagado'=>'success','fallido'=>'danger','reembolsado'=>'info'];
                                    $color = $colors[$payment->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $color }}">{{ ucfirst($payment->status) }}</span>
                            </td>
                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                                No hay pagos registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection