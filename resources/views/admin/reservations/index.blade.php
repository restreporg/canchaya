@extends('layouts.client')
@section('title', 'Mis Reservas')

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0">Mis Reservas</h5>
        <a href="{{ route('client.reservations.create') }}" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Reserva
        </a>
    </div>

    @if($reservations->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-calendar-x fs-1 d-block mb-3"></i>
            <p class="mb-3">Aún no tienes reservas.</p>
            <a href="{{ route('client.reservations.create') }}" class="btn btn-primary">Reservar una cancha</a>
        </div>
    @else
        <div class="row g-3">
            @foreach($reservations as $res)
                @php
                    $colors = ['pendiente'=>'warning','confirmada'=>'success','cancelada'=>'secondary','completada'=>'info'];
                    $color = $colors[$res->status] ?? 'secondary';
                @endphp
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
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
                                <span class="badge bg-{{ $color }}">{{ ucfirst($res->status) }}</span>
                                <a href="{{ route('client.reservations.show', $res) }}" class="btn btn-sm btn-outline-primary">
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