<?php

namespace App\PropertyManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReceiptVoucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'receipt_number',
        'contract_id',
        'client_id',
        'rent_payment_id',
        'receipt_date',
        'amount',
        'payment_method',
        'reference_number',
        'notes',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the contract associated with the receipt voucher.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    /**
     * Get the client that owns the receipt voucher.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the rent payment associated with the receipt voucher.
     */
    public function rentPayment(): BelongsTo
    {
        return $this->belongsTo(RentPayment::class);
    }
}


