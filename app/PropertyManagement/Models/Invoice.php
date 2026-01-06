<?php

namespace App\PropertyManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'contract_id',
        'rent_payment_id',
        'client_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'vat_amount',
        'total_amount',
        'status',
        'notes',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    /**
     * Get the contract that owns the invoice.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the rent payment associated with the invoice.
     */
    public function rentPayment(): BelongsTo
    {
        return $this->belongsTo(RentPayment::class);
    }

    /**
     * Get the client that owns the invoice.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}


