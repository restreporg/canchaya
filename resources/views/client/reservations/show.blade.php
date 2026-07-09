@extends('layouts.client')
@section('title', 'Detalle de Reserva')

@push('styles')
<style>
    /* Mismos badges pastel que en "Mis Reservas", para consistencia visual */
    .badge-pill-soft {
        border-radius: 999px;
        font-weight: 600;
        padding: .4em .9em;
    }
    .status-pendiente  { background: #fff6e0; color: #d99a00; }
    .status-confirmada { background: #e6f9ee; color: #178a45; }
    .status-cancelada  { background: #f1f2f3; color: #6c757d; }
    .status-completada { background: #e8f0ff; color: #2563eb; }
    .status-pagado      { background: #e6f9ee; color: #178a45; }

    .card-soft { border-radius: 14px; }
    .btn-pill  { border-radius: 999px; }
</style>
@endpush

@section('body')

    <div class="mb-4">
        <a href="{{ route('client.reservations.index') }}" class="btn btn-sm btn-outline-secondary btn-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Mis reservas
        </a>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm mb-3 card-soft">
                <div class="card-header bg-white fw-semibold border-0 pt-3">
                    <i class="bi bi-calendar-check me-2"></i>Reserva #{{ $reservation->id }}
                </div>
                <div class="card-body">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <td class="text-muted" style="width:40%">Cancha</td>
                            <td class="fw-semibold">{{ $reservation->court->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Tipo</td>
                            <td>{{ $reservation->court->type ?? '-' }}</td>
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
                            <td class="fw-bold fs-5 text-primary">${{ number_format($reservation->total_price, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Estado</td>
                            <td>
                                <span class="badge badge-pill-soft status-{{ $reservation->status }}">{{ ucfirst($reservation->status) }}</span>
                            </td>
                        </tr>
                    </table>

                    @if($reservation->status === 'pendiente')
                        <hr>
                        <form action="{{ route('client.reservations.destroy', $reservation) }}"
                              method="POST" onsubmit="return confirm('¿Cancelar esta reserva?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-pill px-3">
                                <i class="bi bi-x-circle me-1"></i> Cancelar reserva
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm card-soft">
                <div class="card-header bg-white fw-semibold border-0 pt-3">
                    <i class="bi bi-credit-card me-2"></i>Pago
                </div>
                <div class="card-body">
                    @if($reservation->payment)
                        <p class="mb-1">Método: <strong>{{ ucfirst($reservation->payment->method) }}</strong></p>
                        <p class="mb-0">Estado: <span class="badge badge-pill-soft status-pagado">{{ ucfirst($reservation->payment->status) }}</span></p>
                    @elseif($reservation->status !== 'cancelada')
                        <p class="text-muted mb-3">No se ha registrado pago aún.</p>
                        <form action="{{ route('client.payments.store', $reservation) }}" method="POST">
                            @csrf
                            <div class="input-group">
                                <select name="method" class="form-select @error('method') is-invalid @enderror">
                                    <option value="efectivo">Efectivo</option>
                                    <option value="tarjeta">Tarjeta</option>
                                    <option value="transferencia">Transferencia</option>
                                </select>
                                <button class="btn btn-success btn-pill" type="submit">Pagar</button>
                            </div>
                            @error('method')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            @error('payment')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </form>
                    @else
                        <p class="text-muted mb-0">Reserva cancelada.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm card-soft">
                <div class="card-header bg-white fw-semibold border-0 pt-3">
                    <i class="bi bi-star me-2"></i>Reseña
                </div>
                <div class="card-body">
                    @if($reservation->review)
                        <div class="mb-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $reservation->review->rating ? '-fill' : '' }} text-warning fs-5"></i>
                            @endfor
                        </div>
                        <p class="text-muted mb-3">{{ $reservation->review->comment ?? 'Sin comentario.' }}</p>
                        <form action="{{ route('client.reviews.destroy', $reservation->review) }}"
                              method="POST" onsubmit="return confirm('¿Eliminar tu reseña?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger btn-pill px-3">
                                <i class="bi bi-trash me-1"></i> Eliminar reseña
                            </button>
                        </form>
                    @elseif($reservation->status === 'completada')
                        <p class="text-muted mb-3">Deja tu opinión sobre la cancha.</p>
                        <form action="{{ route('client.reviews.store', $reservation) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Calificación</label>
                                <select name="rating" class="form-select @error('rating') is-invalid @enderror">
                                    @for($i = 5; $i >= 1; $i--)
                                        <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>
                                            {{ $i }} estrella{{ $i > 1 ? 's' : '' }}
                                        </option>
                                    @endfor
                                </select>
                                @error('rating')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Comentario <span class="text-muted fw-normal">(opcional)</span></label>
                                <textarea name="comment" rows="3"
                                          class="form-control @error('comment') is-invalid @enderror"
                                          placeholder="¿Cómo fue tu experiencia?">{{ old('comment') }}</textarea>
                                @error('comment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            @error('review')<div class="text-danger small mb-2">{{ $message }}</div>@enderror
                            <button type="submit" class="btn btn-warning w-100 btn-pill">
                                <i class="bi bi-star me-1"></i> Enviar reseña
                            </button>
                        </form>
                    @else
                        <p class="text-muted mb-0">Podrás dejar una reseña cuando la reserva esté completada.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection