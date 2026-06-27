@extends('layouts.admin')
@section('title', 'Reseñas')

@section('body')

    <p class="text-muted mb-4">Todas las reseñas enviadas por los clientes</p>

    <div class="card border-0 shadow-sm">
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

@endsection