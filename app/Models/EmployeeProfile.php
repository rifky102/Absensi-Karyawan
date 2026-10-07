<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeProfile extends Model
{
    protected $fillable = [
        'user_id', 'nik', 'address', 'place_of_birth', 'date_of_birth',
        'education', 'tmt', 'phone', 'notes'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'tmt' => 'date',
    ];

    /**
     * Get the user associated with this profile
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
