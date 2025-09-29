<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
         if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $type = $request->type ?? 'members';
        $from = $request->from ?? date('Y-m-01');
        $to   = $request->to ?? date('Y-m-d');
        $member_id = $request->member_id ?? null;

        // Members
        $membersQuery = DB::table('members')
        ->where('gym_id', $gym_id)
         
            ->whereBetween('membership_valid_from', [$from, $to]);

        if ($member_id) $membersQuery->where('id', $member_id);

        $members = $membersQuery->get()->map(function($m){
    $m->amount = $m->fees_paid ?? 0;  // members table ka fees_paid
    
    // membership name fetch karna
    $membership = DB::table('memberships')
                    ->where('id', $m->membership_type) // membership_type me id stored hai
                    ->first();
    $m->membership_name = $membership ? $membership->name : 'N/A';

    return $m;
});

        $totalFees = $members->sum('amount');
        $totalMembers = $members->count();

        // Expenses
        $expensesQuery = DB::table('expenses')->where('gym_id', $gym_id)
            ->whereBetween('expense_date', [$from, $to]);

        if ($member_id) $expensesQuery->where('created_by', $member_id);

        $expenses = $expensesQuery->get();
        $totalExpenses = $expenses->sum('amount');

        // Balance
        $balance = $totalFees - $totalExpenses;

        return view('gym.report', compact(
            'members', 'expenses', 'totalFees', 'totalExpenses', 'totalMembers', 'balance',
            'from', 'to', 'type', 'member_id'
        ));
    }

    // PDF Download
    public function downloadPdf(Request $request)
    {
         if (!Auth::check()) {
            return redirect()->route('admin.login')->with('error', 'Please login first.');
        }
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $type = $request->type ?? 'members';
        $from = $request->from ?? date('Y-m-01');
        $to   = $request->to ?? date('Y-m-d');
        $member_id = $request->member_id ?? null;

        if ($type == 'members') {
            $data = DB::table('members as m')
                ->leftJoin('memberships as ms', 'm.membership_type', '=', 'ms.name')
                ->select('m.id',
                         DB::raw("CONCAT(m.first_name,' ',IFNULL(m.last_name,'')) as full_name"),
                         'm.mobile_number',  'ms.name as membership_name', // membership name,
                         'm.membership_valid_from', 'm.membership_valid_to',
                         'm.fees_paid as amount')
                ->where('m.gym_id', $gym_id)
                ->whereBetween('m.membership_valid_from', [$from, $to])
                ->when($member_id, fn($q) => $q->where('m.id', $member_id))
                ->get();
            $totalFees = $data->sum('amount');
            $totalMembers = $data->count();
            $totalExpenses = 0;
        } else {
            $data = DB::table('expenses')
                ->where('gym_id', $gym_id)
                ->whereBetween('expense_date', [$from, $to])
                ->when($member_id, fn($q) => $q->where('created_by', $member_id))
                ->get();
            $totalExpenses = $data->sum('amount');
            $totalFees = 0;
            $totalMembers = 0;
        }

       $pdf = Pdf::loadView('gym.report', compact(
    'data','type','from','to','totalFees','totalExpenses','totalMembers'
))->setPaper('a4', 'landscape'); // optional: landscape page


        return $pdf->download('gym_report.pdf');
    }

   // CSV Download
public function downloadCsv(Request $request)
{
    // Ensure user is logged in
    if (!Auth::check()) {
        return redirect()->route('admin.login')->with('error', 'Please login first.');
    }

    $user = Auth::user();
    $gym_id = $user->gym_id;

    $type = $request->type ?? 'members';
    $from = $request->from ?? date('Y-m-01');
    $to   = $request->to ?? date('Y-m-d');
    $member_id = $request->member_id ?? null;

    // CSV filename
    $filename = "gym_report_" . date('YmdHis') . ".csv";

    // Open temporary file handle
    $handle = fopen('php://temp', 'w+');
fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
    if ($type == 'members') {
        // CSV header
        fputcsv($handle, ['ID','Name','Mobile','Membership Type','Valid From','Valid To','Amount']);

        // Fetch members for the logged-in gym
        $members = DB::table('members as m')
            ->leftJoin('memberships as ms', 'm.membership_type', '=', 'ms.id') // join on ID
            ->select(
                'm.id',
                DB::raw("CONCAT(m.first_name,' ',IFNULL(m.last_name,'')) as name"),
                'm.mobile_number',
                'ms.name as membership_name',
                'm.membership_valid_from',
                'm.membership_valid_to',
                'm.fees_paid as amount'
            )
            ->where('m.gym_id', $gym_id)
            ->whereBetween('m.membership_valid_from', [$from, $to])
            ->when($member_id, fn($q) => $q->where('m.id', $member_id))
            ->get();

        // Write rows
        foreach ($members as $m) {
            fputcsv($handle, [
                $m->id ?? 'NA',
                $m->name ?? 'NA',
                $m->mobile_number ?? 'NA',
                $m->membership_name ?? 'NA',
                $m->membership_valid_from ? \Carbon\Carbon::parse($m->membership_valid_from)->format('M d, Y') : 'NA',
                $m->membership_valid_to ? \Carbon\Carbon::parse($m->membership_valid_to)->format('M d, Y') : 'NA',
                $m->amount ?? 0
            ]);
        }

    } else { // Expenses
        // CSV header
        fputcsv($handle, ['ID','Category','Amount','Description','Date']);

        $expenses = DB::table('expenses')
            ->where('gym_id', $gym_id)
            ->whereBetween('expense_date', [$from, $to])
            ->when($member_id, fn($q) => $q->where('created_by', $member_id))
            ->get();

        foreach ($expenses as $e) {
            fputcsv($handle, [
                $e->id ?? 'NA',
                $e->category ?? 'NA',
                $e->amount ?? 0,
                $e->description ?? 'NA',
                $e->expense_date ? \Carbon\Carbon::parse($e->expense_date)->format('M d, Y') : 'NA'
            ]);
        }
    }

    // Prepare CSV for download
    rewind($handle);
    $csvContent = stream_get_contents($handle);
    fclose($handle);

    return response($csvContent)
        ->header('Content-Type', 'text/csv')
        ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
}
}