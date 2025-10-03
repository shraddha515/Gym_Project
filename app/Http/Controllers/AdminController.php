<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\User;

class AdminController extends Controller
{


    // Main dashboard
    public function index(Request $request)
    {
        return view('gym.Dashboard.index');
    }

    public function membersExpiringToday(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }
        $user = Auth::user();
        $gym_id = $user->gym_id;
        $today = now()->toDateString();

        $query = DB::table('members')
            ->where('gym_id', $gym_id)
            ->whereDate('membership_valid_to', $today);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('mobile_number', 'like', "%$search%")
                    ->orWhere('aadhar_no', 'like', "%$search%");
            });
        }

        $members = $query->orderBy('membership_valid_to', 'asc')->get();

        return view('gym.Dashboard.expiring_today', compact('members'));
    }



    public function expiredMembers(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }
        $user = Auth::user();
        $gym_id = $user->gym_id;
        $today = now()->toDateString();

        $query = DB::table('members')
            ->where('gym_id', $gym_id)
            ->whereDate('membership_valid_to', '<', $today);

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('mobile_number', 'like', "%$search%")
                    ->orWhere('aadhar_no', 'like', "%$search%");
            });
        }

        $members = $query->orderBy('membership_valid_to', 'desc')->get();

        return view('gym.Dashboard.expired_members', compact('members'));
    }


    public function filterMembers(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }
        $user = Auth::user();
        $gym_id = $user->gym_id;
        $today = now()->toDateString();
        $filter = $request->get('filter', 'expiring');
        // Members list
        $members = DB::table('members')
            ->where('gym_id', $gym_id)
            ->get();

        // ✅ Membership types for dropdown
        $membershipTypes = DB::table('memberships')
            ->where('gym_id', $gym_id)
            ->get();
        // ----- Table Data -----
        $query = DB::table('members')
            ->where('gym_id', $gym_id);
