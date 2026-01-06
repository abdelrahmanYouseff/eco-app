<?php

namespace App\PropertyManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Broker extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'representative_name',
        'cr_number',
        'email',
        'mobile',
        'address',
    ];

    /**
     * Get the contracts for the broker.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}


