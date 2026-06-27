@extends('layouts.admin')
@section('title', 'Editar Cancha')

@section('body')
@php use Illuminate\Support\Facades\Storage; @endphp

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-pencil me-2 text-warning"></i>Editar cancha — {{ $court->name }}
                    </h6>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('admin.courts.update', $court) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $court->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Ej: Cancha Principal">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tipo</label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror">
                                <option value="">-- Selecciona un tipo --</option>
                                <option value="Fútbol"     {{ old('type', $court->type) == 'Fútbol'     ? 'selected' : '' }}>Fútbol</option>
                                <option value="Tenis"      {{ old('type', $court->type) == 'Tenis'      ? 'selected' : '' }}>Tenis</option>
                                <option value="Basketball" {{ old('type', $court->type) == 'Basketball' ? 'selected' : '' }}>Basketball</option>
                                <option value="Volleyball" {{ old('type', $court->type) == 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                                <option value="Pádel"      {{ old('type', $court->type) == 'Pádel'      ? 'selected' : '' }}>Pádel</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Precio por hora</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price_per_hour"
                                       value="{{ old('price_per_hour', $court->price_per_hour) }}"
                                       class="form-control @error('price_per_hour') is-invalid @enderror"
                                       placeholder="0.00" step="0.01" min="0">
                                @error('price_per_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ubicación</label>
                            <input type="text" name="location"
                                   value="{{ old('location', $court->location) }}"
                                   class="form-control"
                                   placeholder="Ej: Bloque A, Piso 1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="description" rows="3"
                                      class="form-control"
                                      placeholder="Descripción opcional...">{{ old('description', $court->description) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Imagen</label>
                            @if($court->image)
                                <div class="mb-2">
                                    <img src="{{ Storage::url($court->image) }}" alt="Imagen actual"
                                         class="img-thumbnail d-block" style="max-height: 200px;">
                                    <p class="text-muted small mt-1">Imagen actual. Sube una nueva para reemplazarla.</p>
                                </div>
                            @endif
                            <input type="file" name="image" accept="image/*"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="imageInput">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="preview" class="mt-2 d-none">
                                <img id="previewImg" src="" alt="Preview"
                                     class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Estado</label>
                            <select name="is_active" class="form-select">
                                <option value="1" {{ old('is_active', $court->is_active) == 1 ? 'selected' : '' }}>Activa</option>
                                <option value="0" {{ old('is_active', $court->is_active) == 0 ? 'selected' : '' }}>Inactiva</option>
                            </select>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.courts.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-save me-1"></i> Actualizar cancha
                            </button>
                        </div>

                    </form>

                    {{-- Form eliminar imagen FUERA del form principal --}}
                    @if($court->image)
                        <form action="{{ route('admin.courts.image.destroy', $court) }}" method="POST"
                              class="mt-3" onsubmit="return confirm('¿Eliminar imagen?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash me-1"></i> Eliminar imagen actual
                            </button>
                        </form>
                    @endif

                </div>
            </div>

        </div>
    </div>

@endsection

@push('scripts')
<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('previewImg').src = e.target.result;
                document.getElementById('preview').classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush