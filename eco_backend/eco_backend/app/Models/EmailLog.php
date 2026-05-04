<?php

namespace App\Models;

use App\PropertyManagement\Models\Client;
use App\PropertyManagement\Models\Contract;
use App\PropertyManagement\Models\RentPayment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'rent_payment_id',
        'contract_id',
        'client_id',
        'to_email',
        'from_email',
        'subject',
        'status',
        'error_message',
        'resend_email_id',
        'sent_by',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    /**
     * Get the rent payment that this email is related to.
     */
    public function rentPayment(): BelongsTo
    {
        return $this->belongsTo(RentPayment::class, 'rent_payment_id');
    }

    /**
     * Get the contract that this email is related to.
     */
    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    /**
     * Get the client that this email was sent to.
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    /**
     * Get the user who sent this email.
     */
    public function sentByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }
}
