@extends('layouts.admin')
@section('title', 'Pagos')

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <p class="text-muted mb-0">Todos los pagos registrados en el sistema</p>
        <span class="text-muted small">{{ $payments->total() }} en total</span>
    </div>

    {{-- Tabla: solo en escritorio --}}
    <div class="card border-0 shadow-sm card-soft d-none d-md-block">
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
                                <span class="badge badge-pill-soft status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
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

    {{-- Tarjetas: solo en móvil --}}
    <div class="d-md-none">
        @forelse($payments as $payment)
            <div class="card border-0 shadow-sm card-soft mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small">#{{ $payment->id }}</span>
                            <h6 class="fw-bold mb-0">{{ $payment->user->name ?? '-' }}</h6>
                        </div>
                        <span class="badge badge-pill-soft status-{{ $payment->status }}">{{ ucfirst($payment->status) }}</span>
                    </div>

                    <p class="mb-1 small text-muted">
                        <i class="bi bi-geo-alt me-1"></i>{{ $payment->reservation->court->name ?? '-' }}
                    </p>
                    <p class="mb-1 small text-muted">
                        <i class="bi bi-credit-card me-1"></i>{{ ucfirst($payment->method) }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-calendar me-1"></i>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : '-' }}
                    </p>
                    <p class="fw-bold text-primary mb-3">${{ number_format($payment->amount, 2) }}</p>

                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-outline-info w-100">
                        <i class="bi bi-eye me-1"></i> Ver detalle
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="bi bi-inbox fs-4 d-block mb-2"></i>
                No hay pagos registrados.
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $payments->links() }}
    </div>

@endsection