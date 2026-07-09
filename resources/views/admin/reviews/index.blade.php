@extends('layouts.admin')
@section('title', 'Reseñas')

@section('body')

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <p class="text-muted mb-0">Todas las reseñas enviadas por los clientes</p>
        <span class="text-muted small">{{ $reviews->total() }} en total</span>
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
                        <th>Calificación</th>
                        <th>Comentario</th>
                        <th>Fecha</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reviews as $review)
                        <tr>
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->user->name ?? '-' }}</td>
                            <td>{{ $review->reservation->court->name ?? '-' }}</td>
                            <td>
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning small"></i>
                                @endfor
                            </td>
                            <td class="text-truncate" style="max-width:200px;">
                                {{ $review->comment ?? '-' }}
                            </td>
                            <td>{{ $review->created_at->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-outline-info me-1">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar esta reseña?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="bi bi-star fs-4 d-block mb-2"></i>
                                No hay reseñas aún.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Tarjetas: solo en móvil --}}
    <div class="d-md-none">
        @forelse($reviews as $review)
            <div class="card border-0 shadow-sm card-soft mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <span class="text-muted small">#{{ $review->id }}</span>
                            <h6 class="fw-bold mb-0">{{ $review->user->name ?? '-' }}</h6>
                        </div>
                        <div>
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning small"></i>
                            @endfor
                        </div>
                    </div>

                    <p class="mb-1 small text-muted">
                        <i class="bi bi-geo-alt me-1"></i>{{ $review->reservation->court->name ?? '-' }}
                        &nbsp;·&nbsp;
                        <i class="bi bi-calendar me-1"></i>{{ $review->created_at->format('d/m/Y') }}
                    </p>
                    <p class="mb-3">{{ $review->comment ?? '-' }}</p>

                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.reviews.show', $review) }}" class="btn btn-sm btn-outline-info flex-fill">
                            <i class="bi bi-eye me-1"></i> Ver
                        </a>
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="flex-fill"
                              onsubmit="return confirm('¿Eliminar esta reseña?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger w-100">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-4">
                <i class="bi bi-star fs-4 d-block mb-2"></i>
                No hay reseñas aún.
            </div>
        @endforelse
    </div>

    {{-- Paginación --}}
    <div class="mt-4 d-flex justify-content-center">
        {{ $reviews->links() }}
    </div>

@endsection