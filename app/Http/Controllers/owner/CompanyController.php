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
        $users = User::where('role', 'building_admin')->get();
        dd($users);
        $buildings = Building::all(['id', 'name']);
        return view('owner.company.add_new_company', compact('users', 'buildings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'required|string|max:20',
            'floor_number' => 'required|string|max:10',
            'office_number' => 'required|string|max:10',
            'admin_user_id' => 'required|exists:users,id',
            'building_id' => 'required|exists:buildings,id',
        ]);

        Company::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'floor_number' => $validated['floor_number'],
            'office_number' => $validated['office_number'],
            'admin_user_id' => $validated['admin_user_id'],
            'building_id' => $validated['building_id'],
        ]);

        return redirect()->back()->with('success', 'Company Has been added successfully');
    }

    public function destroy($id)
    {
        try {
            $company = Company::findOrFail($id);

            // التحقق من وجود مستخدمين مرتبطين بالشركة
            $usersCount = User::where('company_id', $id)->count();
            if ($usersCount > 0) {
                return redirect()->back()->with('error', 'لا يمكن حذف الشركة لأنها تحتوي على مستخدمين مرتبطين بها. يرجى حذف المستخدمين أولاً.');
            }

            // التحقق من وجود طلبات صيانة مرتبطة بالشركة
            $maintenanceRequestsCount = \App\Models\MaintenanceRequest::where('company_id', $id)->count();
            if ($maintenanceRequestsCount > 0) {
                return redirect()->back()->with('error', 'لا يمكن حذف الشركة لأنها تحتوي على طلبات صيانة مرتبطة بها. يرجى حذف طلبات الصيانة أولاً.');
            }

            $companyName = $company->name;
            $company->delete();

            return redirect()->back()->with('success', "تم حذف الشركة '{$companyName}' بنجاح.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'خطأ في حذف الشركة: ' . $e->getMessage());
        }
    }
}
