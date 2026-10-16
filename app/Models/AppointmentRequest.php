<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_user_id',
        'waypoint_id',
        'requester_type',
        'requester_honorific',
        'requester_name',
        'grade_level',
        'requester_email',
        'purpose_code',
        'purpose_other_text',
        'status',
        'scheduled_at',
        'responded_at',
    ];

    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'responded_at' => 'datetime',
        ];
    }

    public function targetUser()
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function waypoint()
    {
        return $this->belongsTo(Waypoint::class);
    }
}

