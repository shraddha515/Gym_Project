<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? 'members'; // 'members' or 'expenses'
        $member_id = $request->member_id ?? null;
        $from = $request->from ?? date('Y-01-01');
        $to   = $request->to ?? date('Y-m-d');

        $membersQuery = DB::table('members')
    ->selectRaw("id, CONCAT(first_name,' ',IFNULL(last_name,'')) as full_name, mobile_number, membership_type, membership_valid_from, membership_valid_to")
    ->whereBetween('membership_valid_from', [$from, $to]);

if($member_id){
    $membersQuery->where('id', $member_id);
}

$members = $membersQuery->get();


        // Assuming amount comes from memberships table if needed
        // Joining with memberships table to get fee info (amount)
        $totalFees = DB::table('memberships')->sum('amount'); // total fees
        $totalMembers = $members->count();

        // Expenses data
        $expensesQuery = DB::table('expenses')
            ->whereBetween('expense_date', [$from, $to]);

        if($member_id){
            $expensesQuery->where('created_by', $member_id);
        }

        $expenses = $expensesQuery->get();
        $totalExpenses = $expenses->sum('amount');

        return view('gym.report', compact(
            'members', 'totalFees', 'totalMembers',
            'expenses','totalExpenses','from','to','type','member_id'
        ));
    }

    // PDF Download
    public function downloadPdf(Request $request)
    {
        $type = $request->type ?? 'members';
        $from = $request->from ?? date('Y-01-01');
        $to = $request->to ?? date('Y-m-d');
        $member_id = $request->member_id ?? null;

        $pdf = Pdf::loadView('gym.report_pdf', compact('type','from','to','member_id'));
        return $pdf->download('gym_report.pdf');
    }

    // CSV Download
    public function downloadCsv(Request $request)
    {
        $type = $request->type ?? 'members';
        $from = $request->from ?? date('Y-01-01');
        $to = $request->to ?? date('Y-m-d');
        $member_id = $request->member_id ?? null;

        $filename = "gym_report_".date('YmdHis').".csv";

        $handle = fopen($filename, 'w+');

        if($type == 'members'){
            fputcsv($handle, ['ID','Name','Mobile','Membership Type','Membership From','Membership To']);
            $members = DB::table('members')
                ->select(
                    'id',
                    DB::raw("CONCAT(first_name,' ',IFNULL(last_name,'')) as name"),
                    'mobile_number',
                    'membership_type',
                    'membership_valid_from',
                    'membership_valid_to'
                )
                ->whereBetween('membership_valid_from', [$from,$to])
                ->when($member_id,function($q) use ($member_id){ return $q->where('id',$member_id);})
                ->get();

            foreach($members as $m){
                fputcsv($handle, [$m->id,$m->name,$m->mobile_number,$m->membership_type,$m->membership_valid_from,$m->membership_valid_to]);
            }

        } else {
            fputcsv($handle, ['ID','Category','Amount','Description','Expense Date']);
            $expenses = DB::table('expenses')
                ->whereBetween('expense_date', [$from,$to])
                ->when($member_id,function($q) use ($member_id){ return $q->where('created_by',$member_id);})
                ->get();

            foreach($expenses as $e){
                fputcsv($handle, [$e->id,$e->category,$e->amount,$e->description,$e->expense_date]);
            }
        }

        fclose($handle);
        return response()->download($filename)->deleteFileAfterSend(true);
    }
}
