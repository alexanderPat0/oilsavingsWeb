<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Review extends Model
{
    use Notifiable;
    protected $fillable = [
        'placeIduserId', 
        'placeId', 
        'placeName',
        'userId', 
        'username',
        'review', 
        'rating', 
        'date', 
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

}
