@extends('layouts.client')
@section('title', 'Mis Reservas')

@push('styles')
<style>
    /* Mismo lenguaje visual que "Nueva Reserva": tarjetas redondeadas con hover suave */
    .reservation-card {
        border-radius: 14px;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .reservation-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.06) !important;
    }

    .btn-ver-detalle { border-radius: 999px; }
</style>
@endpush

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Mis Reservas</h5>
        <a href="{{ route('client.reservations.create') }}" class="btn btn-primary btn-sm rounded-pill px-3">
            <i class="bi bi-plus-circle me-1"></i> Nueva Reserva
        </a>
    </div>

    @if($reservations->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
            <p class="mb-3">Aún no tienes reservas. ¡Es un buen momento para hacer una!</p>
            <a href="{{ route('client.reservations.create') }}" class="btn btn-primary rounded-pill px-4">Reservar una cancha</a>
        </div>
    @else
        <div class="row g-3">
            @foreach($reservations as $res)
                @php
                    $statusClass = 'status-' . $res->status;
                @endphp
                <div class="col-12">
                    <div class="card border-0 shadow-sm reservation-card">
                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <h6 class="mb-1 fw-bold">{{ $res->court->name ?? 'Cancha' }}</h6>
                                <small class="text-muted">
                                    <i class="bi bi-calendar me-1"></i>
                                    {{ $res->start_datetime->format('d/m/Y') }}
                                    &nbsp;·&nbsp;
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $res->start_datetime->format('H:i') }} – {{ $res->end_datetime->format('H:i') }}
                                    &nbsp;·&nbsp;
                                    <strong>${{ number_format($res->total_price, 2) }}</strong>
                                </small>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge badge-pill-soft {{ $statusClass }}">{{ ucfirst($res->status) }}</span>
                                <a href="{{ route('client.reservations.show', $res) }}" class="btn btn-sm btn-outline-primary btn-ver-detalle px-3">
                                    Ver detalle
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Paginación --}}
        <div class="mt-4 d-flex justify-content-center">
            {{ $reservations->links() }}
        </div>
    @endif

@endsection