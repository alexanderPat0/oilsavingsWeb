<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Action extends Model
{
    use Notifiable;

    protected $fillable = [
        'admin_id', 'action_type', 'target_id', 'target_type', 'performed_at'
    ];

    protected $casts = [
        'performed_at' => 'datetime',
    ];

    // Relationship with the admin who made the action
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
