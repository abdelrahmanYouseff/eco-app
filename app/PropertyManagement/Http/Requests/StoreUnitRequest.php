<?php

namespace App\PropertyManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUnitRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'building_id' => 'required|exists:buildings,id',
            'unit_number' => 'required|string',
            'floor_number' => 'required|string',
            'unit_type' => 'required|in:مكتب,شقة,محل',
            'area' => 'required|numeric|min:0',
            'direction' => 'nullable|string',
            'parking_lots' => 'integer|min:0',
            'mezzanine' => 'boolean',
            'finishing_type' => 'nullable|in:furnished,unfurnished',
            'ac_units' => 'integer|min:0',
            'current_electricity_meter' => 'nullable|string',
            'current_water_meter' => 'nullable|string',
            'current_gas_meter' => 'nullable|string',
        ];
    }
}


