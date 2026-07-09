@extends('layouts.admin')
@section('title', 'Dashboard')

@section('body')

    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm card-soft text-center py-3">
                <div class="card-body">
                    <i class="bi bi-grid fs-1 text-primary"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalCourts }}</h3>
                    <p class="text-muted mb-0">Canchas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm card-soft text-center py-3">
                <div class="card-body">
                    <i class="bi bi-calendar-check fs-1 text-success"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalReservations }}</h3>
                    <p class="text-muted mb-0">Reservas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm card-soft text-center py-3">
                <div class="card-body">
                    <i class="bi bi-cash-stack fs-1 text-warning"></i>
                    <h3 class="fw-bold mt-2 mb-0">${{ number_format($totalPayments, 0) }}</h3>
                    <p class="text-muted mb-0">Ingresos</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm card-soft text-center py-3">
                <div class="card-body">
                    <i class="bi bi-people fs-1 text-info"></i>
                    <h3 class="fw-bold mt-2 mb-0">{{ $totalClients }}</h3>
                    <p class="text-muted mb-0">Clientes</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm card-soft">
                <div class="card-header bg-white fw-semibold border-0 pt-3">
                    <i class="bi bi-pie-chart me-2"></i>Reservas por estado
                </div>
                <div class="card-body">
                    @php
                        $estados = ['pendiente'=>'warning','confirmada'=>'success','cancelada'=>'secondary','completada'=>'info'];
                    @endphp
                    @foreach($estados as $estado => $color)
                        @php $total = $reservationsByStatus[$estado] ?? 0; @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-capitalize">{{ $estado }}</span>
                                <span class="fw-semibold">{{ $total }}</span>
                            </div>
                            <div class="progress" style="height: 8px; border-radius: 999px;">
                                <div class="progress-bar bg-{{ $color }}"
                                     style="width: {{ $totalReservations > 0 ? ($total / $totalReservations) * 100 : 0 }}%; border-radius: 999px;">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm card-soft">
                <div class="card-header bg-white fw-semibold border-0 pt-3">
                    <i class="bi bi-clock-history me-2"></i>Últimas reservas
                </div>

                {{-- Tabla: solo en escritorio --}}
                <div class="card-body p-0 d-none d-md-block">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Cliente</th>
                                <th>Cancha</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReservations as $res)
                                <tr>
                                    <td>{{ $res->user->name ?? '-' }}</td>
                                    <td>{{ $res->court->name ?? '-' }}</td>
                                    <td>{{ $res->start_datetime->format('d/m/Y') }}</td>
                                    <td>
                                        <span class="badge badge-pill-soft status-{{ $res->status }}">{{ ucfirst($res->status) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">Sin reservas aún</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Tarjetas: solo en móvil --}}
                <div class="card-body d-md-none">
                    @forelse($recentReservations as $res)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="d-flex justify-content-between align-items-start">
                                <span class="fw-semibold">{{ $res->user->name ?? '-' }}</span>
                                <span class="badge badge-pill-soft status-{{ $res->status }}">{{ ucfirst($res->status) }}</span>
                            </div>
                            <small class="text-muted d-block">
                                <i class="bi bi-geo-alt me-1"></i>{{ $res->court->name ?? '-' }}
                                &nbsp;·&nbsp;
                                <i class="bi bi-calendar me-1"></i>{{ $res->start_datetime->format('d/m/Y') }}
                            </small>
                        </div>
                    @empty
                        <p class="text-center text-muted py-3 mb-0">Sin reservas aún</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection