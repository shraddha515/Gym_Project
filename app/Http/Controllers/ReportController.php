<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $type = $request->type ?? 'members'; // 'members' or 'expenses'
        $member_id = $request->member_id ?? null;
        $from = $request->from ?? date('d-m-y');
        $to   = $request->to ?? date('d-m-y');

        $membersQuery = DB::table('members')
            ->selectRaw("id, CONCAT(first_name,' ',IFNULL(last_name,'')) as full_name, mobile_number, membership_type, membership_valid_from, membership_valid_to")
            ->where('gym_id', $gym_id)
            ->whereBetween('membership_valid_from', [$from, $to]);

        if($member_id){
            $membersQuery->where('id', $member_id);
        }

        $members = $membersQuery->get();


        // Assuming amount comes from memberships table
        // Joining with memberships table to get fee info (amount)
        $totalFees = DB::table('memberships')->where('gym_id', $gym_id)->sum('amount'); // total fees
        $totalMembers = $members->count();

        // Expenses data
        $expensesQuery = DB::table('expenses')
            ->where('gym_id', $gym_id)
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
    $request->merge(['pdf' => 1]); // pass pdf param to Blade
    $user = Auth::user();
    $gym_id = $user->gym_id;

    $type = $request->type ?? 'members';
    $from = $request->from ?? date('Y-01-01');
    $to = $request->to ?? date('Y-m-d');
    $member_id = $request->member_id ?? null;

    if ($type == 'members') {
        // join memberships using membership name
        $data = DB::table('members as m')
            ->leftJoin('memberships as ms', 'm.membership_type', '=', 'ms.name')
            ->select(
                'm.id',
                DB::raw("CONCAT(m.first_name,' ',IFNULL(m.last_name,'')) as full_name"),
                'm.mobile_number',
                'm.membership_type',
                'm.membership_valid_from',
                'm.membership_valid_to',
                'ms.amount'
            )
            ->where('m.gym_id', $gym_id)
            ->whereBetween('m.membership_valid_from', [$from, $to])
            ->when($member_id, function($q) use ($member_id){
                return $q->where('m.id', $member_id);
            })
            ->get();

        // total fees from memberships of filtered members
        $totalFees = $data->sum('amount');
        $totalMembers = $data->count();
        $totalExpenses = 0;

    } else {
        $data = DB::table('expenses')
            ->where('gym_id', $gym_id)
            ->whereBetween('expense_date', [$from, $to])
            ->when($member_id, function($q) use ($member_id){
                return $q->where('created_by', $member_id);
            })
            ->get();

        $totalExpenses = $data->sum('amount');
        $totalFees = 0;
        $totalMembers = 0;
    }

    $pdf = Pdf::loadView('gym.report', compact(
        'type','from','to','data','totalFees','totalExpenses','totalMembers','member_id'
    ));

    return $pdf->download('gym_report.pdf');
}


    // CSV Download
    public function downloadCsv(Request $request)
    {
        $user = Auth::user();
        $gym_id = $user->gym_id;

        $type = $request->type ?? 'members';
        $from = $request->from ?? date('d-m-y');
        $to = $request->to ?? date('d-m-y');
        $member_id = $request->member_id ?? null;

        $filename = "gym_report_".date('YmdHis').".csv";

        $handle = fopen('php://temp', 'w+');

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
                ->where('gym_id', $gym_id)
                ->whereBetween('membership_valid_from', [$from,$to])
                ->when($member_id,function($q) use ($member_id){ return $q->where('id',$member_id);})
                ->get();

            foreach($members as $m){
                fputcsv($handle, [$m->id,$m->name,$m->mobile_number,$m->membership_type,$m->membership_valid_from,$m->membership_valid_to]);
            }

        } else {
            fputcsv($handle, ['ID','Category','Amount','Description','Expense Date']);
            $expenses = DB::table('expenses')
                ->where('gym_id', $gym_id)
                ->whereBetween('expense_date', [$from,$to])
                ->when($member_id,function($q) use ($member_id){ return $q->where('created_by',$member_id);})
                ->get();

            foreach($expenses as $e){
                fputcsv($handle, [$e->id,$e->category,$e->amount,$e->description,$e->expense_date]);
            }
        }

        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="'.$filename.'"');
    }
}
