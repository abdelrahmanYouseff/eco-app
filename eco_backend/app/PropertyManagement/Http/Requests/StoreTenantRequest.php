<?php

namespace App\PropertyManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'client_type' => 'required|in:فرد,شركة',
            'id_number_or_cr' => 'required|string|unique:clients,id_number_or_cr',
            'id_type' => 'nullable|string',
            'nationality' => 'nullable|string',
            'email' => 'nullable|email|unique:clients,email',
            'mobile' => 'required|string|unique:clients,mobile',
            'national_address' => 'nullable|string',
        ];
    }
}


