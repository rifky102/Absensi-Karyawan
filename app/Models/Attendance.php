<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'location_id', 'attendance_date', 'check_in_time', 'check_out_time',
        'check_in_latitude', 'check_in_longitude', 'check_out_latitude', 'check_out_longitude',
        'status', 'notes'
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime:H:i:s',
        'check_out_time' => 'datetime:H:i:s',
        'check_in_latitude' => 'float',
        'check_in_longitude' => 'float',
        'check_out_latitude' => 'float',
        'check_out_longitude' => 'float',
    ];

    /**
     * Get the user associated with this attendance
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the location associated with this attendance
     */
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
