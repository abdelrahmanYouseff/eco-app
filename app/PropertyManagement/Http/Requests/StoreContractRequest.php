<?php

namespace App\PropertyManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    public function rules(): array
    {
        return [
            'contract_number' => 'nullable|string|unique:contracts,contract_number',
            'contract_type' => 'required|in:جديد,مجدد',
            'building_id' => 'required|exists:buildings,id',
            'unit_id' => 'required|exists:units,id',
            'client_id' => 'required|exists:clients,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'is_conditional' => 'boolean',
            'total_rent' => 'required|numeric|min:0',
            'annual_rent' => 'required|numeric|min:0',
            'deposit_amount' => 'numeric|min:0',
            'first_payment_amount' => 'numeric|min:0',
            'rent_cycle' => 'required|integer|min:1',
            'vat_amount' => 'numeric|min:0',
            'general_services_amount' => 'numeric|min:0',
            'insurance_policy_number' => 'nullable|string',
            'broker_id' => 'nullable|exists:brokers,id',
            'representatives' => 'array',
            'representatives.*.role' => 'required|in:lessor,lessee',
            'representatives.*.name' => 'required|string',
            'representatives.*.id_type' => 'nullable|string',
            'representatives.*.id_number' => 'required|string',
            'representatives.*.nationality' => 'nullable|string',
            'representatives.*.email' => 'nullable|email',
            'representatives.*.mobile' => 'nullable|string',
            'representatives.*.national_address' => 'nullable|string',
        ];
    }
}


