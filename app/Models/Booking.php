<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    /** @use HasFactory<\Database\Factories\BookingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'total_price',
        'status',
        'notes',
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function event() 
    {
        return $this->belongsTo(Event::class);
    }
}
