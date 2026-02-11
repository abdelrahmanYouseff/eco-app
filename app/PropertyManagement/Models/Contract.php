<?php

namespace App\PropertyManagement\Models;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Contract extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'contract_number',
        'contract_type',
        'building_id',
        'unit_id',
        'client_id',
        'start_date',
        'end_date',
        'contract_signing_date',
        'is_conditional',
        'total_rent',
        'annual_rent',
        'deposit_amount',
        'first_payment_amount',
        'rent_cycle',
        'vat_amount',
        'general_services_amount',
        'fixed_amounts',
        'insurance_policy_number',
        'contract_pdf_path',
        'broker_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'contract_signing_date' => 'date',
        'is_conditional' => 'boolean',
        'total_rent' => 'decimal:2',
        'annual_rent' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'first_payment_amount' => 'decimal:2',
        'rent_cycle' => 'integer',
        'vat_amount' => 'decimal:2',
        'general_services_amount' => 'decimal:2',
        'fixed_amounts' => 'decimal:2',
    ];

    /**
     * Get the building that owns the contract.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the unit for the contract.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get the client for the contract.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the broker for the contract.
     */
    public function broker(): BelongsTo
    {
        return $this->belongsTo(Broker::class);
    }

    /**
     * Get the representatives for the contract.
     */
    public function representatives(): HasMany
    {
        return $this->hasMany(ContractRepresentative::class);
    }

    /**
     * Get the rent payments for the contract.
     */
    public function rentPayments(): HasMany
    {
        return $this->hasMany(RentPayment::class);
    }

    /**
     * Get the transactions for the contract.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the invoices for the contract.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the receipt vouchers for the contract.
     */
    public function receiptVouchers(): HasMany
    {
        return $this->hasMany(ReceiptVoucher::class);
    }

    /**
     * Configure activity log options for this model.
     * This will automatically log create, update, and delete events.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['contract_number', 'contract_type', 'start_date', 'end_date', 'annual_rent', 'client_id', 'unit_id'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => "تم إنشاء عقد جديد: {$this->contract_number}",
                'updated' => "تم تحديث العقد: {$this->contract_number}",
                'deleted' => "تم حذف العقد: {$this->contract_number}",
                default => "إجراء على العقد: {$this->contract_number}",
            })
            ->useLogName('contract');
    }
}

