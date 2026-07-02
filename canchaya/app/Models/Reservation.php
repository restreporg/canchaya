<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
    protected $fillable = [
        'user_id', 'court_id', 'start_datetime',
        'end_datetime', 'total_price', 'status'
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function court()
    {
        return $this->belongsTo(Court::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }
}
