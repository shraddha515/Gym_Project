<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class MemberController extends Controller
{
    /**
     * Display a listing of the members.
     * Includes search and sorting functionality.
     */
 public function index(Request $request)
{
    // Get the authenticated user and their gym_id
    $user = Auth::user();
    $gym_id = $user->gym_id;

    // Start a DB query builder for members
    $query = DB::table('members')
        ->where('members.gym_id', $gym_id) // ✅ gym_id condition on members table
        ->leftJoin('staff_members', 'members.assigned_staff_id', '=', 'staff_members.id')
        ->select('members.*', 'staff_members.name as pt_name');

    // Handle search filter
    if ($request->filled('search')) {
        $search = $request->input('search');
        $query->where(function ($q) use ($search) {
            $q->where('members.first_name', 'like', "%{$search}%")
              ->orWhere('members.last_name', 'like', "%{$search}%")
              ->orWhere('members.member_id', 'like', "%{$search}%")
              ->orWhere('members.mobile_number', 'like', "%{$search}%");
        });
    }

    // Handle sorting (default: members.id)
    $sort = $request->get('sort', 'members.id');
    $direction = $request->get('direction', 'asc');

    // Apply sorting and paginate
    $members = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

    return view('gym.members.index', compact('members'));
}

    public function create()
{
     $user = Auth::user();
    $gym_id = $user->gym_id;

    // Get last member_id
    $lastMember = DB::table('members')->orderByDesc('id')->first();
    $memberId = 'MS' . str_pad(($lastMember->id ?? 0) + 1, 5, '0', STR_PAD_LEFT);

    // Fetch all membership types from DB
    $membershipTypes = DB::table('memberships')->select('id', 'name')->get();
    $pts = DB::table('staff_members')
    ->where('type', 'Personal Trainer')
    ->where('active_status', 'Active')
    ->select('id','name')
    ->get();
$pts = DB::table('staff_members')
    ->where('type', 'Personal Trainer')
    ->where('active_status', 'Active')
    ->select('id','name')
    ->get();

return view('gym.members.create', compact('memberId', 'membershipTypes', 'pts'));

}




public function store(Request $request)
{

    $user = Auth::user();
    $gym_id = $user->gym_id;


    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'gender' => 'nullable|in:Male,Female,Other',
        'date_of_birth' => 'nullable|date',
        'mobile_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'aadhar_no' => 'nullable|string|max:12',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'interested_area' => 'nullable|string',
        'membership_type' => 'required|integer|exists:memberships,id',
        'membership_valid_from' => 'nullable|date',
        'membership_valid_to' => 'nullable|date',
        'weight' => 'nullable|numeric',
        'height' => 'nullable|numeric',
        'fat_percentage' => 'nullable|numeric',
    ]);

    // Generate unique member_id
    $validated['member_id'] = 'M-' . str_pad(DB::table('members')->max('id') + 1, 5, '0', STR_PAD_LEFT);

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $validated['photo_path'] = $request->file('photo')->store('member_photos', 'public');
    }

    unset($validated['photo']); // important

    $validated['gym_id'] = $gym_id;

    $validated['created_at'] = now();
    $validated['updated_at'] = now();

    DB::table('members')->insert($validated);

    return redirect()->route('gym.members.index')->with('success', 'Member added successfully!');
}











    public function show($id)
    {
         $user = Auth::user();
    $gym_id = $user->gym_id;

        $member = DB::table('members')->where('gym_id', $gym_id)->first();
        if (!$member) return redirect()->route('gym.members.index')->with('error', 'Member not found.');

        return view('gym.members.show', compact('member'));
    }




public function edit($id)
{
     $user = Auth::user();
    $gym_id = $user->gym_id;

    $member = DB::table('members')->where('id', $id)->first();

    if (!$member) {
        return redirect()->route('gym.members.index')->with('error', 'Member not found!');
    }

    // Fetch all membership types
    $membershipTypes = DB::table('memberships')->select('id', 'name')->get();

    // Fetch all active Personal Trainers
    $pts = DB::table('staff_members')
        ->where('type', 'Personal Trainer')
        ->where('active_status', 'Active')
        ->select('id','name')
        ->get();

    return view('gym.members.create', compact('member', 'membershipTypes', 'pts'));
}





public function update(Request $request, $id)
{

 $user = Auth::user();
    $gym_id = $user->gym_id;

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'gender' => 'nullable|in:Male,Female,Other',
        'date_of_birth' => 'nullable|date',
        'mobile_number' => 'required|string|max:20',
        'address' => 'required|string|max:255',
        'aadhar_no' => 'nullable|string|max:12',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'interested_area' => 'nullable|string',
        'membership_type' => 'required|integer|exists:memberships,id',
        'membership_valid_from' => 'nullable|date',
        'membership_valid_to' => 'nullable|date',
        'weight' => 'nullable|numeric',
        'height' => 'nullable|numeric',
        'fat_percentage' => 'nullable|numeric',
    ]);

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $validated['photo_path'] = $request->file('photo')->store('member_photos', 'public');
    }

    unset($validated['photo']); // important

    $validated['updated_at'] = now();

    DB::table('members')->where('id', $id)->update($validated);

    return redirect()->route('gym.members.index')->with('success', 'Member updated successfully!');
}












    public function destroy($id)
    {
        $member = DB::table('members')->where('id', $id)->first();
        if (!$member) return redirect()->route('gym.members.index')->with('error', 'Member not found.');

        if ($member->photo_path) Storage::disk('public')->delete($member->photo_path);

        DB::table('members')->where('id', $id)->delete();

        return redirect()->route('gym.members.index')->with('success', 'Member deleted successfully!');
    }












    /**
     * Export members data to a CSV file.
     */
    public function exportCsv()
{
    $members = DB::table('members')->get();

    $filename = "members_" . date('Ymd_His') . ".csv";

    // Set headers
    $headers = [
        "Content-type"        => "text/csv; charset=UTF-8",
        "Content-Disposition" => "attachment; filename=$filename",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    ];

    $columns = ['Member ID', 'Name', 'Mobile', 'City', 'Status', 'Joining Date'];

    $callback = function() use ($members, $columns) {
        $file = fopen('php://output', 'w');

        // Add BOM for UTF-8 support (prevents Excel issues)
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        // Write header
        fputcsv($file, $columns);

        // Write rows
        foreach ($members as $member) {
            fputcsv($file, [
                $member->member_id,
                $member->first_name . ' ' . $member->last_name,
                $member->mobile_number,
                // $member->city,
                $member->member_type,
                \Carbon\Carbon::parse($member->created_at)->format('M d, Y')
            ]);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}





    /**
     * Export members data to a PDF file.
     */
    public function exportPdf()
    {
        $members = DB::table('members')->get();
        $pdf = Pdf::loadView('gym.members.pdf', compact('members'));

        return $pdf->download('members_' . now()->format('Y-m-d') . '.pdf');
    }


    
}