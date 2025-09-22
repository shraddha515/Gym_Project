<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
class AdminController extends Controller
{


    // Main dashboard
    public function index()
    {
        return view('gym.Dashboard.index');
    }

    public function membersExpiringToday(Request $request)
{
    $today = now()->toDateString();

    $query = DB::table('members')
        ->whereDate('membership_valid_to', $today);

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%")
              ->orWhere('mobile_number', 'like', "%$search%");
        });
    }

    $members = $query->orderBy('membership_valid_to', 'asc')->get();

    return view('gym.Dashboard.expiring_today', compact('members'));
}



    public function expiredMembers(Request $request)
{
    $today = now()->toDateString();

    $query = DB::table('members')
        ->whereDate('membership_valid_to', '<', $today);

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%")
              ->orWhere('mobile_number', 'like', "%$search%");
        });
    }

    $members = $query->orderBy('membership_valid_to', 'desc')->get();

    return view('gym.Dashboard.expired_members', compact('members'));
}

public function filterMembers(Request $request)
{
    $today = now()->toDateString();
    $filter = $request->get('filter', 'expiring');

    $query = DB::table('members');

    if ($filter === 'expiring') {
        $query->whereDate('membership_valid_to', $today);
    } elseif ($filter === 'expired') {
        $query->whereDate('membership_valid_to', '<', $today);
    }

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%$search%")
              ->orWhere('last_name', 'like', "%$search%")
              ->orWhere('mobile_number', 'like', "%$search%");
        });
    }

    $members = $query->orderBy('membership_valid_to', 'asc')->get();

    return view('gym.Dashboard.members_filter', compact('members', 'filter'));
}
// public function renewMember($id)
// {
//     $today = now()->toDateString();

//     // Member fetch karo
//     $member = DB::table('members')->where('id', $id)->first();

//     if (!$member) {
//         return redirect()->back()->with('error', 'Member not found!');
//     }

//     // naya expiry = today + member->period_days
//     $newExpiry = now()->addDays($member->period_days)->toDateString();

//     DB::table('members')
//         ->where('id', $id)
//         ->update([
//             'membership_valid_from' => $today,
//             'membership_valid_to'   => $newExpiry,
//         ]);

//     return redirect()->back()->with('success', 'Membership renewed successfully!');
// }




   // Gym Members (list)
    public function gymMembership()
    {
        $memberships = DB::table('memberships')
            ->leftJoin('categories', 'memberships.category_id', '=', 'categories.id')
            ->leftJoin('installments', 'memberships.installment_id', '=', 'installments.id')
            ->select('memberships.*', 'categories.name as category_name', 'installments.title as installment_title')
            ->orderBy('memberships.id', 'desc')
            ->get();

        $categories = DB::table('categories')->orderBy('name')->get();
        $installments = DB::table('installments')->orderBy('title')->get();

        return view('gym.membership', compact('memberships', 'categories', 'installments'));
    }

    // Store membership
    public function storeMembership(Request $request)
    {
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
        $membership = DB::table('memberships')->where('id', $id)->first();
        if (!$membership) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json($membership);
    }

    // Update membership
    public function updateMembership(Request $request, $id)
    {
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

        DB::table('memberships')->where('id', $id)->update($data);

        return redirect()->route('gym.membership')->with('success', 'Membership updated successfully.');
    }

    // Delete membership
    public function deleteMembership($id)
    {
        DB::table('memberships')->where('id', $id)->delete();
        return redirect()->route('gym.membership')->with('success', 'Membership deleted.');
    }

    // AJAX: add category
    public function addCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:150']);
        $id = DB::table('categories')->insertGetId(['name' => $request->name]);
        $category = DB::table('categories')->where('id', $id)->first();
        return response()->json($category);
    }

    // AJAX: delete category
    public function deleteCategory($id)
    {
        DB::table('categories')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

    // AJAX: add installment
    public function addInstallment(Request $request)
    {
        $request->validate(['title' => 'required|string|max:150', 'amount' => 'nullable|numeric']);
        $id = DB::table('installments')->insertGetId([
            'title' => $request->title,
            'amount' => $request->amount ?? 0
        ]);
        $plan = DB::table('installments')->where('id', $id)->first();
        return response()->json($plan);
    }

    // AJAX: delete installment
    public function deleteInstallment($id)
    {
        DB::table('installments')->where('id', $id)->delete();
        return response()->json(['success' => true]);
    }

     // Gym Members
    public function gymMembers()
    {
        return view('gym.members');
    }

    // Gym Packages
    public function gymPackages()
    {
        return view('gym.packages');
    }

    // Gym Trainers
    public function gymTrainers()
    {
        return view('gym.trainers');
    }

    // Gym Reports
    public function gymReports()
    {
        return view('gym.reports');
    }

    // Gym Expenses
    public function gymExpenses()
    {
        return view('gym.expenses');
    }

    // Show Settings Page
    public function gymSettings()
    {
        $user = Auth::user(); // Get logged-in user
        return view('admin.settings', compact('user'));
    }

    // Update Settings
    public function updateGymSettings(Request $request)
    {
        $user = Auth::user();

        // Validation
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'mobile' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed', // password_confirmation field required
        ]);

        // Update user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        if($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('gym.settings')->with('success', 'Profile updated successfully!');
    }
}
