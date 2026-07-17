<?php

namespace Database\Seeders;

use App\Models\Court;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        Court::select('id')->lazyById()->each(function (Court $court) use (&$rows, $now) {
            foreach (range(0, 6) as $dayOfWeek) {
                $rows[] = [
                    'court_id' => $court->id,
                    'day_of_week' => $dayOfWeek,
                    'open_time' => '08:00',
                    'close_time' => '22:00',
                    'is_available' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        });

        collect($rows)->chunk(500)->each(function ($chunk) {
            Schedule::upsert(
                $chunk->toArray(),
                ['court_id', 'day_of_week'], // columnas únicas
                ['open_time', 'close_time', 'is_available', 'updated_at']
            );
        });
    }
}