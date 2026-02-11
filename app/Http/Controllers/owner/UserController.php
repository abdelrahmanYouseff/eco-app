<?php

namespace App\Http\Controllers\owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    use LogsActivity;
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

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'phone' => $validated['phone_number'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
        'company_id' => $validated['company_id'] ?? null, // default لو null
        'badge_id' => Str::uuid(),
    ]);
    
    // Log activity
    $this->logActivity(
        'create',
        $user,
        "تم إنشاء مستخدم جديد: {$user->name} (Role: {$user->role})"
    );

    return redirect()->back()->with('success', 'User added successfully.');
}

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $companies = Company::all();
        return view('owner.users.edit_user', compact('user', 'companies'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:20',
            'role' => 'required|string|in:building_admin,company_admin,employee,visitor,accountant,editor',
            'company_id' => 'nullable|exists:companies,id',
        ]);

        $oldValues = $user->toArray();
        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone_number'],
            'role' => $validated['role'],
            'company_id' => $validated['company_id'] ?? null,
        ]);
        
        // Log activity
        $this->logActivity(
            'update',
            $user,
            "تم تحديث بيانات المستخدم: {$user->name}",
            $oldValues,
            $user->fresh()->toArray()
        );

        return redirect()->route('user.list')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // منع حذف المستخدم إذا كان هو نفسه (building admin)
            if (auth()->id() == $id) {
                return redirect()->back()->with('error', 'You cannot delete your own account.');
            }

            // منع حذف آخر building admin
            if ($user->role === 'building_admin' || $user->role === 'accountant') {
                $buildingAdminsCount = User::where('role', 'building_admin')->count();
                if ($buildingAdminsCount <= 1) {
                    return redirect()->back()->with('error', 'Cannot delete the last building admin.');
                }
            }

            $userName = $user->name;
            
            // Log activity before deletion
            $this->logActivity(
                'delete',
                $user,
                "تم حذف المستخدم: {$userName}"
            );
            
            $user->delete();

            return redirect()->back()->with('success', "User '{$userName}' has been deleted successfully.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }
}
