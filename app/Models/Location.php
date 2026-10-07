<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['unit_id', 'name', 'latitude', 'longitude', 'radius', 'is_active'];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'is_active' => 'boolean',
    ];

    /**
     * Get the unit that owns this location
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get all attendance records for this location
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Check if user is within geofence radius
     */
    public function isUserWithinRadius($userLatitude, $userLongitude): bool
    {
        $earthRadius = 6371000; // Earth radius in meters

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($userLatitude);
        $lonTo = deg2rad($userLongitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(sin($latDelta / 2) ** 2 +
            cos($latFrom) * cos($latTo) * sin($lonDelta / 2) ** 2));
        
        $distance = $angle * $earthRadius; // Distance in meters

        return $distance <= $this->radius;
    }
}
