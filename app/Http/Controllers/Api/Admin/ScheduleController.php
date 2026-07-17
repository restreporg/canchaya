<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Models\Court;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScheduleController extends Controller
{
    public function index(Court $court)
    {
        $schedules = $court->schedules()->orderBy('day_of_week')->get();

        return ScheduleResource::collection($schedules);
    }

    public function store(Request $request, Court $court)
    {
        $validated = $request->validate([
            'day_of_week' => [
                'required', 'integer', 'between:0,6',
                Rule::unique('schedules')->where('court_id', $court->id),
            ],
            'open_time'   => 'required|date_format:H:i',
            'close_time'  => 'required|date_format:H:i|after:open_time',
        ], [
            'day_of_week.unique' => 'Esta cancha ya tiene un horario configurado para ese día.',
        ]);

        $schedule = $court->schedules()->create($validated);

        return new ScheduleResource($schedule);
    }

    public function update(Request $request, Court $court, Schedule $schedule)
    {
        $validated = $request->validate([
            'open_time'  => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ]);

        $schedule->update($validated);

        return new ScheduleResource($schedule);
    }

    public function destroy(Court $court, Schedule $schedule)
    {
        $schedule->delete();

        return response()->json(['message' => 'Horario eliminado.']);
    }
}