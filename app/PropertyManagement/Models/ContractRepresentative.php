<?php

namespace App\PropertyManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractRepresentative extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'role',
        'name',
        'id_type',
        'id_number',
        'nationality',
        'email',
        'mobile',
        'national_address',
    ];

    /**
     * Get the contract that owns the representative.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}


