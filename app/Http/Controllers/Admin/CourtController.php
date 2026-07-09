<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreCourtRequest;
use App\Http\Requests\Admin\UpdateCourtRequest;
use App\Models\Court;
use Illuminate\Support\Facades\Storage;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::latest()->get();
        return view('admin.courts.index', compact('courts'));
    }

    public function create()
    {
        return view('admin.courts.create');
    }

    public function store(StoreCourtRequest $request)
    {
        $data = collect($request->validated())->except('image')->toArray();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('courts', 'public');
        }

        Court::create($data);
        return redirect()->route('admin.courts.index')->with('success', 'Cancha creada.');
    }

    public function show(Court $court)
    {
        $court->load('schedules', 'reservations');
        return view('admin.courts.show', compact('court'));
    }

    public function edit(Court $court)
    {
        return view('admin.courts.edit', compact('court'));
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
        return redirect()->route('admin.courts.index')->with('success', 'Cancha actualizada.');
    }

    public function destroy(Court $court)
    {
        $court->update(['is_active' => false]);
        return redirect()->route('admin.courts.index')->with('success', 'Cancha desactivada.');
    }

    public function destroyImage(Court $court)
    {
        if ($court->image) {
            Storage::disk('public')->delete($court->image);
            $court->update(['image' => null]);
        }

        return redirect()->route('admin.courts.edit', $court)->with('success', 'Imagen eliminada.');
    }
}