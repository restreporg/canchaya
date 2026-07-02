@extends('layouts.admin')
@section('title', 'Detalle de Reseña')

@section('body')

    <div class="mb-4">
        <a href="{{ route('admin.reviews.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="max-width: 560px;">
        <div class="card-body">
            <div class="mb-3">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }} text-warning fs-5"></i>
                @endfor
                <span class="ms-2 text-muted">({{ $review->rating }}/5)</span>
            </div>

            <p class="mb-4 fs-5">{{ $review->comment ?? 'Sin comentario.' }}</p>

            <hr>

            <table class="table table-sm table-borderless mb-3">
                <tr>
                    <td class="text-muted" style="width:35%">Cliente</td>
                    <td>{{ $review->user->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Cancha</td>
                    <td>{{ $review->reservation->court->name ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="text-muted">Fecha</td>
                    <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            </table>

            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                  onsubmit="return confirm('¿Eliminar esta reseña?')">
                @csrf @method('DELETE')
                <button class="btn btn-danger btn-sm">
                    <i class="bi bi-trash me-1"></i> Eliminar reseña
                </button>
            </form>
        </div>
    </div>

@endsection