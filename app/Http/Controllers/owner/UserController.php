<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;



class UserController extends Controller
{
    public function addNewUserView(){
        $companies = Company::all();
        return view('owner.users.add_new_user', compact('companies'));
    }


    public function userList(){
        $users = User::all();
        $usersWithCompanyNames = $users->map(function ($user) {
            $company = Company::find($user->company_id);
            $user->company_name = $company ? $company->name : null;
            return $user;
        });

        return view('owner.users.user_list', ['users' => $usersWithCompanyNames]);
    }


    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'phone_number' => 'required|string|max:20',
        'password' => 'required|string|min:6',
        'role' => 'required|string',
        'company_id' => 'nullable|exists:companies,id', // هنا نخليها nullable
    ]);

    User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone_number'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'company_id' => $validated['company_id'] ?? null, // default لو null
        'badge_id' => Str::uuid(),
    ]);

    return redirect()->back()->with('success', 'User added successfully.');
}


}
