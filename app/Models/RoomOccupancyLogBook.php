<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomOccupancyLogBook extends Model
{
    protected $fillable = [
        'date', 'startTime', 'endTime', 'room_id', 'usageMinutes', 'status', 'location'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
