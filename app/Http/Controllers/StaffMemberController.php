<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffMemberController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user and their gym_id
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // Fetch staff members associated with the current gym
        $staff = DB::table('staff_members')->where('gym_id', $gym_id)->get();
 
        // If editing, get the staff info, ensuring it belongs to the current gym
        $editStaff = null;
        if ($request->has('edit_id')) {
            $editStaff = DB::table('staff_members')
                           ->where('gym_id', $gym_id)
                           ->where('id', $request->edit_id)
                           ->first();
        }

        return view('gym.staff', compact('staff', 'editStaff'));
    }

    public function store(Request $request)
    {
        // Get the authenticated user and their gym_id
        $user = Auth::user();
        $gym_id = $user->gym_id;
        
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|string|in:Personal Trainer,Nutritionist,Admin',
            'mobile_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'active_status' => 'required|in:Active,Inactive',
        ]);

        $validated['created_at'] = now();
        $validated['updated_at'] = now();
        $validated['gym_id'] = $gym_id; // Add the gym_id to the validated data

        DB::table('staff_members')->insert($validated);

        return redirect()->route('gym.staff.index')->with('success', 'Staff member added successfully!');
    }

    public function update(Request $request, $id)
    {
        // Get the authenticated user and their gym_id
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'type' => 'required|string|in:Personal Trainer,Nutritionist,Admin',
            'mobile_number' => 'required|string|max:20',
            'email' => 'nullable|email|max:150',
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'active_status' => 'required|in:Active,Inactive',
        ]);

        $validated['updated_at'] = now();

        // Update the staff member, ensuring it belongs to the current gym
        DB::table('staff_members')
            ->where('gym_id', $gym_id)
            ->where('id', $id)
            ->update($validated);

        return redirect()->route('gym.staff.index')->with('success', 'Staff member updated successfully!');
    }

    public function destroy($id)
    {
        // Get the authenticated user and their gym_id
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // Delete the staff member, ensuring it belongs to the current gym
        DB::table('staff_members')
            ->where('gym_id', $gym_id)
            ->where('id', $id)
            ->delete();

        return redirect()->route('gym.staff.index')->with('success', 'Staff member deleted successfully!');
    }
}
