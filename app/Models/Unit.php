<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = ['name', 'description', 'building_name', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all locations for this unit
     */
    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    /**
     * Get all users assigned to this unit
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
