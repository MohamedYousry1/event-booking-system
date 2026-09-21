<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'end_date',
        'capacity',
        'available_seats',
        'price',
        'image',
        'status',
    ];

    public function category() 
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings() 
    {
        return $this->hasMany(Booking::class);
    }
    
}