if ($filter === 'expiring') {
    $query->whereDate('membership_valid_to', $today);
} elseif ($filter === 'expired') {
    $query->whereDate('membership_valid_to', '<', $today);
} elseif ($filter === 'date_range' && $request->filled('from') && $request->filled('to')) {
    $query->whereDate('membership_valid_to', '>=', $request->from)
          ->whereDate('membership_valid_to', '<=', $request->to);
}


    // 🔥 Universal Search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%")
              ->orWhere('mobile_number', 'like', "%$search%")
              ->orWhere('aadhar_no', 'like', "%$search%");
        });
    }

    $members = $query->orderBy('membership_valid_to', 'asc')->get();

        // ----- Cards Data -----
        $activeMembers = DB::table('members')
            ->where('gym_id', $gym_id)

            ->get();
        $activeMembersCount = $activeMembers->count();

        $staff = DB::table('staff_members')
            ->where('gym_id', $gym_id)
            ->where('active_status', 'Active')
            ->get();
        $staffCount = $staff->count();

        $totalFees = DB::table('members')
            ->where('gym_id', $gym_id)
            ->sum('fees_paid'); // instead of amount

        $totalExpenses = DB::table('expenses')
            ->where('gym_id', $gym_id)
            ->sum('amount');

        $netAmount = $totalFees - $totalExpenses;

        $today = now()->toDateString();

        $expiredCount = DB::table('members')
            ->where('gym_id', $gym_id)
            ->whereDate('membership_valid_to', '<', $today)
            ->count();


        return view('gym.members_filter', compact(
            'members',
            'filter',
            'activeMembers',
            'activeMembersCount',
            'staff',
            'staffCount',
            'netAmount',
            'expiredCount',
            'membershipTypes'
        ));
    }



    public function membersList()
    {
        $members = DB::table('members')->get();

        // Membership types for dropdown
        $membershipTypes = DB::table('memberships')->where('gym_id', Auth::user()->gym_id)->get();

        return view('gym.members_filter', compact('members', 'membershipTypes'));
    }

    
    public function renewSubmit(Request $request, $id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $member = DB::table('members')->where('id', $id)->first();
        if (!$member) {
            return redirect()->back()->with('error', 'Member not found.');
        }

        // Request values
        $membership_type = $request->input('membership_type');
        $fees = $request->input('fees');
        $new_expiry = $request->input('new_expiry_date');

        // Update member record
        $updateData = [
            'membership_type' => $membership_type,
            'membership_valid_to' => $new_expiry,
            'fees_paid' => $fees,
        ];
        DB::table('members')->where('id', $id)->update($updateData);

        // Insert into history
        DB::table('membership_history')->insert([
            'gym_id' => $gym_id,
            'member_id' => $id,
            'package_id' => $membership_type,
            'amount' => $fees,
            'valid_from' => $member->membership_valid_to
                ? date('Y-m-d', strtotime($member->membership_valid_to . ' +1 day'))
                : now()->toDateString(),
            'valid_to' => $new_expiry,
            'renewed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Membership renewed successfully!');
    }






    // Gym Members (list)
    public function gymMembership()
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;
        $memberships = DB::table('memberships')
            ->leftJoin('categories', 'memberships.category_id', '=', 'categories.id')
            ->leftJoin('installments', 'memberships.installment_id', '=', 'installments.id')
            ->select('memberships.*', 'categories.name as category_name', 'installments.title as installment_title')
            ->where('memberships.gym_id', $gym_id)
            ->orderBy('memberships.id', 'desc')
            ->get();

        $categories = DB::table('categories')->where('gym_id', $gym_id)->orderBy('name')->get();
        $installments = DB::table('installments')->where('gym_id', $gym_id)->orderBy('title')->get();

        return view('gym.membership', compact('memberships', 'categories', 'installments'));
    }

    // Store membership
    public function storeMembership(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|numeric',
            'period_days' => 'required|numeric',
            'limit_type' => 'required|in:Limited,Unlimited',
            'amount' => 'nullable|numeric',
            'signup_fee' => 'nullable|numeric',
            'installment_id' => 'nullable|numeric',
            'classes_count' => 'nullable|numeric',
            'classes_freq' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/memberships'), $filename);
            $imagePath = 'uploads/memberships/' . $filename;
        }

        DB::table('memberships')->insert([
            'gym_id' => $gym_id,
            'name' => $validated['name'],
            'category_id' => $validated['category_id'] ?? null,
            'period_days' => $validated['period_days'],
            'limit_type' => $validated['limit_type'],
            'classes_count' => $validated['classes_count'] ?? null,
            'classes_freq' => $validated['classes_freq'] ?? null,
            'amount' => $validated['amount'] ?? 0,
            'installment_id' => $validated['installment_id'] ?? null,
            'signup_fee' => $validated['signup_fee'] ?? 0,
            'description' => $validated['description'] ?? null,
            'image' => $imagePath,
        ]);

        return redirect()->route('gym.membership')->with('success', 'Membership saved successfully.');
    }

    // Return membership data for edit (JSON)
    public function editMembership($id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $membership = DB::table('memberships')->where('id', $id)->where('gym_id', $gym_id)->first();
        if (!$membership) {
            return redirect()->route('gym.membership')->with('error', 'Membership not found.');
        }

        $categories = DB::table('categories')->where('gym_id', $gym_id)->orderBy('name')->get();
        $installments = DB::table('installments')->where('gym_id', $gym_id)->orderBy('title')->get();

        return view('gym.edit_membership', compact('membership', 'categories', 'installments'));
    }



    // Show Edit Membership page
    public function editMembershipPage($id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        // Membership data
        $membership = DB::table('memberships')->where('id', $id)->where('gym_id', $gym_id)->first();
        if (!$membership) {
            return redirect()->route('gym.membership')->with('error', 'Membership not found.');
        }

        // Categories & Installments
        $categories = DB::table('categories')->where('gym_id', $gym_id)->orderBy('name')->get();
        $installments = DB::table('installments')->where('gym_id', $gym_id)->orderBy('title')->get();

        // Return edit view
        return view('gym.edit_membership', compact('membership', 'categories', 'installments'));
    }





    public function updateMembership(Request $request, $id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'category_id' => 'nullable|numeric',
            'period_days' => 'required|numeric',
            'limit_type' => 'required|in:Limited,Unlimited',
            'amount' => 'nullable|numeric',
            'signup_fee' => 'nullable|numeric',
            'installment_id' => 'nullable|numeric',
            'classes_count' => 'nullable|numeric',
            'classes_freq' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        $data = [
            'name' => $validated['name'],
            'category_id' => $validated['category_id'] ?? null,
            'period_days' => $validated['period_days'],
            'limit_type' => $validated['limit_type'],
            'classes_count' => $validated['classes_count'] ?? null,
            'classes_freq' => $validated['classes_freq'] ?? null,
            'amount' => $validated['amount'] ?? 0,
            'installment_id' => $validated['installment_id'] ?? null,
            'signup_fee' => $validated['signup_fee'] ?? 0,
            'description' => $validated['description'] ?? null,
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/memberships'), $filename);
            $data['image'] = 'uploads/memberships/' . $filename;
        }

        DB::table('memberships')->where('id', $id)->where('gym_id', $gym_id)->update($data);

        return redirect()->route('gym.membership')->with('success', 'Membership updated successfully.');
    }

    // Delete membership
    public function deleteMembership($id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;
        DB::table('memberships')->where('id', $id)->where('gym_id', $gym_id)->delete();
        return redirect()->route('gym.membership')->with('success', 'Membership deleted.');
    }

    // AJAX: add category
    public function addCategory(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;
        $request->validate(['name' => 'required|string|max:150']);
        $id = DB::table('categories')->insertGetId(['gym_id' => $gym_id, 'name' => $request->name]);
        $category = DB::table('categories')->where('id', $id)->where('gym_id', $gym_id)->first();
        return response()->json($category);
    }

    // AJAX: delete category
    public function deleteCategory($id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;
        DB::table('categories')->where('id', $id)->where('gym_id', $gym_id)->delete();
        return response()->json(['success' => true]);
    }

    // AJAX: add installment
    public function addInstallment(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;
        $request->validate(['title' => 'required|string|max:150', 'amount' => 'nullable|numeric']);
        $id = DB::table('installments')->insertGetId([
            'gym_id' => $gym_id,
            'title' => $request->title,
            'amount' => $request->amount ?? 0
        ]);
        $plan = DB::table('installments')->where('id', $id)->where('gym_id', $gym_id)->first();
        return response()->json($plan);
    }

    // AJAX: delete installment
    public function deleteInstallment($id)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;
        DB::table('installments')->where('id', $id)->where('gym_id', $gym_id)->delete();
        return response()->json(['success' => true]);
    }

    // Show Settings Page
    public function gymSettings()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        $user = Auth::user();

        $superAdmins = [];
        $role = $user ? $user->role : null;
        // Agar current user superadmin hai, to saare superadmin users fetch kar lo
        if ($user->role === 'superadmin') {
            $superAdmins = DB::table('users')
                ->where('role', 'superadmin')
                ->where('id', '!=', $user->id) // khud ko na dikhao
                ->get();
        }

        return view('admin.settings', compact('user', 'superAdmins'));
    }

    // Update current user's profile
