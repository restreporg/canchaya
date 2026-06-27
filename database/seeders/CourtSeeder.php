<?php

namespace Database\Seeders;

use App\Models\Court;
use Illuminate\Database\Seeder;

class CourtSeeder extends Seeder
{
    public function run(): void
    {
        Court::create([
            'name'           => 'Cancha de Fútbol A',
            'type'           => 'Fútbol',
            'price_per_hour' => 50000,
            'location'       => 'Bloque A, Piso 1',
            'description'    => 'Cancha de fútbol 5 con césped sintético.',
            'is_active'      => true,
        ]);

        Court::create([
            'name'           => 'Cancha de Tenis 1',
            'type'           => 'Tenis',
            'price_per_hour' => 35000,
            'location'       => 'Bloque B, Exterior',
            'description'    => 'Cancha de tenis con superficie dura.',
            'is_active'      => true,
        ]);

        Court::create([
            'name'           => 'Cancha de Basketball',
            'type'           => 'Basketball',
            'price_per_hour' => 30000,
            'location'       => 'Bloque C, Interior',
            'description'    => 'Cancha techada de basketball.',
            'is_active'      => true,
        ]);

        Court::create([
            'name'           => 'Cancha de Pádel 1',
            'type'           => 'Pádel',
            'price_per_hour' => 40000,
            'location'       => 'Bloque D, Exterior',
            'description'    => 'Cancha de pádel con iluminación nocturna.',
            'is_active'      => true,
        ]);

        Court::create([
            'name'           => 'Cancha de Fútbol B',
            'type'           => 'Fútbol',
            'price_per_hour' => 45000,
            'location'       => 'Bloque A, Piso 2',
            'description'    => 'Cancha de fútbol 7 con césped natural.',
            'is_active'      => true,
        ]);
    }
}