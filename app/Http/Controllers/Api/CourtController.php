<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourtResource;
use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    /**
     * Lista canchas activas, con los mismos filtros que la web:
     * ?type=Fútbol&location=Envigado
     */
    public function index(Request $request)
    {
        $courts = Court::where('is_active', true)
            ->with('schedules')
            ->when($request->type, fn ($q) => $q->where('type', $request->type))
            ->when($request->location, fn ($q) => $q->where('location', $request->location))
            ->latest()
            ->paginate(15);

        return CourtResource::collection($courts);
    }

    public function show(Court $court)
    {
        $court->load('schedules');

        return new CourtResource($court);
    }
}