public function updateGymSettings(Request $request)
{
    // Logged-in user ID fetch
    $userId = Auth::id();
 // यह user ID दिखा देगा और script रोक देगा
    // Validate input
  

    // Fetch the user row from DB
    $userRow = DB::table('users')->where('id', $userId)->first();

    // Print the row
  // Laravel dump & die, यह row दिखा देगा और script रोक देगा

    if (!$userRow) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Prepare update data
    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'mobile' => $request->mobile,
        'updated_at' => now(),
    ];

    if ($request->password) {
        $data['password'] = Hash::make($request->password);
    }

    // Update the same row
    DB::table('users')->where('id', $userId)->update($data);

    return redirect()->route('gym.settings')->with('success', 'Profile updated successfully!');
}


    // Add new super admin
    public function addSuperAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:superadmin',
        ]);

        DB::table('users')->insert([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Super Admin added successfully!');
    }

    // Delete existing super admin
    public function deleteSuperAdmin($id)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'superadmin') {
            return redirect()->back()->with('error', 'Unauthorized action.');
        }

        DB::table('users')->where('id', $id)->where('role', 'superadmin')->delete();

        return redirect()->back()->with('success', 'Super Admin deleted successfully!');
    }
    // AdminController.php

// public function updateSuperAdmin(Request $request, $id)
// {
//     $request->validate([
//         'name'   => 'required|string|max:100',
//         'email'  => 'required|email|unique:users,email,' . $id,
//         'mobile' => 'nullable|string|max:15',
//         'status' => 'required|in:0,1', // 0 = inactive, 1 = active
//     ]);

//     $superadmin = DB::table('users')
//         ->where('id', $id)
//         ->where('role', 'superadmin')
//         ->first();

//     if (!$superadmin) {
//         return redirect()->back()->with('error', 'Super Admin not found.');
//     }

//     DB::table('users')
//         ->where('id', $id)
//         ->update([
//             'name'       => $request->name,
//             'email'      => $request->email,
//             'mobile'     => $request->mobile,
//             'status'     => $request->status,
//             'updated_at' => now(),
//         ]);

//     return redirect()->back()->with('success', 'Super Admin updated successfully!');
// }

}
