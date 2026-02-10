<?php

namespace App\Http\Controllers\Owner\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        return $this->redirectByRole(Auth::user()->role);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function ownerDashboardView()
    {
        return view('owner.dashboard');
    }

    public function adminDashboardView()
    {
        return view('company_admin.dashboard');
    }

    protected function redirectByRole(string $role)
    {
        return match ($role) {
            'building_admin', 'accountant' => redirect()->route('building.owner.dashboard'),
            'company_admin' => redirect()->route('building.admin.dashboard'),
            'employee'      => redirect()->route('employee.dashboard'),
            'visitor'       => redirect()->route('visitor.dashboard'),
            'editor'        => redirect()->route('property-management.buildings.index'),
            default         => redirect('/'),
        };
    }
}
