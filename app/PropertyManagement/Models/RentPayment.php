<?php

namespace App\PropertyManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'contract_id',
        'due_date',
        'issued_date',
        'total_value',
        'rent_value',
        'services_value',
        'vat_value',
        'fixed_amounts',
        'status',
        'payment_date',
        'receipt_image_path',
        'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'issued_date' => 'date',
        'payment_date' => 'date',
        'total_value' => 'decimal:2',
        'rent_value' => 'decimal:2',
        'services_value' => 'decimal:2',
        'vat_value' => 'decimal:2',
        'fixed_amounts' => 'decimal:2',
    ];

    /**
     * Get the contract that owns the rent payment.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}

