<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Waypoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location_code',
        'description',
        'floor',
        'image_url',
        'pano_url',
        'coordinates_x',
        'coordinates_y',
    ];

    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'waypoint_user')->withTimestamps();
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }
}
