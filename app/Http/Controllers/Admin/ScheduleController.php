<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Court $court)
    {
        $schedules = $court->schedules()->orderBy('day_of_week')->get();
        return view('admin.schedules.index', compact('court', 'schedules'));
    }

    public function create(Court $court)
    {
        return view('admin.schedules.create', compact('court'));
    }

    public function store(Request $request, Court $court)
    {
        $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'open_time'   => 'required|date_format:H:i',
            'close_time'  => 'required|date_format:H:i|after:open_time',
        ]);

        $court->schedules()->create($request->all());
        return redirect()->route('admin.courts.schedules.index', $court)
                         ->with('success', 'Horario agregado.');
    }

    public function edit(Court $court, Schedule $schedule)
    {
        return view('admin.schedules.edit', compact('court', 'schedule'));
    }

    public function update(Request $request, Court $court, Schedule $schedule)
    {
        $request->validate([
            'open_time'  => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ]);

        $schedule->update($request->all());
        return redirect()->route('admin.courts.schedules.index', $court)
                         ->with('success', 'Horario actualizado.');
    }

    public function destroy(Court $court, Schedule $schedule)
    {
        $schedule->delete();
        return redirect()->route('admin.courts.schedules.index', $court)
                         ->with('success', 'Horario eliminado.');
    }
}