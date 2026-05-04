<?php

namespace App\PropertyManagement\Models;

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    use HasFactory;

    protected $fillable = [
        'building_id',
        'unit_number',
        'floor_number',
        'unit_type',
        'area',
        'direction',
        'parking_lots',
        'mezzanine',
        'finishing_type',
        'ac_units',
        'current_electricity_meter',
        'current_water_meter',
        'current_gas_meter',
    ];

    protected $casts = [
        'mezzanine' => 'boolean',
        'area' => 'decimal:2',
        'parking_lots' => 'integer',
        'ac_units' => 'integer',
    ];

    /**
     * Get the building that owns the unit.
     */
    public function building(): BelongsTo
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Get the contracts for the unit.
     */
    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}


