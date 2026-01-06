<?php

namespace App\PropertyManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'client_type',
        'id_number_or_cr',
        'id_type',
        'nationality',
        'email',
        'mobile',
        'national_address',
    ];

    /**
     * Get the contracts for the client.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    /**
     * Get the transactions for the client.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}


