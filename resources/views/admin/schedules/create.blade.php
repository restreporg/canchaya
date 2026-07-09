@extends('layouts.admin')
@section('title', 'Nuevo Horario - ' . $court->name)

@section('body')

    <div class="mb-4">
        <a href="{{ route('admin.courts.schedules.index', $court) }}" class="btn btn-sm btn-outline-secondary btn-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    <div class="card border-0 shadow-sm card-soft" style="max-width: 480px;">
        <div class="card-body">
            <form action="{{ route('admin.courts.schedules.store', $court) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-semibold">Día de la semana</label>
                    <select name="day_of_week" class="form-select @error('day_of_week') is-invalid @enderror">
                        @php $dias = ['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado']; @endphp
                        @foreach($dias as $i => $dia)
                            <option value="{{ $i }}" {{ old('day_of_week') == $i ? 'selected' : '' }}>{{ $dia }}</option>
                        @endforeach
                    </select>
                    @error('day_of_week')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col">
                        <label class="form-label fw-semibold">Hora de apertura</label>
                        <input type="time" name="open_time" value="{{ old('open_time') }}"
                               class="form-control @error('open_time') is-invalid @enderror">
                        @error('open_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col">
                        <label class="form-label fw-semibold">Hora de cierre</label>
                        <input type="time" name="close_time" value="{{ old('close_time') }}"
                               class="form-control @error('close_time') is-invalid @enderror">
                        @error('close_time')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-pill w-100">
                    <i class="bi bi-save me-1"></i> Guardar horario
                </button>
            </form>
        </div>
    </div>

@endsection