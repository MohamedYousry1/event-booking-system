<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'user_id',
        'otp',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
