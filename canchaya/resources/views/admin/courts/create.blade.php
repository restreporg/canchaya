@extends('layouts.admin')
@section('title', 'Nueva Cancha')

@section('body')

    <div class="row justify-content-center">
        <div class="col-md-7">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">
                        <i class="bi bi-plus-circle me-2 text-primary"></i>Registrar nueva cancha
                    </h6>
                </div>
                <div class="card-body p-4">

                    <form action="{{ route('admin.courts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}"
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
                                <option value="Fútbol"     {{ old('type') == 'Fútbol'     ? 'selected' : '' }}>Fútbol</option>
                                <option value="Tenis"      {{ old('type') == 'Tenis'      ? 'selected' : '' }}>Tenis</option>
                                <option value="Basketball" {{ old('type') == 'Basketball' ? 'selected' : '' }}>Basketball</option>
                                <option value="Volleyball" {{ old('type') == 'Volleyball' ? 'selected' : '' }}>Volleyball</option>
                                <option value="Pádel"      {{ old('type') == 'Pádel'      ? 'selected' : '' }}>Pádel</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Precio por hora</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="price_per_hour" value="{{ old('price_per_hour') }}"
                                       class="form-control @error('price_per_hour') is-invalid @enderror"
                                       placeholder="0.00" step="0.01" min="0">
                                @error('price_per_hour')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Ubicación</label>
                            <input type="text" name="location" value="{{ old('location') }}"
                                   class="form-control"
                                   placeholder="Ej: Bloque A, Piso 1">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Descripción</label>
                            <textarea name="description" rows="3"
                                      class="form-control"
                                      placeholder="Descripción opcional de la cancha...">{{ old('description') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Imagen</label>
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

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.courts.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i> Volver
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Guardar cancha
                            </button>
                        </div>

                    </form>

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