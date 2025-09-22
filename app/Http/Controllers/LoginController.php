<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class LoginController extends Controller
{
    // Show login page
    public function showLoginForm()
    {
        return view('admin.login'); 
    }

    public function login(Request $request)
{
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required|min:6',
    ]);

    // Clean email input (trim and lowercase)
    $email = trim(strtolower($request->email));

    // Try to login using Laravel's built-in Auth
    if (Auth::attempt(['email' => $email, 'password' => $request->password])) {
        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return redirect()->route('superadmin.dashboard');
        } else {
            return redirect()->route('gym.dashboard');
        }
    }

    return back()->withErrors([
        'email' => 'Invalid credentials. कृपया सही ईमेल और पासवर्ड डालें।',
    ]);
}
    // SUPER ADMIN DASHBOARD
    public function superAdminDashboard()
    {
        $gyms = DB::table('gym_companies')->orderBy('id', 'desc')->get();
        return view('admin.superadmin_dashboard', compact('gyms'));
    }

    // ADD COMPANY + Gym Admin User
    public function addCompany(Request $request)
    {
        $request->validate([
            'company_name' => 'required',
            'email' => 'required|email|unique:gym_companies,email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        // 1. Insert into gym_companies
        $companyId = DB::table('gym_companies')->insertGetId([
            'company_name' => $request->company_name,
            'email'        => $request->email,
            'password'     => Hash::make($request->password),
            'phone'        => $request->phone,
            'address'      => $request->address,
        ]);

        // 2. Insert gym admin into users
        DB::table('users')->insert([
            'company_id' => $companyId,
            'name'       => $request->company_name . ' Admin',
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'role'       => 'owner', // default gym company owner
        ]);

        return redirect()->route('superadmin.dashboard')
                         ->with('success', 'Gym Company created successfully!');
    }

    // GYM DASHBOARD
    public function gymDashboard()
    {
        $user = Auth::user();
        return view('admin.gym_dashboard', compact('user'));
    }

    public function layout()
    {
        return view('layout');
    }

     public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
