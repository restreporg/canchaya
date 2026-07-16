<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourtRequest;
use App\Http\Requests\Admin\UpdateCourtRequest;
use App\Http\Resources\CourtResource;
use App\Models\Court;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with('schedules')->latest()->paginate(15);

        return CourtResource::collection($courts);
    }

    public function store(StoreCourtRequest $request)
    {
        $data = collect($request->validated())->except('image')->toArray();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        $court = Court::create($data);

        return new CourtResource($court);
    }

    public function show(Court $court)
    {
        $court->load('schedules', 'reservations');

        return new CourtResource($court);
    }

    public function update(UpdateCourtRequest $request, Court $court)
    {
        $data = collect($request->validated())->except('image')->toArray();

        if ($request->hasFile('image')) {
            if ($court->image) {
                Storage::disk('public')->delete($court->image);
            }
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        $court->update($data);

        return new CourtResource($court);
    }

    /**
     * Igual que en la web: no se borra, se desactiva (soft delete lógico).
     */
    public function destroy(Court $court)
    {
        $court->update(['is_active' => false]);

        return response()->json(['message' => 'Cancha desactivada.']);
    }

    public function destroyImage(Court $court)
    {
        if ($court->image) {
            Storage::disk('public')->delete($court->image);
            $court->update(['image' => null]);
        }

        return new CourtResource($court);
    }
}
