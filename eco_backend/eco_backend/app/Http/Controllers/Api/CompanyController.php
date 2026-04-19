<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function show($id)
{
    $company = Company::find($id);

    if (!$company) {
        return response()->json([
            'status' => false,
            'message' => 'Company not found'
        ], 404);
    }

    return response()->json([
        'status' => true,
        'company' => [
            'id' => $company->id,
            'name' => $company->name,
        ]
    ]);
}
}
