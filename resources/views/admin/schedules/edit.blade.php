@extends('layouts.admin')
@section('title', 'Editar Horario - ' . $court->name)

@section('body')

    <div class="mb-4">
        <a href="{{ route('admin.courts.schedules.index', $court) }}" class="btn btn-sm btn-outline-secondary btn-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm card-soft" style="max-width: 480px;">
        <div class="card-body">
            <form action="{{ route('admin.courts.schedules.update', [$court, $schedule]) }}" method="POST">
                @csrf @method('PUT')

                @php $dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado']; @endphp

                <div class="mb-3">
                    <label class="form-label fw-semibold">Día de la semana</label>
                    <input type="text" class="form-control" value="{{ $dias[$schedule->day_of_week] }}" disabled>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col">
                        <label class="form-label fw-semibold">Hora de apertura</label>
                        <input type="time" name="open_time"
                               value="{{ old('open_time', \Carbon\Carbon::parse($schedule->open_time)->format('H:i')) }}"
                               class="form-control @error('open_time') is-invalid @enderror">
                        @error('open_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Hora de cierre</label>
                        <input type="time" name="close_time"
                               value="{{ old('close_time', \Carbon\Carbon::parse($schedule->close_time)->format('H:i')) }}"
                               class="form-control @error('close_time') is-invalid @enderror">
                        @error('close_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-warning btn-pill w-100">
                    <i class="bi bi-save me-1"></i> Actualizar horario
                </button>
            </form>
        </div>
    </div>

@endsection