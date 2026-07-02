<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    //
    protected $fillable = ['reservation_id', 'user_id', 'amount', 'method', 'status', 'paid_at'];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
