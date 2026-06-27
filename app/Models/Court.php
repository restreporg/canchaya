<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Court extends Model
{
    protected $fillable = ['name', 'type', 'price_per_hour', 'location', 'description', 'is_active', 'image'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}