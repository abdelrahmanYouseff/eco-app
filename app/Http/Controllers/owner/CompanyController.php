<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
use App\Models\Building;


class CompanyController extends Controller
{
    public function CompanyList(){
        $companies = Company::with('admin')->get();

        return view('owner.company.company_list', compact('companies'));
    }


    public function addCompanyView(){
        $users = User::where('role', 'company_admin')->get();
        $buildings = Building::all(['id', 'name']);
        return view('owner.company. add_new_company', compact('users', 'buildings'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'floor_number' => 'required|string|max:10',
        'office_number' => 'required|string|max:10',
        'admin_user_id' => 'required|exists:users,id',
        'building_id' => 'required|string|max:50',
    ]);

    Company::create([
        'name' => $validated['name'],
        'floor_number' => $validated['floor_number'],
        'office_number' => $validated['office_number'],
'admin_user_id' => $validated['admin_user_id'],
        'building_id' => $validated['building_id'],
    ]);

    return redirect()->back()->with('success', 'Company Has been added successfully');
}
}
