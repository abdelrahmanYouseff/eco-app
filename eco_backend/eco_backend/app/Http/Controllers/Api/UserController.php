<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'nullable|string|max:20',
        'password' => 'required|string|min:6',
        'role' => 'required|in:employee,company_admin', // حسب الرولز المتاحة عندك
        'company_id' => 'required|exists:companies,id',
    ]);

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'company_id' => $validated['company_id'],
        'badge_id' => Str::uuid(),
    ]);

    return response()->json([
        'status' => true,
        'message' => 'User created successfully',
        'user' => $user,
    ], 201);
}


public function employeesByCompany($companyId)
{
    $employees = User::where('company_id', $companyId)
                    ->where('role', 'employee')
                    ->get();

    return response()->json([
        'status' => true,
        'data' => $employees,
    ]);
}
}
