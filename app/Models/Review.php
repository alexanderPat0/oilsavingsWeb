<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emotion extends Model
{

    protected $table = 'reviews';

    protected $fillable = [
        'placeId_userId', 
        'placeId', 
        'userId', 
        'review', 
        'rating', 
        'date', 
        'deleted'
    ];

    protected $hidden = [
        'deleted',
    ];

    protected $casts = [
        'date' => 'datetime',
        'deleted' => 'boolean',
    ];

    protected $attributes = [
        'deleted' => 0,
    ];

}
