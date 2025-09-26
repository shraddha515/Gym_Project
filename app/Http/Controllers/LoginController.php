<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon; // This is the crucial line to add
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
            return redirect()->route('gym.members.filter');
        }
    }

    return back()->withErrors([
        'email' => 'Invalid credentials. कृपया सही ईमेल और पासवर्ड डालें।',
    ]);
}



// SUPER ADMIN DASHBOARD
    public function superAdminDashboard()
    {
        $gyms = DB::table('gym_companies')->orderBy('gym_id', 'desc')->get();
        return view('admin.superadmin_dashboard', compact('gyms'));
    }

    // ADD NEW COMPANY & GYM ADMIN USER
public function addCompany(Request $request)
{
    $validator = Validator::make($request->all(), [
        'company_name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email|unique:gym_companies,email',
        'password' => 'required|min:6',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    DB::beginTransaction();

    try {
        // 1️⃣ Insert into users table
        $userId = DB::table('users')->insertGetId([
            'name' => $request->company_name . ' Admin',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'owner',
            'mobile' => $request->phone,
            'created_at' => Carbon::now(),
        ]);

        // Save user ID in session
        Session::put('gym_user_id', $userId);

        // 2️⃣ Insert into gym_companies table with gym_id = userId
        DB::table('gym_companies')->insert([
            'gym_id' => $userId,
            'company_name' => $request->company_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone' => $request->phone,
            'created_at' => Carbon::now(),
        ]);

        DB::commit();

        return redirect()->back()->with('success', 'Gym company and user created successfully!');

    } catch (\Exception $e) {
        DB::rollBack();
        dd('Error adding gym/company: ' . $e->getMessage());
    }
}


    // UPDATE EXISTING COMPANY & GYM ADMIN USER
    public function updateCompany(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'email' => 'required|email|unique:gym_companies,email,' . $id . ',gym_id|unique:users,email,' . $id . ',gym_id',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. Update gym_companies table
            DB::table('gym_companies')->where('gym_id', $id)->update([
                'company_name' => $request->company_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'updated_at' => Carbon::now(),
            ]);

            // 2. Update users table (finding the user by gym_id)
            DB::table('users')->where('gym_id', $id)->update([
                'name' => $request->company_name . ' Admin',
                'email' => $request->email,
                'updated_at' => Carbon::now(),
            ]);
            
            DB::commit();

            return redirect()->route('superadmin.dashboard')
                             ->with('success', 'Gym Company updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update company. Please try again.');
        }
    }

    // DELETE COMPANY & GYM ADMIN USER
    public function deleteCompany($id)
    {
        DB::beginTransaction();
        try {
            // 1. Delete user record first
            DB::table('users')->where('gym_id', $id)->delete();

            // 2. Delete company record
            DB::table('gym_companies')->where('gym_id', $id)->delete();
            
            DB::commit();

            return redirect()->route('superadmin.dashboard')
                             ->with('success', 'Gym Company deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to delete company. Please try again.');
        }
    }



    // GYM DASHBOARD
    public function gymDashboard()
    {
        $user = Auth::user();
        
        // You can use the authenticated user's gym_id to fetch related data
        $gymId = $user->gym_id;
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
