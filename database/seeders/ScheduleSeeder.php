<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        Court::all()->each(function (Court $court) {
            foreach (range(0, 6) as $dayOfWeek) {
                Schedule::updateOrCreate(
                    [
                        'court_id' => $court->id,
                        'day_of_week' => $dayOfWeek,
                    ],
                    [
                        'open_time' => '08:00',
                        'close_time' => '22:00',
                        'is_available' => true,
                    ]
                );
            }
        });
    }
}