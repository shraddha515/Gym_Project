<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffMemberController extends Controller
{
    public function index(Request $request)
    {
        $staff = DB::table('staff_members')->get();

        // If editing, get the staff info
        $editStaff = null;
        if($request->has('edit_id')){
            $editStaff = DB::table('staff_members')->where('id', $request->edit_id)->first();
        }

        return view('gym.staff', compact('staff','editStaff'));
    }

    public function store(Request $request)
    {
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

        DB::table('staff_members')->insert($validated);

        return redirect()->route('gym.staff.index')->with('success','Staff member added successfully!');
    }

    public function update(Request $request, $id)
    {
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

        DB::table('staff_members')->where('id',$id)->update($validated);

        return redirect()->route('gym.staff.index')->with('success','Staff member updated successfully!');
    }

    public function destroy($id)
    {
        DB::table('staff_members')->where('id',$id)->delete();
        return redirect()->route('gym.staff.index')->with('success','Staff member deleted successfully!');
    }
}
