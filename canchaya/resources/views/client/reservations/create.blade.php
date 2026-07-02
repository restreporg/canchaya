@extends('layouts.client')
@section('title', 'Nueva Reserva')

@section('body')

    <div class="mb-4">
        <a href="{{ route('client.reservations.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Mis reservas
        </a>
    </div>

    <h5 class="fw-bold mb-4">Nueva Reserva</h5>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('client.reservations.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cancha</label>
                            <select name="court_id" id="courtSelect"
                                    class="form-select @error('court_id') is-invalid @enderror">
                                <option value="">Selecciona una cancha…</option>
                                @foreach($courts as $court)
                                    <option value="{{ $court->id }}"
                                            data-price="{{ $court->price_per_hour }}"
                                            data-image="{{ $court->image ? Storage::url($court->image) : '' }}"
                                            data-description="{{ $court->description }}"
                                            data-location="{{ $court->location }}"
                                            {{ old('court_id') == $court->id ? 'selected' : '' }}>
                                        {{ $court->name }} — {{ $court->type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('court_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        {{-- Preview de cancha seleccionada --}}
                        <div id="courtPreview" class="card border-0 bg-light mb-3 d-none">
                            <img id="courtImg" src="" alt="Imagen de la cancha"
                                 class="card-img-top" style="max-height: 180px; object-fit: cover;">
                            <div class="card-body py-2">
                                <p id="courtDesc" class="mb-1 small text-muted"></p>
                                <p id="courtLoc" class="mb-0 small text-muted"></p>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col">
                                <label class="form-label fw-semibold">Fecha y hora de inicio</label>
                                <input type="datetime-local" name="start_datetime" id="startDatetime"
                                       value="{{ old('start_datetime') }}"
                                       class="form-control @error('start_datetime') is-invalid @enderror">
                                @error('start_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col">
                                <label class="form-label fw-semibold">Fecha y hora de fin</label>
                                <input type="datetime-local" name="end_datetime" id="endDatetime"
                                       value="{{ old('end_datetime') }}"
                                       class="form-control @error('end_datetime') is-invalid @enderror">
                                @error('end_datetime')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div id="totalBox" class="alert alert-info d-none mb-3">
                            Total estimado: <strong id="totalAmt"></strong>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-calendar-check me-1"></i> Confirmar reserva
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">Canchas disponibles</div>
                <ul class="list-group list-group-flush">
                    @foreach($courts as $court)
                        <li class="list-group-item p-0">
                            @if($court->image)
                                <img src="{{ Storage::url($court->image) }}" alt="{{ $court->name }}"
                                     class="w-100" style="height: 120px; object-fit: cover;">
                            @endif
                            <div class="d-flex justify-content-between align-items-center p-3">
                                <div>
                                    <strong>{{ $court->name }}</strong>
                                    <br><small class="text-muted">{{ $court->type }}</small>
                                    @if($court->location)
                                        <br><small class="text-muted"><i class="bi bi-geo-alt me-1"></i>{{ $court->location }}</small>
                                    @endif
                                </div>
                                <span class="badge bg-primary rounded-pill">${{ number_format($court->price_per_hour, 2) }}/h</span>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const courtSelect = document.getElementById('courtSelect');
    const startDt     = document.getElementById('startDatetime');
    const endDt       = document.getElementById('endDatetime');
    const totalBox    = document.getElementById('totalBox');
    const totalAmt    = document.getElementById('totalAmt');
    const courtPreview = document.getElementById('courtPreview');
    const courtImg    = document.getElementById('courtImg');
    const courtDesc   = document.getElementById('courtDesc');
    const courtLoc    = document.getElementById('courtLoc');

    function calcTotal() {
        const opt   = courtSelect.options[courtSelect.selectedIndex];
        const price = parseFloat(opt?.dataset?.price);
        const start = new Date(startDt.value);
        const end   = new Date(endDt.value);

        if (price && !isNaN(start) && !isNaN(end) && end > start) {
            const hours = (end - start) / 3600000;
            totalAmt.textContent = '$' + (price * hours).toFixed(2);
            totalBox.classList.remove('d-none');
        } else {
            totalBox.classList.add('d-none');
        }
    }

    function updateCourtPreview() {
        const opt = courtSelect.options[courtSelect.selectedIndex];
        const image = opt?.dataset?.image;
        const desc = opt?.dataset?.description;
        const loc = opt?.dataset?.location;

        if (opt?.value) {
            if (image) {
                courtImg.src = image;
                courtImg.classList.remove('d-none');
            } else {
                courtImg.classList.add('d-none');
            }
            courtDesc.textContent = desc || '';
            courtLoc.textContent = loc ? '📍 ' + loc : '';
            courtPreview.classList.remove('d-none');
        } else {
            courtPreview.classList.add('d-none');
        }
    }

    courtSelect.addEventListener('change', () => {
        calcTotal();
        updateCourtPreview();
    });
    startDt.addEventListener('change', calcTotal);
    endDt.addEventListener('change', calcTotal);
</script>
@endpush