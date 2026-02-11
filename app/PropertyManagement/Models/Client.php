<?php

namespace App\PropertyManagement\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Client extends Model
{
    use HasFactory, LogsActivity;

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

    /**
     * Configure activity log options for this model.
     * This will automatically log create, update, and delete events.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'client_type', 'id_number_or_cr', 'email', 'mobile'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => match($eventName) {
                'created' => "تم إنشاء مستأجر جديد: {$this->name}",
                'updated' => "تم تحديث بيانات المستأجر: {$this->name}",
                'deleted' => "تم حذف المستأجر: {$this->name}",
                default => "إجراء على المستأجر: {$this->name}",
            })
            ->useLogName('client');
    }
}


